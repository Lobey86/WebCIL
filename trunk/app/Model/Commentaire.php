<?php
App::uses('AppModel', 'Model');

class Commentaire extends AppModel {
	public $name = 'Commentaire';

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'EtatFiche' => array(
			'className'  => 'EtatFiche',
			'foreignKey' => 'etat_fiche_id',
				//'conditions' => '',
				//'fields'     => '',
				//'order'      => ''
			),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
			),
		'Destinataire' => array(
			'className' => 'User',
			'foreignKey' => 'destinataire_id'
			)
		);
} 