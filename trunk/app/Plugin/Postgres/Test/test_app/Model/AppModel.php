<?php
	/**
	 * Code source de la classe AppModel.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Model', 'Model' );

	/**
	 * Classe parente AppModel
	 *
	 * @package Postgres
	 * @subpackage Test.Model
	 */
	class AppModel extends Model
	{
		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = -1;
	}
?>