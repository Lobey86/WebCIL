<?php

/**
 * Model Formulaire
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

class Formulaire extends AppModel {

    public $tablePrefix = 'fg_';

    /**
     * hasOne associations
     *
     * @var array
     * 
     * @access public
     * @created 18/06/2015
     * @version V0.9.0
     */
    public $hasOne = array(
        'Modele' => array(
            'className' => 'Modele',
            'foreignKey' => 'formulaires_id',
            'dependent' => true
        )
    );
    
    /**
     * belongsTo associations
     *
     * @var array
     * 
     * @author Théo GUILLON <theo.guillon@adullact-projet.coop>
     * @access public
     * @created 04/11/2016
     * @version V1.0.0
     */
    public $belongsTo = array(
        'Service' => array(
            'className' => 'Service',
            'foreignKey' => 'service_id',
        )
    );

}
