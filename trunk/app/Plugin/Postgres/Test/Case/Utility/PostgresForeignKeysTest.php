<?php
	/**
	 * Code source de la classe PostgresForeignKeysTest.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConnectionManager', 'Model' );
	App::uses( 'PostgresForeignKeys', 'Postgres.Utility' );

	/**
	 * La classe PostgresForeignKeysTest teste la classe utilitaire
	 * PostgresForeignKeys.
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Utility
	 */
	class PostgresForeignKeysTest extends CakeTestCase
	{
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
		 * La connexion utilisée par les tests.
		 *
		 * @var DataSource
		 */
		public $Dbo = null;

		/**
		 * setUp method
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			Configure::write( 'Cache.disable', true );
			Cache::clear();
			$this->Dbo = ConnectionManager::getDataSource( 'test' );

			App::build(
				array(
					'Model' => array(
						CakePlugin::path( 'Postgres' ) . 'Test' . DS . 'test_app' . DS . 'Model' . DS
					)
				),
				App::RESET
			);

			PostgresForeignKeys::clear();
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			if( true === $this->Dbo->existsPostgresForeignKey( 'postgres_users', 'group_id', 'postgres_groups', 'id' ) ) {
				$this->Dbo->dropPostgresForeignKey( 'postgres_users', 'group_id' );
			}
			$this->Dbo->addPostgresForeignKey( 'postgres_users', 'group_id', 'postgres_groups', 'id' );
			unset( $this->Shell, $this->Dbo );
			parent::tearDown();
		}

		/**
		 * Test de la méthode PostgresForeignKeys::missing()
		 *
		 * @return void
		 */
		public function testMissingWithoutMissing() {
			$result = PostgresForeignKeys::missing( 'test' );
			$expected = array();
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode PostgresForeignKeys::missing()
		 *
		 * @return void
		 */
		public function testMissingWithMissing() {
			$this->Dbo->dropPostgresForeignKey( 'postgres_users', 'group_id' );
			$result = PostgresForeignKeys::missing( 'test' );
			$expected = array(
				'postgres_users' => array(
					'group_id' => 'postgres_groups',
				)
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}
	}
?>