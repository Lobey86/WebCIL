<?php
	/**
	 * Code source de la classe AdminFixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	/**
	 * La classe AdminFixture ...
	 *
	 * @package app.Test.Fixture
	 */
	class AdminFixture extends CakeTestFixture
	{

		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = [
			'model' => 'Admin',
			'records' => false
		];

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = [
			1 => [
				'user_id' => 1,
				'created' => '2017-07-25 13:55:49',
				'modified' => '2017-07-25 13:55:49'
			]
		];

	}
?>