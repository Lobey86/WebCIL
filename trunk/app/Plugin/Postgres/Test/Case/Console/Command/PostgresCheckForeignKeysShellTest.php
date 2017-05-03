<?php
	/**
	 * Code source de la classe PostgresCheckForeignKeysShell.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConsoleOutput', 'Console' );
	App::uses( 'ConsoleInput', 'Console' );
	App::uses( 'ShellDispatcher', 'Console' );
	App::uses( 'Shell', 'Console' );
	App::uses( 'ConnectionManager', 'Model' );
	App::uses( 'PostgresCheckForeignKeysShell', 'Postgres.Console/Command' );
	App::uses( 'PostgresForeignKeys', 'Postgres.Utility' );

	/**
	 * PostgresCheckForeignKeysShellTest class
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Console.Command
	 */
	class PostgresCheckForeignKeysShellTest extends CakeTestCase
	{
		/**
		 * Constante à utiliser avec la méthode matchesRegularExpression sur les
		 * mock.
		 * Il s'agit d'une expression régulière pour matcher les lignes d'en-tetes
		 * des shells, envoyées à la méthode mockée "out".
		 */
		const SHELL_GENERIC_OUT_REGEXP = '/^(<info>Welcome.*||App *:.*|Path *:.*|PostgreSQL [0-9]+.*|\-+)$/';

		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'plugin.Postgres.PostgresGroup',
			'plugin.Postgres.PostgresUser',
		);

		/**
		 * La connexion utilisée par les tests.
		 *
		 * @var DataSource
		 */
		public $Dbo = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();

			Configure::write( 'Cache.disable', true );
			Cache::clear();
			PostgresForeignKeys::clear();

			App::build(
				array(
					'Model' => array(
						CakePlugin::path( 'Postgres' ) . 'Test' . DS . 'test_app' . DS . 'Model' . DS
					)
				),
				App::RESET
			);

			$out = $this->getMock( 'ConsoleOutput', array( ), array( ), '', false );
			$in = $this->getMock( 'ConsoleInput', array( ), array( ), '', false );

			$this->Shell = $this->getMock(
				'PostgresCheckForeignKeysShell',
				array( 'out', 'err', '_stop', 'log' ),
				array( $out, $out, $in )
			);

			$this->Shell->params['connection'] = 'test';
			$this->Dbo = ConnectionManager::getDataSource( 'test' );
			$this->Dbo->getLog( false, true );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			if( true === $this->Dbo->existsPostgresForeignKey( 'postgres_users', 'group_id', 'postgres_groups', 'id' ) ) {
				$this->Dbo->dropPostgresForeignKey( 'postgres_users', 'group_id' );
			}
			$this->Dbo->addPostgresForeignKey( 'postgres_users', 'group_id', 'postgres_groups', 'id' );
			unset( $this->Shell, $this->Dbo );
			parent::tearDown();
		}

		/**
		 * Test de la méthode PostgresCheckForeignKeysShell::startup().
		 */
		public function testStartup() {
			$this->Shell->expects( $this->any() )->method( '_stop' )->with( PostgresCheckForeignKeysShell::ERROR );
			$this->Shell->expects( $this->once() )->method( 'log' )->with( 'The datasource configuration "foo" was not found in database.php' );

			$this->Shell->params['connection'] = 'foo';
			$this->Shell->startup();
		}

		/**
		 * Test de la méthode PostgresCheckForeignKeysShell::missing() sans clé
		 * étrangère manquante.
		 */
		public function testMissingWithoutErrors() {
			$this->Shell->expects( $this->any() )->method( '_stop' )->with( PostgresCheckForeignKeysShell::SUCCESS );

			$this->Shell->startup();
			$this->Shell->command = 'missing';
			$this->Shell->missing();
		}

		/**
		 * Test de la méthode PostgresCheckForeignKeysShell::missing() avec des
		 * clés étrangères manquantes.
		 */
		public function testMissingWithErrors() {
			$this->Dbo->dropPostgresForeignKey( 'postgres_users', 'group_id' );
			$this->Shell->expects( $this->any() )->method( '_stop' )->with( PostgresCheckForeignKeysShell::ERROR );

			$this->Shell->expects( $this->exactly( 2 ) )->method( 'err' )->with(
				$this->logicalOr(
					$this->equalTo( 'Clés étrangères manquantes en base de données (connexion "test") mais présentes dans les modèles' ),
					$this->equalTo( "\tdepuis <info>postgres_users</info> vers <info>postgres_groups</info>, champ <info>group_id</info>" )
				)
			);

			$this->Shell->startup();
			$this->Shell->command = 'missing';
			$this->Shell->missing();
		}
	}
?>