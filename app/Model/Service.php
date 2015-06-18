<?php
App::uses('AppModel', 'Model');

class Service extends AppModel
{
    public $name = 'Service';

    /**
     * hasMany associations
     * @var array
     */
    public $hasMany = array(
        'OrganisationUser' => array(
            'className' => 'OrganisationUser',
            'foreignKey' => 'service_id',
            'dependent' => false,
            //'conditions' => array('' => ''),
            //'fields'       => '',
            //'order'        => '',
            //'limit'        => '',
            //'offset'       => '',
            //'exclusive'    => '',
            //'finderQuery'  => '',
            //'counterQuery' => ''
        )
    );
}