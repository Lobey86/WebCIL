<?php
App::uses('AppModel', 'Model');

class Organisation extends AppModel
{
    public $name = 'Organisation';

    public $validate = array(
        'nom'           => array(
            array(
                'rule'    => array('notEmpty'),
                'message' => 'Un nom d\'organisation est requis'
                ),
            array(
                'rule'    => 'isUnique',
                'message' => 'Cette organisation existe déjà'
                )
            ),
        'logo_file'     => array(
            'upload' => array(
                'rule'       => array('extension', array('jpg', 'jpeg', 'png', 'gif', '')),
                'allowEmpty' => true,
                'message'    => 'Vous ne pouvez envoyer que des fichiers .jpg, .gif ou .png'
                )
            ),
        'raisonsociale' => array(
            'unicité' => array(
                'rule'    => 'isUnique',
                'message' => 'Cette organisation existe déjà'
                ),
            'nonvide' => array(
                'rule'    => 'notEmpty',
                'message' => 'Le nom doit être précisé'
                )
            ),
        'telephone'     => array(
            'rule'    => 'notEmpty',
            'message' => 'Le numéro de téléphone doit être précisé'
            ),
        'adresse'       => array(
            'rule'    => 'notEmpty',
            'message' => 'L\'adresse doit etre précisée'
            ),
        'email'         => array(
            'format'  => array(
                'rule'    => 'email',
                'message' => 'L\'adresse e-mail présente un format non conforme'
                ),
            'nonvide' => array(
                'rule'    => 'notEmpty',
                'message' => 'Le nom doit être précisé'
                )
            ),
        'siret'         => array(
            'rule'    => 'notEmpty',
            'message' => 'Le numéro de SIRET doit être précisé'
            ),
        'ape'           => array(
            'rule'    => 'notEmpty',
            'message' => 'Le code APE doit être précisé'
            )
        );

public function beforeDelete($cascade = true)
{
    $oldextension = $this->field('logo');
    $oldfile = IMAGES . 'logos' . DS . $this->id . '.' . $oldextension;
    if (file_exists($oldfile)) {
        unlink($oldfile);
    }
}

public function saveAddEditForm($data, $id = null){
    $success = true;
    $this->begin();
    $success = $this->save($data);
    if( $success ) {
        if (isset($data[$this->alias]['logo_file'])) {
            $file = $data[$this->alias]['logo_file'];
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!empty($file['tmp_name'])) {
                $oldextension = $this->field('logo');
                $oldfile = IMAGES . 'logos' . DS . $this->id . '.' . $oldextension;
                if (file_exists($oldfile)) {
                    $success = $success && unlink($oldfile);
                }
                $success = $success && move_uploaded_file($file['tmp_name'],
                    IMAGES . 'logos' . DS . $this->id . '.' . $extension);
                if ( $success ) {
                    $success = $success && $this->saveField('logo', $extension);
                }
            }
        }
    }

    if( $success ) {
        $this->commit();
    }
    else {
        $this->rollback();
    }

    return $success;
}

public $hasAndBelongsToMany = array(
    'User' =>
    array(
        'className'             => 'User',
        'joinTable'             => 'organisations_users',
        'foreignKey'            => 'organisation_id',
        'associationForeignKey' => 'user_id',
        'unique'                => true,
        'conditions'            => '',
        'fields'                => '',
        'order'                 => '',
        'limit'                 => '',
        'offset'                => '',
        'finderQuery'           => '',
        'with'                  => 'OrganisationUser'
    )
);
        /**
         * hasMany associations
         *
         * @var array
         */
        public $hasMany = array(
            'Fiche' => array(
                'className'  => 'Fiche',
                'foreignKey' => 'organisation_id',
                'dependent'  => true
                ),
            'Role' => array(
                'className' => 'Role',
                'foreignKey'=> 'organisation_id',
                'dependent'=>true
                )
            );
    }