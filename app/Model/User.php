<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel
{
    public $name = 'User';
    public $actAs = array('Password');
    public $validate = array(
        'username' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Un nom d\'utilisateur est requis'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'Ce nom d\'utilisateur existe déjà'
            )
        ),
        'password' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Un mot de passe est requis'
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
     * @var array
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
        )
    );


    public function beforeSave($options = array())
    {
        if ( isset($this->data[ $this->alias ][ 'password' ]) ) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[ $this->alias ][ 'password' ] = $passwordHasher->hash($this->data[ $this->alias ][ 'password' ]);
        }
        return true;
    }

    function comparePassword($field = array(), $compareField = null)
    {
        foreach ( $field as $key => $value ) {
            $v1 = $value;
            $v2 = $this->data[ $this->name ][ $compareField ];

            if ( $v1 != $v2 ) return false;
            else
                continue;
        }
        return true;
    }


}