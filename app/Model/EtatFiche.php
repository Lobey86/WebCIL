<?php
App::uses('AppModel', 'Model');

class EtatFiche extends AppModel {
    public $name = 'EtatFiche';
    

/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
        'Etat' => array(
            'className'  => 'Etat',
            'foreignKey' => 'etat_id',
        ),
        'Fiche' => array(
            'className' => 'Fiche',
            'foreignKey' => 'fiche_id'
            ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
            ),
        'PreviousUser' => array(
            'className'=> 'User',
            'foreignKey' => 'previous_user_id'
            )
    );

/**
 * hasOne associations
 *
 * @var array
 */
    public $hasMany = array(
        'Commentaire' => array(
            'className'  => 'Commentaire',
            'foreignKey' => 'etat_fiches_id',
            'dependent'  => true
        )
    );


    
}