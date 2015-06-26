<?php

class RegistresController extends AppController
{
    public $uses = array(
        'EtatFiche',
        'Fiche',
        'OrganisationUser',
        'Modification'
    );


    public function index()
    {
        $this->set('title', 'Registre ' . $this->Session->read('Organisation.raisonsociale'));
        $condition = array(
            'EtatFiche.etat_id' => array(
                5,
                7
            ),
            'Fiche.organisation_id' => $this->Session->read('Organisation.id')
        );
        $search = false;
        if(!empty($this->request->data['Registre']['user'])) {
            $condition['Fiche.user_id'] = $this->request->data['Registre']['user'];
            $search = true;
        }
        if(!empty($this->request->data['Registre']['outil'])) {
            $condition['Fiche.outilnom'] = $this->request->data['Registre']['outil'];
            $search = true;
        }
        if(isset($this->request->data['Registre']['archive']) && $this->request->data['Registre']['archive'] == 1) {
            $condition['EtatFiche.etat_id'] = 7;
            $search = true;
        }
        if(isset($this->request->data['Registre']['nonArchive']) && $this->request->data['Registre']['nonArchive'] == 1) {
            $condition['EtatFiche.etat_id'] = 5;
            $search = true;
        }

        if($this->Droits->authorized(array(
            '4',
            '5',
            '6'
        ))
        ) {
            $fichesValid = $this->EtatFiche->find('all', array(
                'conditions' => $condition,
                'contain' => array(
                    'Fiche' => array(
                        'id',
                        'created',
                        'numero',
                        'User' => array(
                            'nom',
                            'prenom'
                        ),
                        'Valeur' => array(
                            'conditions' => array(
                                'champ_name' => array(
                                    'outilnom',
                                    'finaliteprincipale'
                                )
                            ),
                            'fields' => array(
                                'champ_name',
                                'valeur'
                            )
                        )
                    )
                )
            ));
            foreach($fichesValid as $key => $value) {
                if($this->Droits->isReadable($value['Fiche']['id'])) {
                    $fichesValid[$key]['Readable'] = true;
                } else {
                    $fichesValid[$key]['Readable'] = false;
                }
            }
            $this->set('search', $search);
            $this->set('fichesValid', $fichesValid);


            // Listing des utilisateurs de l'organisation
            $liste = $this->OrganisationUser->find('all', array(
                'conditions' => array(
                    'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id')
                ),
                'contain' => array(
                    'User' => array(
                        'id',
                        'nom',
                        'prenom'
                    )
                )
            ));
            $listeUsers = array();
            foreach($liste as $key => $value) {
                $listeUsers[$value['User']['id']] = $value['User']['prenom'] . ' ' . $value['User']['nom'];
            }
            $this->set('listeUsers', $listeUsers);
        } else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
    }

    public function edit()
    {
        debug($this->request->data);
        $this->Modification->create(array(
            'fiches_id' => $this->request->data['Registre']['idEditRegistre'],
            'modif' => $this->request->data['Registre']['motif']
        ));
        $this->Modification->save();
        $this->redirect(array(
            'controller' => 'fiches',
            'action' => 'edit',
            $this->request->data['Registre']['idEditRegistre']
        ));
    }

    public function add()
    {
        if(isset($this->Request->data['Registre']['numero']) && !empty($this->Request->data['Registre']['numero'])) {
            $this->Fiche->updateAll(array('numero' => $this->request->data['Registre']['numero']), array('id' => $this->request->data['Registre']['idfiche']));
        }
        $this->redirect(array(
            'controller' => 'etat_fiches',
            'action' => 'insertRegistre',
            $this->request->data['Registre']['idfiche']
        ));
    }
}