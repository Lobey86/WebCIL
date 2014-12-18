<?php

    class OrganisationsController extends AppController
    {
        public $uses = array('Organisation', 'OrganisationUser');

        public function index()
        {
            $this->set('organisations', $this->paginate());
        }


        public function add()
        {
            if ($this->request->is('post')) {
                debug($this->request->data);
                $this->Organisation->create();
                if ($this->Organisation->saveAddEditForm($this->request->data)) {
                    $this->Session->setFlash('L\'organisation a été enregistrée', 'flashsuccess');
                    $this->redirect(array('controller' => 'organisations', 'action' => 'index'));
                } else {
                    $this->Session->setFlash('Raté', 'flasherror');
                }
            }
        }

        public function delete($id = null)
        {
            $this->Organisation->delete($id);
            $this->Session->setFlash('L\'organisation a été supprimée', 'flashsuccess');
            $this->redirect(array('controller' => 'organisations', 'action' => 'index'));
        }
       public function show($id = null)
        {
            if (!$id) {
                $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
                $this->redirect(array('controller' => 'organisations', 'action' => 'index'));
            } else {
                $organisation = $this->Organisation->findById($id);
                if (!$organisation) {
                    $this->Session->setFlash('Cette organisation n\'existe pas', 'flasherror');
                    $this->redirect(array('controller' => 'organisations', 'action' => 'index'));
                }
            }
            if (!$this->request->data) {
                $this->request->data = $organisation;
            }
        }

        public function edit($id = null)
        {
            if (!$id) {
                $this->Session->setFlash('Cette organisation n\'existe pas', 'flasherror');
                $this->redirect(array('controller' => 'organisations', 'action' => 'index'));
            } else {
                $organisation = $this->Organisation->findById($id);

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

        public function change($id = null)
        {
            $change = $this->Organisation->find('first', array('conditions' => array('Organisation.id' => $id)));
            $this->Session->write('orgaid', $change['Organisation']['id']);
            $this->Session->write('organom', $change['Organisation']['raisonsociale']);
            $this->redirect(array('controller' => 'pannel', 'action' => 'index'));
        }

    }