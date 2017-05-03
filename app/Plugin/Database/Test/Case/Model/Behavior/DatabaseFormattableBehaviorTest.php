<?php
	/**
	 * Code source de la classe DatabaseFormattableBehaviorTest.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Test.Case.Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Model', 'Model' );
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe DatabaseFormattableBehaviorTest effectue les tests unitaires de
	 * la classe DatabaseFormattableBehavior.
	 *
	 * @package Database
	 * @subpackage Test.Case.Model.Behavior
	 */
	class DatabaseFormattableBehaviorTest extends CakeTestCase
	{
		/**
		 * Fixtures associated with this test case
		 *
		 * @var array
		 */
		public $fixtures = array(
			'plugin.Database.DatabaseSite',
		);

		/**
		 * Jeu de données et de résultats attendus.
		 *
		 * @var array
		 */
		public $records = array(
			array(
				'data' => array(
					'Site' => array(
						'name' => ' X   ',
						'price' => ' '
					)
				),
				'expected' => array(
					'Site' => array(
						'name' => 'X',
						'price' => null
					)
				)
			),
			array(
				'data' => array(
					'Site' => array(
						'id' => null,
						'name' => ' X   ',
						'user_id' => '255_25',
						'price' => ' 6 666,987 ',
						'published' => null,
						'description' => null,
						'birthday' => ' ',
						'birthtime' => ' ',
						'document' => ' XXX '
					)
				),
				'expected' => array(
					'Site' => array(
						'id' => null,
						'name' => 'X',
						'user_id' => 25,
						'price' => 6666.987,
						'published' => null,
						'description' => null,
						'birthday' => null,
						'birthtime' => null,
						'document' => ' XXX '
					)
				)
			),
		);

		/**
		 * Method executed before each test
		 */
		public function setUp() {
			parent::setUp();

			ClassRegistry::flush();
			Cache::clear();

			App::build(
				array(
					'Utility' => array(
						CakePlugin::path( 'Database' ) . 'Utility' . DS,
						CakePlugin::path( 'Database' ) . 'Test' . DS . 'test_app' . DS . 'Utility' . DS
					)
				),
				App::REGISTER
			);

			$this->Site = ClassRegistry::init( array('class' => 'Database.DatabaseSite', 'alias' => 'Site') );
		}

		/**
		 * Method executed after each test
		 */
		public function tearDown() {
			unset( $this->Site );
			parent::tearDown();
		}

		/**
		 * Test de la méthode DatabaseFormattableBehavior::beforeValidate() du
		 * plugin Database.
		 *
		 * @return void
		 */
		public function testBeforeValidate() {
			$this->Site->Behaviors->attach( 'Database.DatabaseFormattable' );

			foreach( $this->records as $record ) {
				$this->Site->create( $record['data'] );
				$this->Site->validates();
				$result = $this->Site->data;
				$this->assertEquals( $record['expected'], $result, var_export( $result, true ) );
			}
		}

		/**
		 * Test de la méthode DatabaseFormattableBehavior::beforeSave() du
		 * plugin Database.
		 *
		 * @return void
		 */
		public function testBeforeSave() {
			$this->Site->Behaviors->attach( 'Database.DatabaseFormattable' );

			foreach( $this->records as $record ) {
				$this->Site->create( $record['data'] );
				$this->Site->Behaviors->trigger( 'beforeSave', array( $this->Site ) );
				$result = $this->Site->data;

				$this->assertEquals( $record['expected'], $result, var_export( $result, true ) );
			}
		}

		/**
		 * Tet de la méthode DatabaseFormattableBehavior::doFormatting() lorsqu'une
		 * autre classe de formattage est utilisée.
		 */
		public function testOtherFormattingClass() {
			$config = array(
				'Database.DatabaseDefaultFormatter' => false,
				'Database.DatabaseTestFormatters' => array(
					'formatStar' => true,
				)
			);
			$this->Site->Behaviors->attach( 'Database.DatabaseFormattable', $config );

			$result = $this->Site->doFormatting( array( 'Site' => array( 'id' => 5, 'name' => 'FooBar' ) ) );
			$expected = array(
				'Site' => array(
					'id' => '*',
					'name' => '******'
				)
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseFormattableBehavior::doFormatting() lorsqu'une
		 * exception est renvoyée.
		 *
		 * @expectedException MissingUtilityException
		 *
		 * @return void
		 */
		public function testDoFormattingException() {
			$config = array(
				'Database.DatabaseDefaultFormatter' => false,
				'Database.DatabaseTestFormatters' => false,
				'InexistantFormatter' => array(
					'formatNull' => true,
				)
			);

			$this->Site->Behaviors->attach( 'Database.DatabaseFormattable', $config );
			$this->Site->doFormatting( array( 'Site' => array( 'id' => 5 ) ) );
		}
	}
?>