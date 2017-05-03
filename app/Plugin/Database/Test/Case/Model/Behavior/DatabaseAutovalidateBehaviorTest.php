<?php
	/**
	 * Code source de la classe DatabaseAutovalidateBehaviorTest.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Test.Case.Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Model', 'Model' );
	App::uses( 'AppModel', 'Model' );

	require_once CakePlugin::path( 'Database' ).'Config'.DS.'bootstrap.php';

	/**
	 * La classe DatabaseAutovalidateBehaviorTest effectue les tests unitaires de
	 * la classe DatabaseAutovalidateBehavior.
	 *
	 * @package Database
	 * @subpackage Test.Case.Model.Behavior
	 */
	class DatabaseAutovalidateBehaviorTest extends CakeTestCase
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
		 * Règles de validation ajoutées par défaut.
		 *
		 * @var array
		 */
		public $expected = array(
			'id' => array(
				'integer' => array(
					'rule' => array( 'integer' ),
					'message' => null,
					'required' => null,
					'allowEmpty' => true,
					'on' => null
				)
			),
			'name' => array(
				NOT_BLANK_RULE_NAME => array(
					'rule' => array( NOT_BLANK_RULE_NAME ),
					'message' => null,
					'required' => null,
					'allowEmpty' => false,
					'on' => null
				),
				'maxLength' => array(
					'rule' => array( 'maxLength', 255 ),
					'message' => null,
					'required' => null,
					'allowEmpty' => true,
					'on' => null
				),
				'isUnique' => array(
					'rule' => array( 'isUnique' ),
					'message' => null,
					'required' => null,
					'allowEmpty' => true,
					'on' => null,
				),
			),
			'user_id' => array(
				NOT_BLANK_RULE_NAME => array(
					'rule' => array( NOT_BLANK_RULE_NAME ),
					'message' => null,
					'required' => null,
					'allowEmpty' => false,
					'on' => null
				),
				'integer' => array(
					'rule' => array( 'integer' ),
					'message' => null,
					'required' => null,
					'allowEmpty' => true,
					'on' => null
				)
			),
			'price' => array(
				'numeric' => array(
					'rule' => array( 'numeric' ),
					'message' => null,
					'required' => null,
					'allowEmpty' => true,
					'on' => null
				)
			),
			'published' => array(
				NOT_BLANK_RULE_NAME => array(
					'rule' => array( NOT_BLANK_RULE_NAME ),
					'message' => null,
					'required' => null,
					'allowEmpty' => false,
					'on' => null
				)
			),
			'document' => array(),
			'description' => array(),
			'birthday' => array(
				'date' => array(
					'rule' => array( 'date' ),
					'message' => null,
					'required' => null,
					'allowEmpty' => true,
					'on' => null
				)
			),
			'birthtime' => array(
				'time' => array(
					'rule' => array( 'time' ),
					'message' => null,
					'required' => null,
					'allowEmpty' => true,
					'on' => null,
				)
			),
			'created' => array(
				'datetime' => array(
					'rule' => array( 'datetime' ),
					'message' => null,
					'required' => null,
					'allowEmpty' => true,
					'on' => null,
				)
			),
			'updated' => array(
				'datetime' => array(
					'rule' => array( 'datetime' ),
					'message' => null,
					'required' => null,
					'allowEmpty' => true,
					'on' => null,
				)
			),
		);

		/**
		 * Règles de validation ajoutées par défaut lorsque l'on veut ajouter
		 * automatiquement uniquement les règles notBlank (notEmpty).
		 *
		 * @var array
		 */
		public $expectedNotEmpty = array(
			'id' => array(),
			'name' => array(
				NOT_BLANK_RULE_NAME => array(
					'rule' => array( NOT_BLANK_RULE_NAME ),
					'message' => null,
					'required' => null,
					'allowEmpty' => false,
					'on' => null
				)
			),
			'user_id' => array(
				NOT_BLANK_RULE_NAME => array(
					'rule' => array( NOT_BLANK_RULE_NAME ),
					'message' => null,
					'required' => null,
					'allowEmpty' => false,
					'on' => null
				)
			),
			'price' => array(),
			'published' => array(
				NOT_BLANK_RULE_NAME => array(
					'rule' => array( NOT_BLANK_RULE_NAME ),
					'message' => null,
					'required' => null,
					'allowEmpty' => false,
					'on' => null
				)
			),
			'document' => array(),
			'description' => array(),
			'birthday' => array(),
			'birthtime' => array(),
			'created' => array(),
			'updated' => array()
		);

		/**
		 * Method executed before each test
		 */
		public function setUp() {
			parent::setUp();

			ClassRegistry::flush();
			Cache::clear();

			Configure::write( 'Config.language', 'eng' );

			App::build(
				array(
					'Locale' => array( CakePlugin::path( 'Database' ) . 'Locale' . DS )
				),
				App::RESET
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
		 * Test de la méthode DatabaseAutovalidateBehavior::setup() du plugin
		 * Database.
		 *
		 * @return void
		 */
		public function testSetup() {
			$this->Site->Behaviors->attach( 'Database.DatabaseAutovalidate' );

			$this->assertEquals( $this->expected, $this->Site->validate, var_export( $this->Site->validate, true ) );
		}

		/**
		 * Test de la méthode DatabaseAutovalidateBehavior::setup() du plugin
		 * Database lorsque l'on ne configure qu'une seule des règles à déduire.
		 *
		 * @return void
		 */
		public function testSetupOnlyOneRule() {
			$config = array(
				'rules' => array(
					NOT_BLANK_RULE_NAME => true,
					'maxLength' => false,
					'integer' => false,
					'numeric' => false,
					'date' => false,
					'datetime' => false,
					'time' => false,
					'isUnique' => false,
				),
				'domain' => 'validation',
				'translate' => true
			);
			$this->Site->Behaviors->attach( 'Database.DatabaseAutovalidate', $config );

			$expected = array_filter( $this->expectedNotEmpty );
			$result = array_filter( $this->Site->validate );
			$this->assertEquals( $expected, $result, var_export( $this->Site->validate, true ) );
		}

		/**
		 * Test de la méthode DatabaseAutovalidateBehavior::setup() du plugin
		 * Database.
		 *
		 * @return void
		 */
		public function testSetupWithExistingRules() {
			$this->Site->validate['name']['alphaNumeric'] = array(
				'rule' => array( 'alphaNumeric' ),
				'message' => null,
				'required' => null,
				'allowEmpty' => true,
				'on' => null,
			);

			$config = array(
				'rules' => array(
					NOT_BLANK_RULE_NAME => true,
					'maxLength' => false,
					'integer' => false,
					'numeric' => false,
					'date' => false,
					'datetime' => false,
					'time' => false,
					'isUnique' => false,
				),
				'domain' => 'validation',
				'translate' => true
			);
			$this->Site->Behaviors->attach( 'Database.DatabaseAutovalidate', $config );

			$expected = array_filter( $this->expectedNotEmpty );
			$expected['name']['alphaNumeric'] = array(
				'rule' => array( 'alphaNumeric' ),
				'message' => null,
				'required' => null,
				'allowEmpty' => true,
				'on' => null
			);

			$result = array_filter( $this->Site->validate );
			$this->assertEquals( $expected, $result, var_export( $this->Site->validate, true ) );
		}

		/**
		 * Test de la méthode DatabaseAutovalidateBehavior::beforeValidate() du
		 * plugin Database.
		 *
		 * @medium
		 *
		 * @return void
		 */
		public function testBeforeValidate() {
			$this->Site->Behaviors->attach( 'Database.DatabaseAutovalidate' );

			$expected = $this->expected;

			foreach( $expected as $field => $rules ) {
				foreach( $rules as $ruleName => $rule ) {
					$params = array_values( array_slice( $rule['rule'], 1 ) );
					$domain = Hash::get( $this->Site->Behaviors->DatabaseAutovalidate->settings, "{$this->Site->alias}.domain" );
					foreach( $params as $key => $param ) {
						if( true === is_array( $param ) ) {
							$params[$key] = '"'.implode( '", "', $param ).'"';
						}
					}
					$expected[$field][$ruleName]['message'] = call_user_func_array( 'sprintf', Hash::merge( array( __d( $domain, "Validate::{$ruleName}" ) ), $params ) );
				}
			}

			$this->Site->create(array());
			$this->Site->validates();

			$this->assertEquals( $expected, $this->Site->validate, var_export( $this->Site->validate, true ) );
		}

		/**
		 * Test de la méthode DatabaseAutovalidateBehavior::integer() du plugin
		 * Database.
		 *
		 * @return void
		 */
		public function testInteger() {
			$this->Site->Behaviors->attach( 'Database.DatabaseAutovalidate' );

			// 1. Test de la méthode en elle-meme
			$this->assertFalse( $this->Site->integer( null ) );
			$this->assertTrue( $this->Site->integer( array( '4' ) ) );
			$this->assertFalse( $this->Site->integer( array( 'foo' ) ) );

			// 2. Tentative d'enregistrement
			$this->Site->create( array( 'user_id' => 'foo' ) );
			$this->assertFalse( $this->Site->validates() );

			$expected = array(
				'user_id' => array(
					'Please enter an integer value'
				)
			);
			$this->assertEquals( $expected, $this->Site->validationErrors, var_export( $this->Site->validationErrors, true ) );
		}
	}
?>