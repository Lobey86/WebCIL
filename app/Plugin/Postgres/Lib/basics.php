<?php
	/**
	 * Fonctions utilitaires du plugin Postgres.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Lib
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	if( !function_exists( 'cacheKey' ) ) {
		/**
		 * Retourne le nom de la clé de l'entrée de cache formée de l'assemblage des
		 * paramètres.
		 *
		 * @param array $params
		 * @param boolean $underscore
		 * @return string
		 */
		function cacheKey( array $params, $underscore = false ) {
			$cacheKey = implode( '_', $params );

			if( $underscore ) {
				$cacheKey = Inflector::underscore( $cacheKey );
			}

			return $cacheKey;
		}
	}
?>