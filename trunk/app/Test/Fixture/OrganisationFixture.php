<?php
	/**
	 * Code source de la classe OrganisationFixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	/**
	 * La classe OrganisationFixture ...
	 *
	 * @package app.Test.Fixture
	 */
	class OrganisationFixture extends CakeTestFixture
	{

		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = [
			'model' => 'Organisation',
			'records' => false
		];

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = [
			1 => [
				'raisonsociale' => 'Montpellier Méditerranée Métropole',
				'telephone' => '0467136000',
				'fax' => null,
				'adresse' => '50 Place de Zeus, 34000 Montpellier',
				'email' => 'montpellier-3m@test.fr',
				'sigle' => null,
				'siret' => '24340001700022',
				'ape' => '8411Z',
				'logo' => null,
				'nomresponsable' => 'SAUREL',
				'prenomresponsable' => 'Philippe',
				'emailresponsable' => 'psaurel@test.fr',
				'telephoneresponsable' => '0601020304',
				'fonctionresponsable' => 'Maire',
				'cil' => 2,
				'numerocil' => '001',
				'created' => '2017-07-25 09:08:21',
				'modified' => '2017-07-25 09:09:37'
			],
			2 => [
				'raisonsociale' => 'Librishop',
				'telephone' => '0400000000',
				'fax' => null,
				'adresse' => '42 rue du blizzard',
				'email' => 'david@example.org',
				'sigle' => 'LS',
				'siret' => '65050134900015',
				'ape' => '12321',
				'logo' => null,
				'nomresponsable' => 'GAILLARD',
				'prenomresponsable' => 'David',
				'emailresponsable' => 'david@example.org',
				'telephoneresponsable' => '0400000000',
				'fonctionresponsable' => 'DG',
				'cil' => 7,
				'numerocil' => '002',
				'created' => '2017-07-25 09:08:25',
				'modified' => '2017-07-25 09:09:21'
			],
			3 => [
				'raisonsociale' => 'CISV',
				'telephone' => '0101010101',
				'fax' => '0101010102',
				'adresse' => "666 avenue Général Leclerc\n34470 Pérols",
				'email' => 'cisv@cil.fr',
				'sigle' => null,
				'siret' => '49101169800025',
				'ape' => '6661A',
				'logo' => null,
				'nomresponsable' => 'ORWELL',
				'prenomresponsable' => 'George',
				'emailresponsable' => 'g.orwell@cisv.fr',
				'telephoneresponsable' => '0101010103',
				'fonctionresponsable' => 'Président directeur général',
				'cil' => null,
				'numerocil' => null,
				'created' => '2017-07-25 09:08:28',
				'modified' => '2017-07-25 09:08:28'
			]
		];

	}
?>