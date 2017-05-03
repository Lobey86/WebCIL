<?php
	/**
	 * AllPostgresTests file
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Case
	 */
	CakePlugin::load( 'Postgres' );

	/**
	 * AllPostgresTests class
	 *
	 * This test group will run all tests.
	 *
	 * @package Postgres
	 * @subpackage Test.Case
	 */
	class AllPostgresTests extends PHPUnit_Framework_TestSuite
	{
		/**
		 * Test suite with all test case files.
		 */
		public static function suite() {
			$suite = new CakeTestSuite( 'All Postgres tests' );
			$suite->addTestDirectoryRecursive( dirname( __FILE__ ).DS.'..'.DS.'Case'.DS );
			return $suite;
		}
	}
?>