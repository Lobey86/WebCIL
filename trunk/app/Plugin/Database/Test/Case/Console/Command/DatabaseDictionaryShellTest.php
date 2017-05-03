<?php
	/**
	 * Code source de la classe DatabaseDictionaryShellTest.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Test.Case.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConsoleOutput', 'Console' );
	App::uses( 'ConsoleInput', 'Console' );
	App::uses( 'ShellDispatcher', 'Console' );
	App::uses( 'Shell', 'Console' );
	App::uses( 'AppShell', 'Console/Command' );
	App::uses( 'DatabaseDictionaryShell', 'Database.Console/Command' );

	/**
	 * La classe DatabaseDictionaryShellTest effectue les tests unitaires de
	 * la classe DatabaseDictionaryShell.
	 *
	 * @package Database
	 * @subpackage Test.Case.Console.Command
	 */
	class DatabaseDictionaryShellTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.User',
			'core.Post',
			'core.Comment',
			'core.Author',
			'core.Tag',
			'core.PostsTag',
			'plugin.Database.DatabaseFichedeliaison',
			'plugin.Database.DatabaseService66'
		);

		/**
		 *
		 * @var AppShell
		 */
		public $Shell = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();

			ClassRegistry::flush();
			App::build(
				array(
					'Model' => array( CakePlugin::path( 'Database' ) . 'Test' . DS . 'test_app' . DS . 'Model' . DS )
				),
				App::RESET
			);

			$out = $this->getMock( 'ConsoleOutput', array( ), array( ), '', false );
			$in = $this->getMock( 'ConsoleInput', array( ), array( ), '', false );

			$this->Shell = $this->getMock(
				'DatabaseDictionaryShell',
				array( 'out', 'err', '_stop' ),
				array( $out, $out, $in )
			);
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Shell );
			parent::tearDown();
		}

		/**
		 * Test de la méthode DatabaseDictionaryShell::main()
		 *
		 * @covers DatabaseDictionaryShell::main
		 * @covers DatabaseTablesTask::formatTableInfos
		 * @covers DatabaseTablesTask::modelsUsingTables
		 * @covers DatabaseTablesTask::read
		 * @covers DatabaseTablesTask::render
		 * @covers DatabaseTablesTask::tableInfos
		 */
		public function testMain() {
			$file = TMP.'dictionary.html';
			$this->Shell->expects( $this->any() )->method( '_stop' )->with( 0 );

			$this->Shell->params['connection'] = 'default';
			$this->Shell->params['file'] = $file;

			$this->Shell->initialize();
			$this->Shell->loadTasks();
			$this->Shell->startup();
			$this->Shell->main();

			$content = file_get_contents( $file );
			unlink( $file );

			$this->assertRegexp( '/<h2>posts<\/h2>/', $content );
			$this->assertRegexp( '/<th><u>id<\/u><\/th>/', $content );
		}
	}
?>