<?php
	/**
	 * Code source de la classe PostgresPostgres.
	 *
	 * @package Postgres
	 * @subpackage Model.Datasource.Database
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	// @codeCoverageIgnoreStart
	App::uses( 'Postgres', 'Model/Datasource/Database' );
	// @codeCoverageIgnoreEnd

	/**
	 * Surcouche au driver Postgres de CakePHP avec des méthodes spécifiques
	 * au SGBD PostgreSQL.
	 *
	 * @package Postgres
	 * @subpackage Model.Datasource.Database
	 */
	class PostgresPostgres extends Postgres
	{
		/**
		 * Permet d'obtenir la version de PostgreSQL utilisée.
		 *
		 * @param boolean $full false pour obtenir uniquement le numéro de version
		 * @return string
		 */
		public function getPostgresVersion( $full = false ) {
			$version = $this->query( 'SELECT version();' );
			$version = Hash::get( $version, '0.0.version' );

			if( !$full ) {
				$version = preg_replace( '/.*PostgreSQL ([^ ]+) .*$/', '\1', $version );
			}

			return $version;
		}

		/**
		 * Retourne la liste des fonctions PostgreSQL disponibles (schema, name,
		 * result, arguments).
		 *
		 * Permet de déprécier Pgsqlcake.PgsqlSchemaBehavior::pgFunctions().
		 *
		 * @param array $conditions Conditions supplémentaires éventuelles.
		 * @return array
		 */
		public function getPostgresFunctions( array $conditions = array() ) {
			$sql = "SELECT
						pg_namespace.nspname AS \"Function__schema\",
						pg_proc.proname AS \"Function__name\",
						FORMAT_TYPE( pg_proc.prorettype, NULL ) AS \"Function__result\",
						OIDVECTORTYPES( pg_proc.proargtypes ) AS \"Function__arguments\"
					FROM pg_proc
						INNER JOIN pg_namespace ON ( pg_proc.pronamespace = pg_namespace.oid )
					WHERE
						pg_proc.prorettype <> 0
						AND (
							pg_proc.pronargs = 0
							OR OIDVECTORTYPES( pg_proc.proargtypes ) <> ''
						)
						".( !empty( $conditions ) ? ' AND '.implode( ' AND ', $conditions ) : '' )."
					ORDER BY
						\"Function__schema\",
						\"Function__name\",
						\"Function__result\",
						\"Function__arguments\";";

			return $this->query( $sql );
		}

		/**
		 * Vérification de la syntaxe d'un morceau de code SQL par PostgreSQL,
		 * en utilisant la méthode EXPLAIN $sql.
		 *
		 * Retourne un arry avec les clés success (boolean) et message qui contient
		 * un éventuel message d'erreur ou la valeur NULL en cas de succès.
		 *
		 * @param string $sql
		 * @return array
		 */
		public function checkPostgresSqlSyntax( $sql ) {
			try {
				$success = ( $this->query( "EXPLAIN {$sql}" ) !== false );
				$message = null;
			} catch( Exception $e ) {
				$success = false;
				$message = $this->lastError();
			}

			return array(
				'success' => $success,
				'message' => $message,
				'value' => $sql,
			);
		}

		/**
		 * Permet de vérifier la syntaxe d'un intervalle au sens PostgreSQL.
		 *
		 * Permet de déprécier Pgsqlcake.PgsqlSchemaBehavior::pgCheckIntervalSyntax().
		 *
		 * @param string $interval
		 * @return array
		 */
		public function checkPostgresIntervalSyntax( $interval ) {
			$sql = "SELECT NOW() + interval '{$interval}'";
			$result = $this->checkPostgresSqlSyntax( $sql );
			$result['value'] = $interval;

			return $result;
		}

		/**
		 * Retourne la liste des clés étrangères présentes en base de données.
		 *
		 * @param array $conditions
		 * @param bool $cache
		 * @return array
		 */
		public function getPostgresForeignKeys( array $conditions = array(), $cache = null ) {
			$cache = ( null === $cache ) ? ( 0 == Configure::read( 'debug' ) ) : (bool)$cache;
			$conditions = $this->conditions( $conditions, true, false );

			$cacheKey = sprintf( "%s_%s_%s_%s", $this->configKeyName, __CLASS__, __FUNCTION__, md5( $conditions ) );
			$results = ( false === $cache ) ? false : Cache::read( $cacheKey );

			if( false === $results ) {
				$sql = "SELECT
					tc.constraint_name AS \"Foreignkey__name\",
					\"Foreignkey\".update_rule AS \"Foreignkey__onupdate\",
					\"Foreignkey\".delete_rule AS \"Foreignkey__ondelete\",
					\"From\".table_schema AS \"From__schema\",
					\"From\".table_name AS \"From__table\",
					\"From\".column_name AS \"From__column\",
					( CASE WHEN kcc.is_nullable = 'NO' THEN false ELSE true END ) AS \"From__nullable\",
					EXISTS(
						SELECT
								*
							FROM pg_catalog.pg_class c, pg_catalog.pg_class c2, pg_catalog.pg_index i
							WHERE
								c.oid = (
									SELECT
											c.oid
										FROM pg_catalog.pg_class c
										INNER JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
										WHERE
											c.relname = \"From\".table_name
											AND pg_catalog.pg_table_is_visible(c.oid)
											AND n.nspname = \"From\".table_schema
								)
								AND c.oid = i.indrelid
								AND i.indexrelid = c2.oid
								AND i.indisunique
								AND regexp_replace( pg_catalog.pg_get_indexdef(i.indexrelid, 0, true), E'^.*\\\\((.*)\\\\)$', E'\\\\1', 'g') = \"From\".column_name
					) AS \"From__unique\",
					\"To\".table_schema AS \"To__schema\",
					\"To\".table_name AS \"To__table\",
					\"To\".column_name AS \"To__column\",
					( CASE WHEN ccc.is_nullable = 'NO' THEN false ELSE true END ) AS \"To__nullable\",
					EXISTS(
						SELECT
								*
							FROM pg_catalog.pg_class c, pg_catalog.pg_class c2, pg_catalog.pg_index i
							WHERE
								c.oid = (
									SELECT
											c.oid
										FROM pg_catalog.pg_class c
										INNER JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
										WHERE
											c.relname = \"To\".table_name
											AND pg_catalog.pg_table_is_visible(c.oid)
											AND n.nspname = \"To\".table_schema
								)
								AND c.oid = i.indrelid
								AND i.indexrelid = c2.oid
								AND i.indisunique
								AND regexp_replace( pg_catalog.pg_get_indexdef(i.indexrelid, 0, true), E'^.*\\\\((.*)\\\\)$', E'\\\\1', 'g') = \"To\".column_name
					) AS \"To__unique\"
				FROM information_schema.table_constraints tc
					INNER JOIN information_schema.key_column_usage AS \"From\" ON (
						tc.constraint_catalog = \"From\".constraint_catalog
						AND tc.constraint_schema = \"From\".constraint_schema
						AND tc.constraint_name = \"From\".constraint_name
					)
					INNER JOIN information_schema.referential_constraints AS \"Foreignkey\" ON (
						tc.constraint_catalog = \"Foreignkey\".constraint_catalog
						AND tc.constraint_schema = \"Foreignkey\".constraint_schema
						AND tc.constraint_name = \"Foreignkey\".constraint_name
					)
					INNER JOIN information_schema.constraint_column_usage AS \"To\" ON (
						\"Foreignkey\".unique_constraint_catalog = \"To\".constraint_catalog
						AND \"Foreignkey\".unique_constraint_schema = \"To\".constraint_schema
						AND \"Foreignkey\".unique_constraint_name = \"To\".constraint_name
					)
					INNER JOIN information_schema.columns kcc ON (
						\"From\".table_schema = kcc.table_schema
						AND \"From\".table_name = kcc.table_name
						AND \"From\".column_name = kcc.column_name
					)
					INNER JOIN information_schema.columns ccc ON (
						\"To\".table_schema = ccc.table_schema
						AND \"To\".table_name = ccc.table_name
						AND \"To\".column_name = ccc.column_name
					)
				WHERE
					tc.constraint_type = 'FOREIGN KEY'
					AND {$conditions}
					ORDER BY tc.constraint_name ASC;";

				if( false === $cache ) {
					$sql .= '/* '.microtime( true ).' */';
				}
				$results = $this->query( $sql );

				if( true === $cache ) {
					Cache::write( $cacheKey, $results );
				}
			}

			return $results;
		}

		/**
		 * Retourne la liste des contraintes de type check concernant la table
		 * liée au modèle.
		 *
		 * @param Model $Model
		 * @return array
		 */
		public function getPostgresCheckConstraints( array $conditions = array() ) {
			$conditions = $this->conditions( $conditions, true, false );

			$cacheKey = sprintf( "%s_%s_%s_%s", $this->configKeyName, __CLASS__, __FUNCTION__, md5( $conditions ) );
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$sql = "SELECT
							pg_namespace.nspname AS \"Constraint__schema\",
							pg_class.relname AS \"Constraint__table\",
							pg_constraint.conname AS \"Constraint__name\",
							pg_constraint.consrc AS \"Constraint__clause\"
						FROM pg_catalog.pg_constraint
							INNER JOIN pg_catalog.pg_class ON (
								pg_class.oid = pg_constraint.conrelid
							)
							INNER JOIN pg_catalog.pg_namespace ON (
								pg_namespace.oid = pg_class.relnamespace
							)
						WHERE
							pg_constraint.contype = 'c'
							AND {$conditions}
						ORDER BY pg_constraint.conname;";

				$results = $this->query( $sql );
				Cache::write( $cacheKey, $results );
			}

			return $results;
		}

		/**
		 * Méthode utilitaire permettant de savoir si une contrainte de clé
		 * étrangère existe dans le schéma de la base de données.
		 *
		 * @param string $fromTable
		 * @param string $fromColumn
		 * @param string $toTable
		 * @param string $toColumn
		 * @param type $cache
		 * @return bool
		 */
		public function existsPostgresForeignKey( $fromTable, $fromColumn, $toTable, $toColumn, $cache = null ) {
			$conditions = array(
				'From.table_schema' => $this->config['schema'],
				'From.table_name' => $fromTable,
				'From.column_name' => $fromColumn,
				'To.table_schema' => $this->config['schema'],
				'To.table_name' => $toTable,
				'To.column_name' => $toColumn
			);
			$result = $this->getPostgresForeignKeys( $conditions, $cache );
			return false === empty( $result );
		}

		/**
		 * Méthode utilitaire permettant d'ajouter une contrainte de clé étrangère
		 * dans le schéma de la base de données.
		 *
		 * @param string $fromTable
		 * @param string $fromColumn
		 * @param string $toTable
		 * @param string $toColumn
		 * @return bool
		 */
		public function addPostgresForeignKey( $fromTable, $fromColumn, $toTable, $toColumn ) {
			$params = array(
				'{schema}' => $this->config['schema'],
				'{fromTable}' => $fromTable,
				'{fromColumn}' => $fromColumn,
				'{toTable}' => $toTable,
				'{toColumn}' => $toColumn
			);
			$sql = 'ALTER TABLE "{schema}"."{fromTable}" ADD CONSTRAINT "{fromTable}_{fromColumn}_fk" FOREIGN KEY ("{fromColumn}") REFERENCES "{schema}"."{toTable}"("{toColumn}") ON UPDATE NO ACTION ON DELETE NO ACTION /* '.microtime( true ).' */;';
			$sql = str_replace( array_keys( $params ), array_values( $params ), $sql );
			return false !== $this->query( $sql );
		}

		/**
		 * Méthode utilitaire permettant de supprimer une contrainte de clé étrangère
		 * dans le schéma de la base de données.
		 *
		 * @param string $fromTable
		 * @param string $fromColumn
		 * @return bool
		 */
		public function dropPostgresForeignKey( $fromTable, $fromColumn ) {
			$params = array(
				'{schema}' => $this->config['schema'],
				'{fromTable}' => $fromTable,
				'{fromColumn}' => $fromColumn
			);
			$sql = 'ALTER TABLE "{schema}"."{fromTable}" DROP CONSTRAINT "{fromTable}_{fromColumn}_fk" /* '.microtime( true ).' */;';
			$sql = str_replace( array_keys( $params ), array_values( $params ), $sql );

			return false !== $this->query( $sql );
		}
	}
?>