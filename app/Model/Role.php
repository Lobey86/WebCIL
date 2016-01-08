<?php

/**
 * Model Role
 *
 * WebCIL : Outil de gestion du Correspondant Informatique et Libertés.
 * Cet outil consiste à accompagner le CIL dans sa gestion des déclarations via 
 * le registre. Le registre est sous la responsabilité du CIL qui doit en 
 * assurer la communication à toute personne qui en fait la demande (art. 48 du décret octobre 2005).
 * 
 * Copyright (c) Adullact (http://www.adullact.org)
 *
 * Licensed under The CeCiLL V2 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright   Copyright (c) Adullact (http://www.adullact.org)
 * @link        https://adullact.net/projects/webcil/
 * @since       webcil v0.9.0
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 * @version     v0.9.0
 * @package     AppModel
 */
App::uses('AppModel', 'Model');

class Role extends AppModel {

    public $name = 'Role';

    /**
     * validate associations
     *
     * @var array
     * 
     * @access public
     * @created 24/10/2015
     * @version V0.9.0
     */
    public $validate = array(
        'libelle' => array(
            array(
                'rule' => array('notEmpty'),
                'message' => 'Un nom de profil est requis'
            )
        )
    );

    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     * 
     * @access public
     * @created 26/03/2015
     * @version V0.9.0
     */
    public $hasAndBelongsToMany = array(
        'ListeDroit' => array(
            'className' => 'ListeDroit',
            'joinTable' => 'role_droits',
            'foreignKey' => 'role_id',
            'associationForeignKey' => 'liste_droit_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'with' => 'RoleDroit'
        )
    );

    /**
     * belongsTo associations
     * 
     * @var array
     * 
     * @access public
     * @created 26/03/2015
     * @version V0.9.0
     */
    public $belongsTo = array(
        'Organisation' => array(
            'className' => 'Organisation',
            'foreignKey' => 'organisation_id',
        )
    );

    /**
     * hasMany associations
     * 
     * @var array
     * 
     * @access public
     * @created 06/05/2015
     * @version V0.9.0
     */
    public $hasMany = array(
        'OrganisationUserRole' => array(
            'className' => 'OrganisationUserRole',
            'foreignKey' => 'role_id',
            'dependent' => true,
        )
    );

}
