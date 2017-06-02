<?php
	/**
	 * Fonctions utilitaires du plugin Translator.
	 *
	 * Définit les fonctions __m, __mn et __domain.
	 *
	 * PHP 5.3
	 *
	 * @package Translator
	 * @subpackage Lib
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Translator', 'Translator.Utility' );

	if( false === function_exists( '__m' ) ) {
		/**
		 * Permet d'obtenir la traduction d'une phrase de façon automatique.
		 *
		 * @param string $singular
		 * @return string
		 */
		function __m( $singular, $args = null ) {
			$instance = Translator::getInstance();
			if( !is_array( $args ) ) {
				$args = array_slice( func_get_args(), 1 );
			}
			return vsprintf( $instance::translate( $singular ), $args );
		}

	}

	if( false === function_exists( '__mn' ) ) {
		/**
		 * Permet d'obtenir la traduction d'une phrase au singulier ou au pluriel de façon automatique.
		 *
		 * @param string $singular
		 * @param string $plural
		 * @param integer $count
		 * @return string
		 */
		function __mn( $singular, $plural, $count, $args = null ) {
			$instance = Translator::getInstance();
			if( !is_array( $args ) ) {
				$args = array_slice( func_get_args(), 3 );
			}
			return vsprintf( $instance::translate( $singular, $plural, 6, $count ), $args );
		}

	}

	if( false === function_exists( '__domain' ) ) {
		/**
		 * Permet d'obtenir le nom du domaine utilisé pour une traduction
		 *
		 * @param string $singular
		 * @param string $plural
		 * @param integer $count
		 * @return string
		 */
		function __domain( $singular, $plural = null, $category = 6, $count = null, $language = null ) {
			$instance = Translator::getInstance();
			$instance::translate( $singular, $plural, $category, $count, $language, false );

			return $instance::$lastDomain;
		}

	}

	if( false === function_exists( 'core_version' ) ) {
		/**
		 * Retourne la version de CakePHP utilisée.
		 *
		 * @deprecated (pas / plus utilisée)
		 *
		 * @return string
		 */
		function core_version() {
			$versionData = array_filter( explode( "\n", file_get_contents( CAKE.'VERSION.txt' ) ) );
			$version = explode( '.', $versionData[count( $versionData ) - 1] );
			return implode( '.', $version );
		}

	}
?>