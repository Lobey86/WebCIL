<?php
App::uses('AppModel', 'Model');

class Notification extends AppModel {
    public $name = 'Notification';
    
/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
        'User' => array(
            'className'  => 'User',
            'foreignKey' => 'user_id',
            //'conditions' => '',
            //'fields'     => '',
            //'order'      => ''
        ),
        'Fiche' => array(
            'className' => 'Fiche',
            'foreignKey' => 'fiche_id'
            )
    );

    
}