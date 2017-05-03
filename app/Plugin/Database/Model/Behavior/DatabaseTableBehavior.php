<?php
	/**
	 * Code source de la classe DatabaseTableBehavior.
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
	 * La classe DatabaseTableBehavior ajoute les méthodes suivantes aux modèles liés à une table:
	 *   - fields: retourne la liste des champs du modèle
	 *   - hasUniqueIndex: permet de savoir si une colonne d'un modèle donné a un index unique
	 *   - join: retourne un array permettant de faire une jointure ad-hoc en CakePHP
	 *   - joinAssociationData: retourne les données d'association avec le modèle aliasé (voir join)
	 *   - sql: retourne une requête SQL à partir d'un querydata (par exemple pour faire des sous-requêtes)
	 *   - types: retourne la liste des types de champs de la table liée
	 *   - uniqueIndexes: retourne la liste des indexes uniques de la table liée
	 *
	 * @package Database
	 * @subpackage Model.Behavior
	 */
	class DatabaseTableBehavior extends ModelBehavior
	{
		/**
		 * Contient une configuration à utiliser, par alias du modèle.
		 *
		 * @var array
		 */
//		public $settings = array();

		/**
		 * Le cache de l'ensemble des modèles.
		 *
		 * @var array
		 */
		protected $_cache = array();

		/**
		 * Permet de savoir si le cache à été modifié.
		 *
		 * @var boolean
		 */
		protected $_cacheChanged = false;

		/**
		 * Le nom de la clé de cache.
		 *
		 * @var string
		 */
		protected $_cacheKey = null;

		/**
		 * A la destruction de la classe, si le cache a été modifié, on
		 * l'enregistre.
		 */
		public function __destruct() {
			if( $this->_cacheChanged ) {
				Cache::write( $this->_cacheKey, $this->_cache );
			}
		}

		/**
		 * Retourne la liste des indexes uniques de la table du modèle, en clé
		 * le nom de l'index, en valeur le ou les champs qui font partie de
		 * l'index unique.
		 *
		 * @param Model $Model
		 * @return array
		 * @throws RuntimeException
		 */
		public function uniqueIndexes( Model $Model ) {
			if( $Model->useTable === false ) {
				$msgstr = __d( 'cake_dev', "Cannot get unique indexes for model \"%s\" since it does not use a table." );
				$message = sprintf( $msgstr, $Model->alias );
				throw new RuntimeException( $message, 500 );
				return array();
			}

			if( Hash::check( $this->_cache, $Model->alias ) ) {
				return $this->_cache[$Model->alias];
			}

			$this->_cacheKey = cacheKey( array( $Model->useDbConfig, __CLASS__, $Model->alias ) );
			$cache = Cache::read( $this->_cacheKey );

			if( $cache === false ) {
				$indexes = $Model->getDataSource( $Model->useDbConfig )->index( $Model );
				$this->_cache[$Model->alias] = array();

				foreach( $indexes as $name => $index ) {
					if( $index['unique'] ) {
						$this->_cache[$Model->alias][$name] = $index['column'];
					}
				}

				// Ecriture du cache en différé dans le destructeur.
				$this->_cacheChanged = true;
			}
			else {
				$this->_cache = $cache;
			}

			return $this->_cache[$Model->alias];
		}

		/**
		 * Permet de savoir si une colonne d'un modèle donné a un index unique,
		 * éventuellement avec un nom d'index donné.
		 *
		 * @param Model $Model La classe du modèle lié à la table sur laquelle
		 * 	l'index s'applique.
		 * @param mixed $columns La colonne (ou un array contenant les colonnes)
		 * 	sur laquelle l'index s'applique.
		 * @param string $expectedName Le nom de l'index (null pour ne pas vérifier)
		 * @return boolean
		 * @throws RuntimeException
		 */
		public function hasUniqueIndex( Model $Model, $columns, $expectedName = null ) {
			if( $Model->useTable === false ) {
				$msgstr = __d( 'cake_dev', "Cannot check unique index for model \"%s\" since it does not use a table." );
				$message = sprintf( $msgstr, $Model->alias );
				throw new RuntimeException( $message, 500 );
				return false;
			}

			$unique = $this->uniqueIndexes( $Model );

			if( !is_null( $expectedName ) ) {
				return ( Hash::get( $unique, $expectedName ) === $columns );
			}

			foreach( $unique as $indexName => $indexColumns ) {
				if( $columns === $indexColumns ) {
					return ( is_null( $expectedName ) || ( $indexName === $expectedName ) );
				}
			}

			return false;
		}

		/**
		 * Transforme les $querydata d'un appel "find all" en requête SQL,
		 * ce qui permet de faire des sous-requêtes moins dépendantes du SGBD.
		 *
		 * Les fields sont échappés.
		 *
		 * INFO: http://book.cakephp.org/view/74/Complex-Find-Conditions (Sub-queries)
		 *
		 * @param Model $Model
		 * @param array $querydata
		 * @return string
		 * @throws RuntimeException
		 */
		public function sql( Model $Model, array $querydata ) {
			if( $Model->useTable === false ) {
				$msgstr = __d( 'cake_dev', "Cannot generate a subquery for model \"%s\" since it does not use a table." );
				$message = sprintf( $msgstr, $Model->alias );
				throw new RuntimeException( $message, 500 );
				return 'NULL';
			}

			$Dbo = $Model->getDataSource( $Model->useDbConfig );
			$fullTableName = $Dbo->fullTableName( $Model, true, true );

			$defaults = array(
				'fields' => array( "{$Model->alias}.{$Model->primaryKey}" ),
				'order' => null,
				'group' => null,
				'limit' => null,
				'table' => $fullTableName,
				'alias' => $Model->alias,
				'conditions' => array(),
			);

			$querydata = array_merge( $defaults, $querydata );
			$querydata['fields'] = $Dbo->fields( $Model, null, $querydata['fields'] );

			return $Dbo->buildStatement( $querydata, $Model );
		}

		/**
		 * Retourne la liste des champs du modèle.
		 *
		 * @param Model $Model
		 * @param boolean $virtualFields Doit-on retourner aussi les champs virtuels ?
		 * @return array
		 * @throws RuntimeException
		 */
		public function fields( Model $Model, $virtualFields = false ) {
			if( $Model->useTable === false ) {
				$msgstr = __d( 'cake_dev', "Cannot get fields for model \"%s\" since it does not use a table." );
				$message = sprintf( $msgstr, $Model->alias );
				throw new RuntimeException( $message, 500 );

				return array( );
			}

			$fields = array( );

			// Champs de la table
			foreach( array_keys( $Model->schema() ) as $field ) {
				$fields[] = "{$Model->alias}.{$field}";
			}

			// Champs virtuels
			if( true === $virtualFields ) {
				foreach( array_keys( (array)$Model->virtualFields ) as $field ) {
					$fields[] = "{$Model->alias}.{$field}";
				}
			}

			return $fields;
		}

		/**
		 * Retourne l'alias du modèle lié ayant le nom de modèle passé en
		 * paramètre correspondant à la clé "with" de l'association.
		 *
		 * @param Model $Model
		 * @param string $needleModelName
		 * @return string
		 */
		protected function _whichHabtmModel( Model $Model, $needleModelName ) {
			foreach( $Model->hasAndBelongsToMany as $habtmModel => $habtmAssoc ) {
				if( $habtmAssoc['with'] == $needleModelName ) {
					return $habtmModel;
				}
			}

			return null;
		}

		/**
		 * Retourne les données d'association avec le modèle aliasé.
		 *
		 * @param Model $Model
		 * @param string $assocModelAlias
		 * @return array
		 * @throws RuntimeException
		 */
		public function joinAssociationData( Model $Model, $assocModelAlias ) {
			$exceptionMessage = null;

			// Is the assoc model really associated ?
			if( $Model->useTable === false ) {
				$exceptionMessage = sprintf( "Cannot generate a join from model \"%s\" since it does not use a table.", $Model->alias );
			}
			else if( !isset( $Model->{$assocModelAlias} ) ) {
				$exceptionMessage = sprintf( "Unknown association \"%s\" for model \"%s\"", $assocModelAlias, $Model->alias );
			}
			else if( $Model->{$assocModelAlias}->useTable === false ) {
				$exceptionMessage = sprintf( "Cannot generate a join from model \"%s\" to model \"%s\" since it does not use a table.", $Model->alias, $Model->{$assocModelAlias}->alias );
			}
			// Is the assoc model using the same DbConfig as the model's ?
			else if( $Model->useDbConfig != $Model->{$assocModelAlias}->useDbConfig ) {
				$exceptionMessage = sprintf( "Database configuration differs: \"%s\" (%s) and \"%s\" (%s)", $Model->alias, $Model->useDbConfig, $assocModelAlias, $Model->{$assocModelAlias}->useDbConfig );
			}

			if( !is_null( $exceptionMessage ) ) {
				throw new RuntimeException( $exceptionMessage, 500 );
				return array();
			}

			$assocModelData = $Model->getAssociated( $assocModelAlias );

			if( empty( $assocModelData ) ) {
				$whichHabtmModel = $this->_whichHabtmModel( $Model, $assocModelAlias );

				$assocModelData = Hash::get( $Model->hasAndBelongsToMany, $whichHabtmModel );
				$assocModelData['association'] = 'hasAndBelongsToMany';
			}

			return $assocModelData;
		}

		/**
		 * Retourne un array permettant de faire la jointure en CakePHP.
		 *
		 * @param Model $Model
		 * @param string $assocModelAlias
		 * @param array $params
		 * @return string
		 */
		public function join( Model $Model, $assocModelAlias, array $params = array() ) {
			$assocModelData = Hash::merge(
				(array)$this->joinAssociationData( $Model, $assocModelAlias ),
				(array)$params
			);

			// hasOne, belongsTo: OK
			$association = Hash::get( $assocModelData, 'association' );
			if( $association === 'hasMany' ) {
				$assocModelData['association'] = 'hasOne';
			}
			else if( $association === 'hasAndBelongsToMany' ) {
				$assocModelData['association'] = 'hasOne';
				$assocModelData['className'] = 'with';
			}

			$Dbo = $Model->getDataSource();

			$join = array(
				'table' => $Dbo->fullTableName( $Model->{$assocModelAlias}, true, false ),
				'alias' => $assocModelAlias,
				'type' => ( isset( $assocModelData['type'] ) ? $assocModelData['type'] : 'LEFT' ),
				'conditions' => trim(
					$Dbo->conditions(
							merge_conditions(
								(array)Hash::get( $assocModelData, 'conditions' ),
								$Dbo->getConstraint(
									Hash::get( $assocModelData, 'association' ),
									$Model,
									$Model->{$assocModelAlias},
									$assocModelAlias,
									$assocModelData
								)
							),
						true,
						false,
						$Model
					)
				)
			);

			return $join;
		}

		/**
		 * Retourne la liste des types (au sens CakePHP) de champs de la table
		 * liée au modèle.
		 *
		 * <pre>
		 * array(
		 * 	'Seance.id' => 'integer',
		 * 	'Seance.created' => 'datetime',
		 * 	'Seance.commentaire' => 'string',
		 * 	'Seance.debat_global' => 'binary',
		 * )
		 * </pre>
		 *
		 * @param Model $Model
		 * @return array
		 * @throws RuntimeException
		 */
		public function types( Model $Model ) {
			if( $Model->useTable === false ) {
				$msgstr = __d( 'cake_dev', "Cannot get field types for model \"%s\" since it does not use a table." );
				$message = sprintf( $msgstr, $Model->alias );
				throw new RuntimeException( $message, 500 );

				return array( );
			}

			$schema = $Model->schema();

			$return = array_combine( array_keys( $schema ), Hash::extract( $schema, '{s}.type' ) );
			$return = Hash::flatten( array( $Model->alias => $return ) );

			return $return;
		}

		/**
		 * Permet de décrire les jointures à appliquer sur un modèle en spécifiant
		 * uniquement les noms des modèles (et éventuellement le type, condition,
		 * alias, table) ainsi que des sous-jointures dans la clé joins, un peu
		 * à la manière des contain.
		 *
		 * @param Model $Model
		 * @param array $joins
		 * @return array
		 */
		public function joins( Model $Model, array $joins = array() ) {
			$results = array();
			$joins = array_normalize( $joins );

			foreach( $joins as $joinModel => $joinParams ) {
				if( false === is_int( $joinModel) ) {
					$joinParams = (array)$joinParams;

					$innerJoins = (array)Hash::get( $joinParams, 'joins' );
					unset( $joinParams['joins'] );

					$results[] = $Model->join( $joinModel, $joinParams );

					if( false === empty( $innerJoins ) ) {
						$results = array_merge(
							$results,
							$Model->{$joinModel}->joins( $innerJoins )
						);
					}
				}
				else {
					$results = array_merge(
						$results,
						array( $joinParams )
					);
				}
			}

			return $results;
		}
	}
?>