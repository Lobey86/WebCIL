<?php
	/**
	 * Code source de la classe PostgresExtraValidationRulesBehaviorTest.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Model.Behavior
	 */
	App::uses( 'Model', 'Model' );
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe PostgresExtraValidationRulesBehaviorTest...
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Model.Behavior
	 */
	class PostgresExtraValidationRulesBehaviorTest extends CakeTestCase
	{
		/**
		 * Fixtures associated with this test case
		 *
		 * @var array
		 */
		public $fixtures = array(
			'plugin.Postgres.PostgresGroup'
		);

		/**
		 * Method executed before each test
		 *
		 */
		public function setUp() {
			ClassRegistry::flush();
			Cache::clear();

			App::build(
				array(
					'Model' => array(
						CakePlugin::path( 'Postgres' ) . 'Test' . DS . 'test_app' . DS . 'Model' . DS
					)
				),
				App::RESET
			);

			$this->Group = ClassRegistry::init( 'Group' );
			$this->Group->Behaviors->attach( 'Postgres.PostgresExtraValidationRules' );
		}

		/**
		 * Method executed after each test
		 *
		 */
		public function tearDown() {
			unset( $this->Group );
			parent::tearDown();
		}

		/**
		 * Test de la méthode PostgresExtraValidationRulesBehavior::compareDates
		 *
		 * @return void
		 */
		public function testCompareDates() {
			$result = $this->Group->compareDates( null, null, null );
			$expected = false;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$this->Group->create( array( 'from' => null, 'to' => null ) );
			$result = $this->Group->compareDates( array( 'from' => null ), 'to', 'null' );
			$expected = true;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$data = array( 'from' => '20120101', 'to' => '20120102' );
			$this->Group->create( $data );

			$result = $this->Group->compareDates( array( 'from' => $data['from'] ), 'to', '<' );
			$expected = true;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = $this->Group->compareDates( array( 'from' => $data['from'] ), 'to', '*' );
			$expected = false;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = $this->Group->compareDates( array( 'from' => $data['from'] ), 'to', '>' );
			$expected = false;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode PostgresExtraValidationRulesBehavior::inclusiveRange
		 *
		 * @return void
		 */
		public function testInclusiveRange() {
			$result = $this->Group->inclusiveRange( null, null, null );
			$expected = false;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = $this->Group->inclusiveRange( array( 'value' => 5 ), 0, 5 );
			$expected = true;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}
	}
?>