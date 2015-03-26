<?php


/**************************************************
************** Controller des fiches **************
**************************************************/


class FichesController extends AppController {

    public $helpers = array('Html', 'Form', 'Session');
    public $uses=array('Fiche', 'Organisation', 'File', 'EtatFiche');


/**
*** La page d'accueil des fiches est celle du pannel général
**/

    public function index(){
        $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
    }


/**
*** Gère l'ajout de fiches
**/

    public function add(){
        if($this->Droits->authorized(1)){
            if ($this->request->is('post')) {
                $this->Fiche->create($this->request->data);
                if ($this->Fiche->save()) {
                    $this->EtatFiche->create(array('EtatFiche'=>array('fiche_id'=>$this->Fiche->getInsertID(), 'etat_id'=>1, 'previous_user_id'=>$this->Auth->user('id'), 'user_id'=>$this->Auth->user('id'))));
                    if($this->EtatFiche->save()){
                        if($this->File->saveFile($this->request->data, $this->Fiche->getInsertID())){
                            $this->Session->setFlash('La fiche a été enregistrée', 'flashsuccess');
                            $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
                        }
                    }
                }
                $this->Session->setFlash('La fiche n\'a pas été enregistrée', 'flasherror');
                $this->redirect($this->referer());
            }
            else{
                $this->set('organisation', $this->Organisation->findById($this->Session->read('Organisation.id')));
            }
        }
        else
        {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
        }
    }


/**
*** Gère la suppression de fiches
**/

    public function delete($id = null){
        if ($this->Droits->authorized(1)){
            $fiche = $this->Fiche->findById($id);
            if(!($this->Fiche->isOwner($this->Auth->user('id'), $fiche) || $this->Auth->user('id')!=1 || $this->Droits->authorized(5))){
                $this->Session->setFlash('Vous n\'avez pas accès à cette fiche', 'flasherror');
                $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
            } 
            $this->Fiche->delete($id);
            $this->Session->setFlash('La fiche a bien été supprimée', 'flashsuccess');
            $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
        }else{
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
        }
    }


/**
*** Gère l'édition de fiches
**/

    public function edit($id = null) {
        if (!$id) {
            $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
            $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
        }
        else{

            $fiche = $this->Fiche->findById($id);
            if (!$fiche) {
                $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
                $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
            }
            else{
                if(!($this->Fiche->isOwner($this->Auth->user('id'), $fiche) || $this->Auth->user('id')!=1 || $this->Droits->authorized(5))){
                    $this->Session->setFlash('Vous n\'avez pas accès à cette fiche', 'flasherror');
                    $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
                } 
                if ($this->request->is(array('post', 'put'))) {
                    $this->Fiche->id = $id;
                    if ($this->Fiche->save($this->request->data)) {
                        if(array_key_exists('FileDelete', $this->request->data)){
                            foreach ($this->request->data['FileDelete'] as $key => $value) {
                                $fichier = $this->File->find('first', array('conditions'=>array('File.id'=>$value), 'recursive'=>-1));
                                unlink(WWW_ROOT.'files/'.$fichier['File']['url']);
                                $this->File->delete($fichier['File']['id']);
                            }
                        }
                        $this->Session->setFlash('La fiche a été modifiée', 'flashsuccess');
                        $this->redirect(array('controller'=>'pannel', 'action' => 'index'));
                    }
                    $this->Session->setFlash('La modification a échoué.', 'flasherror');
                    $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
                }
            }
        }
        if (!$this->request->data) {
            $this->set('organisation', $this->Organisation->findById($this->Session->read('Organisation.id')));
            $this->request->data = $fiche;
        }
    }


/**
*** Gère l'affichage des fiches
**/

    public function show($id = null) {
        if (!$id) {
            $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
            $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
        }
        else{
            $fiche = $this->Fiche->findById($id);
            if (!$fiche) {
                $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
                $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
            }
            else{
                if(!($this->Fiche->isOwner($this->Auth->user('id'), $fiche) || $this->Auth->user('id')!=1 || $this->Droits->authorized(5))){
                    $this->Session->setFlash('Vous n\'avez pas accès à cette fiche', 'flasherror');
                    $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
                } 
            }
        }
        if (!$this->request->data) {
            $this->request->data = $fiche;
        }
    }


/**
*** Gère le téléchargement des pieces jointes d'une fiche
**/

    public function download($url = null) {
        $this->response->file(WWW_ROOT.'files/'. $url, array('download' => true, 'name' => 'file'));
    }
}