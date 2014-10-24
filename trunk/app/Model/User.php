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
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Un mot de passe est requis'
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