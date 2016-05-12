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
        $this->set('title', __d('formulaire', 'formulaire.titreListeFormulaire') . $this->Session->read('Organisation.raisonsociale'));
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
     * Permet de dupliquer un formulaire qui est vérouiller  
     * 
     * @access public
     * @created 26/04/2016
     * @version V1.0.2
     */
    public function dupliquer() {
        
        $id = $this->request->data['Formulaire']['id'];

        $organisation = $this->Organisation->find('first', array(
            'conditions' => array('Organisation.id' => $this->Session->read('Organisation.id'))
        ));

        $organisation['Organisation']['service'] = ($this->Session->read('User.service') == null) ? '' : $this->Session->read('User.service');

        //pour que le formulaire qu'on duplique reste actif
        $this->Formulaire->updateAll(array('active' => true), array('id' => $id));

        //on c'est un nouveau formulaire en renseignant les infos
        $this->Formulaire->create(array(
            'organisations_id' => $organisation['Organisation']['id'],
            'libelle' => $this->request->data['Formulaire']['libelle'],
            'description' => $this->request->data['Formulaire']['description'],
            'active' => false,
        ));

        //on enregistre le formualire
        $this->Formulaire->save();

        //on recupere l'id du formulaire qu'on vien d'enregistré
        $idForm = $this->Formulaire->getLastInsertId();

        //on recupere en BDD tout les champs qui corresponde a $id
        $champs = $this->Champ->find('all', array(
            'conditions' => array(
                'formulaires_id' => $id
            )
        ));
        
        foreach ($champs as $key => $champ) {
            //on decode pour récupere les info
            $array = json_decode($champ['Champ']['details'], true);

            //on cree un nouveau champs avec l'id du nouveau formulaire qu'on a cree et les info qu'on a décodé
            $this->Champ->create(array(
                'formulaires_id' => $idForm,
                'type' => $champ['Champ']['type'],
                'ligne' => $champ['Champ']['ligne'],
                'colonne' => $champ['Champ']['colonne'],
                'details' => $champ['Champ']['details']
            ));

            //on enregistre le champ
            $this->Champ->save();
        }

        $this->Session->setFlash(__d('formulaire','formulaire.flashsuccessFormulaireDupliquer'), 'flashsuccess');
        $this->redirect(array(
            'controller' => 'Formulaires',
            'action' => 'index'
        ));
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
                $this->Session->setFlash(__d('formulaire','formulaire.flashsuccessFormulaireSupprimer'), 'flashsuccess');
            } else {
                $this->Session->setFlash(__d('formulaire','formulaire.flasherrorErreurSupprimerFormulaire'), 'flasherror');
            }
        } else {
            $this->Session->setFlash(__d('formulaire','formulaire.flasherrorFormulaireInexistant'), 'flasherror');
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

        $this->set('title', __d('formulaire', 'formulaire.titreCreerFormulaire'));
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
            $this->Session->setFlash(__d('formulaire','formulaire.flashsuccessFormulaireEnregistrer'), 'flashsuccess');
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
    public function toggle($id, $state = null) {
        $this->Formulaire->id = $id;
        if ($this->Formulaire->updateAll(array('active' => (int) !$state), array('id' => $id))) {
            $this->redirect(array(
                'controller' => 'formulaires',
                'action' => 'index'
            ));
        }
    }

    /**
     * Permet d'éditer un formulaire
     * 
     * @param int|null $id
     * 
     * @access public
     * @version V0.9.0
     */
    public function edit($id = null) {
        $this->set('title', __d('formulaire', 'formulaire.titreEditerFormulaire'));

        $organisation = $this->Organisation->find('first', array(
            'conditions' => array('Organisation.id' => $this->Session->read('Organisation.id'))
        ));

        $organisation['Organisation']['service'] = ($this->Session->read('User.service') == null) ? '' : $this->Session->read('User.service');

        $champs = $this->Champ->find('all', array('conditions' => array('formulaires_id' => $id)));
        $this->set(compact(['id', 'organisation', 'champs']));
        $this->Formulaire->updateAll(array('active' => 0), array('id' => $id));

        if ($this->request->is(array('POST', 'PUT'))) {
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

                $this->Session->setFlash(__d('formulaire','formulaire.flashsuccessFormulaireEnregistrer'), 'flashsuccess');
                $this->redirect(array(
                    'controller' => 'formulaires',
                    'action' => 'index'
                ));
            }
        }
    }

}
