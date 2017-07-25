<?php
	/**
	 * Code source de la classe ServiceFixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	/**
	 * La classe ServiceFixture ...
	 *
	 * @package app.Test.Fixture
	 */
	class ServiceFixture extends CakeTestFixture
	{

		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = [
			'model' => 'Service',
			'records' => false
		];

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = [
			1 => [
				'libelle' => 'Service Gratuité',
				'organisation_id' => 1,
				'created' => '2017-07-25 09:08:30',
				'modified' => '2017-07-25 09:08:30'
			],
			2 => [
				'libelle' => 'Service Abonnements',
				'organisation_id' => 1,
				'created' => '2017-07-25 09:08:32',
				'modified' => '2017-07-25 09:08:32'
			],
			3 => [
				'libelle' => 'Service Immobilier',
				'organisation_id' => 1,
				'created' => '2017-07-25 09:08:35',
				'modified' => '2017-07-25 09:08:35'
			],
			4 => [
				'libelle' => 'Service Transport',
				'organisation_id' => 1,
				'created' => '2017-07-25 09:08:37',
				'modified' => '2017-07-25 09:08:37'
			],
			5 => [
				'libelle' => 'Service cuillère',
				'organisation_id' => 2,
				'created' => '2017-07-25 09:08:40',
				'modified' => '2017-07-25 09:08:40'
			],
			6 => [
				'libelle' => 'Service des armées',
				'organisation_id' => 2,
				'created' => '2017-07-25 09:08:42',
				'modified' => '2017-07-25 09:08:42'
			]
		];

	}
?>