<?php

class FormulairesController extends AppController
{

    public $uses = array(
        'FormGenerator.Formulaire',
        'FormGenerator.Champ',
        'FormGeneric',
        'Fiche',
        'Organisation'
    );

    public function index()
    {
        $this->set('title', 'Liste des formulaires de ' . $this->Session->read('Organisation.raisonsociale'));
        $all = $this->FormGen->getAll(array('organisations_id' => $this->Session->read('Organisation.id')));
        $valid = array();
        foreach($all as $key => $value) {
            $verif = $this->Fiche->find('count', array('conditions' => array('form_id' => $value['Formulaire']['id'])));
            if($verif == 0) {
                $valid[$value['Formulaire']['id']] = true;
            } else {
                $valid[$value['Formulaire']['id']] = false;
            }
        }
        $this->set(compact('valid'));
        $this->set('formulaires', $all);
    }

    public function delete($id)
    {
        if($id != null) {
            if($this->FormGen->del($id)) {
                $this->Session->setFlash('Le formulaire a été supprimé', 'flashsuccess');
            } else {
                $this->Session->setFlash('Une erreur s\'est produite lors de la suppression du formulaire', 'flasherror');
            }

        } else {
            $this->Session->setFlash('Ce formulaire n\'existe pas', 'flasherror');
        }
        $this->redirect($this->referer());
    }

    public function addFirst()
    {
        if($this->request->is('POST')) {
            $this->Formulaire->create(array(
                'organisations_id' => $this->Session->read('Organisation.id'),
                'libelle' => $this->request->data['Formulaire']['libelle'],
                'description' => $this->request->data['Formulaire']['description'],
                'active' => 0
            ));
            $this->Formulaire->save();
            $this->redirect(array(
                'controller' => 'formulaires',
                'action' => 'add',
                $this->Formulaire->getInsertId()
            ));
        }
    } 

    public function add($id = null)
    {
        $organisation =  $this->Organisation->find('first', array(
            'conditions' => array('Organisation.id' => $this->Session->read('Organisation.id'))
        ));

        $organisation['Organisation']['service'] = ($this->Session->read('User.service') == null)? '' : $this->Session->read('User.service');
        
        $this->set('title', 'Créer un formulaire');
        $this->set(compact(['id', 'organisation']));
        if($this->request->is('POST')) {
            if($id == null) {
                $id = $this->request->data['Formulaire']['id'];
            }
            $array = json_decode($this->request->data['Formulaire']['json'], true);
            foreach($array as $key => $value) {
                $sortie = array();
                foreach($value as $clef => $valeur) {
                    switch($clef) {
                        case 'type':
                            $type = $valeur;
                            break;
                        case 'ligne':
                            $ligne = $valeur;
                            break;
                        case 'colonne':
                            $colonne = $valeur;
                            break;
                        default:
                            $sortie[$clef] = $valeur;
                            break;
                    }
                }
                $this->Champ->create(array(
                    'formulaires_id' => $id,
                    'type' => $type,
                    'ligne' => $ligne,
                    'colonne' => $colonne,
                    'details' => json_encode($sortie)
                ));
                $this->Champ->save();
            }
            $this->Session->setFlash('Le formulaire a été enregistré', 'flashsuccess');
            $this->redirect(array(
                'controller' => 'formulaires',
                'action' => 'index'
            ));
        }
    }

    public function toggle($id, $state)
    {
        $this->Formulaire->id = $id;
        if($this->Formulaire->updateAll(array('active' => (int)!$state), array('id' => $id))) {
            $this->redirect(array(
                'controller' => 'formulaires',
                'action' => 'index'
            ));
        }
    }

    public function edit($id = null)
    {
        $organisation =  $this->Organisation->find('first', array(
            'conditions' => array('Organisation.id' => $this->Session->read('Organisation.id'))
        ));

        $organisation['Organisation']['service'] = ($this->Session->read('User.service') == null)? '' : $this->Session->read('User.service');
        
        $this->set('title', 'Editer un formulaire');
        $champs = $this->Champ->find('all', array('conditions' => array('formulaires_id' => $id)));
        $this->set(compact(['id', 'organisation', 'champs']));
        $this->Formulaire->updateAll(array('active' => 0), array('id' => $id));
        if($this->request->is(array(
            'POST',
            'PUT'
        ))
        ) {
            if($id == null) {
                $id = $this->request->data['Formulaire']['id'];
            }
            if($this->Champ->deleteAll(array('formulaires_id' => $id))) {
                $array = json_decode($this->request->data['Formulaire']['json'], true);
                foreach($array as $key => $value) {
                    $sortie = array();
                    foreach($value as $clef => $valeur) {
                        switch($clef) {
                            case 'type':
                                $type = $valeur;
                                break;
                            case 'ligne':
                                $ligne = $valeur;
                                break;
                            case 'colonne':
                                $colonne = $valeur;
                                break;
                            default:
                                $sortie[$clef] = $valeur;
                                break;
                        }
                    }
                    $this->Champ->create(array(
                        'formulaires_id' => $id,
                        'type' => $type,
                        'ligne' => $ligne,
                        'colonne' => $colonne,
                        'details' => json_encode($sortie)
                    ));
                    $this->Champ->save();
                }
                $this->Session->setFlash('Le formulaire a été enregistré', 'flashsuccess');
                $this->redirect(array(
                    'controller' => 'formulaires',
                    'action' => 'index'
                ));
            }
        }
    }
}
