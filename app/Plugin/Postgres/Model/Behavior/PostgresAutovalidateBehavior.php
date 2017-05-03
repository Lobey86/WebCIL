<?php
	/**
	 * Code source de la classe PostgresAutovalidateBehavior.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	require_once CakePlugin::path( 'Postgres' ).'Lib'.DS.'basics.php';

	/**
	 * La classe PostgresAutovalidateBehavior ajoute aux fonctionnalités de la
	 * classe Validation2AutovalidateBehavior la possibilité de lire des
	 * règles de validation à partir de contraintes postgresql.
	 *
	 * Ces contraintes doivent porter un nom commençant par cakephp_validate_
	 * pour être automatiquement ajoutées aux contraintes du modèle.
	 *
	 * @see Pgsqlcake.PgsqlAutovalidateBehavior (sera dépréciée par cette classe-ci)
	 *
	 * @package Postgres
	 * @subpackage Model.Behavior
	 */
	class PostgresAutovalidateBehavior extends ModelBehavior
	{
		/**
		 * Configuration.
		 *
		 * @var array
		 */
		public $settings = array();

		/**
		 * Configuration par défaut.
		 *
		 * @var array
		 */
		public $defaultConfig = array(
			'rules' => array(
				'postgres_constraints' => false
			),
			'domain' => 'postgres',
			'translate' => true
		);

		/**
		 * Liste des règles cakephp_validate_ groupées par alias du modèle.
		 *
		 * @var array
		 */
		protected $_checkRules = array();

		/**
		 * Liste des règles de validation déduites d'un modèle.
		 *
		 * @param Model $Model
		 * @param boolean $cache
		 * @return array
		 */
		public function deduceValidationRules( Model $Model, $cache = true ) {
			if( $cache ) {
				$cacheKey = cacheKey( array( $Model->useDbConfig, __CLASS__, $Model->alias, __FUNCTION__ ) );
				$validate = Cache::read( $cacheKey );
			}

			if( !$cache || $validate === false ) {
				$validate = array();

				foreach( $Model->schema() as $field => $params ) {
					$validate[$field] = $this->deduceFieldValidationRules(
						$Model,
						$field,
						$params
					);
				}

				if( $cache ) {
					Cache::write( $cacheKey, $validate );
				}
			}

			return $validate;
		}

		/**
		 * -> array( 'rule' => array( 'XXXXXXX'[, ...] ) )
		 *
		 * @param Model $Model
		 * @param string|array $rule
		 * @return array
		 */
		public function normalizeValidationRule( Model $Model, $rule ) {
			if( !is_array( $rule ) ) {
				$rule = array( 'rule' => array( $rule ) );
			}
            else if( !isset( $rule['rule'] ) && isset( $rule[0] ) ) {
                $rule = array( 'rule' => $rule );
            }
			else if( !is_array( $rule['rule'] ) ) {
				$rule['rule'] = (array)$rule['rule'];
			}

			$defaults = array(
				'rule' => null,
				'message' => null,
				'required' => null,
				'allowEmpty' => null,
				'on' => null
			);

			$rule = Hash::merge( $defaults, $rule );

			return $rule;
		}

		/**
		 * Lecture des contraintes dont le nom commence par cakephp_validate_.
		 *
		 * @param Model $Model
		 */
		protected function _readTableConstraints( Model $Model ) {
			$cacheKey = cacheKey( array( $Model->useDbConfig, __CLASS__, __FUNCTION__, $Model->alias ) );
			$this->_checkRules[$Model->alias] = Cache::read( $cacheKey );

			if( $this->_checkRules[$Model->alias] === false ) {
				if( !$Model->Behaviors->attached( 'Postgres.PostgresTable' ) ) {
					$Model->Behaviors->attach( 'Postgres.PostgresTable' );
				}
				$checks = $Model->getPostgresCheckConstraints();

				$this->_checkRules[$Model->alias] = array();
				foreach( $checks as $check ) {
					$this->_checkRules[$Model->alias] = $this->_addGuessedPostgresConstraint(
						$Model,
						$this->_checkRules[$Model->alias],
						$check['Constraint']['clause']
					);
				}

				Cache::write( $cacheKey, $this->_checkRules[$Model->alias] );
			}
		}

		/**
		 * Configuration du behavior.
		 *
		 * @param Model $Model Le modèle qui utilise ce behavior
		 * @param array $config La configuration à appliquer
		 */
		public function setup( Model $Model, $config = array() ) {
			parent::setup( $Model, $config );

			$datasourceName = Hash::get( $Model->getDataSource()->config, 'datasource' );
			if( stristr( $datasourceName, 'Postgres' ) !== false ) {
				$this->defaultConfig['rules']['postgres_constraints'] = true;
			}

			$config = Hash::merge( $this->defaultConfig, $config );

			$this->settings[$Model->alias] = array_merge(
				(array)Hash::get( $this->settings, $Model->alias ),
				(array)Hash::normalize( $config )
			);

			$this->_readTableConstraints( $Model );
			$this->mergeDeducedValidationRules( $Model );
		}

		/**
		 * Regroupement des règles de validation présentes dans le modèle et des
		 * règles de validation déduites.
		 *
		 * @param Model $Model
		 * @param boolean $cache
		 * @return void
		 */
		public function mergeDeducedValidationRules( Model $Model, $cache = true ) {
			if( $cache ) {
				$cacheKey = cacheKey( array( $Model->useDbConfig, __CLASS__, $Model->alias, __FUNCTION__ ) );
				$validate = Cache::read( $cacheKey );
			}

			if( !$cache || $validate === false ) {
				$Model->validate = Hash::normalize( $Model->validate );

				$Model->validate = Hash::merge(
					$Model->validate,
					$this->deduceValidationRules( $Model )
				);

				$validate = $Model->validate;
				if( $cache ) {
					Cache::write( $cacheKey, $validate );
				}
			}

			$Model->validate = $validate;
		}

		/**
		 * Déduction des règles de validation pour un champ d'un modèle donné.
		 *
		 * @param Model $Model
		 * @param string $field
		 * @param array $params
		 * @return array
		 */
		public function deduceFieldValidationRules( Model $Model, $field, $params ) {
			$rules = array();

			if( Hash::get( $this->settings, "{$Model->alias}.rules.postgres_constraints" ) ) {
				if( isset( $this->_checkRules[$Model->alias][$field] ) && !empty( $this->_checkRules[$Model->alias][$field] ) ) {
					foreach( $this->_checkRules[$Model->alias][$field] as $rule ) {
						$rules[$rule['rule'][0]] = $rule;
					}
				}
			}

			return $rules;
		}

		/**
		 * Lecture des paramètres de la contrainte postgresql.
		 *
		 * @param array $parameters
		 * @return array
		 */
		protected function _extractPostgresParams( array $parameters ) {
			$params = array();

			if( isset( $parameters['params'] ) ) {
				if( preg_match( '/^ARRAY\[(.*)\]$/', $parameters['params'], $matches ) ) {
					if( preg_match_all( '/ *([^,]+) */', $matches[1], $values ) ) {
						foreach( $values[1] as $k => $v ) {
							$values[1][$k] = preg_replace( '/^\'(.*)\'$/', '\1', $v );
						}
						$params = array( $values[1] );
					}
				}
				else if( preg_match_all( '/([^, ]+),{0,1}/', $parameters['params'], $matches ) ) {
					foreach( $matches[1] as $k => $v ) {
						$matches[1][$k] = preg_replace( '/^\'(.*)\'$/', '\1', $v );
					}
					$params = $matches[1];
				}
			}

			foreach( $params as $i => $param ) {
				if( is_string( $param ) && strtolower( $param ) == 'null' ) {
					$params[$i] = null;
				}
			}

			return $params;
		}

		/**
		 * Complète les $règles avec les règles déduites des contraintes postgresql.
		 *
		 * @param Model $Model
		 * @param array $rules
		 * @param string $code
		 * @return array
		 */
		protected function _addGuessedPostgresConstraint( Model $Model, array $rules, $code ) {
			// INFO: IIF the check is "xx()" or "xx() AND xx()" etc.
			// Transform (("position"))::
			$code = preg_replace( '/\("([^\(\)"]+)"\)::/', '(\1)::', $code );
			// Remove extra parenthesis
			$code = preg_replace( '/^( *\(+ *)? *(.+) *(?(1) *\)+ *)$/', '\2', $code );
			// Transform '.*'::text
			$code = preg_replace( '/\'([^\']+)\'::[^,\)\]]+/', '\'\1\'', $code );
			// Transform (0)::numeric
			$code = preg_replace( '/\(([^\(\)]+)\)::[^,\)\]]+/', '\1', $code );
			// Transform ((-1))::double precision
			$code = preg_replace( '/\(\(([^\(\)]+)\)\)::[^,\)\]]+/', '\1', $code );
			// Transform NULL::character varying
			$code = preg_replace( '/NULL::[^,\)]+/', 'NULL', $code );

			if( preg_match_all( '/cakephp_validate_.*\((\(.+\).*|.+)\)/U', $code, $matches, PREG_PATTERN_ORDER ) ) {
				foreach( $matches[0] as $rule ) {
					// INFO: '.*'::text, (0)::numeric and ((-1))::double precision are transformed above
					// if( preg_match( '/^cakephp_validate_(?<function>[^\(]+)\((?<field>\(.*\)::\w+|\w+)(, *(?<params>.*)){0,1}\)$/', $rule, $parameters ) ) {
					if( preg_match( '/^cakephp_validate_(?<function>[^\(]+)\((?<field>\(.*\)|\w+)(, *(?<params>.*)){0,1}\)$/', $rule, $parameters ) ) {
						$ruleName = Inflector::camelize( $parameters['function'] );
						$ruleName[0] = strtolower( $ruleName[0] );

						$field = trim( $parameters['field'] );
						$params = $this->_extractPostgresParams( $parameters );

						$rules[$field][$ruleName] = $this->normalizeValidationRule( $Model, array( 'rule' => array_merge( array( $ruleName ), $params ), 'allowEmpty' => true ) );
					}
				}
			}

			return $rules;
		}

		/**
		 * 'notEmpty' => Champ obligatoire
		 *
		 * @param Model $Model
		 * @param mixed $rule
		 * @return string
		 */
		public function defaultValidationRuleMessage( Model $Model, $rule ) {
			$rule = $this->normalizeValidationRule( $Model, $rule );
			if( !isset( $rule['rule'][0] ) ) {
				return null;
			}

			$message = "Validate::{$rule['rule'][0]}";

			$params = array();
			if( count( $rule['rule'] ) > 1 ) {
				$params = array_slice( $rule['rule'], 1 );

				if( is_array( $params[0] ) ) {
					$params = $params[0];
				}
			}

			if( strtolower( $rule['rule'][0] ) == 'inlist' ) {
				$params = '"'.implode( '", "', $params ).'"';
			}

			if( isset( $rule['domain'] ) ) {
				$domain = $rule['domain'];
			}
			else {
				$domain = $this->settings[$Model->alias]['domain'];
			}

			return call_user_func_array( 'sprintf', Hash::merge( array( __d( $domain, $message ) ), $params ) );
		}

		/**
		 * Before validate callback, translate validation messages
		 *
		 * @param Model $Model Model using this behavior
		 * @param array $options
		 * @return boolean True if validate operation should continue, false to abort
		 */
		public function beforeValidate( Model $Model, $options = array() ) {
			$success = parent::beforeValidate( $Model, $options );

			if( $this->settings[$Model->alias]['translate'] ) {
				if( is_array( $Model->validate ) && !empty( $Model->validate ) ) {
					foreach( $Model->validate as $field => $rules ) {
						foreach( $rules as $key => $rule ) {
							$rule = $this->normalizeValidationRule( $Model, $rule );
							if( !isset( $rule['message'] ) || empty( $rule['message'] ) ) {
								$rule['message'] = $this->defaultValidationRuleMessage( $Model, $rule );
								$Model->validate[$field][$key] = $rule;
							}
						}
					}
				}
			}

			return $success;
		}
	}
?>