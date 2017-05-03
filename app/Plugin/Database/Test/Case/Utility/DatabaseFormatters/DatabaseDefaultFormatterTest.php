<?php
	/**
	 * Code source de la classe DatabaseDefaultFormatterTest.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Test.Case.Utility.DatabaseFormatters
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'DatabaseDefaultFormatter', 'Database.Utility/DatabaseFormatters' );

	/**
	 * La classe DatabaseDefaultFormatterTest effectue les tests unitaires de
	 * la classe DatabaseDefaultFormatter.
	 *
	 * @package Database
	 * @subpackage Test.Case.Utility.DatabaseFormatters
	 */
	class DatabaseDefaultFormatterTest extends CakeTestCase
	{
		/**
		 * Test de la méthode DatabaseDefaultFormatter::formatTrim();
		 */
		public function testFormatTrim() {
			$result = DatabaseDefaultFormatter::formatTrim( null );
			$expected = null;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatTrim( '' );
			$expected = '';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatTrim( '  ' );
			$expected = '';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatTrim( ' 0 ' );
			$expected = '0';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatTrim( 0.00 );
			$expected = 0.00;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseDefaultFormatter::formatNull();
		 */
		public function testFormatNull() {
			$result = DatabaseDefaultFormatter::formatNull( null );
			$expected = null;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatNull( '' );
			$expected = null;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatNull( '  ' );
			$expected = null;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatNull( 0.00 );
			$expected = 0.00;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseDefaultFormatter::formatNumeric();
		 */
		public function testFormatNumeric() {
			$result = DatabaseDefaultFormatter::formatNumeric( null );
			$expected = null;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatNumeric( '' );
			$expected = '';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatNumeric( '  ' );
			$expected = '  ';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatNumeric( 0.00 );
			$expected = 0.00;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatNumeric( '0,00' );
			$expected = 0.00;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatNumeric( '6 661' );
			$expected = 6661;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatNumeric( '-10 123,67' );
			$expected = -10123.67;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseDefaultFormatter::formatSuffix();
		 */
		public function testFormatSuffix() {
			$result = DatabaseDefaultFormatter::formatSuffix( null );
			$expected = null;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatSuffix( '_15' );
			$expected = 15;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatSuffix( '11_21_150_666' );
			$expected = 666;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatSuffix( '11_' );
			$expected = null;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseDefaultFormatter::formatSuffix( 33 );
			$expected = 33;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}
	}
?>