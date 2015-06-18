<?php
App::uses('AppModel', 'Model');

class Formulaire extends FormGeneratorAppModel
{
    public $tablePrefix = 'fg_';
    /**
     * hasOne associations
     *
     * @var array
     */
    public $hasOne = array(
        'Modele' => array(
            'className' => 'Modele',
            'foreignKey' => 'formulaires_id',
            'dependent' => true
        )
    );
}