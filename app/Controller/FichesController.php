<?php
class FichesController extends AppController {

    public $helpers = array('Html', 'Form', 'Session');
    public $uses=array('Fiche', 'Organisation');



    public function index(){
    $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
    }




    public function add(){
        if ($this->request->is('post')) {
            $this->Fiche->create($this->request->data);
            if ($this->Fiche->save()) {
                $this->Session->setFlash('La fiche a été enregistrée', 'flashsuccess');
                $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
            }
            else{
                $this->Session->setFlash('La fiche n\'a pas été enregistrée', 'flasherror');
                $this->redirect($this->referer());
            }
        }
        else{
            $this->set('organisation', $this->Organisation->findById($this->Session->read('orgaid')));
        }
    }




    public function delete($id = null){
        $this->Fiche->delete($id);
        $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
    }




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
                if ($this->request->is(array('post', 'put'))) {
                    $this->Fiche->id = $id;
                    if ($this->Fiche->save($this->request->data)) {
                        $this->Session->setFlash('La fiche a été modifiée', 'flashsuccess');
                        $this->redirect(array('controller'=>'pannel', 'action' => 'index'));
                    }
                    $this->Session->setFlash('La modification a échoué.', 'flasherror');
                    $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
                }
            }
        }
        if (!$this->request->data) {
            $this->request->data = $fiche;
        }
    }



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
        }
        if (!$this->request->data) {
            $this->request->data = $fiche;
        }
    }
}
