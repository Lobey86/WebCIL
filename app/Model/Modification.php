<?php
App::uses('AppModel', 'Model');

class Modification extends AppModel
{
    public $name = 'Modification';

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Fiche' => array(
            'className' => 'Fiche',
            'foreignKey' => 'fiches_id',
            //'conditions' => '',
            //'fields'     => '',
            //'order'      => ''
        ),
    );
}