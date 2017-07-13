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
 * @since       webcil V1.0.0
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 * @version     V1.0.0
 * @package     App.Controller
 */
App::uses('ListeDroit', 'Model');

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
     * @version V1.0.0
     */
    public function index() {
        if (true !== $this->Droits->authorized([ListeDroit::CREER_UTILISATEUR, ListeDroit::MODIFIER_UTILISATEUR, ListeDroit::SUPPRIMER_UTILISATEUR, ListeDroit::CREER_ORGANISATION, ListeDroit::MODIFIER_ORGANISATION, ListeDroit::CREER_PROFIL, ListeDroit::MODIFIER_PROFIL, ListeDroit::SUPPRIMER_PROFIL])) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }
        
        // Superadmin non autorisé
        if ($this->Droits->isSu() == true) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->set('title', __d('formulaire', 'formulaire.titreListeFormulaire') . $this->Session->read('Organisation.raisonsociale'));
        
        $all = $this->FormGen->getAll([
            'organisations_id' => $this->Session->read('Organisation.id')
        ]);
        
        $valid = array();
        foreach ($all as $key => $value) {
            $verif = $this->Fiche->find('count', [
                'conditions' => [
                    'form_id' => $value['Formulaire']['id']
                ]
            ]);
            
            if ($verif == 0) {
                $valid[$value['Formulaire']['id']] = true;
            } else {
                $valid[$value['Formulaire']['id']] = false;
            }
        }
        
        $this->set(compact('valid'));
        $this->set('formulaires', $all);
        
        $nbOrganisationsUser = $this->OrganisationUser->find('count', [
            'conditions' => [
                'user_id' => $this->Session->read('Auth.User.id')
            ]
        ]);

        if ($nbOrganisationsUser >= 2) {
            $organisationsUser = $this->OrganisationUser->find('all', [
                'conditions' => [
                    'user_id' => $this->Session->read('Auth.User.id'),
                    'NOT' => [
                        'organisation_id' => $this->Session->read('Organisation.id')
                    ]
                ]
            ]);
            
            $listeOrganisations = [];
            foreach ($organisationsUser as $organisationUser) {
                $organisations = $this->Organisation->find('first', [
                   'conditions' => [
                       'id' => $organisationUser['OrganisationUser']['organisation_id']
                    ], 
                    'fields' => [
                        'id',
                        'raisonsociale'
                    ]
                ]);
                
                $listeOrganisations[$organisations['Organisation']['id']] = $organisations['Organisation']['raisonsociale'];
            }
            $this->set('listeOrganisations', $listeOrganisations);
        }
    }

    /**
     * Permet de dupliquer un formulaire qui est vérouiller  
     * 
     * @access public
     * @created 26/04/2016
     * @version V1.0.0
     */
    public function dupliquer() {
        $success = true;
        $this->Formulaire->begin();

        $id = $this->request->data['Formulaire']['id'];

        $organisation = $this->Organisation->find('first', array(
            'conditions' => array('Organisation.id' => $this->Session->read('Organisation.id'))
        ));

        $organisation['Organisation']['service'] = ($this->Session->read('User.service') == null) ? '' : $this->Session->read('User.service');

        //pour que le formulaire qu'on duplique reste actif
        $success = $success && $this->Formulaire->updateAll(array(
                    'active' => true
                        ), array(
                    'id' => $id
                )) !== false;

        if ($success == true) {
            //on c'est un nouveau formulaire en renseignant les infos
            $this->Formulaire->create(array(
                'organisations_id' => $organisation['Organisation']['id'],
                'libelle' => $this->request->data['Formulaire']['libelle'],
                'description' => $this->request->data['Formulaire']['description'],
                'active' => false
            ));
            //on enregistre le formualire
            unset($this->Formulaire->validate['active']['notEmpty']); //@FIXME christian
            $success = $success && false !== $this->Formulaire->save();

            if ($success == true) {
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
                    $success = $success && false !== $this->Champ->save();
                }
            }
        }

        if ($success == true) {
            $this->Formulaire->commit();
            $this->Session->setFlash(__d('formulaire', 'formulaire.flashsuccessFormulaireDupliquer'), 'flashsuccess');
        } else {
            $this->Formulaire->rollback();
            $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
        }

        $this->redirect($this->Referers->get());
    }
    
    /**
     * Dupliquer un formulaire d'un organisation à une autre en tant que CIL
     * dans une collectivité ou on a les droits.
     * 
     * @access public
     * @created 18/05/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    public function dupliquerOrganisation() {
        $success = true;
        $this->Formulaire->begin();

        $id = $this->request->data['Formulaire']['id'];
        
        // C'est un nouveau formulaire en renseignant les infos
        $this->Formulaire->create(array(
            'organisations_id' => $this->request->data['Formulaire']['organisationCible'],
            'libelle' => $this->request->data['Formulaire']['libelle'],
            'description' => $this->request->data['Formulaire']['description'],
            'active' => false
        ));
        
        //on enregistre le formualire
        unset($this->Formulaire->validate['active']['notEmpty']); //@FIXME christian
        $success = $success && false !== $this->Formulaire->save();

        if ($success == true) {
            // On recupere l'id du formulaire qu'on vien d'enregistré
            $idForm = $this->Formulaire->getLastInsertId();

            // On recupere en BDD tout les champs qui corresponde a $id
            $champs = $this->Champ->find('all', array(
                'conditions' => array(
                    'formulaires_id' => $id
                )
            ));

            foreach ($champs as $key => $champ) {
                // On decode pour récupere les info
                $array = json_decode($champ['Champ']['details'], true);

                // On cree un nouveau champs avec l'id du nouveau formulaire qu'on a cree et les info qu'on a décodé
                $this->Champ->create(array(
                    'formulaires_id' => $idForm,
                    'type' => $champ['Champ']['type'],
                    'ligne' => $champ['Champ']['ligne'],
                    'colonne' => $champ['Champ']['colonne'],
                    'details' => $champ['Champ']['details']
                ));

                // On enregistre le champ
                $success = $success && false !== $this->Champ->save();
            }
        }

        if ($success == true) {
            $this->Formulaire->commit();
            $this->Session->setFlash(__d('formulaire', 'formulaire.flashsuccessFormulaireDupliquer'), 'flashsuccess');
        } else {
            $this->Formulaire->rollback();
            $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
        }

        $this->redirect($this->Referers->get());
    }

    /**
     * Supprime un formulaire
     * 
     * @param int $id
     * 
     * @access public
     * @created 18/06/2015
     * @version V1.0.0
     */
    public function delete($id) {
        if ($id != null) {
            $success = true;
            $this->Formulaire->begin();

            $success = $success && false !== $this->FormGen->del($id);

            if ($success == true) {
                $this->Formulaire->commit();
                $this->Session->setFlash(__d('formulaire', 'formulaire.flashsuccessFormulaireSupprimer'), 'flashsuccess');
            } else {
                $this->Formulaire->rollback();
                $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
            }
        } else {
            $this->Session->setFlash(__d('formulaire', 'formulaire.flasherrorFormulaireInexistant'), 'flasherror');
        }
        $this->redirect($this->referer());
    }

    /**
     * @access public
     * @created 18/06/2015
     * @version V1.0.0
     */
    public function addFirst() {
        if ($this->request->is('POST')) {
            $success = true;
            $this->Formulaire->begin();

            $this->Formulaire->create(array(
                'organisations_id' => $this->Session->read('Organisation.id'),
                'libelle' => $this->request->data['Formulaire']['libelle'],
                'description' => $this->request->data['Formulaire']['description'],
                'active' => 0
            ));
            $success = $success && false !== $this->Formulaire->save();

            if ($success == true) {
                $this->Formulaire->commit();
                $this->redirect(array(
                    'controller' => 'formulaires',
                    'action' => 'add',
                    $this->Formulaire->getInsertId()
                ));
            } else {
                $this->Formulaire->rollback();
                $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
            }
        }
    }

    /**
     * @param int|null $id
     * 
     * @access public
     * @created 18/06/2015
     * @edit 24/12/2015
     * @version V1.0.0
     */
    public function add($id = null) {
        $formulaire = $this->Formulaire->find('first', [
            'conditions' => [
                'id' => $id
            ],
            'fields' => [
                'libelle'
            ]
        ]);
        $this->set('title', __d('formulaire', 'formulaire.titreCreerFormulaire') . $formulaire['Formulaire']['libelle']);

        $organisation = $this->Organisation->find('first', array(
            'conditions' => array('Organisation.id' => $this->Session->read('Organisation.id'))
        ));

        $organisation['Organisation']['service'] = ($this->Session->read('User.service') == null) ? '' : $this->Session->read('User.service');

        $this->set(compact(['id', 'organisation']));

        if ($this->request->is('POST')) {
            $success = true;
            $this->Formulaire->begin();

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
                $success = $success && false !== $this->Champ->save();
            }

            if ($success == true) {
                $this->Formulaire->commit();
                $this->Session->setFlash(__d('formulaire', 'formulaire.flashsuccessFormulaireEnregistrer'), 'flashsuccess');

                $this->redirect([
                    'controller' => 'formulaires',
                    'action' => 'index'
                ]);
            } else {
                $this->Formulaire->rollback();
                $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
            }
        }
    }

    /**
     * @param int $id
     * @param type $state
     * 
     * @access public
     * @created 18/06/2015
     * @edit 24/12/2015
     * @version V1.0.0
     */
    public function toggle($id, $state = null) {
        $success = true;
        $this->Formulaire->begin();

        $this->Formulaire->id = $id;

        $success = $success && $this->Formulaire->updateAll(array(
                    'active' => (int) !$state
                        ), array(
                    'id' => $id
                )) !== false;

        if ($success == true) {
            $this->Formulaire->commit();
            $this->Session->setFlash(__d('formulaire', 'formulaire.flashsuccessFormulaireEnregistrer'), 'flashsuccess');

            $this->redirect($this->Referers->get());
        } else {
            $this->Formulaire->rollback();
            $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
        }
    }

    /**
     * Permet d'éditer un formulaire
     * 
     * @param int $id
     * 
     * @access public
     * @version V1.0.0
     */
    public function edit($id) {
        $formulaire = $this->Formulaire->find('first', [
            'conditions' => [
                'id' => $id
            ],
            'fields' => [
                'libelle'
            ]
        ]);
        $this->set('title', __d('formulaire', 'formulaire.titreEditerFormulaire') . $formulaire['Formulaire']['libelle']);

        $organisation = $this->Organisation->find('first', array(
            'conditions' => array('Organisation.id' => $this->Session->read('Organisation.id'))
        ));

        $organisation['Organisation']['service'] = ($this->Session->read('User.service') == null) ? '' : $this->Session->read('User.service');

        $champs = $this->Champ->find('all', array('conditions' => array('formulaires_id' => $id)));
        $this->set(compact(['id', 'organisation', 'champs']));

        if ($this->request->is('POST')) {
            $success = true;
            $this->Formulaire->begin();

            $success = $success && $this->Formulaire->updateAll(array(
                        'active' => 0
                            ), array(
                        'id' => $id
                    )) !== false;

            if ($success == true) {
                if ($this->request->is(array('POST', 'PUT'))) {
                    if ($id == null) {
                        $id = $this->request->data['Formulaire']['id'];
                    }

                    $success = $success && false !== $this->Champ->deleteAll(array('formulaires_id' => $id));

                    if ($success == true) {
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

                            $success = $success && false !== $this->Champ->save();
                        }
                    }
                }
            }

            if ($success == true) {
                $this->Formulaire->commit();
                $this->Session->setFlash(__d('formulaire', 'formulaire.flashsuccessFormulaireEnregistrer'), 'flashsuccess');
//LA
                $this->redirect([
                    'controller' => 'formulaires',
                    'action' => 'index'
                ]);
            } else {
                $this->Formulaire->rollback();
                $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
            }
        }
    }

    /**
     * Permet de visualiser un formulaire
     * 
     * @param int $id ID du formulaire
     * 
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     * @created 03 novembre 2016 
     * @version V1.0.0
     * @access public
     */
    public function show($id) {
        if ($id != null) {
            $formulaire = $this->Formulaire->find('first', [
                'conditions' => [
                    'id' => $id
                ],
                'fields' => [
                    'libelle'
                ]
            ]);
            $this->set('title', __d('formulaire', 'formulaire.titreShowFormulaire') . $formulaire['Formulaire']['libelle']);

            $champs = $this->Champ->find('all', array('conditions' => array('formulaires_id' => $id)));
            $this->set(compact(['champs']));
        } else {
            $this->Session->setFlash(__d('formulaire', 'formulaire.flashsuccessFormulaireEnregistrer'), 'flashsuccess');
            $this->redirect(array(
                'controller' => 'formulaires',
                'action' => 'index'
            ));
        }
    }

}
