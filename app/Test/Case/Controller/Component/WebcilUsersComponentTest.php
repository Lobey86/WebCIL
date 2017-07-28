<?php
	/**
	 * Code source de la classe WebcilUsersComponentTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'AppController', 'Controller' );
	App::uses( 'WebcilUsersComponent', 'Controller/Component' );
	App::uses( 'CakeTestSession', 'CakeTest.Model/Datasource' );
	App::uses( 'ListeDroit', 'Model' );

	/**
	 * WebcilUsersTestsController class
	 *
	 * @package app.Test.Case.Controller.Component
	 */
	class WebcilUsersTestsController extends AppController
	{

		/**
		 * name property
		 *
		 * @var string
		 */
		public $name = 'WebcilUsersTestsController';

		/**
		 * uses property
		 *
		 * @var mixed null
		 */
		public $uses = ['Users'];

		/**
		 * components property
		 *
		 * @var array
		 */
		public $components = ['WebcilUsers'];

	}
	/**
	 * La classe WebcilUsersComponentTest effectue les tests unitaires de la
	 * classe WebcilUsersComponent.
	 *
	 * sudo chown www-data /var/lib/php5/sess_00000000000000000000000000
	 * sudo -u www-data ant quality -f app/Vendor/Jenkins/build.xml
	 *
	 * @package app.Test.Case.Controller.Component
	 */
	class WebcilUsersComponentTest extends CakeTestCase
	{

		/**
		 * Fixtures utilisées.
		 *
		 * @var array
		 */
		public $fixtures = [
			'app.ListeDroit',
			'app.Organisation',
			'app.OrganisationUser',
			'app.OrganisationUserRole',
			'app.Role',
			'app.RoleDroit',
			'app.Service',
			'app.User'
		];

		/**
		 * Le contrôleur.
		 *
		 * @var WebcilUsersComponent
		 */
		public $Controller;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();

			$Request = new CakeRequest( 'users/index', false );
			$Request->addParams(['controller' => 'users', 'action' => 'index']);

			$this->Controller = new WebcilUsersTestsController( $Request );
			$this->Controller->Components->init( $this->Controller );
			$this->Controller->WebcilUsers->initialize( $this->Controller );

			CakeTestSession::start();
			CakeTestSession::destroy();
		}

		/**
		 * Prépare la session avec les données d'un super administrateur.
		 */
		protected function setupSuperadministrateur() {
			$session = [
				'Su' => true,
				'Organisation.id' => 1,
				'Auth.User.id' => 1,
				'Droit' => [
					'liste' => []
				],
				'User' => ['service' => []]
			];
			CakeTestSession::write($session);
		}

		/**
		 * Prépare la session avec les données d'un simple administrateur.
		 */
		protected function setupAdministrateur() {
			$session = [
				'Su' => false,
				'Organisation.id' => 1,
				'Auth.User.id' => 2,
				'Droit' => [
					'liste' => [1, 2, 3, 4, 7, 8, 9, 10, 12, 13, 14, 15 ]
				],
				'User' => ['service' => []]
			];
			CakeTestSession::write($session);
		}

		/**
		 * Test de la méthode WebcilUsersComponent::organisations() pour un
		 * super admin.
		 */
		public function testOrganisationsSuperAdmin() {
			$this->setupSuperadministrateur();

			// 1. Type "list"
			$result = $this->Controller->WebcilUsers->organisations('list');
			$expected = [
				3 => 'CISV',
				2 => 'Librishop',
				1 => 'Montpellier Méditerranée Métropole',
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Type "first"
			$result = $this->Controller->WebcilUsers->organisations('first');
			$expected = [
				'Organisation' => [
					'id' => 3,
					'raisonsociale' => 'CISV',
					'telephone' => '0101010101',
					'fax' => '0101010102',
					'adresse' => "666 avenue Général Leclerc\n34470 Pérols",
					'email' => 'cisv@cil.fr',
					'sigle' => NULL,
					'siret' => '49101169800025',
					'ape' => '6661A',
					'logo' => NULL,
					'nomresponsable' => 'ORWELL',
					'prenomresponsable' => 'George',
					'emailresponsable' => 'g.orwell@cisv.fr',
					'telephoneresponsable' => '0101010103',
					'fonctionresponsable' => 'Président directeur général',
					'cil' => NULL,
					'numerocil' => NULL,
					'created' => '2017-07-25 09:08:28',
					'modified' => '2017-07-25 09:08:28',
				]
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Type "query" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->organisations('query', ['fields' => ['id', 'raisonsociale']]);
			$expected = [
				'fields' => ['id','raisonsociale'],
				'conditions' => [],
				'order' => ['Organisation.raisonsociale ASC']
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 4. Type "list" avec le paramètre "droits"
			$result = $this->Controller->WebcilUsers->organisations('list',['droits' => ListeDroit::CREER_UTILISATEUR]);
			$expected = [
				3 => 'CISV',
				2 => 'Librishop',
				1 => 'Montpellier Méditerranée Métropole',
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebcilUsersComponent::organisations() pour un
		 * simple admin.
		 */
		public function testOrganisationsAdmin() {
			$this->setupAdministrateur();

			// 1. Type "list"
			$result = $this->Controller->WebcilUsers->organisations('list');
			$expected = [
				2 => 'Librishop',
				1 => 'Montpellier Méditerranée Métropole'
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Type "first"
			$result = $this->Controller->WebcilUsers->organisations('first');
			$expected = [
				'Organisation' => [
					'id' => 2,
					'raisonsociale' => 'Librishop',
					'telephone' => '0400000000',
					'fax' => NULL,
					'adresse' => '42 rue du blizzard',
					'email' => 'david@example.org',
					'sigle' => 'LS',
					'siret' => '65050134900015',
					'ape' => '12321',
					'logo' => NULL,
					'nomresponsable' => 'GAILLARD',
					'prenomresponsable' => 'David',
					'emailresponsable' => 'david@example.org',
					'telephoneresponsable' => '0400000000',
					'fonctionresponsable' => 'DG',
					'cil' => 7,
					'numerocil' => '002',
					'created' => '2017-07-25 09:08:25',
					'modified' => '2017-07-25 09:09:21',
				]
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Type "query" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->organisations('query', ['fields' => ['id', 'raisonsociale']]);
			$expected = [
				'fields' => ['id','raisonsociale'],
				'conditions' => [
					'EXISTS( SELECT "organisations_users"."id" AS "organisations_users__id" FROM "public"."organisations_users" AS "organisations_users"   WHERE "organisations_users"."organisation_id" = "Organisation"."id" AND "organisations_users"."user_id" = 2 )'
				],
				'order' => ['Organisation.raisonsociale ASC']
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 4. Type "list" avec le paramètre "droits"
			$result = $this->Controller->WebcilUsers->organisations('list', ['droits' => ListeDroit::CREER_UTILISATEUR]);
			$expected = [
				2 => 'Librishop',
				1 => 'Montpellier Méditerranée Métropole'
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebcilUsersComponent::roles() pour un
		 * super admin.
		 */
		public function testRolesSuperAdmin() {
			$this->setupSuperadministrateur();

			// 1. Type "list"
			$result = $this->Controller->WebcilUsers->roles('list');
			$expected = [
				'Administrateur' => 'Administrateur',
				'Consultant' => 'Consultant',
				'Rédacteur' => 'Rédacteur',
				'Valideur' => 'Valideur'
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Type "list" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->roles('list', ['fields' => ['id', 'libelle', 'organisation_id']]);
			$expected = [
				1 => [
					4 => 'Administrateur',
					3 => 'Consultant',
					1 => 'Rédacteur',
					2 => 'Valideur',
				],
				2 => [
					8 => 'Administrateur',
					7 => 'Consultant',
					5 => 'Rédacteur',
					6 => 'Valideur',
				],
				3 => [
					12 => 'Administrateur',
					11 => 'Consultant',
					9 => 'Rédacteur',
					10 => 'Valideur',
				]
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Type "query" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->roles('query', ['fields' => ['id', 'libelle', 'organisation_id']]);
			$expected = [
				'fields' => ['id', 'libelle', 'organisation_id'],
				'conditions' => [],
				'joins' => [
					[
						'table' => '"organisations"',
						'alias' => 'Organisation',
						'type' => 'INNER',
						'conditions' => '"Role"."organisation_id" = "Organisation"."id"'
					]
				],
				'order' => ['Role.libelle ASC', 'Role.id ASC']
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebcilUsersComponent::organisations() pour un
		 * simple admin.
		 */
		public function testRolesAdmin() {
			$this->setupAdministrateur();

			// 1. Type "list"
			$result = $this->Controller->WebcilUsers->roles('list');
			$expected = [
				'Administrateur' => 'Administrateur',
				'Consultant' => 'Consultant',
				'Rédacteur' => 'Rédacteur',
				'Valideur' => 'Valideur'
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Type "list" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->roles('list', ['fields' => ['id', 'libelle', 'organisation_id']]);
			$expected = [
				1 => [
					4 => 'Administrateur',
					3 => 'Consultant',
					1 => 'Rédacteur',
					2 => 'Valideur'
				],
				2 => [
					8 => 'Administrateur',
					7 => 'Consultant',
					5 => 'Rédacteur',
					6 => 'Valideur'
				]
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Type "query" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->roles('query', ['fields' => ['id', 'libelle', 'organisation_id']]);
			$expected = [
				'fields' => ['id', 'libelle', 'organisation_id'],
				'conditions' => [
					'EXISTS( SELECT "organisations_users"."id" AS "organisations_users__id" FROM "public"."organisations_users" AS "organisations_users"   WHERE "organisations_users"."organisation_id" = "Organisation"."id" AND "organisations_users"."user_id" = 2 )',
				],
				'joins' => [
					[
						'table' => '"organisations"',
						'alias' => 'Organisation',
						'type' => 'INNER',
						'conditions' => '"Role"."organisation_id" = "Organisation"."id"'
					]
				],
				'order' => ['Role.libelle ASC', 'Role.id ASC']
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebcilUsersComponent::services() pour un
		 * super admin.
		 */
		public function testServicesSuperAdmin() {
			$this->setupSuperadministrateur();

			// 1. Type "list"
			$result = $this->Controller->WebcilUsers->services('list');
			$expected = [
				'Service Abonnements' => 'Service Abonnements',
				'Service cuillère' => 'Service cuillère',
				'Service des armées' => 'Service des armées',
				'Service Gratuité' => 'Service Gratuité',
				'Service Immobilier' => 'Service Immobilier',
				'Service Transport' => 'Service Transport'
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Type "list" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->services('list', ['fields' => ['id', 'libelle', 'organisation_id']]);
			$expected = [
				1 => [
					2 => 'Service Abonnements',
					1 => 'Service Gratuité',
					3 => 'Service Immobilier',
					4 => 'Service Transport'
				],
				2 => [
					5 => 'Service cuillère',
					6 => 'Service des armées'
				]
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Type "query" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->services('query', ['fields' => ['id', 'libelle', 'organisation_id']]);
			$expected = [
				'fields' => ['id', 'libelle', 'organisation_id'],
				'conditions' => [],
				'joins' => [
					[
						'table' => '"organisations"',
						'alias' => 'Organisation',
						'type' => 'INNER',
						'conditions' => '"Service"."organisation_id" = "Organisation"."id"'
					]
				],
				'order' => ['Service.libelle ASC', 'Service.id ASC']
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebcilUsersComponent::services() pour un
		 * simple admin.
		 */
		public function testServicesAdmin() {
			$this->setupAdministrateur();

			// 1. Type "list"
			$result = $this->Controller->WebcilUsers->services('list');
			$expected = [
				'Service Abonnements' => 'Service Abonnements',
				'Service Gratuité' => 'Service Gratuité',
				'Service Immobilier' => 'Service Immobilier',
				'Service Transport' => 'Service Transport',
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Type "list" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->services('list', ['fields' => ['id', 'libelle', 'organisation_id']]);
			$expected = [
				1 => [
					2 => 'Service Abonnements',
					1 => 'Service Gratuité',
					3 => 'Service Immobilier',
					4 => 'Service Transport'
				]
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Type "query" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->services('query', ['fields' => ['id', 'libelle', 'organisation_id']]);
			$expected = [
				'fields' => ['id', 'libelle', 'organisation_id'],
				'conditions' => [
					['Service.organisation_id' => 1]
				],
				'joins' => [
					[
						'table' => '"organisations"',
						'alias' => 'Organisation',
						'type' => 'INNER',
						'conditions' => '"Service"."organisation_id" = "Organisation"."id"'
					]
				],
				'order' => ['Service.libelle ASC', 'Service.id ASC']
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebcilUsersComponent::users() pour un
		 * super admin.
		 */
		public function testUsersSuperAdmin() {
			$this->setupSuperadministrateur();

			// 1. Type "list"
			$result = $this->Controller->WebcilUsers->users('list');
			$expected = [
				3 => 'Mme. Amélie MONT',
				7 => 'Mme. Camille Hallépée',
				6 => 'M. David CHANTALOU',
				5 => 'M. David Gaillard',
				2 => 'Mme. Marjorie HUETTER',
				1 => 'M. Super Admin',
				4 => 'M. Théo Guillon'
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Type "list" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->users('list', ['fields' => ['id', 'username']]);
			$expected = [
				3 => 'a.mont',
				7 => 'c.hallepee',
				6 => 'd.chantalou',
				5 => 'd.gaillard',
				2 => 'm.huetter',
				1 => 'superadmin',
				4 => 't.guillon'
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Type "query" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->users('query', ['fields' => ['id', 'username']]);
			$expected = [
				'fields' => ['id', 'username'],
				'conditions' => [],
//				'joins' => [],
				'order' => ['User.nom_complet_court ASC'],
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebcilUsersComponent::users() pour un
		 * simple admin.
		 */
		public function testUsersAdmin() {
			$this->setupAdministrateur();

			// 1. Type "list"
			$result = $this->Controller->WebcilUsers->users('list');
			$expected = [
				3 => 'Mme. Amélie MONT',
				7 => 'Mme. Camille Hallépée',
				5 => 'M. David Gaillard',
				2 => 'Mme. Marjorie HUETTER',
				4 => 'M. Théo Guillon'
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Type "list" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->users('list', ['fields' => ['id', 'username']]);
			$expected = [
				3 => 'a.mont',
				7 => 'c.hallepee',
				5 => 'd.gaillard',
				2 => 'm.huetter',
				4 => 't.guillon'
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Type "query" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->users('query', ['fields' => ['id', 'username']]);
			$expected = [
				'fields' => ['id', 'username'],
				'conditions' => [
					'User.id IN ( SELECT "organisations_users"."user_id" AS "organisations_users__user_id" FROM "public"."organisations_users" AS "organisations_users" INNER JOIN "public"."organisations" AS "organisations" ON ("organisations_users"."organisation_id" = "organisations"."id")  WHERE "organisations_users"."user_id" = "User"."id" AND "organisations_users"."organisation_id" = 1 )',
				],
				'order' => ['User.nom_complet_court ASC'],
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>