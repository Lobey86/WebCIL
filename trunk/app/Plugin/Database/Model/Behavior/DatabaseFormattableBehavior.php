<?php
	/**
	 * Code source de la classe DatabaseFormattableBehavior.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	// @codeCoverageIgnoreStart
	App::import( 'Model', 'Model' );
	require_once CakePlugin::path( 'Database' ).'Config'.DS.'bootstrap.php';
	// @codeCoverageIgnoreEnd

	/**
	 * La classe DatabaseFormattableBehavior permet d'appliquer des méthodes
	 * de classes utilitaires aux valeurs avant enregistrement.
	 *
	 * Les classes utilitaires doivent se trouver dans Utility/DatabaseFormatters.
	 *
	 * Accepte en valeurs:
	 * 	- true/null
	 * 	- false
	 * 	- array()
	 *  - string (expression rationnelle pour le nom du champ)
	 *
	 * Une clé NOT est possible dans l'array pour prendre tous les types,
	 * moins ce qui est en valeur de cette clé.
	 *
	 * Les types (PostgreSQL) sont:
	 * - binary
	 * - boolean
	 * - date
	 * - datetime
	 * - float
	 * - inet
	 * - integer
	 * - string
	 * - text
	 * - time
	 *
	 * Configuration par défaut:
	 * <pre>
	 * 'Database.DatabaseDefaultFormatter' => array(
	 *	'trim' => array( 'NOT' => array( 'binary' ) ),
	 *	'null' => true,
	 *	'numeric' => array( 'float', 'integer' ),
	 *	'suffix'  => '/_id$/'
	 * )
	 * </pre>
	 *
	 * @package Database
	 * @subpackage Model.Behavior
	 */
	class DatabaseFormattableBehavior extends ModelBehavior
	{
		/**
		 * Contains configuration settings for use with individual model objects.  This
		 * is used because if multiple models use this Behavior, each will use the same
		 * object instance.  Individual model settings should be stored as an
		 * associative array, keyed off of the model name.
		 *
		 * @var array
		 * @see Model::$alias
		 */
		public $settings = array();

		/**
		 * Liste des fonctions de formattage, ordonnées, avec en paramètre,
		 * le type de champs à prendre en compte.
		 *
		 * @var array
		 */
		public $defaultSettings = array(
			'Database.DatabaseDefaultFormatter' => array(
				'formatTrim' => array( 'NOT' => array( 'binary' ) ),
				'formatNull' => true,
				'formatNumeric' => array( 'float', 'integer' ),
				'formatSuffix'  => '/_id$/'
			)
		);

		/**
		 * Permet de savoir si le cache a été chargé.
		 *
		 * @var boolean
		 */
		protected $_cacheLoaded = array();

		/**
		 * Liste des objets formatteurs.
		 *
		 * @var array
		 */
		protected $_oFormatters = array();

		/**
		 * Cache des noms de champ, groupés par type.
		 *
		 * @var array
		 */
		protected $_fieldsByType = array();

		/**
		 * Cache des noms de champ, avec leur type en valeur.
		 *
		 * @var array
		 */
		protected $_typeByField = array();

		/**
		 * Cache des expressions rationnelles par modèle, formatteur et méthode.
		 *
		 * @var array
		 */
		protected $_regexes = array();

		/**
		 * Configuration du behavior.
		 *
		 * @param Model $Model Le modèle qui utilise ce behavior
		 * @param array $config La configuration à appliquer
		 */
		public function setup( Model $Model, $config = array() ) {
			$config = Hash::merge( $this->defaultSettings, $config );

			if( !isset( $this->settings[$Model->alias] ) ) {
				$this->settings[$Model->alias] = array( );
			}

			$this->settings[$Model->alias] = Hash::merge(
				$this->settings[$Model->alias],
				(array)Hash::normalize( $config )
			);
		}

		/**
		 * Retourne la liste des champs en clé et le type en valeur.
		 *
		 * @param Model $Model
		 * @return array
		 */
		protected function _getTypeByField( Model $Model ) {
			$schema = $Model->schema();
			$fields = array_keys( $schema );
			$types = Hash::extract( $schema, '{s}.type' );

			return array_combine( $fields, $types );
		}

		/**
		 * Retourne tous les noms de champs du modèles des types passés en
		 * paramètres.
		 *
		 * @param Model $Model
		 * @param array $types
		 * @return array
		 */
		protected function _getFieldsByType( Model $Model, array $types ) {
			$return = array();

			foreach( $types as $type ) {
				$tmp = (array)Hash::get( $this->_fieldsByType, "{$Model->alias}.{$type}" );
				$return = array_merge( $return, $tmp );
			}

			return $return;
		}


		/**
		 * Liste des champs par type (suivant la configuration).
		 *
		 * @param Model $Model
		 * @param mixed $types
		 * @return array
		 */
		protected function _getFields( Model $Model, $types ) {
			$fields = array();

			// On prend tous les champs en compte
			if( $types === true || is_null( $types ) ) {
				$fields = array_keys( $this->_typeByField[$Model->alias] );
			}
			// Sinon, on applique une expression rationnelle sur les noms de champs
			else if( is_string( $types ) ) {
				$tmp = array_keys( $this->_typeByField[$Model->alias] );
				foreach( $tmp as $field ) {
					if( preg_match( $types, $field ) ) {
						$fields[] = $field;
					}
				}
			}
			// Si c'est un array
			else if( is_array( $types ) ) {
				// Si la clé NOT, on enlèver les champs des types spécifiés
				if( isset( $types['NOT'] ) ) {
					$except = $this->_getFieldsByType( $Model, $types['NOT'] );
					$fields = array_keys( $this->_typeByField[$Model->alias] );
					$fields = array_diff( $fields, $except );
				}
				// Sinon, on prend tous les champs des types spécifiés
				else {
					$fields = $this->_getFieldsByType( $Model, $types );
				}
			}

			return array_unique( $fields );
		}

		/**
		 * Retourne, pour chaque classe, chaque méthode, la regex sur le flatten
		 * du nom du champ.
		 *
		 * Il faut que $_typeByField[$Model->alias] et $_fieldsByType[$Model->alias]
		 * aient la bonne valeur.
		 *
		 * @param Model $Model
		 * @return array
		 */
		protected function _getRegexes( Model $Model ) {
			$regexes = array();

			foreach( $this->settings[$Model->alias] as $fullClassName => $params ) {
				if( false !== $params ) {
					foreach( $params as $formatter => $types ) {
						$fields = $this->_getFields( $Model, $types );

						if( !empty( $fields ) ) {
							$fields = '('.implode( '|', $fields ).')';
							$regex = "/(?<!\w){$Model->alias}(\.|\.[0-9]+\.){$fields}$/";
							$regexes[$fullClassName][$formatter] = $regex;
						}
					}
				}
			}

			return $regexes;
		}

		/**
		 * Chargement du cache.
		 *
		 * @param Model $Model
		 * @param boolean $force
		 */
		protected function _loadCache( Model $Model, $force = false ) {
			if( !Hash::get( $this->_cacheLoaded, $Model->alias ) || $force ) {
				$cacheKey = array( $Model->useDbConfig, __CLASS__, $Model->alias, __FUNCTION__ );
				$cacheKey = cacheKey( $cacheKey );

				$cache = Cache::read( $cacheKey );
				if( $cache === false ) {
					$this->_typeByField[$Model->alias] = $this->_getTypeByField( $Model );
					$this->_fieldsByType[$Model->alias] = groupKeysByValues( $this->_typeByField[$Model->alias] );
					$this->_regexes[$Model->alias] = $this->_getRegexes( $Model );

					$cache = array(
						'fields' => $this->_typeByField[$Model->alias],
						'types' => $this->_fieldsByType[$Model->alias],
						'regexes' => $this->_regexes[$Model->alias],
					);
					Cache::write( $cacheKey, $cache );
				}

				$this->_typeByField[$Model->alias] = $cache['fields'];
				$this->_fieldsByType[$Model->alias] = $cache['types'];
				$this->_regexes[$Model->alias] = $cache['regexes'];

				$this->_cacheLoaded[$Model->alias] = true;
			}
		}

		/**
		 * Application d'un formattage d'une classe à une valeur.
		 *
		 * @param string $fullClassName
		 * @param string $formatter
		 * @param mixed $value
		 * @return mixed
		 * @throws MissingUtilityException
		 */
		protected function _formatField( $fullClassName, $formatter, $value ) {
			if( false === isset( $this->_oFormatters[$fullClassName] ) ) {
				$this->_oFormatters[$fullClassName] = false;

				list( $pluginName, $className ) = pluginSplit( $fullClassName );

				App::uses( $className, implode( '.', array( $pluginName, 'Utility/DatabaseFormatters' ) ) );

				$paths = App::path( 'Utility', $pluginName );
				foreach( $paths as $path ) {
					$fileName = $path.'DatabaseFormatters'.DS.$className.'.php';
					if( true === file_exists( $fileName ) ) {
						include_once $fileName;
					}
				}

				if( true === class_exists( $className ) ) {
					$this->_oFormatters[$fullClassName] = new $className();
				}
			}

			if( false === $this->_oFormatters[$fullClassName] ) {
				list( $pluginName, $className ) = pluginSplit( $fullClassName );

				throw new MissingUtilityException(
					array(
						'class' => $className,
						'plugin' => $pluginName
					)
				);
			}

			return $this->_oFormatters[$fullClassName]->{$formatter}( $value );
		}

		/**
		 * Format data according to rules defined in the settings for the
		 * current model
		 *
		 * @param Model $Model
		 * @param array $data
		 * @return array
		 */
		public function doFormatting( Model $Model, $data ) {
			$this->_loadCache( $Model );

			$data = Hash::flatten( $data );
			foreach( $this->_regexes[$Model->alias] as $fullClassName => $params ) {
				foreach( $params as $formatter => $regex ) {
					foreach( $data as $key => $value ) {
						if( preg_match( $regex, $key ) ) {
							$data[$key] = $this->_formatField( $fullClassName, $formatter, $value );
						}
					}
				}
			}

			return Hash::expand( $data );
		}

		/**
		 * Formatte les champs avant la validation.
		 *
		 * @param Model $Model
		 * @param array $options
		 * @return boolean
		 */
		public function beforeValidate( Model $Model, $options = array() ) {
			$return = parent::beforeValidate( $Model, $options );
			$Model->data = $this->doFormatting( $Model, $Model->data );

			return $return;
		}

		/**
		 * Formatte les champs avant l'enregistrement.
		 *
		 * @param Model $Model
		 * @param array $options
		 * @return boolean
		 */
		public function beforeSave( Model $Model, $options = array() ) {
			$return = parent::beforeSave( $Model, $options );
			$Model->data = $this->doFormatting( $Model, $Model->data );

			return $return;
		}

	}
?>