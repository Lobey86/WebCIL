<?php
App::uses('AppModel', 'Model');

class Droit extends AppModel {
    public $name = 'Droit';

    /**
     * belongsTo associations
     *
     * @var array
     */
    	public $belongsTo =
    		array(
				'OrganisationUser'	=> array(
				'className'			=> 'OrganisationUser',
				'foreignKey'		=> 'organisation_user_id'	
    	),
    	
    		'ListeDroit'=>array(
				'className'		=>'ListeDroit',
				'foreignKey'	=>'liste_droit_id')
    	);
    	

    
    
}