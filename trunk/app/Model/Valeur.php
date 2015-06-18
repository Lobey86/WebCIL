<?php
App::uses('AppModel', 'Model');

class Valeur extends AppModel
{
    public $name = 'Valeur';

    /**
     * belongsTo associations
     *
     * @var array
     */
    	public $belongsTo = array(
    		'Fiche' => array(
    			'className'  => 'Fiche',
    			'foreignKey' => 'fiche_id',
    			//'conditions' => '',
    			//'fields'     => '',
    			//'order'      => ''
    		)
    	);

}