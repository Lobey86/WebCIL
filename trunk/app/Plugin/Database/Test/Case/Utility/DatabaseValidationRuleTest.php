<?php
	/**
	 * Code source de la classe DatabaseValidationRuleTest.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Test.Case.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	CakePlugin::load( 'Database', array( 'bootstrap' => true ) );
	App::uses( 'DatabaseValidationRule', 'Database.Utility' );
	require_once CakePlugin::path( 'Database' ).DS.'Test'.DS.'Case'.DS.'blog_models.php';

	/**
	 * La classe DatabaseValidationRuleTest effectue les tests unitaires de
	 * la classe DatabaseValidationRule.
	 *
	 * @package Database
	 * @subpackage Test.Case.Utility
	 */
	class DatabaseValidationRuleTest extends CakeTestCase
	{
		public function setUp() {
			parent::setUp();

			Configure::write( 'Config.language', 'eng' );
		}

		/**
		 * Test de la méthode DatabaseValidationRule::normalize()
		 *
		 * @covers DatabaseValidationRule::normalize
		 */
		public function testNormalize() {
			$expected = array(
				'rule' => array( NOT_BLANK_RULE_NAME ),
				'message' => NULL,
				'required' => NULL,
				'allowEmpty' => NULL,
				'on' => NULL
			);

			// 1. Avec une chaîne de caractères
			$result = DatabaseValidationRule::normalize( NOT_BLANK_RULE_NAME );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 2. Avec un array contenant une chaîne de caractères
			$result = DatabaseValidationRule::normalize( array( NOT_BLANK_RULE_NAME ) );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 3. Avec un array contenant une clé rule contenant une chaîne de caractères
			$result = DatabaseValidationRule::normalize( array( 'rule' => NOT_BLANK_RULE_NAME ) );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 4. Avec un array contenant une clé rule contenant un array
			$result = DatabaseValidationRule::normalize( array( 'rule' => array( NOT_BLANK_RULE_NAME ) ) );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 5. Avec un array normalisé contenant d'autres valeurs que celles par défaut
			$rule = array(
				'rule' => array( NOT_BLANK_RULE_NAME ),
				'required' => true,
				'allowEmpty' => false
			);
			$result = DatabaseValidationRule::normalize( $rule );
			$expected = array(
				'rule' => array( NOT_BLANK_RULE_NAME ),
				'message' => NULL,
				'required' => true,
				'allowEmpty' => false,
				'on' => NULL
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseValidationRule::message()
		 *
		 * @covers DatabaseValidationRule::message
		 */
		public function testMessage() {
			// 1. Avec une simple chaîne de caractères
			$result = DatabaseValidationRule::message( NOT_BLANK_RULE_NAME );
			$expected = 'Mandatory field';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 2. Avec plusieurs paramètres
			$result = DatabaseValidationRule::message( array( 'rule' => array( LENGTH_BETWEEN_RULE_NAME, 1, 2 ) ) );
			$expected = 'Please enter a value that is between 1 and 2 characters long';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 3. Avec un array
			$result = DatabaseValidationRule::message( array( 'rule' => array( 'inList', array( 1, 2 ) ) ) );
			$expected = 'Please enter a value amongst "1", "2"';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseValidationRule::translate()
		 *
		 * @covers DatabaseValidationRule::translate
		 */
		public function testTranslate() {
			$validate = array(
				'id' => array( 'integer' ),
				'name' => array(
					array( NOT_BLANK_RULE_NAME ),
					array( LENGTH_BETWEEN_RULE_NAME, 4, 8 ),
				),
				'phone' => array(
					NOT_BLANK_RULE_NAME => array( 'phone' )
				),
				'ip' => array(
					NOT_BLANK_RULE_NAME => array( 'rule' => 'ip' )
				),
			);
			$result = DatabaseValidationRule::translate( $validate, 'database' );
			$expected = array(
				'id' => array(
					array(
						'rule' => array( 'integer' ),
						'message' => 'Please enter an integer value',
						'required' => NULL,
						'allowEmpty' => NULL,
						'on' => NULL,
					),
				),
				'name' => array(
					array(
						'rule' => array( NOT_BLANK_RULE_NAME ),
						'message' => 'Mandatory field',
						'required' => NULL,
						'allowEmpty' => NULL,
						'on' => NULL,
					),
					array(
						'rule' => array( LENGTH_BETWEEN_RULE_NAME, 4, 8 ),
						'message' => 'Please enter a value that is between 4 and 8 characters long',
						'required' => NULL,
						'allowEmpty' => NULL,
						'on' => NULL,
					),
				),
				'phone' => array(
					NOT_BLANK_RULE_NAME => array(
						'rule' => array( 'phone' ),
						'message' => 'Please enter a valid phone number',
						'required' => NULL,
						'allowEmpty' => NULL,
						'on' => NULL,
					),
				),
				'ip' => array(
					NOT_BLANK_RULE_NAME => array(
						'rule' => array( 'ip' ),
						'message' => 'Please enter a valid IP adress',
						'required' => NULL,
						'allowEmpty' => NULL,
						'on' => NULL,
					),
				),
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}
	}
?>
