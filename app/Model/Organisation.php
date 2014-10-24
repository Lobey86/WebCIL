<?php
App::uses('AppModel', 'Model');

class Organisation extends AppModel {
    public $name = 'Organisation';
    public $validate = array(
        'nom' => array(
            array(
                'rule' => array('notEmpty'),
                'message' => 'Un nom d\'organisation est requis'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'Cette organisation existe déjà'
            )
        )
    );
}