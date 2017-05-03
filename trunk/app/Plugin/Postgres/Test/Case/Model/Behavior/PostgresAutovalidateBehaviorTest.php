<?php
	/**
	 * Code source de la classe PostgresAutovalidateBehaviorTest.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe PostgresAutovalidateBehaviorTest ...
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Model.Behavior
	 */
	class PostgresAutovalidateBehaviorTest extends CakeTestCase
	{
		/**
		 *
		 * @var Model
		 */
		public $User = null;

		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'plugin.Postgres.PostgresGroup',
			'plugin.Postgres.PostgresUser',
		);

		/**
		 * Règles de validation ajoutées par défaut.
		 *
		 * @var array
		 */
		public $expected = array(
			'id' => array(),
			'group_id' => array(),
			'username' => array(),
			'password' => array(),
			'phone' => array(
				'phone' => array(
					'rule' => array( 'phone', NULL, 'fr' ),
					'message' => NULL,
					'required' => NULL,
					'allowEmpty' => true,
					'on' => NULL,
				),
			),
			'popularity' => array(
				'inclusiveRange' => array(
					'rule' => array( 'inclusiveRange', 0, 10 ),
					'message' => NULL,
					'required' => NULL,
					'allowEmpty' => true,
					'on' => NULL,
				),
			),
			'active' => array(
				'inList' => array(
					'rule' => array( 'inList', array( '0', '1' ) ),
					'message' => NULL,
					'required' => NULL,
					'allowEmpty' => true,
					'on' => NULL,
				),
			),
			'position' => array(
				'inList' => array(
					'rule' => array( 'inList', array( 'in line', 'out of line' ) ),
					'message' => NULL,
					'required' => NULL,
					'allowEmpty' => true,
					'on' => NULL,
				),
			),
			'created' => array(),
			'updated' => array(),
		);

		/**
		 * Préparation du test.
		 *
		 * INFO: ne pas utiliser parent::setUp();
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

			$this->User = ClassRegistry::init( 'User' );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->User );
			parent::tearDown();
		}

		/**
		 * Test de la méthode PostgresAutovalidateBehavior::setup()
		 */
		public function testSetup() {
			$this->User->Behaviors->attach( 'Postgres.PostgresAutovalidate' );

			$result = Hash::get( $this->User->validate, 'active.inList' );
			$expected = array(
				'rule' => array( 'inList', array( '0', '1' ) ),
				'message' => null,
				'required' => null,
				'allowEmpty' => true,
				'on' => null
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = Hash::get( $this->User->validate, 'popularity.inclusiveRange' );
			$expected = array(
				'rule' => array( 'inclusiveRange', '0', '10' ),
				'message' => null,
				'required' => null,
				'allowEmpty' => true,
				'on' => null
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = Hash::get( $this->User->validate, 'phone.phone' );
			$expected = array(
				'rule' => array( 'phone', null, 'fr' ),
				'message' => null,
				'required' => null,
				'allowEmpty' => true,
				'on' => null
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// inList avec des espaces et un nom de colonne entre quotes
			// CHECK (cakephp_validate_in_list("position"::text, ARRAY['in line'::text, 'out of line'::text]))
			$result = Hash::get( $this->User->validate, 'position.inList' );
			$expected = array(
				'rule' => array( 'inList', array( 'in line', 'out of line' ) ),
				'message' => null,
				'required' => null,
				'allowEmpty' => true,
				'on' => null
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode PostgresAutovalidateBehavior::setup()
		 */
		public function testTranslate() {
			$this->User->Behaviors->attach( 'Postgres.PostgresAutovalidate' );
			$this->User->Behaviors->attach( 'Postgres.PostgresExtraValidationRules' );

			$expected = array_filter( $this->expected );
			foreach( $expected as $field => $rules ) {
				foreach( $rules as $ruleName => $rule ) {
					$params = array_values( array_slice( $rule['rule'], 1 ) );
					$domain = Hash::get( $this->User->Behaviors->PostgresAutovalidate->settings, "{$this->User->alias}.domain" );
					foreach( $params as $key => $param ) {
						if( true === is_array( $param ) ) {
							$params[$key] = '"'.implode( '", "', $param ).'"';
						}
					}
					$expected[$field][$ruleName]['message'] = call_user_func_array( 'sprintf', Hash::merge( array( __d( $domain, "Validate::{$ruleName}" ) ), $params ) );
				}
			}

			$this->User->create(array());
			$this->User->validates();

			$result = array_filter( $this->User->validate );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}
	}
?>