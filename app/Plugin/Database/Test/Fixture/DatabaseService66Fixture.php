<?php
	/**
	 * Short description for file.
	 *
	 * PHP version 5.3
	 *
	 * @package Database
	 * @subpackage Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Short description for class.
	 *
	 * @package Database
	 * @subpackage Test.Fixture
	 */
	class DatabaseService66Fixture extends CakeTestFixture
	{

		/**
		 * name property
		 *
		 * @var string 'DatabaseService66'
		 * @access public
		 */
		public $name = 'DatabaseService66';

		/**
		 * Full Table Name
		 *
		 * @var string
		 */
		public $table = 'services66';

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
				'services66_name_idx' => array(
					'column' => array( 'name' ),
					'unique' => 1
				)
			)
		);

		/**
		 * records property
		 *
		 * @var array
		 * @access public
		 */
		public $records = array();

	}
?>

