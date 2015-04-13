<?php
App::uses('AppModel', 'Model');

class OrganisationUser extends AppModel {
    public $name = 'OrganisationUser';

    public $belongsTo = array(
        'User' =>
        array(
            'className' => 'User',
            'foreignKey' => 'user_id'
            ),
        'Organisation' =>
        array(
            'className' => 'Organisation',
            'foreignKey' => 'organisation_id'
            )
        );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Droit' => array(
            'className'  => 'Droit',
            'foreignKey' => 'organisation_user_id',
            'dependent'  => true
            ),
        'OrganisationUserRole'=>array(
            'className' => 'OrganisationUserRole',
            'foreignKey' => 'organisation_user_id',
            'dependent' => true
            )
        );
}