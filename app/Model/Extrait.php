<?php
App::uses('AppModel', 'Model');

class Extrait extends AppModel 
{
    public $name = 'Extrait';
    
    /**
     * belongsTo associations
     * @var array
     */
    public $belongsTo = array(
        'Fiche' => array(
            'className' => 'Fiche',
            'foreignKey' => 'fiche_id'
        )
    );
}