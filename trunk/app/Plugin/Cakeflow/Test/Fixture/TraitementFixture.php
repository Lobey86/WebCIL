<?php
/**
* Code source de la classe TraitementFixture.
 *
* PHP 5.3
*
* @package app.Test.Fixture
* @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */
class TraitementFixture extends CakeTestFixture {
        var $name = 'Traitement';
        var $table = 'wkf_traitements';
/**
* Classe TraitementFixture.
 *
* @package Cakeflow.Test.Fixture
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'circuit_id' => array('type' => 'integer', 'null' => false),
		'target_id' => array('type' => 'integer', 'null' => false),
		'numero_traitement' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'treated_orig' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created_user_id' => array('type' => 'integer', 'null' => true),
		'modified_user_id' => array('type' => 'integer', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'treated' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'circuits' => array('unique' => false, 'column' => 'circuit_id'),
			'target' => array('unique' => false, 'column' => 'target_id'),
			'traitements_treated' => array('unique' => false, 'column' => 'treated_orig')
		),
		'tableParameters' => array()
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
            //Circuit 1
            //
            /*
                array(
			//'id' => 1, A cause du serial
			'circuit_id' => 1,
			'target_id' => 2,
			'numero_traitement' => 8,
			'treated_orig' => 0,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-03 17:30:42',
			'modified' => '2014-01-10 09:55:37',
			'treated' => 0
		),
                array(
			//'id' => 2,
			'circuit_id' => 1,
			'target_id' => 5,
			'numero_traitement' => 6,
			'treated_orig' => 0,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-06 10:12:17',
			'modified' => '2014-01-09 12:03:41',
			'treated' => 0
		),
		array(
			//'id' => 3,
			'circuit_id' => 1,
			'target_id' => 7,
			'numero_traitement' => 1,
			'treated_orig' => 0,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-08 12:19:24',
			'modified' => '2014-01-08 12:19:34',
			'treated' => 1
		),
		array(
			//'id' => 4,
			'circuit_id' => 1,
			'target_id' => 8,
			'numero_traitement' => 1,
			'treated_orig' => 0,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-08 12:20:53',
			'modified' => '2014-01-08 12:21:00',
			'treated' => 1
		),
		
		array(
			//'id' => 5,
			'circuit_id' => 1,
			'target_id' => 6,
			'numero_traitement' => 1,
			'treated_orig' => 0,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-09 12:04:24',
			'modified' => '2014-01-09 12:04:24',
			'treated' => 0
		),
		array(
			//'id' => 6,
			'circuit_id' => 1,
			'target_id' => 4,
			'numero_traitement' => 2,
			'treated_orig' => 0,
			'created_user_id' => 8,
			'modified_user_id' => 8,
			'created' => '2014-01-09 12:08:04',
			'modified' => '2014-01-09 12:10:47',
			'treated' => 0
		),
            //Circuit 2
		array(
			//'id' => 7,
			'circuit_id' => 2,
			'target_id' => 10,
			'numero_traitement' => 7,
			'treated_orig' => 0,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-10 10:16:49',
			'modified' => '2014-01-10 16:31:25',
			'treated' => 0
		),
		array(
			//'id' => 8,
			'circuit_id' => 2,
			'target_id' => 11,
			'numero_traitement' => 2,
			'treated_orig' => 0,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-10 16:27:47',
			'modified' => '2014-01-10 16:33:00',
			'treated' => 0
		),
		array(
			//'id' => 9,
			'circuit_id' => 2,
			'target_id' => 9,
			'numero_traitement' => 1,
			'treated_orig' => 0,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-10 16:29:43',
			'modified' => '2014-01-10 16:29:43',
			'treated' => 0
		),
		array(
			//'id' => 10,
			'circuit_id' => 2,
			'target_id' => 12,
			'numero_traitement' => 1,
			'treated_orig' => 0,
			'created_user_id' => 1,
			'modified_user_id' => 1,
			'created' => '2014-01-10 16:34:33',
			'modified' => '2014-01-10 16:34:33',
			'treated' => 0
		),*/
	);

}
