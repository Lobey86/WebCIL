<?php
	/**
	 * Code source de la classe PostgresGroupFixture.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	require_once dirname( __FILE__ ).DS.'postgres_autovalidate_fixture.php';

	/**
	 * La classe PostgresGroupFixture ...
	 *
	 * @package Postgres
	 * @subpackage Test.Fixture
	 */
	class PostgresGroupFixture extends PostgresAutovalidateFixture
	{
		/**
		 * name property
		 *
		 * @var string 'PostgresSite'
		 * @access public
		 */
		public $name = 'PostgresGroup';

		/**
		 * table property
		 *
		 * @var string
		 */
		public $table = 'postgres_groups';

		/**
		 * fields property
		 *
		 * @var array
		 * @access public
		 */
		public $fields = array(
			'id' => array( 'type' => 'integer', 'key' => 'primary' ),
			'name' => array( 'type' => 'string', 'length' => 255, 'null' => false ),
			'created' => 'datetime',
			'updated' => 'datetime',
			'indexes' => array(
				'postgres_groups_name_idx' => array(
					'column' => array( 'name' ),
					'unique' => 1
				)
			)
		);

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = array(
		);
	}
?>