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

			// 2. Type "all" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->organisations('all', ['fields' => ['id', 'raisonsociale']]);
			$expected = [
				[
					'Organisation' => [
						'id' => 3,
						'raisonsociale' => 'CISV',
					]
				],
				[
					'Organisation' => [
						'id' => 2,
						'raisonsociale' => 'Librishop',
					]
				],
				[
					'Organisation' => [
						'id' => 1,
						'raisonsociale' => 'Montpellier Méditerranée Métropole',
					]
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

			// 4. Avec le paramètre "droits"
			$result = $this->Controller->WebcilUsers->organisations('list', ['droits' => ListeDroit::CREER_UTILISATEUR]);
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

			// 2. Type "all" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->organisations('all', ['fields' => ['id', 'raisonsociale']]);
			$expected = [
				[
					'Organisation' => [
						'id' => 2,
						'raisonsociale' => 'Librishop',
					]
				],
				[
					'Organisation' => [
						'id' => 1,
						'raisonsociale' => 'Montpellier Méditerranée Métropole',
					]
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

			// 4. Avec le paramètre "droits"
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

			// 2. Type "all" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->roles('all', ['fields' => ['id', 'libelle', 'organisation_id']]);
			$expected = [
				[
					'Role' => [
						'id' => 4,
						'libelle' => 'Administrateur',
						'organisation_id' => 1,
					],
				],
				[
					'Role' => [
						'id' => 8,
						'libelle' => 'Administrateur',
						'organisation_id' => 2,
					],
				],
				[
					'Role' => [
						'id' => 12,
						'libelle' => 'Administrateur',
						'organisation_id' => 3,
					],
				],
				[
					'Role' => [
						'id' => 3,
						'libelle' => 'Consultant',
						'organisation_id' => 1,
					],
				],
				[
					'Role' => [
						'id' => 7,
						'libelle' => 'Consultant',
						'organisation_id' => 2,
					],
				],
				[
					'Role' => [
						'id' => 11,
						'libelle' => 'Consultant',
						'organisation_id' => 3,
					],
				],
				[
					'Role' => [
						'id' => 1,
						'libelle' => 'Rédacteur',
						'organisation_id' => 1,
					],
				],
				[
					'Role' => [
						'id' => 5,
						'libelle' => 'Rédacteur',
						'organisation_id' => 2,
					],
				],
				[
					'Role' => [
						'id' => 9,
						'libelle' => 'Rédacteur',
						'organisation_id' => 3,
					],
				],
				[
					'Role' => [
						'id' => 2,
						'libelle' => 'Valideur',
						'organisation_id' => 1,
					],
				],
				[
					'Role' => [
						'id' => 6,
						'libelle' => 'Valideur',
						'organisation_id' => 2,
					],
				],
				[
					'Role' => [
						'id' => 10,
						'libelle' => 'Valideur',
						'organisation_id' => 3,
					],
				],
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

			// 2. Type "all" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->roles('all', ['fields' => ['id', 'libelle', 'organisation_id']]);
			$expected = [
				[
					'Role' => [
						'id' => 4,
						'libelle' => 'Administrateur',
						'organisation_id' => 1,
					],
				],
				[
					'Role' => [
						'id' => 3,
						'libelle' => 'Consultant',
						'organisation_id' => 1,
					],
				],
				[
					'Role' => [
						'id' => 1,
						'libelle' => 'Rédacteur',
						'organisation_id' => 1,
					],
				],
				[
					'Role' => [
						'id' => 2,
						'libelle' => 'Valideur',
						'organisation_id' => 1,
					],
				],
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Type "query" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->roles('query', ['fields' => ['id', 'libelle', 'organisation_id']]);
			$expected = [
				'fields' => ['id', 'libelle', 'organisation_id'],
				'conditions' => [
					'Role.organisation_id' => 1,
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

			// 2. Type "all" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->services('all', ['fields' => ['id', 'libelle']]);
			$expected = [
				[
					'Service' => [
						'id' => 2,
						'libelle' => 'Service Abonnements'
					]
				],
				[
					'Service' => [
						'id' => 5,
						'libelle' => 'Service cuillère'
					]
				],
				[
					'Service' => [
						'id' => 6,
						'libelle' => 'Service des armées'
					]
				],
				[
					'Service' => [
						'id' => 1,
						'libelle' => 'Service Gratuité'
					]
				],
				[
					'Service' => [
						'id' => 3,
						'libelle' => 'Service Immobilier'
					]
				],
				[
					'Service' => [
						'id' => 4,
						'libelle' => 'Service Transport'
					]
				],
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

			// 2. Type "all" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->services('all', ['fields' => ['id', 'libelle']]);
			$expected = [
				[
					'Service' => [
						'id' => 2,
						'libelle' => 'Service Abonnements'
					]
				],
				[
					'Service' => [
						'id' => 1,
						'libelle' => 'Service Gratuité'
					]
				],
				[
					'Service' => [
						'id' => 3,
						'libelle' => 'Service Immobilier'
					]
				],
				[
					'Service' => [
						'id' => 4,
						'libelle' => 'Service Transport'
					]
				],
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

			// 2. Type "all" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->users('all', ['fields' => ['id', 'username']]);
			$expected = [
				[
					'User' => [
						'id' => 3,
						'username' => 'a.mont'
					]
				],
				[
					'User' => [
						'id' => 7,
						'username' => 'c.hallepee'
					]
				],
				[
					'User' => [
						'id' => 6,
						'username' => 'd.chantalou'
					]
				],
				[
					'User' => [
						'id' => 5,
						'username' => 'd.gaillard'
					]
				],
				[
					'User' => [
						'id' => 2,
						'username' => 'm.huetter'
					]
				],
				[
					'User' => [
						'id' => 1,
						'username' => 'superadmin'
					]
				],
				[
					'User' => [
						'id' => 4,
						'username' => 't.guillon'
					]
				],
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

			// 2. Type "all" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->users('all', ['fields' => ['id', 'username']]);
			$expected = [
				[
					'User' => [
						'id' => 3,
						'username' => 'a.mont'
					]
				],
				[
					'User' => [
						'id' => 7,
						'username' => 'c.hallepee'
					]
				],
				[
					'User' => [
						'id' => 5,
						'username' => 'd.gaillard'
					]
				],
				[
					'User' => [
						'id' => 2,
						'username' => 'm.huetter'
					]
				],
				[
					'User' => [
						'id' => 4,
						'username' => 't.guillon'
					]
				],
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Type "query" avec la clé "fields"
			$result = $this->Controller->WebcilUsers->users('query', ['fields' => ['id', 'username']]);
			$expected = [
				'fields' => ['id', 'username'],
				'conditions' => [
					'User.id IN ( SELECT "organisations_users"."user_id" AS "organisations_users__user_id" FROM "public"."organisations_users" AS "organisations_users" INNER JOIN "public"."organisations" AS "organisations" ON ("organisations_users"."organisation_id" = "organisations"."id")  WHERE "organisations_users"."user_id" = "User"."id" AND "organisations_users"."organisation_id" = 1 )',
				],
//				'joins' => [],
				'order' => ['User.nom_complet_court ASC'],
			];
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>