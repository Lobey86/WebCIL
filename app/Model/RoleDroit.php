<?php
App::uses('AppModel', 'Model');

class RoleDroit extends AppModel {
    public $name = 'RoleDroit';

    public $belongsTo = array(
        'Role' =>
            array(
                'className' => 'Role',
                'foreignKey' => 'role_id'
            ),
        'ListeDroit' =>
            array(
                'className' => 'ListeDroit',
                'foreignKey' => 'liste_droit_id'
            )
    );
}