<?php
	/**
	 * Code source de la classe FusionConvConverterUnoconv.
	 *
	 * PHP 5.3
	 *
	 * @package FusionConv
	 * @subpackage Utility.Converter
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Debugger', 'Utility' );
	App::uses( 'FusionConvAbstractConverter', 'FusionConv.Utility/Converter' );

	/**
	 * La classe FusionConvConverterUnoconv ...
     *
     * @fixme Le code est totalement incorrect, c'est simplement un copié/collé
     *  du contenu d'une méthode d'une classe équivalente du plugin Gedooo (WebRSA).
	 *
	 * @package FusionConv
	 * @subpackage Utility.Converter
	 */
	abstract class FusionConvConverterUnoconv extends FusionConvAbstractConverter
	{
		/**
		 * Le chemin vers l'exécutable unoconv
		 *
		 * @var string
		 */
		protected static $_path = null;

		/**
		 * Le chemin vers le répertoire temporaire.
		 *
		 * @var string
		 */
		protected static $_tmpDir = null;

        /**
         * Les chemins vers les variables dans la configuration CakePHP:
         *  - FusionConv.FusionConvConverterUnoconv.path (string)
         *
         * @var array
         */
        public static $configured = array(
			'path' => array(
                'path' => 'FusionConv.FusionConvConverterUnoconv.path',
                'type' => 'string',
                'default' => '/usr/bin/unoconv',
			),
			'tmp_dir' => array(
                'path' => 'FusionConv.FusionConvConverterUnoconv.tmp_dir',
                'type' => 'string',
                'default' => TMP,
			),
		);

		/**
		 * Initialisation: si le chemin vers l'exécutable pas été spécifié, on
		 * essaie de lire sa valeur configurée sous la clé
		 * FusionConv.FusionConvConverterUnoconv.path (string).
		 * FusionConv.FusionConvConverterUnoconv.path (string).
		 */
		protected static function _init() {
			self::$_path = self::_configured( self::$configured, 'path', self::$_path );
			self::$_tmpDir = self::_configured( self::$configured, 'tmp_dir', self::$_tmpDir );
		}

		/**
		 * Initialisation et conversion du contenu d'un fichier d'un format vers
		 * un autre.
		 *
		 * @fixme Pas encore implémenté
		 *
		 * @param string $content Le contenu du fichier à convertir
		 * @param string $inputFormat Le format d'entrée du fichier.
		 * @param string $outputFormat Le format de sortie du fichier.
		 * @return string
		 */
		public static function convert( $content, $inputFormat = 'odt', $outputFormat = 'pdf' ) {
			self::_init();
			return false;

//			if( empty( self::$_path ) ) {
//				Debugger::log( 'Exécutable unoconv non spécifié' , LOG_ERROR );
//				return false;
//			}
			// exécution
//			$fileName = escapeshellarg( $fileName );
//			$cmd = "LANG=fr_FR.UTF-8; {$bin} -f {$format} --stdout {$fileName}";
//			$result = shell_exec( $cmd );

			// guess that if there is less than this characters probably an error
//			if( strlen( $result ) < 10 ) {
//				Debugger::log( sprintf( "Résultat de la conversion erroné: %s", $result ) , LOG_ERROR );
//				return false;
//			}

//			return $result;
		}
	}