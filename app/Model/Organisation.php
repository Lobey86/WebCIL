<?php

/**
 * Model Organisation
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

class Organisation extends AppModel {

    public $name = 'Organisation';

    /**
     * validate associations
     *
     * @var array
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public $validate = [
        'logo_file' => [
            'upload' => [
                'rule' => [
                    'extension',
                        [
                        'jpg',
                        'jpeg',
                        'png',
                        'gif',
                        ''
                    ]
                ],
                'allowEmpty' => true,
                'message' => 'Vous ne pouvez envoyer que des fichiers .jpg, .gif ou .png'
            ]
        ],
        'model_file' => [
            'upload' => [
                'rule' => [
                    'extension',
                        [
                        'odt',
                        ''
                    ]
                ],
                'allowEmpty' => true,
                'message' => 'Vous ne pouvez envoyer que des fichiers .odt'
            ]
        ],
        'siret' => [
            'luhn' => [
                'rule' => [
                    'luhn',
                    true
                ],
                'message' => 'Le numéro de SIRET n\'est pas valide'
            ]
        ],
    ];

    /**
     * @param type|true $cascade
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function beforeDelete($cascade = TRUE) {
        $oldextension = $this->field('logo');
        $oldfile = IMAGES . 'logos' . DS . $this->id . '.' . $oldextension;
        if (file_exists($oldfile)) {
            unlink($oldfile);
        }
    }

    /**
     * @param type $data
     * @return boolean
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function saveAddEditForm($data) {
        $this->begin();
        $success = $this->save($data);
        $errors = $this->validationErrors;

        if ($success) {
            debug($success);
            if (isset($data[$this->alias]['logo_file']['tmp_name'])) {
                $file = $data[$this->alias]['logo_file'];
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (!empty($file['tmp_name'])) {
                    $oldextension = $this->field('logo');
                    $oldfile = IMAGES . 'logos' . DS . $this->id . '.' . $oldextension;
                    if (file_exists($oldfile)) {
                        $success = $success && unlink($oldfile);
                    }
                    $success = $success && move_uploaded_file($file['tmp_name'], IMAGES . 'logos' . DS . $this->id . '.' . $extension);
                    if ($success) {
                        $success = $success && $this->saveField('logo', $extension);
                    }
                }
            }
            if (isset($data[$this->alias]['model_file'])) {
                $file = $data[$this->alias]['model_file'];
                if (!empty($file['tmp_name'])) {
                    $oldfile = 'files' . DS . 'modeles' . DS . $this->id . '.odt';
                    if (file_exists($oldfile)) {
                        $success = $success && unlink($oldfile);
                    }
                    $success = $success && move_uploaded_file($file['tmp_name'], 'files' . DS . 'modeles' . DS . $this->id . '.odt');
                }
            }
        }
        if ($success) {
            $this->commit();

            return TRUE;
        } else {
            $this->rollback();

            return $errors;
        }
    }

    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public $hasAndBelongsToMany = [
        'User' => [
            'className' => 'User',
            'joinTable' => 'organisations_users',
            'foreignKey' => 'organisation_id',
            'associationForeignKey' => 'user_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'with' => 'OrganisationUser'
        ]
    ];

    /**
     * hasMany associations
     *
     * @var array
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public $hasMany = [
        'Fiche' => [
            'className' => 'Fiche',
            'foreignKey' => 'organisation_id',
            'dependent' => TRUE
        ],
        'Role' => [
            'className' => 'Role',
            'foreignKey' => 'organisation_id',
            'dependent' => TRUE
        ]
    ];

    /**
     * belongsTo associations
     * 
     * @var array
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public $belongsTo = [
        'Cil' => [
            'className' => 'User',
            'foreignKey' => 'cil',
        //'conditions' => '',
        //'fields'     => '',
        //'order'      => ''
        ],
        'Service' => [
            'className' => 'Service',
            'foreignKey' => 'organisation_id',
        //'conditions' => '',
        //'fields'     => '',
        //'order'      => ''
        ]
    ];

}
