<?php
	/**
	 * Code source de la classe RoleFixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	/**
	 * La classe RoleFixture ...
	 *
	 * @package app.Test.Fixture
	 */
	class RoleFixture extends CakeTestFixture
	{

		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = [
			'model' => 'Role',
			'records' => false
		];

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = [
			1 => [
				'libelle' => 'Rédacteur',
				'organisation_id' => 1,
				'created' => '2017-07-25 09:08:21',
				'modified' => '2017-07-25 09:08:21'
			],
			2 => [
				'libelle' => 'Valideur',
				'organisation_id' => 1,
				'created' => '2017-07-25 09:08:21',
				'modified' => '2017-07-25 09:08:21'
			],
			3 => [
				'libelle' => 'Consultant',
				'organisation_id' => 1,
				'created' => '2017-07-25 09:08:21',
				'modified' => '2017-07-25 09:08:21'
			],
			4 => [
				'libelle' => 'Administrateur',
				'organisation_id' => 1,
				'created' => '2017-07-25 09:08:21',
				'modified' => '2017-07-25 09:08:21'
			],
			5 => [
				'libelle' => 'Rédacteur',
				'organisation_id' => 2,
				'created' => '2017-07-25 09:08:25',
				'modified' => '2017-07-25 09:08:25'
			],
			6 => [
				'libelle' => 'Valideur',
				'organisation_id' => 2,
				'created' => '2017-07-25 09:08:25',
				'modified' => '2017-07-25 09:08:25'
			],
			7 => [
				'libelle' => 'Consultant',
				'organisation_id' => 2,
				'created' => '2017-07-25 09:08:25',
				'modified' => '2017-07-25 09:08:25'
			],
			8 => [
				'libelle' => 'Administrateur',
				'organisation_id' => 2,
				'created' => '2017-07-25 09:08:25',
				'modified' => '2017-07-25 09:08:25'
			],
			9 => [
				'libelle' => 'Rédacteur',
				'organisation_id' => 3,
				'created' => '2017-07-25 09:08:28',
				'modified' => '2017-07-25 09:08:28'
			],
			10 => [
				'libelle' => 'Valideur',
				'organisation_id' => 3,
				'created' => '2017-07-25 09:08:28',
				'modified' => '2017-07-25 09:08:28'
			],
			11 => [
				'libelle' => 'Consultant',
				'organisation_id' => 3,
				'created' => '2017-07-25 09:08:28',
				'modified' => '2017-07-25 09:08:28'
			],
			12 => [
				'libelle' => 'Administrateur',
				'organisation_id' => 3,
				'created' => '2017-07-25 09:08:28',
				'modified' => '2017-07-25 09:08:28'
			]
		];

	}
?>