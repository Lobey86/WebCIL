<?php
    /**
     * Code source de la classe DatabaseTestFormatters.
     *
     * PHP 5.3
     *
     * @package Database
     * @subpackage Utility.DatabaseFormatters
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */

    /**
	 * La classe DatabaseTestFormatters fournit une méthode de forattage utilisée
	 * par les tests unitaires de la classe DatabaseFormattableBehavior.
	 *
	 * @fixme: doc
     *
     * @package Database
     * @subpackage Utility.DatabaseFormatters
     */
	class DatabaseTestFormatters
	{
		/**
		 * Retourne une étoile à la place de chacun des caratères.
		 *
		 * @param mixed $value
		 * @return string
		 */
		public static function formatStar( $value ) {
			return str_repeat( '*', strlen( (string)$value ) );
		}
	}
?>
