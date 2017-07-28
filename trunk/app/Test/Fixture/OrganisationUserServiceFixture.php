<?php
	/**
	 * Code source de la classe OrganisationUserServiceFixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	/**
	 * La classe OrganisationUserServiceFixture ...
	 *
	 * @package app.Test.Fixture
	 */
	class OrganisationUserServiceFixture extends CakeTestFixture
	{

		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = [
			'model' => 'OrganisationUserService',
			'records' => false
		];

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = [
			1 => [
				'organisation_user_id' => 2,
				'service_id' => 1,
				'created' => '2017-07-25 13:55:35',
				'modified' => '2017-07-25 13:55:35'
			],
			2 => [
				'organisation_user_id' => 5,
				'service_id' => 2,
				'created' => '2017-07-25 13:55:45',
				'modified' => '2017-07-25 13:55:45'
			],
			3 => [
				'organisation_user_id' => 5,
				'service_id' => 1,
				'created' => '2017-07-25 13:55:45',
				'modified' => '2017-07-25 13:55:45'
			],
			4 => [
				'organisation_user_id' => 5,
				'service_id' => 3,
				'created' => '2017-07-25 13:55:45',
				'modified' => '2017-07-25 13:55:45'
			],
			5 => [
				'organisation_user_id' => 5,
				'service_id' => 4,
				'created' => '2017-07-25 13:55:45',
				'modified' => '2017-07-25 13:55:45'
			],
			6 => [
				'organisation_user_id' => 6,
				'service_id' => 6,
				'created' => '2017-07-25 13:55:49',
				'modified' => '2017-07-25 13:55:49'
			]
		];

	}
?>