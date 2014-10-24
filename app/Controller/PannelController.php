<?php
class PannelController extends AppController {
public $uses=array('Pannel', 'Fiche', 'User');


    public function index(){
        $this->set('encours', $this->Fiche->find('all', array('conditions'=>array('Fiche.created_user_id'=>$this->Auth->user('id')), 'joins'=>array(array('table'=>'users', 'alias'=>'User', 'type'=>'inner', 'conditions'=>array('Fiche.created_user_id = User.id')), array('table'=>'users', 'alias'=>'UserM', 'type'=>'inner', 'conditions'=>array('Fiche.modified_user_id = UserM.id'))), 'fields'=>array('Fiche.id', 'Fiche.nomoutil', 'Fiche.created', 'Fiche.modified', 'User.id', 'User.nom', 'User.prenom', 'UserM.id', 'UserM.nom', 'UserM.prenom'))));
        $this->set('countencours', $this->Fiche->find('count', array('conditions'=>array('Fiche.created_user_id'=>$this->Auth->user('id')))));
        $this->set('users', $this->User->find('all', array('fields'=>array('User.id', 'User.nom', 'User.prenom'), 'order'=>'User.nom')));
    }



    public function add() {

    }



    public function test(){
        $this->Session->setFlash('La fiche a correctement été envoyée', 'flashsuccess');
        $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
    }



    public function relancer(){
        $this->Session->setFlash('La fiche a été replacée en cours de rédaction', 'flashsuccess');
        $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
    }


}
