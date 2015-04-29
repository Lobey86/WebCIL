<?php

class RolesController extends AppController
{
    public $uses = array(
        'Role',
        'ListeDroit',
        'RoleDroit'
    );


    public function index()
    {


        if ( $this->Droits->authorized(array(
            '13',
            '14',
            '15'
        ))
        ) {
            $this->set('roles', $this->paginate(array('organisation_id' => $this->Session->read('Organisation.id'))));
        }
        else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
    }

    public function add()
    {
        if ( $this->Droits->authorized(13) || $this->Droits->isSu() ) {
            if ( $this->request->is('post') ) {
                $this->Role->create($this->request->data);
                if ( $this->Role->save() ) {
                    foreach ( $this->request->data[ 'Droits' ] as $key => $donnee ) {
                        if ( $donnee ) {
                            $this->RoleDroit->create(array(
                                'role_id' => $this->Role->getInsertID(),
                                'liste_droit_id' => $key
                            ));
                            $this->RoleDroit->save();
                        }
                    }
                    $this->Session->setFlash("Le rôle a bien été enregistré", 'flashsuccess');
                    $this->redirect(array(
                        'controller' => 'roles',
                        'action' => 'index'
                    ));
                }
                else {
                    $this->Session->setFlash("Une erreur s'est produite lors de l'enregistrement", 'flasherror');
                    $this->redirect(array(
                        'controller' => 'roles',
                        'action' => 'index'
                    ));
                }
            }
            else {
                $this->set('listedroit', $this->ListeDroit->find('all', array('conditions' => array('NOT' => array('ListeDroit.id' => array('11'))))));
            }
        }
        else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
    }


    public function show($id)
    {
        if ( ($this->Droits->authorized(13) && $this->Droits->currentOrgaRole($id)) || $this->Droits->isSu() ) {
            if ( !$id ) {
                throw new NotFoundException('Ce rôle n\'existe pas');
            }
            $role = $this->Role->findById($id);
            if ( !$role ) {
                throw new NotFoundException('Ce rôle n\'existe pas');
            }

            $this->set('listedroit', $this->ListeDroit->find('all', array('conditions' => array('NOT' => array('ListeDroit.id' => array('11'))))));
            $resultat = $this->RoleDroit->find('all', array(
                'conditions' => array('role_id' => $id),
                'fields' => 'liste_droit_id'
            ));
            $result = array();
            foreach ( $resultat as $donnee ) {
                array_push($result, $donnee[ 'RoleDroit' ][ 'liste_droit_id' ]);
            }
            $this->set('tableDroits', $result);
        }
        if ( !$this->request->data ) {
            $this->request->data = $role;
        }

        else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));

        }
    }


    public function edit($id = null)
    {
        if ( ($this->Droits->authorized(14) && $this->Droits->currentOrgaRole($id)) || $this->Droits->isSu() ) {
            if ( !$id ) {
                throw new NotFoundException('Ce rôle n\'existe pas');
            }
            $role = $this->Role->findById($id);
            if ( !$role ) {
                throw new NotFoundException('Ce rôle n\'existe pas');
            }
            if ( $this->request->is(array(
                'post',
                'put'
            ))
            ) {
                $this->Role->id = $id;
                if ( $this->Role->save($this->request->data) ) {
                    $this->RoleDroit->deleteAll(array('role_id' => $id), false);
                    foreach ( $this->request->data[ 'Droits' ] as $key => $donnee ) {
                        if ( $donnee ) {
                            $this->RoleDroit->create(array(
                                'role_id' => $id,
                                'liste_droit_id' => $key
                            ));
                            $this->RoleDroit->save();
                        }
                    }
                    $this->Session->setFlash('Le rôle a bien été mis à jour', 'flashsuccess');
                    $this->redirect(array(
                        'controller' => 'Roles',
                        'action' => 'index'
                    ));
                }
                $this->Session->setFlash("Une erreur s'est produite lors de la mise à jour", 'flasherror');
                $this->redirect(array(
                    'controller' => 'Roles',
                    'action' => 'index'
                ));
            }
            else {
                $this->set('listedroit', $this->ListeDroit->find('all', array('conditions' => array('NOT' => array('ListeDroit.id' => array('11'))))));
                $resultat = $this->RoleDroit->find('all', array(
                    'conditions' => array('role_id' => $id),
                    'fields' => 'liste_droit_id'
                ));
                $result = array();
                foreach ( $resultat as $donnee ) {
                    array_push($result, $donnee[ 'RoleDroit' ][ 'liste_droit_id' ]);
                }
                $this->set('tableDroits', $result);
            }
            if ( !$this->request->data ) {
                $this->request->data = $role;
            }
        }
        else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));

        }
    }


    /**
     * Suppression d'un rôle
     *
     * @param  [integer] $id [id du rôle à supprimer]
     */

    public function delete($id = null)
    {
        if ( ($this->Droits->authorized(15) && $this->Droits->currentOrgaRole($id)) || $this->Droits->isSu() ) {
            $this->Role->id = $id;
            if ( !$this->Role->exists() ) {
                throw new NotFoundException('Ce rôle n\'existe pas');
            }
            if ( $this->Role->delete() ) {
                $this->Session->setFlash('Role supprimé', 'flashsuccess');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash('Le rôle n\'a pas été supprimé', 'flasherror');
            return $this->redirect(array('action' => 'index'));
        }
        else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
    }
}
