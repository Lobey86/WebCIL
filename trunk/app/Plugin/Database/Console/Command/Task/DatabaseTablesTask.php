<?php
	/**
	 * Code source de la classe DatabaseTablesTask.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Console.Command.Task
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	// @codeCoverageIgnoreStart
	App::uses( 'AppShell', 'Console/Command' );
	App::uses( 'Model', 'Model' );
	// @codeCoverageIgnoreEnd

	/**
	 * La classe DatabaseTablesTask permet de lire les informations des schémas
	 * des tables en base de données dès lors que celles-ci ont une classe de modèle
	 * associée et d'obtenir un dictionnaire de données au format HTML.
	 *
	 * @package Database
	 * @subpackage Console.Command.Task
	 */
	class DatabaseTablesTask extends AppShell
	{
		/**
		 * Lecture des informations à envoyer à la vue.
		 *
		 * @param array $connections
		 * @return array
		 */
		public function read( array $connections = array() ) {
			$modelNames = $this->modelsUsingTables( $connections );

			$this->out( 'Lecture des informations des tables', 1, self::NORMAL );
			$tables = array();
			foreach( $modelNames as $tableName => $modelName ) {
				$this->out( sprintf( "\t%s", $tableName ), 1, self::VERBOSE );
				$infos = $this->tableInfos( $modelName );
				$tables[$infos['useTable']] = $this->formatTableInfos( $infos );
			}

			ksort( $tables );

			return array_values( $tables );
		}

		/**
		 * Retourne la liste des modèles/tables disponibles.
		 *
		 * @param array $connections
		 * @return array
		 */
		public function modelsUsingTables( array $connections = array() ) {
			$this->out( 'Sélection des tables', 1, self::NORMAL );

			$return = array();
			$modelNames = App::objects( 'Model' );

			if( empty( $connections ) ) {
				$connections = array_keys( ConnectionManager::enumConnectionObjects() );
			}

			foreach( $modelNames as $modelName ) {
				if( $modelName !== 'AppModel' ) {
					App::uses( $modelName, 'Model' );
					$Reflection = new ReflectionClass( $modelName );
					if( false === $Reflection->isAbstract() ) {
						$Model = ClassRegistry::init( $modelName );

						if( $Model->useTable !== false && ( $Model->useDbConfig == 'test' || in_array( $Model->useDbConfig, $connections ) ) ) {
							$this->out( sprintf( "\t%s", $Model->useTable ), 1, self::VERBOSE );
							$return[$Model->useTable] = $modelName;
						}
					}
				}
			}

			return $return;
		}

		/**
		 * Lecture de diverses informations concernant une table.
		 *
		 * @param string $modelName
		 * @return array
		 */
		public function tableInfos( $modelName ) {
			$Model = ClassRegistry::init( $modelName );
			$Dbo = $Model->getDatasource();

			// Indexes
			$indexes = $Dbo->index( $Model->useTable );
			foreach( $indexes as $indexName => $indexParams ) {
				if( !$indexParams['unique'] || is_array( $indexParams['column'] ) ) {
					unset( $indexes[$indexName] );
				}
			}

			$foreignKeys = array();
			foreach( $Model->associations() as $associationName ) {
				foreach( $Model->{$associationName} as $associationAlias => $associationParams ) {
					// belongsTo: OK
					if( $associationName === 'belongsTo' ) {
						$foreignKey = Hash::get( $associationParams, 'foreignKey' );
						$foreignKeys[$foreignKey] = "{$Model->{$associationAlias}->useTable}({$Model->{$associationAlias}->primaryKey})";
					}
				}
			}

			$table = array(
				'schema' => $Model->schema(),
				'index' => $indexes,
				'foreignKeys' => $foreignKeys,
				'useTable' => $Model->useTable
			);

			return $table;
		}

		/**
		 * Transforme les informations des tables en données exploitables par la
		 * vue.
		 *
		 * @param array $tableInfos
		 * @return array
		 */
		public function formatTableInfos( array $tableInfos ) {
			$table = array(
				'Table' => array(
					'name' => $tableInfos['useTable']
				),
				'Fields' => array()
			);

			foreach( $tableInfos['schema'] as $field => $fieldParams  ) {
				// TODO: formatTable
				$type = $fieldParams['type'];
				if( !empty( $fieldParams['length'] ) ) {
					$type = "{$type}({$fieldParams['length']})";
				}

				$foreignKey = Hash::get( $tableInfos, "foreignKeys.{$field}" );
				if( empty( $foreignKey ) && Hash::get( $fieldParams, 'key') === 'primary' ) {
					$foreignKey = 'PRIMARY KEY';
					$field = "<u>{$field}</u>";
				}

				$default = $fieldParams['default'];
				if( is_null( $default ) ) {
					$default = 'NULL';
				}

				$table['Field'][] = array(
					'name' => $field,
					'type' => $type,
					'not_null' => ( $fieldParams['null'] ? null : 'NOT NULL' ),
					'default' => $default,
					'key' => $foreignKey // TODO: pas de HTML
				);
			}

			return $table;
		}

		/**
		 * Fournit le rendu HTML.
		 *
		 * @param array $tables
		 * @return string
		 */
		public function render( array $tables ) {
			$this->out( 'Écriture des tables', 1, self::NORMAL );
			$html = '';
			foreach( $tables as $infos ) {
				$this->out( sprintf( "\t%s", $infos['Table']['name'] ), 1, self::VERBOSE );
				$table = '';
				foreach( $infos['Field'] as $field  ) {
					$table .= "<tr>
							<th>{$field['name']}</th>
							<td>{$field['type']}</td>
							<td>{$field['not_null']}</td>
							<td>{$field['default']}</td>
							<td>{$field['key']}</td>
						</tr>";
				}

				$table = "<h2>{$infos['Table']['name']}</h2>
					<table>
						<thead>
							<tr>
								<th class=\"name\">Nom du champ</th>
								<th class=\"type\">Type de données</th>
								<th class=\"notnull\">Not null ?</th>
								<th class=\"default\">Défaut</th>
								<th class=\"key\">Clé</th>
							</tr>
						</thead>
						<tbody>
							{$table}
						</tbody>
					</table>";

				$html .= $table;
			}

			$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
					"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
						<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							<title>Dictionnaire de données</title>
							<style type="text/css" media="all">
								body, table { font-size: 11px; font-family: sans-serif; }
								table { border-collapse: collapse; width: 100%;}
								th, td { border: 1px solid silver; padding: 0.25em 0.5em; font-weight: normal; }
								th { background: #f0f0f0; color: black; }
								tbody th { text-align: left; }
								td { vertical-align: top; }
								tr.even td { background: #fcfcfc; }
								pre {padding: 0; margin: 0;}
								h1, h2, h3 { font-weight: normal; }
								h1 { font-size: 2.2em; }
								h2 { font-size: 1.8em; }
								h3 { font-size: 1.5em; }
								th.name		{ width: 20%; }
								th.label	{ width: 15%; }
								th.comment	{ width: 15%; }
								th.type		{ width: 15%; }
								th.size		{ width: 5%; }
								th.options	{ width: 15%; }
								th.key		{ width: 15%; }
								td ul, td li { padding: 0; margin: 0; }
								td ul { margin-left: 1.2em; }
							</style>
						</head>
						<body>'.$html.'</body></html>';
			return $html;
		}
	}
?>