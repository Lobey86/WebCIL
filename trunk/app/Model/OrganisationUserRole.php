<?php
App::uses('AppModel', 'Model');

class OrganisationUserRole extends AppModel {
    public $name = 'OrganisationUserRole';

    public $belongsTo = array(
        'OrganisationUser' =>
            array(
                'className' => 'OrganisationUser',
                'foreignKey' => 'organisation_user_id'
            ),
        'Role' =>
            array(
                'className' => 'Role',
                'foreignKey' => 'role_id'
            )
    );
}