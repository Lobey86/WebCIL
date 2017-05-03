<?php
	/**
	 * Classes d'exceptions du plugin Database.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Lib.Error
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	// @codeCoverageIgnore
	if( !class_exists( 'MissingUtilityException' ) ) {
		/**
		 * Missing Utility exception - utilisée lorsqu'une classe utilitaire est
		 * manquante.
		 *
		 * @package Database
		 * @subpckage Lib.Error
		 */
		class MissingUtilityException extends CakeException
		{
			/**
			 * Le gabarit du message d'erreur.
			 *
			 * @var string
			 */
			protected $_messageTemplate = 'Utility class %s could not be found.';

		}
	}