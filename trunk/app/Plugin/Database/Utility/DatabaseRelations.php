<?php
	/**
	 * Code source de la classe DatabaseRelations.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe DatabaseRelations permet d'obtenir des informations sur les liaisons
	 * entre modèles à partir des classes de modèles.
	 *
	 * @package Database
	 * @subpackage Utility
	 */
	abstract class DatabaseRelations
	{
		/**
		 * La liste des alias utilisés par la méthode self::missing().
		 *
		 * @var array
		 */
		protected static $_aliases = array();

		/**
		 * Retourne les clés étrangères sous forme alias.clé => aliasLié.cléPrimaire.
		 *
		 * @param Model $Model
		 * @return array
		 */
		public static function from( Model $Model ) {
			$return = array();

			foreach( (array)$Model->belongsTo as $alias => $params ) {
				if( !empty( $params['foreignKey'] ) ) {
					$return["{$Model->alias}.{$params['foreignKey']}"] = "{$Model->{$alias}->alias}.{$Model->{$alias}->primaryKey}";
				}
			}

			ksort( $return );

			return $return;
		}

		/**
		 * Retourne les clés étrangères sous forme d'un array( alias.clé =>
		 * aliasLié.cléPrimaire ).
		 *
		 * @SuppressWarnings(PHPMD.ShortMethodName)
		 *
		 * @param Model $Model
		 * @return array
		 */
		public static function to( Model $Model ) {
			$return = array();

			foreach( (array)$Model->hasOne + (array)$Model->hasMany as $alias => $params ) {
				if( !empty( $params['foreignKey'] ) ) {
					$return["{$Model->{$alias}->alias}.{$params['foreignKey']}"] = "{$Model->alias}.{$Model->primaryKey}";
				}
			}

			foreach( (array)$Model->hasAndBelongsToMany as $alias => $params ) {
				if( !empty( $params['foreignKey'] ) ) {
					$alias = $params['with'];
					$return["{$Model->{$alias}->alias}.{$params['foreignKey']}"] = "{$Model->alias}.{$Model->primaryKey}";
				}
			}

			ksort( $return );

			return $return;
		}

		/**
		 * Retourne les modèles liés sous la forme d'un array( alias => modèle ).
		 *
		 * @param Model $Model
		 * @return array
		 */
		public static function links( Model $Model ) {
			$return = array();

			foreach( (array)$Model->belongsTo + (array)$Model->hasOne + (array)$Model->hasMany as $alias => $params ) {
				$return[$alias] = $Model->{$alias}->name;
			}

			foreach( (array)$Model->hasAndBelongsToMany as $alias => $params ) {
				$alias = $params['with'];
				$return[$alias] = $Model->{$alias}->name;
			}

			ksort( $return );

			return $return;
		}

		/**
		 * Retourne un tableau contenant les noms et alias de tous les modèles
		 * avec chacune de ces valeurs en clé.
		 *
		 * @param array $relations
		 * @return array
		 */
		public static function aliases( array $relations ) {
			$return = array();

			// Traitement des alias du nom de modèle
			foreach( $relations as $params ) {
				foreach( $params['links'] as $alias => $model ) {
					if( !isset( $return[$model] ) ) {
						$return[$model] = array_unique( array( $model, $alias ) );
					}
					else if( !in_array( $alias, $return[$model] ) ) {
						$return[$model][] = $alias;
					}
				}
			}

			// Copie des alias et du nom de modèle pour chacun des alias
			foreach( $return as $modelName => $aliases ) {
				if( count( $aliases ) > 1 ) {
					sort( $aliases );
					foreach( $aliases as $alias ) {
						if( $alias !== $modelName ) {
							$return[$alias] = $aliases;
						}
					}
				}
			}

			ksort( $return );

			return $return;
		}

		/**
		 * Retourne un array contenant en 'from', 'to' et 'links'.
		 *
		 * @param Model $Model
		 * @return array
		 */
		public static function relations( Model $Model ) {
			return array(
				'from' => self::from( $Model ),
				'to' => self::to( $Model ),
				'links' => self::links( $Model ),
			);
		}

		/**
		 * Retourne vrai si la relation a été trouvée dans l'ensemble des relations
		 * entre modèles.
		 *
		 * @param array $relations
		 * @param string $direction (from/to)
		 * @param string $fromAliasField
		 * @param string $toAliasField
		 * @return boolean
		 */
		protected static function _find( array $relations, $direction, $fromAliasField, $toAliasField ) {
			list( $fromAlias, $fromField ) = explode( '.', $fromAliasField );
			list( $toAlias, $toField ) = explode( '.', $toAliasField );

			$aliases = self::aliases( $relations );

			if( Hash::check( $aliases, $fromAlias ) && Hash::check( $aliases, $toAlias ) ) {
				foreach( $aliases[$fromAlias] as $fromAliasItem ) {
					foreach( $aliases[$toAlias] as $toAliasItem ) {
						$key = "{$fromAliasItem}.{$fromField}";
						$value = "{$toAliasItem}.{$toField}";

						$currentAlias = ( $direction === 'from' ) ? $fromAliasItem : $toAliasItem;
						// INFO: Hash::get() retournerait un mauvais résultat à cause des clés Model.champ
						if( isset( $relations[$currentAlias][$direction][$key] ) && $relations[$currentAlias][$direction][$key] == $value ) {
							return true;
						}
					}
				}
			}

			return false;
		}

		/**
		 * Retourne la liste des relations manquantes, c'est à dire les relations
		 * qui sont définies dans un des modèles et pas dans l'autre.
		 *
		 * @param array $relations
		 * @return array
		 */
		public static function missing( array $relations ) {
			$missing = array( 'from' => array(), 'to' => array() );

			foreach( $relations as $informations ) {
				foreach( $informations['from'] as $from => $to ) {
					$found = self::_find( $relations, 'to', $from, $to );
					if( true !== $found ) {
						$missing['to'][$from] = $to;
					}
				}

				foreach( $informations['to'] as $from => $to ) {
					$found = self::_find( $relations, 'from', $from, $to );
					if( true !== $found ) {
						$missing['from'][$from] = $to;
					}
				}
			}

			if( empty( $missing['from'] ) ) {
				unset( $missing['from'] );
			}
			else {
				ksort( $missing['from'] );
			}

			if( empty( $missing['to'] ) ) {
				unset( $missing['to'] );
			}
			else {
				ksort( $missing['to'] );
			}

			return $missing;
		}
	}
?>