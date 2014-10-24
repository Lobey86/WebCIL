<?php
/**
* Code source de la classe CompositionFixture.
*
* PHP 5.3
*
* @package app.Test.Fixture
* @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
*/

/**
* Classe CompositionFixture.
*
* @package Cakeflow.Test.Fixture
*/

class CompositionFixture extends CakeTestFixture {
        var $name = 'Composition';
        var $table = 'wkf_compositions';

        public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'etape_id' => array('type' => 'integer', 'null' => false),
		'type_validation' => array('type' => 'string', 'null' => false, 'length' => 1),
		'soustype' => array('type' => 'integer', 'null' => true),
		'type_composition' => array('type' => 'string', 'null' => true, 'default' => 'USER', 'length' => 20),
		'trigger_id' => array('type' => 'integer', 'null' => true),
		'created_user_id' => array('type' => 'integer', 'null' => true),
		'modified_user_id' => array('type' => 'integer', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'etape_id' => array('unique' => false, 'column' => 'etape_id'),
			'trigger' => array('unique' => false, 'column' => 'trigger_id')
		),
		'tableParameters' => array()
	);

        /**
         * Records
         *
         * @var array
         */
	public $records = array(
		array(
			'id' => 1,
			'etape_id' => 1,
			'type_validation' => 'V',
			'soustype' => null,
			'type_composition' => 'USER',
			'trigger_id' => 5,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-03 16:03:58',
			'modified' => '2014-01-03 16:03:58'
		),
		array(
			'id' => 2,
			'etape_id' => 2,
			'type_validation' => 'V',
			'soustype' => null,
			'type_composition' => 'USER',
			'trigger_id' => 3,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-03 16:04:29',
			'modified' => '2014-01-03 16:04:29'
		),
		array(
			'id' => 3,
			'etape_id' => 3,
			'type_validation' => 'V',
			'soustype' => null,
			'type_composition' => 'USER',
			'trigger_id' => 4,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-03 16:04:48',
			'modified' => '2014-01-03 16:04:48'
		),
		array(
			'id' => 4,
			'etape_id' => 4,
			'type_validation' => 'V',
			'soustype' => null,
			'type_composition' => 'USER',
			'trigger_id' => 0,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-10 10:12:37',
			'modified' => '2014-01-10 10:12:37'
		),
		array(
			'id' => 5,
			'etape_id' => 5,
			'type_validation' => 'V',
			'soustype' => null,
			'type_composition' => 'USER',
			'trigger_id' => 4,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-10 10:13:15',
			'modified' => '2014-01-10 10:13:15'
		),
		array(
			'id' => 6,
			'etape_id' => 6,
			'type_validation' => 'V',
			'soustype' => null,
			'type_composition' => 'USER',
			'trigger_id' => 5,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-10 10:13:49',
			'modified' => '2014-01-10 10:13:49'
		),
		array(
			'id' => 7,
			'etape_id' => 7,
			'type_validation' => 'V',
			'soustype' => null,
			'type_composition' => 'USER',
			'trigger_id' => 6,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-10 10:14:19',
			'modified' => '2014-01-10 10:14:19'
		),
		array(
			'id' => 8,
			'etape_id' => 8,
			'type_validation' => 'V',
			'soustype' => null,
			'type_composition' => 'USER',
			'trigger_id' => 3,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-10 16:42:39',
			'modified' => '2014-01-10 16:42:39'
		),
	);
}