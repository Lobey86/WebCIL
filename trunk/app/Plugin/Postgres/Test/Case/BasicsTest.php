<?php
	/**
	 * BasicsTest file
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Case
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	require_once CakePlugin::path( 'Postgres' ).'Lib'.DS.'basics.php';

	/**
	 * La classe BasicsTest effectue les tests unitaires des fonctions utilitaires
	 * du plugin Postgres.
	 *
	 * @package Postgres
	 * @subpackage Test.Case
	 */
	class BasicsTest extends CakeTestCase
	{

		/**
		 * Test de la fonction cacheKey()
		 */
		public function testCacheKey() {
			$result = cacheKey( array( 'ClassName', 'methodName' ) );
			$expected = 'ClassName_methodName';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = cacheKey( array( 'ClassName', 'methodName' ), true );
			$expected = 'class_name_method_name';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}
	}
?>