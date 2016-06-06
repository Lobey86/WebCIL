<?php
	/**
	 * AllFusionConvTests file
	 *
	 * PHP 5.3
	 *
	 * @package FusionConv
	 * @subpackage Test.Case
	 */
	CakePlugin::load( 'FusionConv', array( 'bootstrap' => true ) );

	/**
	 * AllFusionConvTests class
	 *
	 * This test group will run all tests.
	 *
	 * @package FusionConv
	 * @subpackage Test.Case
	 */
	class AllFusionConvTests extends PHPUnit_Framework_TestSuite
	{
		/**
		 * Test suite with all test case files.
		 *
		 * @return void
		 */
		public static function suite() {
			$suite = new CakeTestSuite( 'All FusionConv tests' );
			$suite->addTestDirectoryRecursive( dirname( __FILE__ ).DS.'..'.DS.'Case'.DS );
			return $suite;
		}
	}
?>