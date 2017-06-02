<?php
	/**
	 * AllTranslatorTests file
	 *
	 * PHP 5.3
	 *
	 * @package Translator
	 * @subpackage Test.Case
	 */
	CakePlugin::load( 'Translator' );

	/**
	 * AllTranslatorTests class
	 *
	 * This test group will run all tests.
	 *
	 * @package Translator
	 * @subpackage Test.Case
	 */
	class AllTranslatorTests extends PHPUnit_Framework_TestSuite
	{
		/**
		 * Test suite with all test case files.
		 */
		public static function suite() {
			$suite = new CakeTestSuite( 'All Translator tests' );
			$suite->addTestDirectoryRecursive( dirname( __FILE__ ).DS.'..'.DS.'Case'.DS );
			return $suite;
		}
	}
?>