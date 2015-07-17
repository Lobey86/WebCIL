<?php

    class AdminsController extends AppController
    {
        public $uses = [
            'Admin',
            'User'
        ];

        public function index()
        {
            $this->set('title', 'Super administrateurs');
            $admins = $this->Admin->find('all', [
                'contain' => [
                    'User'
                ]
            ]);
            $this->set('admins', $admins);
            $users = $this->User->find('all', [
                'fields' => [
                    'id',
                    'prenom',
                    'nom'
                ]
            ]);
            $listeuser = [];
            foreach($users as $value) {
                $listeuser[$value['User']['id']] = $value['User']['prenom'] . ' ' . $value['User']['nom'];
            }
            $this->set('listeusers', $listeuser);
        }


        public function add()
        {
            if($this->request->data['Admin']['user'] != '') {
                $this->Admin->create(['user_id' => $this->request->data['Admin']['user']]);
                $count = $this->Admin->find('count', ['conditions' => ['user_id' => $this->request->data['Admin']['user']]]);
                if(!$count) {
                    if($this->Admin->save()) {
                        $this->Session->setFlash('L\'utilisateur a reçu les privilèges', 'flashsuccess');
                    } else {
                        $this->Session->setFlash('Une erreur s\'est produite lors de l\'enregistrement', 'flasherror');
                    }
                } else {
                    $this->Session->setFlash('Cet utilisateur possède déjà les privilèges administrateur', 'flasherror');

                }
            } else {
                $this->Session->setFlash('Aucun utilisateur n\'a été séléctionné', 'flasherror');
            }
            $this->redirect([
                'controller' => 'admins',
                'action'     => 'index'
            ]);
        }


        public function delete($id)
        {
            if($this->Admin->exists($id)) {
                if($this->Admin->delete($id)) {
                    $this->Session->setFlash('Les privilèges ont été retirés', 'flashsuccess');
                } else {
                    $this->Session->setFlash('Une erreur s\'est produite durant l\'enregistrement', 'flasherror');
                }
            } else {
                $this->Session->setFlash('Cet utilisateur n\'existe pas', 'flasherror');
            }
            $this->redirect([
                'controller' => 'admins',
                'action'     => 'index'
            ]);
        }
    }
