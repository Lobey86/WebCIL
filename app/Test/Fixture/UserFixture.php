<?php
	/**
	 * Code source de la classe UserFixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	/**
	 * La classe UserFixture ...
	 *
	 * @package app.Test.Fixture
	 */
	class UserFixture extends CakeTestFixture
	{

		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = array(
			'model' => 'User',
			'records' => false
		);

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = [
			1 => [
				'password' => '*****',
				'civilite' => 'M.',
				'nom' => 'Admin',
				'prenom' => 'Super',
				'username' => 'superadmin',
				'email' => 'admin@test.fr',
				'telephonefixe' => null,
				'telephoneportable' => null,
				'createdby' => null,
				'created' => '2017-07-25 09:07:53.322381',
				'modified' => '2017-07-25 09:07:53.322381'
			],
			2 => [
				'password' => '*****',
				'civilite' => 'Mme.',
				'nom' => 'HUETTER',
				'prenom' => 'Marjorie',
				'username' => 'm.huetter',
				'email' => 'marjo@example.org',
				'telephonefixe' => null,
				'telephoneportable' => null,
				'createdby' => null,
				'created' => '2017-07-25 09:08:46',
				'modified' => '2017-07-25 09:08:46'
			],
			3 => [
				'password' => '*****',
				'civilite' => 'Mme.',
				'nom' => 'MONT',
				'prenom' => 'Amélie',
				'username' => 'a.mont',
				'email' => 'amelie.mont@test.fr',
				'telephonefixe' => null,
				'telephoneportable' => null,
				'createdby' => null,
				'created' => '2017-07-25 09:08:49',
				'modified' => '2017-07-25 09:08:49'
			],
			4 => [
				'password' => '*****',
				'civilite' => 'M.',
				'nom' => 'Guillon',
				'prenom' => 'Théo',
				'username' => 't.guillon',
				'email' => 't.guillon@test.fr',
				'telephonefixe' => null,
				'telephoneportable' => null,
				'createdby' => null,
				'created' => '2017-07-25 09:08:52',
				'modified' => '2017-07-25 09:08:52'
			],
			5 => [
				'password' => '*****',
				'civilite' => 'M.',
				'nom' => 'Gaillard',
				'prenom' => 'David',
				'username' => 'd.gaillard',
				'email' => 'david.gaillard@test.fr',
				'telephonefixe' => null,
				'telephoneportable' => null,
				'createdby' => null,
				'created' => '2017-07-25 09:08:55',
				'modified' => '2017-07-25 09:08:55'
			],
			6 => [
				'password' => '*****',
				'civilite' => 'M.',
				'nom' => 'CHANTALOU',
				'prenom' => 'David',
				'username' => 'd.chantalou',
				'email' => 'davis@example.org',
				'telephonefixe' => null,
				'telephoneportable' => null,
				'createdby' => null,
				'created' => '2017-07-25 09:08:58',
				'modified' => '2017-07-25 09:08:58'
			],
			7 => [
				'password' => '*****',
				'civilite' => 'Mme.',
				'nom' => 'Hallépée',
				'prenom' => 'Camille',
				'username' => 'c.hallepee',
				'email' => 'camille@example.org',
				'telephonefixe' => null,
				'telephoneportable' => null,
				'createdby' => null,
				'created' => '2017-07-25 09:09:01',
				'modified' => '2017-07-25 09:09:01'
			]
		];

	}
?>