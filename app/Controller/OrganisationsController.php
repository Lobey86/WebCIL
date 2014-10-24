<?php
class OrganisationsController extends AppController {




    public function index() {
        $this->set('organisations', $this->paginate());
    }


    public function add() {

    }

    public function test(){

    }

    public function show(){

    }

    public function edit(){

    }

    public function change($id = null){
        $change = $this->Organisation->find('first', array('conditions' => array('Organisation.id'=>$id)));
        debug($change);
        $this->Session->write('orgaid', $change['Organisation']['id']);
        $this->Session->write('organom', $change['Organisation']['nom']);
        $this->redirect(array('controller'=>'pannel', 'action'=>'index'));
    }

}