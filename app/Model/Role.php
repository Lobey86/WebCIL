<?php
App::uses('AppModel', 'Model');

class Role extends AppModel {
    public $name = 'Role';
    public $validate = array(
        'libelle' => array(
            array(
                'rule' => array('notEmpty'),
                'message' => 'Un nom de rôle est requis'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'Ce rôle existe déjà'
            )
        )
    );
}