<?php
	/**
	 * Code source de la classe FusionConvDebugger.
	 *
	 * PHP 5.3
	 *
	 * @package FusionConv
	 * @subpackage Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	// require_once( dirname( __FILE__ ).DS.'..'.DS.'Config'.DS.'bootstrap.php' );

	/**
	 * La classe FusionConvDebugger permet d'exporter récursivement les informations
	 * d'un GDO_PartType et de ses parties field, iteration et content au format CSV.
	 *
	 * Exemple d'utilisation:
	 * <pre>
	 * // TODO: bootstrap true n'est pas encore nécessaire
	 * Configure::write(
	 * 	'FusionConv',
	 * 	array(
	 * 		'debugHashPathsToCsv' => true,
	 * 		'debugAllPathsToCsv' => true,
	 * 		'debugAllPathsValuesToCsv' => true,
	 * 	)
	 * );
	 *
	 * $debugHashPathsToCsv = ( Configure::read( 'FusionConv.debugHashPathsToCsv' ) === true );
	 * $debugAllPathsToCsv = ( Configure::read( 'FusionConv.debugAllPathsToCsv' ) === true );
	 *
	 * if( $debugHashPathsToCsv || $debugAllPathsToCsv ) {
	 * 	CakePlugin::load( 'FusionConv', array( 'bootstrap' => true ) );
	 * 	App::uses( 'FusionConvDebugger', 'FusionConv.Utility' );
	 *
	 * 	if( $debugHashPathsToCsv ) {
	 * 		debug( FusionConvDebugger::hashPathsToCsv( $oMainPart ) );
	 * 	}
	 *
	 * 	if( $debugAllPathsToCsv ) {
	 * 		debug( FusionConvDebugger::allPathsToCsv( $oMainPart, Configure::read( 'FusionConv.debugAllPathsValuesToCsv' ) === true ) );
	 * 	}
	 *
	 * 	die();
	 * }
	 * </pre>
	 *
	 * Pour trouver les types utilisés dans un debug de
	 * <pre>
	 * grep -ri "'\(field\|matrix\|content\|drawing\|iteration\)'" oMainPart_generer_apercu_convocation_null_31_4_true.php.bak | grep -v "NULL"
	 * </pre>
	 *
	 * Voici la structure traitée par cette classe dans les fonction d'export.
	 * <pre>
	 * GDO_PartType::__set_state( array(
	 *	'content' => NULL|array(), Utilisé dans WebDelib
	 *		array(
	 *			GDO_ContentType::__set_state( array(
	 *				'name' => 'maquette_delibere.odt',
	 *				'target' => 'delibere',
	 *				'mimeType' => 'application/vnd.oasis.opendocument.text',
	 *				'url' => NULL,
	 *				'binary' => '...'
	 *				'text' => NULL,
	 *				'mode' => 'binary',
	 *			) )
	 *		)
	 *	'drawing' => NULL|array(),
	 *	'field' => NULL|array(), Utilisé dans WebDelib et WebRSA
	 *		array(
	 *			GDO_FieldType::__set_state( array(
	 *				'target' => 'nom_collectivite',
	 *				'value' => 'ADULLACT',
	 *				'dataType' => 'text',
	 *			) )
	 *		)
	 *	'iteration' => NULL|array(), Utilisé dans WebDelib et WebRSA
	 *		array(
	 *			GDO_IterationType::__set_state(array(
	 *				'name' => 'Projets',
	 *				'part' => array(
	 *					// GDO_PartType
	 *				)
	 *			) )
	 *		)
	 *	'matrix' => NULL|array(),
	 * )
	 * </pre>
	 *
	 * @package FusionConv
	 * @subpackage Utility
	 */
	abstract class FusionConvDebugger
	{
		/**
		 * Séparateur CSV.
		 *
		 * @var string
		 */
		public static $csvSeparator = ',';
		/**
		 * Séparateur des parties des clés du chemin des variables.
		 *
		 * @var string
		 */
		public static $pathSeparator = ',';

		/**
		 * La ligne de titre utilisée par self::allPathsToCsv().
		 *
		 * @var array
		 */
		public static $allPathsToCsvTitles = array(
			'Chemins',
			'Types',
			'Valeurs'
		);

		/**
		 * La ligne de titre utilisée par self::hashPathsToCsv().
		 *
		 * @var array
		 */
		public static $hashPathsToCsvTitles = array(
			'Chemins',
			'Types'
		);

		/**
		 * Permet de savoir quels sont les chemins "Hash" déjà trouver si on doit
		 * seuelement exporter les chemins "Hash".
		 *
		 * @see allPathsToCsv(), _toArray(), hashPathsToCsv(), ...
		 * @var array
		 */
		protected static $_hashPaths = array();

		/**
		 * Permet de savoir si on doit exporter tous les chemins ou seulement les
		 * chemins "Hash".
		 *
		 * @see allPathsToCsv(), _toArray(), hashPathsToCsv()
		 * @var boolean
		 */
		protected static $_returnHashPaths = false;

		/**
		 * Permet de savoir si on doit exporter les valeurs des champs ou le md5
		 * des fichiers depuis l'appel de self::allPathsToCsv().
		 *
		 *
		 * @see self::_gdoFieldTypeToArray(), self::_gdoContentTypeToArray()
		 * @var boolean
		 */
		protected static $_exportValues = false;

		/**
		 * Permet de savoir si on veut une colonne permet de traduire le nom du champ.
		 *
		 * @todo: à implémenter, avec le domaine, etc... -> dans une sous-classe
		 *
		 * @see allPathsToCsv(), hashPathsToCsv(), _lineToCsv()
		 * @var boolean
		 */
		protected static $_translateField = false;

		/**
		 * Retourne la clé d'un champ depuis les parties du chemin.
		 *
		 * @param array $parts
		 * @return string
		 */
		protected static function _keyPath( array $parts ) {
			return implode( '.', Hash::filter( $parts ) );
		}

		/**
		 * Traitement des GDO_FieldType de $Part->field.
		 *
		 * @param GDO_PartType $Part
		 * @param string $iterationName
		 * @param integer $iterationNumber
		 * @return array
		 */
		protected static function _gdoFieldTypeToArray( GDO_PartType $Part, $iterationName = null, $iterationNumber = null ) {
			$return = array();

			if( isset( $Part->field ) && !empty( $Part->field ) ) {
				foreach( $Part->field as $Field ) {
					$keyPath = self::_keyPath( array( $iterationName, $iterationNumber ) );
					$line = array();

					if( self::$_returnHashPaths ) {
						$sectionPath = self::_hashPath( self::_keyPath( array( $iterationName, $iterationNumber, $Field->target ) ) );
						if( !in_array( $sectionPath, self::$_hashPaths ) ) {
							self::$_hashPaths[] = $sectionPath;
							$line = array( $sectionPath, $Field->dataType );
						}
					}
					else {
						$keyPath = self::_keyPath( array( $iterationName, $iterationNumber, $Field->target ) );
						$line = array( $keyPath, $Field->dataType );
						if( self::$_exportValues ) {
							$line[] = $Field->value;
						}
					}

					if( !empty( $line ) ) {
						$return[] = self::_lineToCsv( $line );
					}
				}
			}

			return $return;
		}

		/**
		 * Traitement des GDO_IterationType de $Part->iteration.
		 *
		 * @param GDO_PartType $Part
		 * @param string $iterationName
		 * @param integer $iterationNumber
		 * @return array
		 */
		protected static function _gdoIterationTypeToArray( GDO_PartType $Part, $iterationName = null, $iterationNumber = null ) {
			$return = array();

			if( isset( $Part->iteration ) && !empty( $Part->iteration ) ) {
				foreach( $Part->iteration as $Iteration ) {
					foreach( $Iteration->part as $i => $Part ) {
						$keyPath = self::_keyPath( array( $iterationName, $iterationNumber, $Iteration->name ) );
						$return = array_merge( $return, self::_toArray( $Part, $keyPath, $i ) );
					}
				}
			}

			return $return;
		}

		/**
		 * Traitement des GDO_ContentType de $Part->content.
		 *
		 * @todo à tester avec WebDelib
		 *
		 * @param GDO_PartType $Part
		 * @param string $iterationName
		 * @param integer $iterationNumber
		 * @return array
		 */
		protected static function _gdoContentTypeToArray( GDO_PartType $Part, $iterationName = null, $iterationNumber = null ) {
			$return = array();

			if( isset( $Part->content ) && !empty( $Part->content ) ) {
				foreach( $Part->content as $Content ) {
					$keyPath = self::_keyPath( array( $iterationName, $iterationNumber, $Content->name ) );
					$line = array();

					if( self::$_returnHashPaths ) {
						$sectionPath = self::_hashPath( $keyPath );
						if( !in_array( $sectionPath, self::$_hashPaths ) ) {
							self::$_hashPaths[] = $sectionPath;
							$line = array( $sectionPath, $Content->mimeType );
						}
					}
					else {
						$line = array( $keyPath, $Content->mimeType, $Content->target );
						if( self::$_exportValues ) {
							$line[] = md5( $Content->binary );
						}
					}

					if( !empty( $line ) ) {
						$return[] = self::_lineToCsv( $line );
					}
				}
			}

			return $return;
		}

		/**
		 * Traitement des GDO_FieldType, GDO_IterationType et GDO_ContentType
		 * d'un GDO_PartType.
		 *
		 * On peut facilement ajouter les autres dans une sous-classe.
		 *
		 * @param GDO_PartType $Part
		 * @param string $iterationName
		 * @param integer $iterationNumber
		 * @return array
		 */
		protected static function _toArray( GDO_PartType $Part, $iterationName = null, $iterationNumber = null ) {
			return array_merge(
				self::_gdoFieldTypeToArray( $Part, $iterationName, $iterationNumber ),
				self::_gdoIterationTypeToArray( $Part, $iterationName, $iterationNumber ),
				self::_gdoContentTypeToArray( $Part, $iterationName, $iterationNumber )
			);
		}

		/**
		 * Retourne des informations concernant tous les chemins, au format CSV.
		 *
		 * @param GDO_PartType $Part
		 * @param boolean $exportValues
		 * @return string
		 */
		public static function allPathsToCsv( GDO_PartType $Part, $exportValues = false ) {
			// Initialisation
			self::$_hashPaths = array();
			self::$_returnHashPaths = false;
			self::$_exportValues = $exportValues;

			$lines = self::_toArray( $Part );
			$titles = self::$allPathsToCsvTitles;
			if( !self::$_exportValues ) {
				array_pop( $titles );
			}
			array_unshift( $lines, self::_lineToCsv( $titles ) );
			return implode( "\n", $lines );
		}

		/**
		 * Retourne des informations concernant les chemins "Hash", au format CSV.
		 *
		 * @param GDO_PartType $Part
		 * @return string
		 */
		public static function hashPathsToCsv( GDO_PartType $Part ) {
			// Initialisation
			self::$_hashPaths = array();
			self::$_returnHashPaths = true;
			self::$_exportValues = false;

			$lines = self::_toArray( $Part );
			array_unshift( $lines, self::_lineToCsv( self::$hashPathsToCsvTitles ) );
			return implode( "\n", $lines );
		}

		/**
		 * Retourne un chemin "Hash" à partir d'un chemin.
		 *
		 * @param string $sectionPath
		 * @return string
		 */
		protected static function _hashPath( $sectionPath ) {
			if( !empty( $sectionPath ) ) {
				$sectionPath = preg_replace( '/\.[0-9]+/', '.{n}', $sectionPath );
			}

			return $sectionPath;
		}

		/**
		 * Transforme un array de valeurs en une ligne au format CSV, dont les
		 * champs sont échappés. Le séparateur utilisé sera self::$csvSeparator.
		 *
		 * @param array $line
		 * @return string
		 */
		protected static function _lineToCsv( array $line ) {
			foreach( array_keys( $line ) as $index ) {
				$line[$index] = '"'.trim( addslashes( $line[$index] ) ).'"';
			}
			return implode( self::$csvSeparator, $line );
		}
	}
?>