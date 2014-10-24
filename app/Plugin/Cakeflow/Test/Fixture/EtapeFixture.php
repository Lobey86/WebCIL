<?php

/**
 * Code source de la classe EtapeFixture.
 *
 * PHP 5.3
 *
 * @package app.Test.Fixture
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

/**
 * Classe EtapeFixture.
 *
 * @package Cakeflow.Test.Fixture
 */
class EtapeFixture extends CakeTestFixture {

    var $name = 'Etape';
    var $table = 'wkf_etapes';
    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
        'circuit_id' => array('type' => 'integer', 'null' => false),
        'nom' => array('type' => 'string', 'null' => false, 'length' => 250),
        'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
        'type' => array('type' => 'integer', 'null' => false),
        'soustype' => array('type' => 'integer', 'null' => true),
        'ordre' => array('type' => 'integer', 'null' => false),
        'created_user_id' => array('type' => 'integer', 'null' => false),
        'modified_user_id' => array('type' => 'integer', 'null' => true),
        'created' => array('type' => 'datetime', 'null' => true),
        'modified' => array('type' => 'datetime', 'null' => true),
        'indexes' => array(
            'PRIMARY' => array('unique' => true, 'column' => 'id'),
            'circuit_id' => array('unique' => false, 'column' => 'circuit_id'),
            'nom' => array('unique' => false, 'column' => 'nom')
        ),
        'tableParameters' => array()
    );

    /**
     * Records
     *
     * @var array
     */
    public $records = array(
        // Premier circuit
        array(
            'id' => 1,
            'circuit_id' => 1,
            'nom' => 'Chef de service',
            'description' => '',
            'type' => 1,
            'soustype' => null,
            'ordre' => 1,
            'created_user_id' => 1,
            'modified_user_id' => 1,
            'created' => '2014-01-03 16:03:44',
            'modified' => '2014-01-03 16:03:44'
        ),
        array(
            'id' => 2,
            'circuit_id' => 1,
            'nom' => 'Directeur',
            'description' => '',
            'type' => 1,
            'soustype' => null,
            'ordre' => 2,
            'created_user_id' => 1,
            'modified_user_id' => 1,
            'created' => '2014-01-03 16:04:10',
            'modified' => '2014-01-03 16:04:10'
        ),
        array(
            'id' => 3,
            'circuit_id' => 1,
            'nom' => 'Assemblées',
            'description' => '',
            'type' => 1,
            'soustype' => null,
            'ordre' => 3,
            'created_user_id' => 1,
            'modified_user_id' => 1,
            'created' => '2014-01-03 16:04:38',
            'modified' => '2014-01-03 16:04:38'
        ),
        // Deuxieme circuit
        array(
            'id' => 4,
            'circuit_id' => 2,
            'nom' => 'ETAPE 1 REDACTEUR',
            'description' => '',
            'type' => 1,
            'soustype' => null,
            'ordre' => 1,
            'created_user_id' => 1,
            'modified_user_id' => 1,
            'created' => '2014-01-10 16:42:28',
            'modified' => '2014-01-10 16:42:28'
        ),
        array(
            'id' => 5,
            'circuit_id' => 2,
            'nom' => 'valideur A',
            'description' => '',
            'type' => 1,
            'soustype' => null,
            'ordre' => 2,
            'created_user_id' => 1,
            'modified_user_id' => 1,
            'created' => '2014-01-10 10:12:26',
            'modified' => '2014-01-10 10:12:26'
        ),
        array(
            'id' => 6,
            'circuit_id' => 2,
            'nom' => 'valideur B',
            'description' => '',
            'type' => 1,
            'soustype' => null,
            'ordre' => 3,
            'created_user_id' => 1,
            'modified_user_id' => 1,
            'created' => '2014-01-10 10:12:54',
            'modified' => '2014-01-10 10:13:03'
        ),
        array(
            'id' => 7,
            'circuit_id' => 2,
            'nom' => 'valideur C',
            'description' => '',
            'type' => 1,
            'soustype' => null,
            'ordre' => 4,
            'created_user_id' => 1,
            'modified_user_id' => 1,
            'created' => '2014-01-10 10:13:29',
            'modified' => '2014-01-10 10:13:29'
        ),
        array(
            'id' => 8,
            'circuit_id' => 2,
            'nom' => 'valideur D',
            'description' => '',
            'type' => 1,
            'soustype' => null,
            'ordre' => 5,
            'created_user_id' => 1,
            'modified_user_id' => 1,
            'created' => '2014-01-10 10:14:10',
            'modified' => '2014-01-10 10:14:10'
        ),
    );

}
