<?php
	/**
	 * Source file for the DatabaseAutovalidateBehavior class.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	// @codeCoverageIgnoreStart
	App::uses( 'DatabaseValidationRule', 'Database.Utility' );
	require_once CakePlugin::path( 'Database' ).'Config'.DS.'bootstrap.php';
	// @codeCoverageIgnoreEnd

	/**
	 * La classe DatabaseAutovalidateBehavior permet d'ajouter automatiquement
	 * des règles de validation aux modèles auxquels il est attaché en fonction
	 * du schéma de la table au niveau de la base de données (voir la méthode
	 * CakePHP Model::schema()).
	 *
	 * Les règles suivantes sont déduites:
	 *	- notEmpty: si le champ est NOT NULL (avant la version 2.7.0 de CakePHP)
	 *	- notBlank: si le champ est NOT NULL (avant la version 2.7.0 de CakePHP)
	 *	- maxLength: si le champ est de type CHAR ou VARCHAR
	 *	- integer: si le champ est de type entier
	 *	- numeric: si le champ est de type numérique
	 *	- date: si le champ est de type date
	 *	- datetime: si le champ est de type date et heure
	 *	- time: si le champ est de type heure
	 *	- isUnique: si le champ possède un index unique
	 *
	 * @package Database
	 * @subpackage Model.Behavior
	 */
	class DatabaseAutovalidateBehavior extends ModelBehavior
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
				NOT_BLANK_RULE_NAME => true,
				'maxLength' => true,
				'integer' => true,
				'numeric' => true,
				'date' => true,
				'datetime' => true,
				'time' => true,
				'isUnique' => true,
			),
			'domain' => 'database',
			'translate' => true
		);

		/**
		 * Not null -> notEmpty
		 *
		 * @param Model $Model
		 * @param array $fieldParams
		 * @return boolean
		 */
		protected function _isNotEmptyField( Model $Model, $fieldParams ) {
			return ( $this->settings[$Model->alias]['rules'][NOT_BLANK_RULE_NAME] && Hash::check( $fieldParams, 'null' ) && $fieldParams['null'] == false );
		}

		/**
		 * string -> maxLength
		 *
		 * @param Model $Model
		 * @param array $fieldParams
		 * @return boolean
		 */
		protected function _isMaxLengthField( Model $Model, $fieldParams ) {
			return ( $this->settings[$Model->alias]['rules']['maxLength'] && ( $fieldParams['type'] == 'string' ) && Hash::check( $fieldParams, 'length' ) && is_numeric( $fieldParams['length'] ) );
		}

		/**
		 * unique index -> isUnique
		 *
		 * @param Model $Model
		 * @param string $field
		 * @param array $indexes
		 * @return boolean
		 */
		protected function _isUniqueField( Model $Model, $field, $indexes ) {
			return ( $this->settings[$Model->alias]['rules']['isUnique'] && in_array( $field, $indexes ) );
		}

		/**
		 * integer -> integer
		 * date -> date
		 * time -> time
		 * datetime -> datetime
		 *
		 * @param Model $Model
		 * @param string $type
		 * @param array $fieldParams
		 * @return boolean
		 */
		protected function _isTypeField( Model $Model, $type, $fieldParams ) {
			return ( $this->settings[$Model->alias]['rules'][$type] && $fieldParams['type'] == $type );
		}

		/**
		 * float -> numeric
		 *
		 * @param Model $Model
		 * @param array $fieldParams
		 * @return boolean
		 */
		protected function _isNumericField( Model $Model, $fieldParams ) {
			return ( $this->settings[$Model->alias]['rules']['numeric'] && $fieldParams['type'] == 'float' );
		}

		/**
		 * Déduction des règles de validation pour un champ d'un modèle donné.
		 *
		 * @param Model $Model
		 * @param string $field
		 * @param array $params
		 * @param array $indexes
		 * @return array
		 */
		public function deduceFieldDatabaseRules( Model $Model, $field, $params, $indexes = array() ) {
			$rules = array();

			if( $this->_isNotEmptyField( $Model, $params ) && ( $field != $Model->primaryKey ) ) {
				$rule = DatabaseValidationRule::normalize( array( 'rule' => NOT_BLANK_RULE_NAME, 'allowEmpty' => false ) );
				$rules[$rule['rule'][0]] = $rule;
			}

			if( $this->_isMaxLengthField( $Model, $params ) ) {
				$rule = DatabaseValidationRule::normalize( array( 'rule' => array( 'maxLength', $params['length'] ), 'allowEmpty' => true ) );
				$rules[$rule['rule'][0]] = $rule;
			}

			if( $this->_isUniqueField( $Model, $field, $indexes ) ) {
				$rule = DatabaseValidationRule::normalize( array( 'rule' => 'isUnique', 'allowEmpty' => true ) );
				$rules[$rule['rule'][0]] = $rule;
			}

			// Par type de champ
			if( $this->_isNumericField( $Model, $params ) ) {
				$rule = DatabaseValidationRule::normalize( array( 'rule' => 'numeric', 'allowEmpty' => true ) );
				$rules[$rule['rule'][0]] = $rule;
			}
			else if( in_array( $params['type'], array( 'integer', 'date', 'datetime', 'time' ) ) && $this->_isTypeField( $Model, $params['type'], $params ) ) {
				//FIXME $ruleName = 'integer' === $params['type'] ? 'isInteger' : $params['type'];
				$rule = DatabaseValidationRule::normalize( array( 'rule' => $params['type'], 'allowEmpty' => true ) );
				$rules[$rule['rule'][0]] = $rule;
			}

			return $rules;
		}

		/**
		 * Retourne la liste des champs sur lesquels se trouve un index unique
		 * en base.
		 *
		 * @param Model $Model
		 * @param boolean $cache
		 * @return array
		 */
		public function uniqueColumnIndexes( Model $Model, $cache = true ) {
			if( $cache ) {
				$cacheKey = cacheKey( array( $Model->useDbConfig, __CLASS__, $Model->alias, __FUNCTION__ ) );
				$uniqueColumnIndexes = Cache::read( $cacheKey );
			}

			if( !$cache || $uniqueColumnIndexes === false ) {
				$uniqueColumnIndexes = array();

				$indexes = $Model->getDataSource()->index( $Model );
				foreach( $indexes as $name => $index ) {
					if( $index['unique'] && ( $name != 'PRIMARY' ) && count( (array)$index['column'] ) == 1 ) {
						$uniqueColumnIndexes[] = $index['column'];
					}
				}

				if( $cache ) {
					Cache::write( $cacheKey, $uniqueColumnIndexes );
				}
			}

			return $uniqueColumnIndexes;
		}

		/**
		 * Liste des règles de validation déduites d'un modèle.
		 *
		 * @param Model $Model
		 * @param boolean $cache
		 * @return array
		 */
		public function deduceDatabaseRules( Model $Model, $cache = true ) {
			if( $cache ) {
				$cacheKey = cacheKey( array( $Model->useDbConfig, __CLASS__, $Model->alias, __FUNCTION__ ) );
				$validate = Cache::read( $cacheKey );
			}

			if( !$cache || $validate === false ) {
				$validate = array();
				$indexes = $this->uniqueColumnIndexes( $Model );

				foreach( $Model->schema() as $field => $params ) {
					$validate[$field] = $this->deduceFieldDatabaseRules(
						$Model,
						$field,
						$params,
						$indexes
					);
				}

				if( $cache ) {
					Cache::write( $cacheKey, $validate );
				}
			}

			return $validate;
		}

		/**
		 * Regroupement des règles de validation présentes dans le modèle et des
		 * règles de validation déduites.
		 *
		 * @param Model $Model
		 * @param boolean $cache
		 * @return void
		 */
		public function mergeDeducedDatabaseRules( Model $Model, $cache = true ) {
			if( $cache ) {
				$cacheKey = cacheKey( array( $Model->useDbConfig, __CLASS__, $Model->alias, __FUNCTION__ ) );
				$validate = Cache::read( $cacheKey );
			}

			if( !$cache || $validate === false ) {
				$Model->validate = Hash::normalize( $Model->validate );

				$Model->validate = Hash::merge(
					$Model->validate,
					$this->deduceDatabaseRules( $Model )
				);

				$validate = $Model->validate;
				if( $cache ) {
					Cache::write( $cacheKey, $validate );
				}
			}

			$Model->validate = $validate;
		}

		/**
		 * Configuration du behavior.
		 *
		 * @param Model $Model Le modèle qui utilise ce behavior
		 * @param array $config La configuration à appliquer
		 */
		public function setup( Model $Model, $config = array() ) {
			parent::setup( $Model, $config );
			$config = Hash::merge( $this->defaultConfig, $config );

			$this->settings[$Model->alias] = array_merge(
				(array)Hash::get( $this->settings, $Model->alias ),
				(array)Hash::normalize( $config )
			);

			// INFO: on en a besoin avant d'utiliser les formulaires
			// pour les dates, pas pour les maxLength apparemment
			$this->mergeDeducedDatabaseRules( $Model );
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
					$Model->validate = DatabaseValidationRule::translate(
						$Model->validate,
						$this->settings[$Model->alias]['domain']
					);
				}
			}

			return $success;
		}

		/**
		 * Permet de s'assurer qu'une valeur soit un nombre entier.
		 *
		 * @param Model $Model
		 * @param mixed $check
		 * @return boolean
		 */
		public function integer( Model $Model, $check ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$result = true;
			foreach( $check as $value ) {
				$result = preg_match( '/^[0-9]+$/', $value ) && $result;
			}

			return $result;
		}
	}
?>