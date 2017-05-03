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
	class DatabaseFichedeliaisonFixture extends CakeTestFixture
	{

		/**
		 * name property
		 *
		 * @var string 'DatabaseFichedeliaison'
		 * @access public
		 */
		public $name = 'DatabaseFichedeliaison';

		/**
		 * Full Table Name
		 *
		 * @var string
		 */
		public $table = 'fichesdeliaison';

		/**
		 * fields property
		 *
		 * @var array
		 * @access public
		 */
		public $fields = array(
			'id' => array( 'type' => 'integer', 'key' => 'primary' ),
			'destinataire_id' => array( 'type' => 'integer', 'null' => false ),
			'created' => 'datetime',
			'updated' => 'datetime'
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
