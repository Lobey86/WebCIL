<?php
	/**
	 * Code source de la classe AllDatabaseTests.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Test.Case
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	CakePlugin::load( 'Database', array( 'bootstrap' => true ) );

	/**
	 * La classe AllDatabaseTests effectue tous les tests unitaires du plugin
	 * Database.
	 *
	 * @package Database
	 * @subpackage Test.Case
	 */
	class AllDatabaseTests extends PHPUnit_Framework_TestSuite
	{
		/**
		 * Test suite with all test case files.
		 *
		 * @return void
		 */
		public static function suite() {
			$suite = new CakeTestSuite( 'All Database tests' );
			$suite->addTestDirectoryRecursive( CakePlugin::path( 'Database' ).DS.'Test'.DS.'Case'.DS );
			return $suite;
		}
	}
?>