<?php

/**************************************************
 ********** Controller des organisations ***********
 **************************************************/
class OrganisationsController extends AppController
{
    public $uses = array(
        'Organisation',
        'OrganisationUser',
        'Droit',
        'User',
        'Role',
        'RoleDroit'
    );


    /**
     *** Accueil de la page, listing des organisations
     **/

    public function index()
    {
        $this->set('title', 'Les organisations de l\'application');
        if($this->Droits->isSu()) {
            $organisations = $this->Organisation->find('all');
            foreach($organisations as $key => $value) {
                $organisations[$key]['Count'] = $this->OrganisationUser->find('count', array('conditions' => array('organisation_id' => $value['Organisation']['id'])));
            }
            $this->set('organisations', $organisations);
        } elseif($this->Droits->authorized(array(
            '11',
            '12'
        ))
        ) {
            $this->set('organisations', $this->OrganisationUser->find('all', array(
                'conditions' => array(
                    'OrganisationUser.user_id' => $this->Auth->user('id')
                ),
                'contain' => array(
                    'Organisation'
                )
            )));
        } else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
    }


    /**
     *** Gère l'ajout d'organisation
     **/

    public function add()
    {
        $this->set('title', 'Créer une organisation');
        if($this->Droits->isSu()) {
            if($this->request->is('post')) {
                $this->Organisation->create();
                $this->Organisation->saveAddEditForm($this->request->data);
                $this->_insertRoles($this->Organisation->getInsertID());
                $this->Session->setFlash('L\'organisation a été enregistrée', 'flashsuccess');
                $this->redirect(array(
                    'controller' => 'organisations',
                    'action' => 'index'
                ));
            }
        } else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
    }


    /**
     *** Gère la suppression d'une organisation
     **/

    public function delete($id = null)
    {
        if($this->Droits->isSu()) {
            $this->Organisation->delete($id);
            $this->Session->setFlash('L\'organisation a été supprimée', 'flashsuccess');
            $this->redirect(array(
                'controller' => 'organisations',
                'action' => 'index'
            ));
        } else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
    }


    /**
     *** Gère l'affichage des informations d'une organisation
     **/

    public function show($id = null)
    {
        $this->set('title', 'Informations générales - ' . $this->Session->read('Organisation.raisonsociale'));
        if(!$id) {
            $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
            $this->redirect(array(
                'controller' => 'organisations',
                'action' => 'index'
            ));
        } else {
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
            ));
            $array_users = array();
            foreach($users as $key => $value) {
                $array_users[$value['User']['id']] = $value['User']['prenom'] . " " . $value['User']['nom'];
            }
            $this->set('users', $array_users);
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
            ));
            if(!$organisation) {
                $this->Session->setFlash('Cette organisation n\'existe pas', 'flasherror');
                $this->redirect(array(
                    'controller' => 'organisations',
                    'action' => 'index'
                ));
            }
        }
        if(!$this->request->data) {
            $this->request->data = $organisation;
        }
    }


    /**
     *** Gère l'édition d'une organisation
     **/

    public function edit($id = null)
    {
        if($id == $this->Session->read('Organisation.id')) {
            $this->set('title', 'Informations générales - ' . $this->Session->read('Organisation.raisonsociale'));
        } else {
            $this->set('title', 'Editer une organisation');
        }

        if(($this->Droits->authorized(12) && $this->Session->read('Organisation.id') == $id) || $this->Droits->isSu()) {
            if(!$id) {
                $this->Session->setFlash('Cette organisation n\'existe pas', 'flasherror');
                $this->redirect(array(
                    'controller' => 'organisations',
                    'action' => 'index'
                ));
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
                ));
                $array_users = array();
                foreach($users as $key => $value) {
                    $array_users[$value['User']['id']] = $value['User']['prenom'] . " " . $value['User']['nom'];
                }
                $this->set('users', $array_users);
                if(!$organisation) {
                    $this->Session->setFlash('Cette organisation n\'existe pas', 'flasherror');
                    $this->redirect(array(
                        'controller' => 'organisations',
                        'action' => 'index'
                    ));
                } else {
                    if($this->request->is(array(
                        'post',
                        'put'
                    ))
                    ) {
                        $this->Organisation->id = $id;
                        if($this->Organisation->saveAddEditForm($this->request->data, $id)) {
                            $this->Session->setFlash('L\'organisation a été modifiée', 'flashsuccess');
                            $this->redirect(array(
                                'controller' => 'organisations',
                                'action' => 'index'
                            ));
                        } else {
                            $this->Session->setFlash('La modification a échoué.', 'flasherror');
                        }
                    }
                }
            }
            if(!$this->request->data) {
                $this->request->data = $organisation;
            }
        } else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
    }


    /**
     ** Changement d'organisation
     **/

    public function change($id = null)
    {
        if($id == null) {
            $idArray = $this->OrganisationUser->find('first', array('conditions' => array('OrganisationUser.user_id' => $this->Auth->user('id'))));
            if(!empty($idArray)) {
                $id = $idArray['OrganisationUser']['organisation_id'];
            } else {
                if($this->Droits->isSu()) {
                    $compte = $this->Organisation->find('count');
                    if($compte == 0) {
                        $this->Session->setFlash('Il n\'existe aucune organisation. Vous devez en créer une pour utiliser l\'application', 'flashwarning');
                        $this->redirect(array(
                            'controller' => 'organisations',
                            'action' => 'add'
                        ));
                    } else {
                        $idOrga = $this->Organisation->find('first');
                        $id = $idOrga['Organisation']['id'];
                    }
                } else {
                    $this->Session->setFlash('Vous n\'appartenez à aucune organisation', 'flasherror');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'logout'
                    ));
                }
            }
        }
        $change = $this->Organisation->find('first', array('conditions' => array('Organisation.id' => $id)));
        $this->Session->write('Organisation', $change['Organisation']);

        $test = $this->Droit->find('all', array(
            'conditions' => array(
                'OrganisationUser.user_id' => $this->Auth->user('id'),
                'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id')
            ),
            'contain' => array(
                'ListeDroit' => array(
                    'value'
                ),
                'OrganisationUser' => array(
                    'id'
                )
            )
        ));
        $result = array();
        foreach($test as $value) {
            array_push($result, $value['ListeDroit']['value']);
        }
        if(empty($result) && !$this->Droits->isSu()) {
            $this->Session->setFlash('Vous n\'avez pas de droit sur cette organisation', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        } else {
            $this->Session->write('Droit.liste', $result);
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
    }

    protected function _insertRoles($id = null)
    {
        if($id != null) {
            $data = array(
                array(
                    'Role' => array(
                        'libelle' => 'Rédacteur',
                        'organisation_id' => $id
                    ),
                    'Droit' => array(
                        '1',
                        '4',
                        '7'
                    )
                ),
                array(
                    'Role' => array(
                        'libelle' => 'Valideur',
                        'organisation_id' => $id
                    ),
                    'Droit' => array(
                        '2',
                        '4',
                        '7'
                    )
                ),
                array(
                    'Role' => array(
                        'libelle' => 'Consultant',
                        'organisation_id' => $id
                    ),
                    'Droit' => array(
                        '3',
                        '4',
                        '7'
                    )
                ),
                array(
                    'Role' => array(
                        'libelle' => 'Administrateur',
                        'organisation_id' => $id
                    ),
                    'Droit' => array(
                        '4',
                        '7',
                        '8',
                        '9',
                        '10',
                        '12',
                        '13',
                        '14',
                        '15'
                    )
                )
            );
            foreach($data as $key => $value) {
                $this->Role->create($value['Role']);
                $this->Role->save();
                $last = $this->Role->getInsertID();
                foreach($value['Droit'] as $valeur) {
                    $this->RoleDroit->create(array(
                        'RoleDroit' => array(
                            'role_id' => $last,
                            'liste_droit_id' => $valeur
                        )
                    ));
                    $this->RoleDroit->save();
                }
            }
        }
    }


}