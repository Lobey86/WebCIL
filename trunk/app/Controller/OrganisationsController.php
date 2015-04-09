<?php

/**************************************************
********** Controller des organisations ***********
**************************************************/

class OrganisationsController extends AppController
{
	public $uses = array('Organisation', 'OrganisationUser', 'Droit', 'User');


/**
*** Accueil de la page, listing des organisations
**/

public function index()
{
	if($this->Droits->isSu()){
		$this->set('organisations', $this->paginate());
	}
	elseif($this->Droits->authorized(array('11','12'))){
		$this->set('organisations', $this->OrganisationUser->find(
			'all', 
			array(
				'conditions' => array(
					'OrganisationUser.user_id' => $this->Auth->user('id')
					),
				'contain' => array(
					'Organisation'
					)
				)
			)
		);
	}
	else
	{
		$this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
		$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
	}
}


/**
*** Gère l'ajout d'organisation
**/

public function add()
{
	if($this->Droits->isSu()){
		if ($this->request->is('post')) {
			$this->Organisation->create();
			if ($this->Organisation->saveAddEditForm($this->request->data)) {
				$this->OrganisationUser->create(array('user_id'=>1, 'organisation_id'=>$this->Organisation->getInsertID()));
				$this->OrganisationUser->save();
				$organisationUserId = $this->OrganisationUser->getInsertID();
				for ($i=1; $i < 16; $i++) { 
					$this->Droit->create(array('organisation_user_id'=> $organisationUserId, 'liste_droit_id'=>$i));
				}
				$this->Session->setFlash('L\'organisation a été enregistrée', 'flashsuccess');
				$this->redirect(array('controller' => 'organisations', 'action' => 'index'));
			} else {
				$this->Session->setFlash('Une erreur s\'est produite', 'flasherror');
			}
		}
	}
	else {
		$this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
		$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
	}
}


/**
*** Gère la suppression d'une organisation
**/

public function delete($id = null)
{
	if($this->Droits->authorized(12) || $this->Droits->isSu()){
		$this->Organisation->delete($id);
		$this->Session->setFlash('L\'organisation a été supprimée', 'flashsuccess');
		$this->redirect(array('controller' => 'organisations', 'action' => 'index'));
	}
	else{
		$this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
		$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
	}
}


/**
*** Gère l'affichage des informations d'une organisation
**/

public function show($id = null)
{
	if (!$id) {
		$this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
		$this->redirect(array('controller' => 'organisations', 'action' => 'index'));
	} else {
		$organisation = $this->Organisation->find('first', array(
			'conditions' => array(
				'Organisation.id' => $id
				),
			'contain' => array(
				'Cil' => array(
					'id',
					'nom',
					'prenom'
					)
				)
			)
		);
		if (!$organisation) {
			$this->Session->setFlash('Cette organisation n\'existe pas', 'flasherror');
			$this->redirect(array('controller' => 'organisations', 'action' => 'index'));
		}
	}
	if (!$this->request->data) {
		$this->request->data = $organisation;
	}
}


/**
*** Gère l'édition d'une organisation
**/

public function edit($id = null)
{
	if(($this->Droits->authorized(11) && $this->Session->read('Organisation.id') == $id) || $this->Droits->isSu()){
		if (!$id) {
			$this->Session->setFlash('Cette organisation n\'existe pas', 'flasherror');
			$this->redirect(array('controller' => 'organisations', 'action' => 'index'));
		} else {
			$organisation = $this->Organisation->findById($id);
			$users = $this->OrganisationUser->find('all', array(
				'conditions' => array(
					'OrganisationUser.organisation_id' => $id
					),
				'contain' => array(
					'User' => array(
						'id',
						'nom',
						'prenom'
						)
					)
				)
			);
			$array_users=array();
			foreach ($users as $key => $value) {
				$array_users[$value['User']['id']] =  $value['User']['prenom']." ".$value['User']['nom'];
			}
			$this->set('users', $array_users);
			if (!$organisation) {
				$this->Session->setFlash('Cette organisation n\'existe pas', 'flasherror');
				$this->redirect(array('controller' => 'organisations', 'action' => 'index'));
			} else {
				if ($this->request->is(array('post', 'put'))) {

					$this->Organisation->id = $id;
					if ($this->Organisation->saveAddEditForm($this->request->data, $id)) {
						$this->Session->setFlash('L\'organisation a été modifiée', 'flashsuccess');
						$this->redirect(array('controller' => 'organisations', 'action' => 'index'));
					} else {
						$this->Session->setFlash('La modification a échoué.', 'flasherror');
						$this->redirect(array('controller' => 'organisations', 'action' => 'index'));
					}
				}
			}
		}
		if (!$this->request->data) {
			$this->request->data = $organisation;
		}
	}
	else
	{
		$this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
		$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
	}
}


/**
** Changement d'organisation
**/

public function change($id = null)
{
	if ($id == null) {
		$idArray=$this->OrganisationUser->find('first', array('conditions' => array('OrganisationUser.user_id' => $this->Auth->user('id'))));
		if(!empty($idArray)){
			$id = $idArray['OrganisationUser']['organisation_id'];
		}
		else{
			if($this->Droits->isSu()){
				$compte = $this->Organisation->find('count');
				if($compte == 0){
					$this->Session->setFlash('Il n\'existe aucune organisation. Vous devez en créer une pour utiliser l\'application', 'flashwarning');
					$this->redirect(array('controller' => 'organisations', 'action' => 'add'));
				}
				else{
					$idOrga = $this->Organisation->find('first');
					$id = $idOrga['Organisation']['id'];
				}
			}
			else{
				$this->Session->setFlash('Vous n\'appartenez à aucune organisation', 'flasherror');
				$this->redirect(array('controller' => 'users', 'action' => 'logout'));
			}
		}
	}
	$change = $this->Organisation->find('first', array('conditions' => array('Organisation.id' => $id)));
	$this->Session->write('Organisation', array('id'=>$change['Organisation']['id'], 'raisonsociale'=>$change['Organisation']['raisonsociale'], 'cil' => $change['Organisation']['cil']));

	$test = $this->Droit->find('all', 
		array(
			'conditions'=>array(
				'OrganisationUser.user_id'=>$this->Auth->user('id'), 'OrganisationUser.organisation_id'=>$this->Session->read('Organisation.id')
				), 
			'contain'=>array(
				'ListeDroit'=>array(
					'value'
					),
				'OrganisationUser'=>array(
					'id'
					)
				)
			)
		);
	$result=array();
	foreach($test as $value){
		array_push($result, $value['ListeDroit']['value']);
	}
	if(empty($result) && !$this->Droits->isSu()){
		$this->Session->setFlash('Vous n\'avez pas de droit sur cette organisation', 'flasherror');
		$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
	}
	else{
		$this->Session->write('Droit.liste', $result);
		$this->redirect(array('controller' => 'pannel', 'action' => 'index'));
	}
}
}