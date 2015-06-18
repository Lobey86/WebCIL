<?php
App::uses('AppModel', 'Model');

class Historique extends AppModel
{
    public $name = 'Historique';

    public $belongsTo = array(
        'Fiche' => array(
            'className' => 'Fiche',
            'foreignKey' => 'fiche_id',
            //'conditions' => '',
            //'fields'     => '',
            //'order'      => ''
        )
    );
}