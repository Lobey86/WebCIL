<?php
	/**
	 * Code source de la classe OrganisationUserFixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	/**
	 * La classe OrganisationUserFixture ...
	 *
	 * @package app.Test.Fixture
	 */
	class OrganisationUserFixture extends CakeTestFixture
	{

		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = [
			'model' => 'OrganisationUser',
			'records' => false
		];

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = [
			1 => [
				'user_id' => 2,
				'organisation_id' => 2,
				'created' => '2017-07-25 09:08:46',
				'modified' => '2017-07-25 09:08:46',
			],
			2 => [
				'user_id' => 2,
				'organisation_id' => 1,
				'created' => '2017-07-25 09:08:46',
				'modified' => '2017-07-25 09:08:46',
			],
			3 => [
				'user_id' => 3,
				'organisation_id' => 1,
				'created' => '2017-07-25 09:08:49',
				'modified' => '2017-07-25 09:08:49',
			],
			4 => [
				'user_id' => 4,
				'organisation_id' => 1,
				'created' => '2017-07-25 09:08:52',
				'modified' => '2017-07-25 09:08:52',
			],
			5 => [
				'user_id' => 5,
				'organisation_id' => 1,
				'created' => '2017-07-25 09:08:55',
				'modified' => '2017-07-25 09:08:55',
			],
			6 => [
				'user_id' => 6,
				'organisation_id' => 2,
				'created' => '2017-07-25 09:08:58',
				'modified' => '2017-07-25 09:08:58',
			],
			7 => [
				'user_id' => 7,
				'organisation_id' => 2,
				'created' => '2017-07-25 09:09:01',
				'modified' => '2017-07-25 09:09:01',
			],
			8 => [
				'user_id' => 7,
				'organisation_id' => 1,
				'created' => '2017-07-25 09:09:01',
				'modified' => '2017-07-25 09:09:01',
			]
		];

	}
?>