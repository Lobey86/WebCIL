<?php

/**
 * Model Service
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

class Service extends AppModel {

    public $name = 'Service';

    public $displayField = 'libelle';

    /**
     * Règles de validation supplémentaires.
     *
     * @var array
     */
    public $validate = [
        'libelle' => [
            'isUniqueMultiple' => [
                'rule' => ['isUniqueMultiple', ['libelle', 'organisation_id']],
                'message' => 'Valeur déjà utilisée'
            ]
        ],
        'organisation_id' => [
            'isUniqueMultiple' => [
                'rule' => ['isUniqueMultiple', ['libelle', 'organisation_id']],
                'message' => 'Valeur déjà utilisée'
            ]
        ]
    ];

    /**
     * hasMany associations
     *
     * @var array
     *
     * @access public
     * @created 18/06/2015
     * @modified 04/11/2016
     * @version V0.9.0
     */
    public $hasMany = array(
        'OrganisationUser' => array(
            'className' => 'OrganisationUser',
            'foreignKey' => 'service_id',
            'dependent' => false,
        ),
        'Formulaire' => array(
            'className' => 'Formulaire',
            'foreignKey' => 'service_id',
            'dependent' => false,
        ),
        'OrganisationUserService' => array(
            'className' => 'OrganisationUserService',
            'foreignKey' => 'service_id',
            'dependent' => false,
        )
    );

}
