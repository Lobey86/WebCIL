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
	class DatabaseSiteFixture extends CakeTestFixture
	{

		/**
		 * name property
		 *
		 * @var string 'DatabaseSite'
		 * @access public
		 */
		public $name = 'DatabaseSite';

		/**
		 * fields property
		 *
		 * @var array
		 * @access public
		 */
		public $fields = array(
			'id' => array( 'type' => 'integer', 'key' => 'primary' ),
			'name' => array( 'type' => 'string', 'length' => 255, 'null' => false ),
			'user_id' => array( 'type' => 'integer', 'null' => false ),
			'price' => array( 'type' => 'float', 'null' => true ),
			'published' => array( 'type' => 'boolean', 'null' => false ),
			'document' => array( 'type' => 'binary', 'null' => true ),
			'description' => 'text',
			'birthday' => 'date',
			'birthtime' => 'time',
			'created' => 'datetime',
			'updated' => 'datetime',
			'indexes' => array(
				'sites_name_idx' => array(
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
		public $records = array(
			array(
				'name' => 'CakePHP',
				'user_id' => 1,
				'published' => true,
				'created' => '2007-03-17 01:16:23',
				'updated' => '2007-03-17 01:18:31'
			),
		);

	}
?>