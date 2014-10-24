<?php

/**
 * Code source de la classe CircuitFixture.
 *
 * PHP 5.3
 *
 * @package app.Test.Fixture
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

/**
 * Classe CircuitFixture.
 *
 * @package Cakeflow.Test.Fixture
 */
class CircuitFixture extends CakeTestFixture {

    var $name = 'Circuit';
    var $table = 'wkf_circuits';    
   /**
 * fields property
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'nom' => array('type' => 'string', 'null' => false),
                'description' => array('type' => 'text', 'null' => true),
                'actif' => array('type' => 'string', 'null' => false, 'default'=>true),
                'defaut'=> array('type' => 'string', 'null' => false, 'default'=>false),
                'created_user' => 'integer',
                'modified_user' => 'integer',
		'created' => 'datetime',
		'modified' => 'datetime'
	); 
    /**
 * records property
 *
 * @var array
 */
	public $records = array(
		array('nom' => 'Circuit Test 1', 'created' => '2014-01-03 16:03:33', 'modified' => '2014-01-03 16:03:33'),
		array('nom' => 'Circuit Test 2', 'created' => '2014-01-03 16:03:33', 'modified' => '2014-01-03 16:03:33'),
	);

}

?>