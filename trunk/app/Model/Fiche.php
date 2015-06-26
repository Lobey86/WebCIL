<?php
App::uses('AppModel', 'Model');

class Fiche extends AppModel
{
    public $name = 'Fiche';

    /**
     * belongsTo associations
     * @var array
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
     * @var array
     */
    public $hasMany = array(
        'File' => array(
            'className' => 'File',
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


    public function isOwner($idUser = NULL, $fiche = NULL)
    {
        if($idUser == $fiche['Fiche']['user_id']) {
            return true;
        } else {
            return false;
        }
    }

    public function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
} 