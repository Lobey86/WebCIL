<?php
	/**
	 * Code source de la classe FusionConvAbstractConverter.
	 *
	 * PHP 5.3
	 *
	 * @package FusionConv
	 * @subpackage Utility.Converter
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Interface pour les classes descendantes de FusionConvAbstractConverter.
	 *
	 * @package FusionConv
	 * @subpackage Utility.Converter
	 */
	interface FusionConvConverterInterface
	{
		/**
		 * Initialisation et conversion du contenu d'un fichier d'un format vers
		 * un autre.
		 *
		 * @param string $content Le contenu du fichier à convertir
		 * @param string $inputFormat Le format d'entrée du fichier.
		 * @param string $outputFormat Le format de sortie du fichier.
		 * @return string
		 */
		static function convert( $content, $inputFormat = 'odt', $outputFormat = 'pdf' );


        /**
         * @param string $fileName
         * @return string
         */
//		protected static function _fileFormat( $fileName ) {
//			if( preg_match( '/\.(odt|pdf)/', $fileName, $matches ) ) {
//				return $matches[1];
//			}
//
//			return null;
//		}

        /**
         * @param string $inputFile
         * @param string $outputFile
         */
//		public static function convertFile( $inputFile, $outputFile ) {
//			$inputFormat = self::_fileFormat( $inputFile );
//			$outputFormat = self::_fileFormat( $outputFile );
//		}
	}

	/**
	 * La classe FusionConvAbstractConverter ...
	 *
	 * @package FusionConv
	 * @subpackage Utility.Converter
	 */
	abstract class FusionConvAbstractConverter implements FusionConvConverterInterface
	{
        /**
         * Retourne la valeur lue dans le path de self::$configure sous la clé
         * $configureKey ou la valeur par défaut, tant que la valeur est null.
         *
         * @todo Se servir du plugin Configured, qui reste à écrire
         *
         * @param array $configured
         * @param string $configureKey
         * @param mixed $currentValue
         * @return mixed
         */
        protected static function _configured( $configured, $configureKey, $currentValue = null ) {
			if( is_null( $currentValue ) ) {
				$configuredPath = Hash::get( $configured, "{$configureKey}.path" );
				$currentValue = Configure::read( $configuredPath );

				if( is_null( $currentValue ) ) {
					$currentValue = Hash::get( $configured, "{$configureKey}.default" );
				}
			}

			return $currentValue;
		}
	}