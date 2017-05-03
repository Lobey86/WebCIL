<?php
	/**
	 * Code source de la classe PostgresUserFixture.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	require_once dirname( __FILE__ ).DS.'postgres_autovalidate_fixture.php';

	/**
	 * La classe PostgresUserFixture ...
	 *
	 * @package Postgres
	 * @subpackage Test.Fixture
	 */
	class PostgresUserFixture extends PostgresAutovalidateFixture
	{
		/**
		 * name property
		 *
		 * @var string 'PostgresSite'
		 * @access public
		 */
		public $name = 'PostgresUser';

		/**
		 * table property
		 *
		 * @var string
		 */
		public $table = 'postgres_users';

		/**
		 * fields property
		 *
		 * @var array
		 * @access public
		 */
		public $fields = array(
			'id' => array( 'type' => 'integer', 'key' => 'primary' ),
			'group_id' => array( 'type' => 'integer', 'null' => false ),
			'username' => array( 'type' => 'string', 'length' => 50, 'null' => false ),
			'password' => array( 'type' => 'string', 'length' => 50, 'null' => false ),
			'phone' => array( 'type' => 'string', 'length' => 10, 'null' => true ),
			'popularity' => array( 'type' => 'integer', 'null' => false, 'default' => 5 ),
			'active' => array( 'type' => 'integer', 'null' => false, 'default' => 0 ),
			'position' => array( 'type' => 'string', 'length' => 11, 'null' => true ),
			'created' => 'datetime',
			'updated' => 'datetime',
			'indexes' => array(
				'postgres_users_group_id_idx' => array(
					'column' => array( 'group_id' ),
					'unique' => 0
				),
				'postgres_users_username_idx' => array(
					'column' => array( 'username' ),
					'unique' => 1
				),
			)
		);

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = array(
		);

		/**
		 * Liste des noms de contraintes CHECK pour la table, avec la mise en
		 * contrainte en base de données.
		 *
		 * Les fonctions retournent un boolean:
		 *	- cakephp_validate_in_list (text, text[])
		 *	- cakephp_validate_in_list (integer, integer[])
		 *	- cakephp_validate_range (double precision, double precision, double precision)
		 *
		 * @var array
		 */
		public $constraints = array(
			'postgres_users_active_in_list_chk' => "( cakephp_validate_in_list( active, ARRAY[0, 1] ) )",
			'postgres_users_popularity_inclusive_range_chk' => "( cakephp_validate_inclusive_range( popularity, 0, 10 ) )",
			'postgres_users_phone_phone_chk' => "( cakephp_validate_phone( phone, NULL, 'fr' ) )",
			'postgres_users_position_in_list_chk' => "( cakephp_validate_in_list( position, ARRAY[ 'in line', 'out of line' ] ) )"
		);

		/**
		 *
		 * @param type $db
		 * @return boolean
		 */
		public function create( $db ) {
			if( !parent::create( $db ) ) {
				return false;
			}

			$sql = "ALTER TABLE postgres_users ADD CONSTRAINT postgres_users_group_id_fk FOREIGN KEY (group_id) REFERENCES postgres_groups(id);";
			return ( $db->query( $sql ) !== false );
		}
	}
?>