<?php

/**
 * Model Fiche
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

class Fiche extends AppModel {

    public $name = 'Fiche';

    /**
     * hasOne associations
     * 
     * @var array
     * 
     * @access public
     * @created 04/01/2016
     * @version V0.9.0
     */
    public $hasOne = array(
        'Extrait' => array(
            'className' => 'Extrait',
            'foreignKey' => 'fiche_id'
        ),
    );

    /**
     * belongsTo associations
     * 
     * @var array
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public $belongsTo = array(
        'Organisation' => array(
            'className' => 'Organisation',
            'foreignKey' => 'organisation_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

    /**
     * hasMany associations
     * 
     * @var array
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public $hasMany = array(
        'Fichier' => array(
            'className' => 'Fichier',
            'foreignKey' => 'fiche_id',
            'dependent' => true,
        ),
        'EtatFiche' => array(
            'className' => 'EtatFiche',
            'foreignKey' => 'fiche_id',
            'dependent' => true,
        ),
        'Notification' => array(
            'className' => 'Notification',
            'foreignKey' => 'fiche_id',
            'dependent' => true
        ),
        'Historique' => array(
            'className' => 'Historique',
            'foreignKey' => 'fiche_id',
            'dependant' => true
        ),
        'Valeur' => array(
            'className' => 'Valeur',
            'foreignKey' => 'fiche_id',
            'dependant' => true
        ),
        'Modification' => array(
            'className' => 'Modification',
            'foreignKey' => 'fiche_id',
            'dependant' => true
        )
    );

    /**
     * @param int|null $idUser
     * @param type|null $fiche
     * @return boolean
     * 
     * @access public
     * @created 09/04/2015
     * @version V0.9.0
     */
    public function isOwner($idUser = null, $fiche = null) {
        if ($idUser == $fiche['Fiche']['user_id']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param type $string
     * @return type
     * 
     * @access public
     * @created 26/06/2015
     * @version V0.9.0
     */
    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}
