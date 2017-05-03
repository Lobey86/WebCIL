<?php
	/**
	 * Code source de la classe PostgresForeignKeys.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	// @codeCoverageIgnoreStart
	App::uses( 'ConnectionManager', 'Model' );
	// @codeCoverageIgnoreEnd

	/**
	 * La classe PostgresForeignKeys fournit des méthodes utilitaires permettant
	 * de vérifier la correspondance entre les clés étrangères définies en base
	 * de données et celles utilisées dans la relations entre classes de modèles
	 * CakePHP.
	 *
	 * Au niveau des relations entre classes de modèles CakePHP, seule la clé
	 * "foreignKey" est prise en compte.
	 *
	 * @package Postgres
	 * @subpackage Utility
	 */
	abstract class PostgresForeignKeys
	{
		/**
		 * Live cache des modèles initialisés.
		 *
		 * @var array
		 */
		protected static $_modelCache = array();

		/**
		 * Clés étrangères présentes en base de données.
		 *
		 * @var array
		 */
		protected static $_foreignKeys = array();

		/**
		 * Modèles présents dans l'application.
		 *
		 * @var array
		 */
		protected static $_modelNames = array();

		/**
		 * Liste des clés étrangères manquantes en base de données.
		 *
		 * @var array
		 */
		protected static $_missing = array();

		/**
		 * La classe statique a-t-elle été initialisée ?
		 *
		 * @var bool
		 */
		protected static $_connexions = false;

		/**
		 * Initialisation de la classe statique: peuple les attributs $_connexions,
		 * $_modelNames et $_foreignKeys.
		 *
		 * @param string $connexion Le nom de la connexion à vérifier (null /
		 *	default par défaut).
		 * @throws RuntimeException
		 */
		protected static function _init( $connexion = null ) {
			if( false === isset( static::$_connexions[$connexion] ) ) {
				static::$_connexions[$connexion] = ConnectionManager::getDataSource( $connexion );

				// Peut-on se connecter à la DataSource ?
				$isDataSource = is_a( static::$_connexions[$connexion], 'DataSource' );
				$isConnected = $isDataSource && static::$_connexions[$connexion]->connected;
				if( false === $isDataSource || false === $isConnected ) {
					$message = sprintf( 'Impossible de se connecter avec la connexion "%s"', $connexion );
					throw new RuntimeException( $message, 500 );
				}

				if( false === ( static::$_connexions[$connexion] instanceof PostgresPostgres ) ) {
					$message = sprintf( 'La connexion "%s" n\'utilise pas le driver Postgres.PostgresPostgres', $connexion );
					throw new RuntimeException( $message, 500 );
				}

				static::$_modelNames = App::objects( 'Model' );
				if( false === empty( static::$_modelNames ) ) {
					$conditions = array(
						'From.table_schema' => static::$_connexions[$connexion]->config['schema'],
						'To.table_schema' => static::$_connexions[$connexion]->config['schema']
					);
					$foreignKeys = static::$_connexions[$connexion]->getPostgresForeignKeys( $conditions );

					static::$_foreignKeys[$connexion] = array();
					foreach( $foreignKeys as $foreignKey ) {
						static::$_foreignKeys[$connexion] = Hash::insert(
							static::$_foreignKeys[$connexion],
							"{$foreignKey['From']['table']}.from.{$foreignKey['Foreignkey']['name']}",
							$foreignKey
						);
						static::$_foreignKeys[$connexion] = Hash::insert(
							static::$_foreignKeys[$connexion],
							"{$foreignKey['To']['table']}.to.{$foreignKey['Foreignkey']['name']}",
							$foreignKey
						);
					}
				}

				static::$_missing[$connexion] = array();
			}
		}

		/**
		 * Initialise et met en live cache si besoin; retourne une instance du
		 * modèle demandé.
		 *
		 * @param string $modelName Le nom du modèle à retourner
		 * @return Model
		 */
		protected static function _model( $modelName ) {
			if( false === isset( static::$_modelCache[$modelName] ) ) {
				try {
					static::$_modelCache[$modelName] = ClassRegistry::init( $modelName );
				} catch( Exception $e ) {
					static::$_modelCache[$modelName] = false;
				}
			}

			return static::$_modelCache[$modelName];
		}

		/**
		 * Vérifie si une relation de clé étrangère existe entre deux tables,
		 * dans la connexion spécifiée, au moyen du champ de clé étrangère spécifié.
		 *
		 * @param string $connexion Le nom de la connexion à vérifier
		 * @param string $fromTable La table contenant la clé étrangère
		 * @param string $toTable La table vers laquelle pointe la clé étrangère
		 * @param string $foreignKey Le champ servant de clé étrangère
		 * @return boolean
		 */
		protected static function _exists( $connexion, $fromTable, $toTable, $foreignKey ) {
			$exists = false;

			if( true === isset( static::$_foreignKeys[$connexion][$fromTable]['from'] ) ) {
				$from = static::$_foreignKeys[$connexion][$fromTable]['from'];

				if( true === isset( static::$_foreignKeys[$connexion][$fromTable]['from'] ) ) {
					foreach( $from as $relation ) {
						$linkExists = $toTable === $relation['To']['table'];
						$foreignKeyExists = $foreignKey === $relation['From']['column'];
						if( true === $linkExists && true === $foreignKeyExists ) {
							$exists = true;
						}
					}
				}
			}

			return $exists;
		}

		/**
		 * Vérifie les relations belongsTo du modèle et complète l'attribut
		 * $_missing avec les clés étrangères manquantes en base de données.
		 *
		 * @param string $connexion Le nom de la connexion à vérifier
		 * @param string $modelName Le nom du modèle à vérifier
		 */
		protected static function _addMissingBelongsTo( $connexion, $modelName ) {
			$Model = static::_model( $modelName );

			foreach( $Model->belongsTo as $params ) {
				if( false === empty( $params['foreignKey'] ) ) {
					$fromTable = $Model->useTable;
					$toTable = static::_model( $params['className'] )->useTable;
					$exists = static::_exists( $connexion, $fromTable, $toTable, $params['foreignKey'] );

					if( false === $exists ) {
						static::$_missing[$connexion][$fromTable][$params['foreignKey']] = $toTable;
					}
				}
			}
		}

		/**
		 * Vérifie les relations hasOne et hasMany du modèle et complète l'attribut
		 * $_missing avec les clés étrangères manquantes en base de données.
		 *
		 * @param string $connexion Le nom de la connexion à vérifier
		 * @param string $modelName Le nom du modèle à vérifier
		 */
		protected static function _addMissingHasOneHasMany( $connexion, $modelName ) {
			$Model = static::_model( $modelName );

			foreach( array( 'hasOne', 'hasMany' ) as $assoc ) {
				foreach( $Model->{$assoc} as $params ) {
					if( false === empty( $params['foreignKey'] ) ) {
						$toTable = $Model->useTable;
						$fromTable = static::_model( $params['className'] )->useTable;

						$exists = static::_exists( $connexion, $fromTable, $toTable, $params['foreignKey'] );

						if( false === $exists ) {
							static::$_missing[$connexion][$fromTable][$params['foreignKey']] = $toTable;
						}
					}
				}
			}
		}

		/**
		 * Vérifie les relations hasAndBelongsToMany du modèle et complète l'attribut
		 * $_missing avec les clés étrangères manquantes en base de données.
		 *
		 * @param string $connexion Le nom de la connexion à vérifier
		 * @param string $modelName Le nom du modèle à vérifier
		 */
		protected static function _addMissingHasAndBelongsToMany( $connexion, $modelName ) {
			$Model = static::_model( $modelName );

			foreach( $Model->hasAndBelongsToMany as $params ) {
				if( false === empty( $params['foreignKey'] ) ) {
					$with = $params['with'];
					$fromTable = static::_model( $with )->useTable;
					$toTable = $Model->useTable;
					$exists = static::_exists( $connexion, $fromTable, $toTable, $params['foreignKey'] );

					if( false === $exists ) {
						static::$_missing[$connexion][$fromTable][$params['foreignKey']] = $toTable;
					}
				}
			}
		}

		/**
		 * Retourne un array contenant les clés étrangères manquantes en base de
		 * données par-rapport aux relations définies entre classes de modèles
		 * CakePHP.
		 *
		 * @param string $connexion Le nom de la connexion à vérifier.
		 * @return array
		 */
		public static function missing( $connexion = null ) {
			$connexion = null === $connexion ? 'default' : $connexion;
			static::_init( $connexion );

			foreach( static::$_modelNames as $modelName ) {
				$Model = static::_model( $modelName );

				if( false === empty( $Model ) && $connexion === $Model->useDbConfig ) {
					static::_addMissingBelongsTo( $connexion, $modelName );
					static::_addMissingHasOneHasMany( $connexion, $modelName );
					static::_addMissingHasAndBelongsToMany( $connexion, $modelName );
				}
			}

			return static::$_missing[$connexion];
		}

		/**
		 * Supprime les différents live caches de l'objet.
		 */
		public static function clear() {
			static::$_modelCache = array();
			static::$_foreignKeys = array();
			static::$_modelNames = array();
			static::$_missing = array();
			static::$_connexions = false;
		}
	}
?>