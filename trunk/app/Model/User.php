<?php

/**
 * Model User
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
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {

    public $name = 'User';
    public $actAs = array('Password');

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
        'password' => array(
            array(
                'rule' => array('minLength', '5'),
                'message' => 'Un mot de passe est requis avec minimum 5 caractères'
            )
        ),
        'passwd' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Vous devez confirmer le mot de passe'
            ),
            array(
                'rule' => array(
                    'comparePassword',
                    'password'
                ),
                'message' => 'Les mots de passe ne sont pas identiques'
            )
        ),
        'nom' => array(
            array(
                'rule' => array('custom', REGEXP_ALPHA_FR),
                'message' => 'Seulement des lettres sont accepté'
            )
        ),
        'prenom' => array(
            array(
                'rule' => array('custom', REGEXP_ALPHA_FR),
                'message' => 'Seulement des lettres sont accepté'
            )
        ),
    );

    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     * 
     * @access public
     * @created 18/12/2015
     * @version V0.9.0
     */
    public $hasAndBelongsToMany = array(
        'Organisation' => array(
            'className' => 'Organisation',
            'joinTable' => 'organisations_users',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'organisation_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'with' => 'OrganisationUser'
        )
    );

    /**
     * hasMany associations
     * 
     * @var array
     * 
     * @access public
     * @created 26/03/2015
     * @version V0.9.0 
     */
    public $hasMany = array(
        'Fiche' => array(
            'className' => 'Fiche',
            'foreignKey' => 'user_id',
            'dependent' => true,
        ),
        'EtatFiche' => array(
            'className' => 'EtatFiche',
            'foreignKey' => 'user_id',
            'dependent' => true,
        ),
        'Previous' => array(
            'className' => 'EtatFiche',
            'foreignKey' => 'previous_user_id'
        ),
        'Commentaire' => array(
            'className' => 'Commentaire',
            'foreignKey' => 'user_id'
        ),
        'Destin' => array(
            'className' => 'Commentaire',
            'foreignKey' => 'destinataire_id'
        ),
        'Cil' => array(
            'className' => 'Organisation',
            'foreignKey' => 'cil'
        ),
        'Notification' => array(
            'className' => 'Notification',
            'foreignKey' => 'user_id'
        ),
        'OrganisationUserRole' => array(
            'className' => 'OrganisationUserRole',
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
        'Admin' => array(
            'className' => 'Admin',
            'foreignKey' => 'user_id',
            'dependent' => true
        )
    );

    /**
     * @param type $options
     * @return boolean
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
        }
        return true;
    }

    /**
     * @param type $field
     * @param type|null $compareField
     * @return boolean
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    function comparePassword($field = array(), $compareField = null) {
        foreach ($field as $key => $value) {
            $v1 = $value;
            $v2 = $this->data[$this->name][$compareField];

            if ($v1 != $v2) {
                return false;
            } else {
                continue;
            }
        }
        return true;
    }

}
