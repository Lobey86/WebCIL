<?php
// app/Controller/UsersController.php
App::uses('CakeEmail', 'Network/Email');
class UsersController extends AppController {

    public $uses = array('User','Organisation','Role');


    public function beforeFilter() {
        parent::beforeFilter();
    }



    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }




    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException('User invalide');
        }
        $this->set('user', $this->User->read(null, $id));
    }




    public function add() {
        $this->set('idUser', $this->Auth->user('id'));
        if ($this->request->is('post')) {

            $this->User->create($this->request->data);
            if($this->request->data['User']['password'] == $this->request->data['User']['passwd']){
                if ($this->User->save()) {
                    $this->Session->setFlash('L\'user a été sauvegardé', 'flashsuccess');
                    $this->redirect(array('controller'=>'users', 'action'=>'index'));

                } else {
                    $this->Session->setFlash('L\'user n\'a pas été sauvegardé. Merci de réessayer.', 'flasherror');
                }
            }
            else{
                $this->Session->setFlash('Les deux mots de passe ne correspondent pas.', 'flasherror');
            }
        }
        else{
            $listeOrganisations = $this->Organisation->find('all', array('fields' => array('id', 'nom')));
            $listingOrganisations = array();
            foreach ($listeOrganisations as $donnees){
                $listingOrganisations[$donnees['Organisation']['id']] = $donnees['Organisation']['nom'];
            }
            $this->set('listeOrganisations', $listingOrganisations);
            $listeRoles = $this->Role->find('all');
            $listingRoles = array();
            foreach ($listeRoles as $donnees){
              $listingRoles[$donnees['Role']['id']] = $donnees['Role']['libelle'];
            }
            $this->set('listeroles', $listingRoles);
        }
    }




    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException('User Invalide');
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash('L\'user a été sauvegardé', "flashsuccess");
                return $this->redirect(array('controller'=> 'pannel', 'action' => 'index'));
            } else {
                $this->Session->setFlash('L\'user n\'a pas été sauvegardé. Merci de réessayer.', "flasherror");
            }
        } else {
            $listeOrganisations = $this->Organisation->find('all', array('fields' => array('id', 'nom')));


            foreach ($listeOrganisations as $donnees){
                $listingOrganisations[$donnees['Organisation']['id']] = $donnees['Organisation']['nom'];
            }
            $this->set('listeOrganisations', $listingOrganisations);

            $listeRoles = $this->Role->find('all');
            $listingRoles = array();
            foreach ($listeRoles as $donnees){
                $listingRoles[$donnees['Role']['id']] = $donnees['Role']['libelle'];
            }
            $this->set('listeroles', $listingRoles);
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }




    public function delete($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException('User invalide');
        }
        if ($this->User->delete()) {
            $this->Session->setFlash('User supprimé', 'flashsuccess');
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('L\'user n\'a pas été supprimé', 'flasherror');
        return $this->redirect(array('action' => 'index'));
    }




    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash('Nom d\'user ou mot de passe invalide, réessayer', 'flasherror');
            }
        }
    }




    public function logout() {
        return $this->redirect($this->Auth->logout());
    }
}