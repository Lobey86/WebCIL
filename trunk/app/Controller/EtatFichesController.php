<?php

/**************************************************
** Controller de l'état de validation des fiches **
**************************************************/




class EtatFichesController extends AppController {

	public $uses=array('EtatFiche', 'Commentaire', 'Fiche', 'Organisation');


/**
*** Envoie ou renvoie une fiche en validation et crée les états
**/

public function sendValidation(){
	$idEncoursValid=$this->EtatFiche->find('first', array('conditions'=>array('EtatFiche.fiche_id'=>$this->request->data['EtatFiche']['ficheNum'], 'EtatFiche.etat_id'=>2), 'fields'=>'id'));
	if(!empty($idEncoursValid)){
		$id = $idEncoursValid['EtatFiche']['id'];
		$this->EtatFiche->id = $id;
		$this->EtatFiche->saveField('etat_id', 3);
	}
	$this->EtatFiche->create(array('EtatFiche'=>array('fiche_id'=>$this->request->data['EtatFiche']['ficheNum'], 'etat_id'=>2, 'previous_user_id'=>$this->Auth->user('id'), 'user_id'=>$this->request->data['EtatFiche']['destinataire'])));
	$this->EtatFiche->save();
	$this->Notifications->add(2, $this->request->data['EtatFiche']['ficheNum'], $this->request->data['EtatFiche']['destinataire']);
	$this->Session->setFlash('La fiche a été envoyée en validation', 'flashsuccess');
	$this->redirect(array('controller'=>'pannel', 'action'=>'index'));

}


/**
*** Envoie une fiche en réorientation
**/

public function reorientation(){
	$this->EtatFiche->delete($this->request->data['EtatFiche']['etatFiche']);
	$this->EtatFiche->create(array('EtatFiche'=>array('fiche_id'=>$this->request->data['EtatFiche']['ficheNum'], 'etat_id'=>2, 'previous_user_id'=>$this->Auth->user('id'), 'user_id'=>$this->request->data['EtatFiche']['destinataire'])));
	$this->EtatFiche->save();
	$this->Notifications->add(2, $this->request->data['EtatFiche']['ficheNum'], $this->request->data['EtatFiche']['destinataire']);
	$this->Session->setFlash('La fiche a été redirigée', 'flashsuccess');
	$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
}



/**
*** Gère le refus de validation et le commentaire associé
**/

public function refuse(){
	$idEncoursValid=$this->EtatFiche->find('first', array('conditions'=>array('EtatFiche.fiche_id'=>$this->request->data['EtatFiche']['ficheNum'], 'EtatFiche.etat_id'=>2), 'fields'=>'id'));
	$id = $idEncoursValid['EtatFiche']['id'];
	$this->EtatFiche->id = $id;
	$this->EtatFiche->saveField('etat_id', 4);
	$idDestinataire = $this->Fiche->find('first', array('conditions' => array('Fiche.id' => $this->request->data['EtatFiche']['ficheNum']), 'fields' => array('id'), 'contain' => array('User' => array('id'))));
	$idDestinataire = $idDestinataire['User']['id'];

	$this->Commentaire->create(array('Commentaire'=>array('etat_fiches_id'=>$id, 'content'=>$this->request->data['EtatFiche']['content'], 'user_id' => $this->Auth->user('id'), 'destinataire_id' => $idDestinataire)));
	$this->Commentaire->save();
	$this->Notifications->add(4, $this->request->data['EtatFiche']['ficheNum'], $idDestinataire);
	$this->Session->setFlash('La fiche a été refusée', 'flashsuccess');
	$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
}


/**
*** Gère l'envoie de la demande d'avis
**/

public function askAvis(){
	$count = $this->EtatFiche->find('count', array('conditions' => array('previous_user_id' => $this->Auth->user('id'), 'user_id'=>$this->request->data['EtatFiche']['destinataire'], 'previous_etat_id'=>$this->request->data['EtatFiche']['etatFiche'], 'fiche_id'=>$this->request->data['EtatFiche']['ficheNum'])));
	if($count>0){
		$this->Session->setFlash('La fiche est déjà en attente d\'avis de la part de cet utilisateur', 'flashwarning');
		$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
	}
	else{
		$this->EtatFiche->create(array('EtatFiche'=>array('etat_id'=>6, 'previous_user_id' => $this->Auth->user('id'), 'user_id'=>$this->request->data['EtatFiche']['destinataire'], 'previous_etat_id'=>$this->request->data['EtatFiche']['etatFiche'], 'fiche_id'=>$this->request->data['EtatFiche']['ficheNum'])));
		$this->EtatFiche->save();
		$this->Notifications->add(1, $this->request->data['EtatFiche']['ficheNum'], $this->request->data['EtatFiche']['destinataire']);

		$this->Session->setFlash('La fiche a été envoyée pour avis', 'flashsuccess');
		$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
	}
}


/**
*** Gère la réponse à une demande d'avis
**/

public function answerAvis(){
	$idEncoursAnswer=$this->EtatFiche->find('first', array('conditions'=>array('EtatFiche.id'=>$this->request->data['EtatFiche']['etatFiche']), 'fields'=>'previous_etat_id'));
	$id = $idEncoursAnswer['EtatFiche']['previous_etat_id'];
	$this->EtatFiche->delete($this->request->data['EtatFiche']['etatFiche']);

	$this->Commentaire->create(array('Commentaire'=>array('etat_fiches_id'=>$id, 'content'=>$this->request->data['EtatFiche']['commentaireRepondre'], 'user_id' => $this->Auth->user('id'), 'destinataire_id' => $this->request->data['EtatFiche']['previousUserId'])));
	$this->Commentaire->save();
	$this->Notifications->add(5, $this->request->data['EtatFiche']['ficheNum'], $this->request->data['EtatFiche']['previousUserId']);

	$this->Session->setFlash('Le commentaire a été ajouté', 'flashsuccess');
	$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
}


/**
*** Gère la remise en rédaction d'une fiche refusée
**/

public function relaunch($id){
	if(!$id){
		$this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
		$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
	}
	else{
		if($this->EtatFiche->deleteAll(array('fiche_id'=>$id))){
			$this->EtatFiche->create(array('EtatFiche'=>array('fiche_id'=>$id, 'etat_id'=>1, 'previous_user_id'=>$this->Auth->user('id'), 'user_id'=>$this->Auth->user('id'))));
			if($this->EtatFiche->save()){
				$this->Session->setFlash('La fiche a bien été replacée en rédaction', 'flashsuccess');
				$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
			}
		}
	}
}


/**
*** Gère l'envoie en validation au CIL
**/
public function cilValid($id){
	$cil=$this->Organisation->find('first', array(
		'conditions' => array(
			'Organisation.id' => $this->Session->read('Organisation.id')
			),
		'fields'  => array(
			'cil'
			)
		)
	);
	if($cil['Organisation']['cil'] != null){


		$idEncoursValid=$this->EtatFiche->find('first', array('conditions'=>array('EtatFiche.fiche_id'=>$id, 'EtatFiche.etat_id'=>2), 'fields'=>'id'));
		if(!empty($idEncoursValid)){
			$etatId = $idEncoursValid['EtatFiche']['id'];
			$this->EtatFiche->id = $etatId;
			$this->EtatFiche->saveField('etat_id', 3);
		}
		$this->EtatFiche->create(array('EtatFiche'=>array('fiche_id'=>$id, 'etat_id'=>2, 'previous_user_id'=>$this->Auth->user('id'), 'user_id'=>$cil['Organisation']['cil'])));
		$this->EtatFiche->save();

		$this->Notifications->add(2, $id, $cil['Organisation']['cil']);
		$this->Session->setFlash('La fiche a été envoyée au CIL', 'flashsuccess');
		$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
	}
	else{
		$this->Session->setFlash('Aucun CIL n\'a été défini pour cette organisation', 'flasherror');
		$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
	}
}


/**
*** Gère la validation du CIL
**/
public function insertRegistre($id){
	$idEncoursValid=$this->EtatFiche->find('first', array(
		'conditions'=>array(
			'EtatFiche.fiche_id'=>$id, 
			'EtatFiche.etat_id'=>2
			), 
		'fields'=>array(
			'id'
			),
		'contain' => array(
			'Fiche' => array(
				'user_id'
				)
			)
		)
	);
	if(!empty($idEncoursValid)){
		$id_etat = $idEncoursValid['EtatFiche']['id'];
		$this->EtatFiche->id = $id_etat;
		$this->EtatFiche->saveField('etat_id', 5);
		$this->Notifications->add(3, $id, $idEncoursValid['Fiche']['user_id']);
		$this->Session->setFlash('La fiche a été enregistrée dans le registre', 'flashsuccess');
		$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
	}
	else{
		$this->Session->setFlash('Cette fiche n\'est pas en cours de validation', 'flasherror');
		$this->redirect(array('controller'=>'pannel', 'action'=>'index'));
	}
	
}


/**
*** Gère l'archivage des fiches
**/
public function archive($id){

	if(!$id){
		$this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
		$this->redirect(array('controller'=>'registres', 'action'=>'index'));
	}
	else{
		$this->EtatFiche->deleteAll(array('EtatFiche.fiche_id' => $id), false);
		$this->EtatFiche->create(array('EtatFiche'=>array('fiche_id'=>$id, 'etat_id'=>7, 'previous_user_id'=>$this->Auth->user('id'), 'user_id'=>$this->Auth->user('id'))));
		$this->EtatFiche->save();

// TODO: Génération PDF définitive

		$this->Session->setFlash('La fiche a été archivée', 'flashsuccess');
		$this->redirect(array('controller'=>'registres', 'action'=>'index'));
	}
}
}