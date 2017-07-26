<?php

/**
 * Model OrganisationUser
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

class OrganisationUser extends AppModel {

    public $name = 'OrganisationUser';

    /**
     * validate associations
     *
     * @var array
     *
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public $validate = array(
        'organisation_id' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Vous devez sélectionner une organisation'
            )
        )
    );

    /**
     * belongsTo associations
     *
     * @var array
     *
     * @access public
     * @created 13/04/2015
     * @version V0.9.0
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'Organisation' => array(
            'className' => 'Organisation',
            'foreignKey' => 'organisation_id'
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     *
     * @access public
     * @created 13/04/2015
     * @version V0.9.0
     */
    public $hasMany = array(
        'Droit' => array(
            'className' => 'Droit',
            'foreignKey' => 'organisation_user_id',
            'dependent' => true
        ),
        'OrganisationUserRole' => array(
            'className' => 'OrganisationUserRole',
            'foreignKey' => 'organisation_user_id',
            'dependent' => true
        ),
        'OrganisationUserService' => array(
            'className' => 'OrganisationUserService',
            'foreignKey' => 'organisation_user_id'
        )
    );

    /**
     * hasOne associations
     *
     * @var array
     *
     * @access public
     * @created 26/06/2015
     * @version V0.9.0
     */
    public $hasOne = array(
        'OrganisationUserService' => array(
            'className' => 'OrganisationUserService',
            'foreignKey' => 'organisation_user_id',
            'dependent' => true
        )
    );

}
