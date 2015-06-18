<?php
App::uses('AppModel', 'Model');

class OrganisationUserService extends AppModel
{
    public $name = 'OrganisationUserService';

    /**
     * belongsTo associations
     * @var array
     */
    public $belongsTo = array(
        'OrganisationUser' => array(
            'className' => 'OrganisationUser',
            'foreignKey' => 'organisation_user_id',
            //'conditions' => '',
            //'fields'     => '',
            //'order'      => ''
        ),
        'Service' => array(
            'className' => 'Service',
            'foreignKey' => 'service_id',
            //'conditions' => '',
            //'fields'     => '',
            //'order'      => ''
        )
    );
}