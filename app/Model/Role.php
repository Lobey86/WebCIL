<?php
App::uses('AppModel', 'Model');

class Role extends AppModel {
    public $name     = 'Role';
    public $validate = array(
        'libelle'        => array(
            array(
                'rule'    => array('notEmpty'),
                'message' => 'Un nom de rôle est requis'
                ),
            array(
                'rule'    => 'isUnique',
                'message' => 'Ce rôle existe déjà'
                )
            )
        );

    public $hasAndBelongsToMany = array(
        'ListeDroit' =>
            array(
                'className'             => 'ListeDroit',
                'joinTable'             => 'role_droits',
                'foreignKey'            => 'role_id',
                'associationForeignKey' => 'liste_droit_id',
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

/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
        'Organisation' => array(
            'className'  => 'Organisation',
            'foreignKey' => 'organisation_id',
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
        public $hasMany = array(
            'OrganisationUserRole' => array(
                'className'  => 'OrganisationUserRole',
                'foreignKey' => 'role_id',
                'dependent'  => true,
            )
        );
    

    
}