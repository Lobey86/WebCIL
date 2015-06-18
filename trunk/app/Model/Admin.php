<?php
App::uses('AppModel', 'Model');

class Admin extends AppModel {
    public $name = 'Admin';
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
    		)
    	);
}