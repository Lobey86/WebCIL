<?php
	/**
	 * Code source de la classe FusionConvDebuggerTest.
	 *
	 * PHP 5.3
	 *
	 * @package FusionConv
	 * @subpackage Test.Case.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'FusionConvDebugger', 'FusionConv.Utility' );
	require_once( dirname( __FILE__ ).DS.'..'.DS.'..'.DS.'..'.DS.'Config'.DS.'bootstrap.php' );

	/**
	 * La classe FusionConvDebuggerTest ...
	 *
	 * @package FusionConv
	 * @subpackage Test.Case.Utility
	 */
	class FusionConvDebuggerTest extends CakeTestCase
	{
		/**
		 * Retourne un objet GDO_PartType contenant des GDO_FieldType et des
		 * GDO_IterationType pour les tests.
		 *
		 * Types de GDO_FieldType connus: date, number, text, string.
		 *
		 * @todo: GDO_ContentType
		 *
		 * @return GDO_PartType
		 */
		protected function _getGDOPartType() {
			$Part = new GDO_PartType();

			// Ajout de champs à la partie principale.
			$Part->addElement( new GDO_FieldType( 'Clé1', 'valeur1', 'text' ) );
			$Part->addElement( new GDO_FieldType( 'Clé2', 'valeur2', 'string' ) );
			$Part->addElement( new GDO_FieldType( 'Clé3', '24/01/1979', 'date' ) );
			$Part->addElement( new GDO_FieldType( 'Clé4', 5, 'number' ) );
			$Part->addElement( new GDO_FieldType( 'Clé5', 0.5, 'number' ) );

			// Ajout d'une série d'itérations
			$oIteration = new GDO_IterationType( 'Iteration1' );
			// Ajout de deux itérations
			for( $i = 0 ; $i < 2 ; $i++ ) {
				$oIterationPart = new GDO_PartType();
				$oIterationPart->addElement( new GDO_FieldType( 'Clé1', 'valeur1', 'text' ) );
				$oIteration->addPart( $oIterationPart );
			}
			$Part->addElement( $oIteration );

			// Ajout de GDO_ContentType
			$fileName = GEDOOO_TEST_FILE;
			$contents = file_get_contents( $fileName );
			$oContent = new GDO_ContentType( 'gedooo_test_file', basename( $fileName ), 'application/vnd.oasis.opendocument.text', 'binary', $contents );
			$Part->addElement( $oContent );

			return $Part;
		}

		/**
		 * Test de la méthode FusionConvDebugger::allPathsToCsv() avec le paramètre
		 * $exportValues à false.
		 */
		public function testAllPathsToCsvWithoutValues() {
			$Part = $this->_getGDOPartType();

			$result = FusionConvDebugger::allPathsToCsv( $Part );
			$expected = '"Chemins","Types"
"Clé1","text"
"Clé2","string"
"Clé3","date"
"Clé4","number"
"Clé5","number"
"Iteration1.0.Clé1","text"
"Iteration1.1.Clé1","text"
"test_gedooo.odt","application/vnd.oasis.opendocument.text","gedooo_test_file"';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode FusionConvDebugger::allPathsToCsv() avec le paramètre
		 * $exportValues à true.
		 */
		public function testAllPathsToCsvWithValues() {
			$Part = $this->_getGDOPartType();

			$result = FusionConvDebugger::allPathsToCsv( $Part, true );
			$expected = '"Chemins","Types","Valeurs"
"Clé1","text","valeur1"
"Clé2","string","valeur2"
"Clé3","date","24/01/1979"
"Clé4","number","5"
"Clé5","number","'.sprintf( '%.1f', 0.5 ).'"
"Iteration1.0.Clé1","text","valeur1"
"Iteration1.1.Clé1","text","valeur1"
"test_gedooo.odt","application/vnd.oasis.opendocument.text","gedooo_test_file","9d57b903055ebeafacd1cd6767bf9fda"';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode FusionConvDebugger::hashPathsToCsv()
		 */
		public function testHashPathsToCsv() {
			$Part = $this->_getGDOPartType();

			$result = FusionConvDebugger::hashPathsToCsv( $Part );
			$expected = '"Chemins","Types"
"Clé1","text"
"Clé2","string"
"Clé3","date"
"Clé4","number"
"Clé5","number"
"Iteration1.{n}.Clé1","text"
"test_gedooo.odt","application/vnd.oasis.opendocument.text"';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
	}
?>
