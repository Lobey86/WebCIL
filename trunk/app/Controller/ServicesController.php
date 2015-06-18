<?php

class ServicesController extends AppController
{
    public $uses = array(
        'Service',
        'OrganisationUserService'
    );

    public function index()
    {
        $this->set('title', 'Les services de ' . $this->Session->read('Organisation.raisonsociale'));
        $serv = $this->Service->find('all', array('conditions' => array('organisation_id' => $this->Session->read('Organisation.id'))));
        foreach ( $serv as $key => $value ) {
            $count = $this->OrganisationUserService->find('count', array('conditions' => array('service_id' => $value[ 'Service' ][ 'id' ])));
            $serv[ $key ][ 'count' ] = $count;
        }
        $this->set('serv', $serv);
    }

    public function add()
    {
        $this->set('title', 'Ajouter un service');
        if ( $this->request->is('post') ) {
            $this->Service->create($this->request->data);
            if ( $this->Service->save() ) {
                $this->Session->setFlash('Le service a bien été enregistré', 'flashsuccess');
                $this->redirect(array(
                    'controller' => 'services',
                    'action' => 'index'
                ));
            }
            else {
                $this->Session->setFlash('Une erreur s\'est produite durant l\'enregistrement', 'flasherror');
                $this->redirect(array(
                    'controller' => 'services',
                    'action' => 'index'
                ));
            }
        }
    }

    public function edit($id = null)
    {
        $this->set('title', 'Modification d\'un service');
        $this->Service->id = $id;
        $servi = $this->Service->findById($id);
        if ( $this->Service->exists() ) {
            if ( $this->request->is('post') || $this->request->is('put') ) {
                if ( $this->Service->save($this->request->data) ) {
                    $this->Session->setFlash('Le service a été sauvegardé', "flashsuccess");
                    $this->redirect(array(
                        'controller' => 'services',
                        'action' => 'index'
                    ));
                }
                else {
                    $this->Session->setFlash('Une erreur s\'est produite durant l\'enregistrement', 'flasherror');
                }
            }
            if ( !$this->request->data ) {
                $this->request->data = $servi;
            }
        }
        else {
            $this->Session->setFlash('Ce service n\'existe pas', "flasherror");
            $this->redirect(array(
                'controller' => 'services',
                'action' => 'index'
            ));
        }
    }

    public function delete($id = null)
    {
        $this->set('title', 'Supprimer un service');
        $this->Service->id = $id;
        if ( $this->Service->exists() ) {
            $this->Service->delete($id, false);
            $this->Session->setFlash('Le service a bien été supprimé', 'flashsuccess');
            $this->redirect(array(
                'controller' => 'services',
                'action' => 'index'
            ));
        }
        else {
            $this->Session->setFlash('Une erreur s\'est produite lors de la suppression', 'flasherror');
            $this->redirect(array(
                'controller' => 'services',
                'action' => 'index'
            ));
        }
    }
}
