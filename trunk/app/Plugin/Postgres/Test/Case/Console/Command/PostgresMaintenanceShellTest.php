<?php
	/**
	 * Code source de la classe PostgresMaintenanceShell.
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
	App::uses( 'PostgresMaintenanceShell', 'Postgres.Console/Command' );

	/**
	 * PostgresMaintenanceShellTest class
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Console.Command
	 */
	class PostgresMaintenanceShellTest extends CakeTestCase
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
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();

			$out = $this->getMock( 'ConsoleOutput', array( ), array( ), '', false );
			$in = $this->getMock( 'ConsoleInput', array( ), array( ), '', false );

			$this->Shell = $this->getMock(
				'PostgresMaintenanceShell',
				array( 'out', 'err', '_stop', 'log' ),
				array( $out, $out, $in )
			);

			$this->Shell->params['connection'] = 'test';
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Shell );
			parent::tearDown();
		}

		/**
		 * Test de la méthode PostgresMaintenanceShell::startup().
		 *
		 * @large
		 */
		public function testStartup() {
			$this->Shell->expects( $this->any() )->method( '_stop' )->with( PostgresMaintenanceShell::ERROR );
			$this->Shell->expects( $this->once() )->method( 'log' )->with( 'The datasource configuration "foo" was not found in database.php' );

			$this->Shell->params['connection'] = 'foo';
			$this->Shell->startup();
		}

		/**
		 * Test de la méthode PostgresMaintenanceShell::all().
		 *
		 * @large
		 */
		public function testAll() {
			$this->Shell->expects( $this->any() )->method( '_stop' )->with( PostgresMaintenanceShell::SUCCESS );

			$this->Shell->expects( $this->any() )->method( 'out' )->with(
				$this->logicalOr(
					$this->matchesRegularExpression( static::SHELL_GENERIC_OUT_REGEXP ),
					$this->matchesRegularExpression( '/[0-9]{2}:[0-9]{2}:[0-9]{2} \- Mise à jour des compteurs des champs auto-incrémentés \(sequences\)/' ),
					$this->matchesRegularExpression( '/[0-9]{2}:[0-9]{2}:[0-9]{2} \- Nettoyage de la base de données et mise à jour des statistiques du planificateur \(vacuum\)/' ),
					$this->matchesRegularExpression( '/[0-9]{2}:[0-9]{2}:[0-9]{2} \- Reconstruction des indexes \(reindex\)/' )
				)
			);

			$this->Shell->startup();
			$this->Shell->Dbo->getLog( false, true );
			$this->Shell->command = 'all';
			$this->Shell->all();

			$result = Hash::extract( $this->Shell->Dbo->getLog( false, false ), 'log.{n}.query' );
			$expected = array(
				'BEGIN',
				'SELECT table_name AS "Model__table",
						column_name	AS "Model__column",
						column_default AS "Model__sequence"
						FROM information_schema.columns
						WHERE table_schema = \'public\'
							AND column_default LIKE \'nextval(%::regclass)\'
						ORDER BY table_name, column_name',
				'SELECT setval(\'postgres_groups_id_seq\', COALESCE(MAX(id),0)+1, false) FROM postgres_groups;',
				'SELECT setval(\'postgres_users_id_seq\', COALESCE(MAX(id),0)+1, false) FROM postgres_users;',
				'COMMIT',
				'VACUUM ANALYZE;',
				"REINDEX DATABASE {$this->Shell->Dbo->config['database']};"
			);
			$this->assertEquals( $expected, $result );
		}

		/**
		 * Test de la méthode PostgresMaintenanceShell::reindex().
		 *
		 * @large
		 */
		public function testReindex() {
			$this->Shell->expects( $this->any() )->method( '_stop' )->with( PostgresMaintenanceShell::SUCCESS );

			$this->Shell->expects( $this->any() )->method( 'out' )->with(
				$this->logicalOr(
					$this->matchesRegularExpression( static::SHELL_GENERIC_OUT_REGEXP ),
					$this->matchesRegularExpression( '/[0-9]{2}:[0-9]{2}:[0-9]{2} \- Reconstruction des indexes \(reindex\)/' )
				)
			);

			$this->Shell->startup();
			$this->Shell->Dbo->getLog( false, true );
			$this->Shell->command = 'reindex';
			$this->Shell->reindex();

			$result = Hash::extract( $this->Shell->Dbo->getLog( false, false ), 'log.{n}.query' );
			$expected = array(
				"REINDEX DATABASE {$this->Shell->Dbo->config['database']};"
			);
			$this->assertEquals( $expected, $result );
		}

		/**
		 * Test de la méthode PostgresMaintenanceShell::sequences().
		 *
		 * @large
		 */
		public function testSequences() {
			$this->Shell->expects( $this->any() )->method( '_stop' )->with( PostgresMaintenanceShell::SUCCESS );

			$this->Shell->expects( $this->any() )->method( 'out' )->with(
				$this->logicalOr(
					$this->matchesRegularExpression( static::SHELL_GENERIC_OUT_REGEXP ),
					$this->matchesRegularExpression( '/[0-9]{2}:[0-9]{2}:[0-9]{2} \- Mise à jour des compteurs des champs auto-incrémentés \(sequences\)/' )
				)
			);

			$this->Shell->startup();
			$this->Shell->Dbo->getLog( false, true );
			$this->Shell->command = 'sequences';
			$this->Shell->sequences();

			$result = Hash::extract( $this->Shell->Dbo->getLog( false, false ), 'log.{n}.query' );
			// INFO: les commandes query( 'SELECT ...' ) sont cachées par DboSource
			$expected = array(
				'BEGIN',
				'COMMIT',
			);
			$this->assertEquals( $expected, $result );
		}

		/**
		 * Test de la méthode PostgresMaintenanceShell::vacuum().
		 *
		 * @large
		 */
		public function testVacuum() {
			$this->Shell->expects( $this->any() )->method( '_stop' )->with( PostgresMaintenanceShell::SUCCESS );

			$this->Shell->expects( $this->any() )->method( 'out' )->with(
				$this->logicalOr(
					$this->matchesRegularExpression( static::SHELL_GENERIC_OUT_REGEXP ),
					$this->matchesRegularExpression( '/[0-9]{2}:[0-9]{2}:[0-9]{2} \- Nettoyage de la base de données et mise à jour des statistiques du planificateur \(vacuum\)/' )
				)
			);

			$this->Shell->startup();
			$this->Shell->Dbo->getLog( false, true );
			$this->Shell->command = 'vacuum';
			$this->Shell->vacuum();

			$result = Hash::extract( $this->Shell->Dbo->getLog( false, false ), 'log.{n}.query' );
			$expected = array(
				'VACUUM ANALYZE;'
			);
			$this->assertEquals( $expected, $result );
		}
	}
?>