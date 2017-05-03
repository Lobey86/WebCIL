<?php
	/**
	 * Code source de la classe DatabaseCheckRelationsShellTest.
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
	App::uses( 'DatabaseCheckRelationsShell', 'Database.Console/Command' );

	/**
	 * La classe DatabaseCheckRelationsShellTest effectue les tests unitaires de
	 * la classe DatabaseCheckRelationsShell.
	 *
	 * @package Database
	 * @subpackage Test.Case.Console.Command
	 */
	class DatabaseCheckRelationsShellTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.Post',
			'core.Comment',
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
				'DatabaseCheckRelationsShell',
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
		 * Test de la méthode DatabaseCheckRelationsShell::main()
		 *
		 * @covers DatabaseCheckRelationsShell::main
		 * @covers DatabaseCheckRelationsShell::_getMissingRelations
		 * @covers DatabaseCheckRelationsShell::_getErrorMessages
		 */
		public function testMain() {
			$this->Shell->expects( $this->once() )->method( '_stop' )->with( DatabaseCheckRelationsShell::ERROR );

			$this->Shell->expects( $this->exactly( 3 ) )->method( 'err' )->with(
				$this->logicalOr(
					$this->equalTo( "<error>2 relation(s) non définie(s)</error>" ),
					$this->equalTo( "\tRelation non définie: <error>Post.id</error> -> <error>Comment.post_id</error>" ),
					$this->equalTo( "\tRelation non définie: <error>Post.post_id</error> -> <error>User.id</error>" )
				)
			);

			$this->Shell->initialize();
			$this->Shell->loadTasks();
			$this->Shell->startup();
			$this->Shell->main();
		}
	}
?>