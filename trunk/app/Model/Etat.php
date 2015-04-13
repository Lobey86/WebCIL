<?php
App::uses('AppModel', 'Model');

class Etat extends AppModel {
    public $name = 'Etat';
    

      /**
       * hasMany associations
       *
       * @var array
       */
          public $hasMany = array(
              'EtatFiche' => array(
                  'className'  => 'EtatFiche',
                  'foreignKey' => 'etat_id',
                  'dependent'  => false,
              )
          );
      
    
}