<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
    public $name = 'User';
    public $validate = array(
        'username' => array(
            array(
                'rule' => array('notEmpty'),
                'message' => 'Un nom d\'utilisateur est requis'
                ),
            array(
                'rule' => 'isUnique',
                'message' => 'Ce nom d\'utilisateur existe déjà'
                )
            ),
        'email' => array(
            array(
                'rule' => array('notEmpty'),
                'message' => 'Une adresse e-mail doit être renseignée'
                ),
            array(
                'rule' => 'isUnique',
                'message' => 'Cette adresse e-mail est déjà utilisée'
                )
            )
        );

    public $hasAndBelongsToMany = array(
        'Organisation' =>
        array(
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
     */
    public $hasMany = array(
        'Fiche' => array(
            'className'  => 'Fiche',
            'foreignKey' => 'user_id',
            'dependent'  => true,
            ),
        'EtatFiche' => array(
            'className'  => 'EtatFiche',
            'foreignKey' => 'user_id',
            'dependent'  => true,
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
            )
        );
    

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
                );
        }
        return true;
    }

}