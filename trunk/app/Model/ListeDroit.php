<?php
App::uses('AppModel', 'Model');

class ListeDroit extends AppModel {
    public $name = 'ListeDroit';
    
/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Droit' => array(
			'className'  => 'Droit',
			'foreignKey' => 'liste_droit_id',
			'dependent'  => true
		)
	);
        public $hasAndBelongsToMany = array(
            'Role' =>
                array(
                    'className'             => 'Role',
                    'joinTable'             => 'role_droits',
                    'foreignKey'            => 'liste_droit_id',
                    'associationForeignKey' => 'role_id',
                    'unique'                => true,
                    'conditions'            => '',
                    'fields'                => '',
                    'order'                 => '',
                    'limit'                 => '',
                    'offset'                => '',
                    'finderQuery'           => '',
                    'with'                  => 'RoleDroit'
                )
        );
    
}