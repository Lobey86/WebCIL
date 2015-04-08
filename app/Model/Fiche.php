<?php
App::uses('AppModel', 'Model');

class Fiche extends AppModel {
	public $name = 'Fiche';

/**
 * belongsTo associations
 *
 * @var array
 */
public $belongsTo = array(
	'Organisation' => array(
		'className'  => 'Organisation',
		'foreignKey' => 'organisation_id'
		),
	'User' => array(
		'className' => 'User',
		'foreignKey' => 'user_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
public $hasMany = array(
	'File' => array(
		'className'  => 'File',
		'foreignKey' => 'fiche_id',
		'dependent'  => true,
		),
	'EtatFiche' => array(
		'className'  => 'EtatFiche',
		'foreignKey' => 'fiche_id',
		'dependent'  => true,
		)
	);


public function isOwner($idUser=NULL, $fiche=NULL){
	if($idUser == $fiche['Fiche']['user_id']){
		return true;
	}
	else{
		return false;
	}
}
} 