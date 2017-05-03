<?php
	/**
	 * Code source de la classe PostgresPostgresTest.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Model.Datasource.Database
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe PostgresPostgresTest ...
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Model.Datasource.Database
	 */
	class PostgresPostgresTest extends CakeTestCase
	{
		public $Dbo = null;

		public $Group = null;

		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'plugin.Postgres.PostgresGroup',
			'plugin.Postgres.PostgresUser'
		);

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			Cache::clear();
			$this->Group = ClassRegistry::init(
				array(
					'class' => 'Postgres.PostgresGroup',
					'alias' => 'Group',
					'ds' => 'test'
				)
			);
			$this->Dbo = $this->Group->getDatasource();
			$this->skipIf( !($this->Dbo instanceof PostgresPostgres) );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			if( true === $this->Dbo->existsPostgresForeignKey( 'postgres_users', 'group_id', 'postgres_groups', 'id' ) ) {
				$this->Dbo->dropPostgresForeignKey( 'postgres_users', 'group_id' );
			}
			$this->Dbo->addPostgresForeignKey( 'postgres_users', 'group_id', 'postgres_groups', 'id' );
			unset( $this->Dbo, $this->Group );
			parent::tearDown();
		}

		/**
		 * Test de la méthode PostgresPostgres::getPostgresVersion()
		 */
		public function testGetPostgresVersion() {
			$result = $this->Dbo->getPostgresVersion();
			$this->assertPattern( '/^[0-9]+\.[0-9]+/', $result );

			$result = $this->Dbo->getPostgresVersion( true );
			$this->assertPattern( '/^PostgreSQL [0-9]+\.[0-9]+/', $result );
		}

		/**
		 * Test de la méthode PostgresPostgres::getPostgresFunctions()
		 */
		public function testGetPostgresFunctions() {
			$result = $this->Dbo->getPostgresFunctions( array( "pg_proc.proname ~ '^cakephp_validate_'" ) );
			$result = array_values( array_unique( (array)Hash::extract( $result, '{n}.Function.name' ) ) );

			$expected = array(
				'cakephp_validate__ipv4',
				'cakephp_validate__ipv6',
				'cakephp_validate_alpha_numeric',
				'cakephp_validate_between',
				'cakephp_validate_blank',
				'cakephp_validate_cc',
				'cakephp_validate_compare_dates',
				'cakephp_validate_comparison',
				'cakephp_validate_decimal',
				'cakephp_validate_email',
				'cakephp_validate_in_list',
				'cakephp_validate_inclusive_range',
				'cakephp_validate_ip',
				'cakephp_validate_luhn',
				'cakephp_validate_max_length',
				'cakephp_validate_min_length',
				'cakephp_validate_not_empty',
				'cakephp_validate_phone',
				'cakephp_validate_range',
				'cakephp_validate_ssn',
				'cakephp_validate_uuid'
			);

			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode PostgresPostgres::checkPostgresSqlSyntax()
		 */
		public function testCheckPostgresSqlSyntax() {
			// 1. Succès
			$sql = "SELECT NOW() + interval '4 DAY 1 MONTH'";
			$result = $this->Dbo->checkPostgresSqlSyntax( $sql );
			$expected = array(
				'success' => true,
				'message' => null,
				'value' => 'SELECT NOW() + interval \'4 DAY 1 MONTH\'',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 2. Erreur
			$sql = "SELECT NOW() + interval '4 DBY 1 MONTH'";
			$result = $this->Dbo->checkPostgresSqlSyntax( $sql );
			$expected = array(
				'success' => false,
				'message' => '7: ERROR:  invalid input syntax for type interval: "4 DBY 1 MONTH"',
				'value' => 'SELECT NOW() + interval \'4 DBY 1 MONTH\'',
			);
			if( preg_match( '/ERR(O|EU)R.*interval.*4 DBY 1 MONTH/', $result['message'] ) ) {
				$expected['message'] = $result['message'];
			}
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode PostgresPostgres::checkPostgresIntervalSyntax()
		 */
		public function testCheckPostgresIntervalSyntax() {
			// 1. Succès
			$interval = '4 DAY 1 MONTH';
			$result = $this->Dbo->checkPostgresIntervalSyntax( $interval );
			$expected = array(
				'value' => $interval,
				'success' => true,
				'message' => null
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 2. Erreur
			$interval = '4 DBY 1 MONTH';
			$result = $this->Dbo->checkPostgresIntervalSyntax( $interval );
			$expected = array(
				'value' => $interval,
				'success' => false,
				'message' => '7: ERROR:  invalid input syntax for type interval: "4 DBY 1 MONTH"'
			);
			if( preg_match( '/ERR(O|EU)R.*interval.*4 DBY 1 MONTH/', $result['message'] ) ) {
				$expected['message'] = $result['message'];
			}
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode PostgresPostgres::getPostgresForeignKeys()
		 */
		public function testGetPostgresForeignKeys() {
			$result = $this->Dbo->getPostgresForeignKeys();
			$expected = array(
				array(
					'Foreignkey' => array(
						'name' => 'postgres_users_group_id_fk',
						'onupdate' => 'NO ACTION',
						'ondelete' => 'NO ACTION'
					),
					'From' => array(
						'schema' => 'public',
						'table' => 'postgres_users',
						'column' => 'group_id',
						'nullable' => false,
						'unique' => false
					),
					'To' => array(
						'schema' => 'public',
						'table' => 'postgres_groups',
						'column' => 'id',
						'nullable' => false,
						'unique' => true
					)
				)
			);
			$this->assertEquals( $expected, $result );
		}

		/**
		 * Test de la méthode PostgresPostgres::getPostgresCheckConstraints()
		 *
		 * @medium
		 */
		public function testGetPostgresCheckConstraints() {
			$result = $this->Dbo->getPostgresCheckConstraints();
			$expected = array(
				array(
					'Constraint' => array(
						'schema' => 'public',
						'table' => 'postgres_users',
						'name' => 'postgres_users_active_in_list_chk',
						'clause' => 'cakephp_validate_in_list(active, ARRAY[0, 1])'
					)
				),
				array(
					'Constraint' => array(
						'schema' => 'public',
						'table' => 'postgres_users',
						'name' => 'postgres_users_phone_phone_chk',
						'clause' => 'cakephp_validate_phone((phone)::text, NULL::text, \'fr\'::text)'
					)
				),
				array(
					'Constraint' => array(
						'schema' => 'public',
						'table' => 'postgres_users',
						'name' => 'postgres_users_popularity_inclusive_range_chk',
						'clause' => 'cakephp_validate_inclusive_range((popularity)::double precision, (0)::double precision, (10)::double precision)'
					)
				),
				array(
					'Constraint' => array(
						'schema' => 'public',
						'table' => 'postgres_users',
						'name' => 'postgres_users_position_in_list_chk',
						'clause' => 'cakephp_validate_in_list(("position")::text, ARRAY[\'in line\'::text, \'out of line\'::text])',
					),
				)
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode PostgresPostgres::existsPostgresForeignKey()
		 */
		public function testExistsPostgresForeignKey() {
			$this->assertTrue( $this->Dbo->existsPostgresForeignKey( 'postgres_users', 'group_id', 'postgres_groups', 'id' ) );
			$this->assertFalse( $this->Dbo->existsPostgresForeignKey( 'postgres_groups', 'id', 'postgres_users', 'group_id' ) );
		}

		/**
		 * Test de la méthode PostgresPostgres::dropPostgresForeignKey()
		 */
		public function testDropPostgresForeignKey() {
			$this->assertTrue( $this->Dbo->dropPostgresForeignKey( 'postgres_users', 'group_id' ) );
			$this->assertFalse( $this->Dbo->existsPostgresForeignKey( 'postgres_users', 'group_id', 'postgres_groups', 'id' ) );
		}

		/**
		 * Test de la méthode PostgresPostgres::addPostgresForeignKey()
		 */
		public function testAddPostgresForeignKey() {
			$this->assertTrue( $this->Dbo->dropPostgresForeignKey( 'postgres_users', 'group_id' ) );
			$this->assertFalse( $this->Dbo->existsPostgresForeignKey( 'postgres_users', 'group_id', 'postgres_groups', 'id' ) );
			$this->assertTrue( $this->Dbo->addPostgresForeignKey( 'postgres_users', 'group_id', 'postgres_groups', 'id' ) );
			$this->assertTrue( $this->Dbo->existsPostgresForeignKey( 'postgres_users', 'group_id', 'postgres_groups', 'id' ) );
		}
	}
?>