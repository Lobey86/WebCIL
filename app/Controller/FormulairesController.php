<?php

/**
 * FormulairesController
 *
 * WebCIL : Outil de gestion du Correspondant Informatique et Libertés.
 * Cet outil consiste à accompagner le CIL dans sa gestion des déclarations via 
 * le registre. Le registre est sous la responsabilité du CIL qui doit en 
 * assurer la communication à toute personne qui en fait la demande (art. 48 du décret octobre 2005).
 * 
 * Copyright (c) Adullact (http://www.adullact.org)
 *
 * Licensed under The CeCiLL V2 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright   Copyright (c) Adullact (http://www.adullact.org)
 * @link        https://adullact.net/projects/webcil/
 * @since       webcil v0.9.0
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 * @version     v0.9.0
 * @package     App.Controller
 */
class FormulairesController extends AppController {

    public $uses = array(
        'FormGenerator.Formulaire',
        'FormGenerator.Champ',
        'FormGeneric',
        'Fiche',
        'Organisation'
    );

    /**
     * @access public
     * @created 18/06/2015
     * @version V0.9.0
     */
    public function index() {
        $this->set('title', __d('formulaire','formulaire.titreListeFormulaire') . $this->Session->read('Organisation.raisonsociale'));
        $all = $this->FormGen->getAll(array('organisations_id' => $this->Session->read('Organisation.id')));
        $valid = array();
        foreach ($all as $key => $value) {
            $verif = $this->Fiche->find('count', array('conditions' => array('form_id' => $value['Formulaire']['id'])));
            if ($verif == 0) {
                $valid[$value['Formulaire']['id']] = true;
            } else {
                $valid[$value['Formulaire']['id']] = false;
            }
        }
        $this->set(compact('valid'));
        $this->set('formulaires', $all);
    }

    /**
     * @param int $id
     * 
     * @access public
     * @created 18/06/2015
     * @version V0.9.0
     */
    public function delete($id) {
        if ($id != null) {
            if ($this->FormGen->del($id)) {
                $this->Session->setFlash('Le formulaire a été supprimé', 'flashsuccess');
            } else {
                $this->Session->setFlash('Une erreur s\'est produite lors de la suppression du formulaire', 'flasherror');
            }
        } else {
            $this->Session->setFlash('Ce formulaire n\'existe pas', 'flasherror');
        }
        $this->redirect($this->referer());
    }

    /**
     * @access public
     * @created 18/06/2015
     * @version V0.9.0
     */
    public function addFirst() {
        if ($this->request->is('POST')) {
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

    /**
     * @param int|null $id
     * 
     * @access public
     * @created 18/06/2015
     * @edit 24/12/2015
     * @version V0.9.0
     */
    public function add($id = null) {
        $organisation = $this->Organisation->find('first', array(
            'conditions' => array('Organisation.id' => $this->Session->read('Organisation.id'))
        ));

        $organisation['Organisation']['service'] = ($this->Session->read('User.service') == null) ? '' : $this->Session->read('User.service');

        $this->set('title', __d('formulaire','formulaire.titreCreerFormulaire'));
        $this->set(compact(['id', 'organisation']));
        if ($this->request->is('POST')) {
            if ($id == null) {
                $id = $this->request->data['Formulaire']['id'];
            }
            $array = json_decode($this->request->data['Formulaire']['json'], true);
            foreach ($array as $key => $value) {
                $sortie = array();
                foreach ($value as $clef => $valeur) {
                    switch ($clef) {
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

    /**
     * @param int $id
     * @param type $state
     * 
     * @access public
     * @created 18/06/2015
     * @edit 24/12/2015
     * @version V0.9.0
     */
    public function toggle($id, $state) {
        $this->Formulaire->id = $id;
        if ($this->Formulaire->updateAll(array('active' => (int) !$state), array('id' => $id))) {
            $this->redirect(array(
                'controller' => 'formulaires',
                'action' => 'index'
            ));
        }
    }

    public function edit($id = null) {
        $this->set('title', __d('formulaire','formulaire.titreEditerFormulaire'));
        
        $organisation = $this->Organisation->find('first', array(
            'conditions' => array('Organisation.id' => $this->Session->read('Organisation.id'))
        ));

        $organisation['Organisation']['service'] = ($this->Session->read('User.service') == null) ? '' : $this->Session->read('User.service');

        $champs = $this->Champ->find('all', array('conditions' => array('formulaires_id' => $id)));
        $this->set(compact(['id', 'organisation', 'champs']));
        $this->Formulaire->updateAll(array('active' => 0), array('id' => $id));
        
        if ($this->request->is(array(
                    'POST',
                    'PUT'
                ))
        ) {
            if ($id == null) {
                $id = $this->request->data['Formulaire']['id'];
            }
            
            if ($this->Champ->deleteAll(array('formulaires_id' => $id))) {
                $array = json_decode($this->request->data['Formulaire']['json'], true);
                
                foreach ($array as $key => $value) {
                    $sortie = array();
                    foreach ($value as $clef => $valeur) {
                        switch ($clef) {
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
