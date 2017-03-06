<?php

/**
 * FichesController
 * Controller des fiches
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

class FichesController extends AppController {

    public $helpers = [
        'Html',
        'Form',
        'Session'
    ];
    public $uses = [
        'Fiche',
        'Organisation',
        'Fichier',
        'EtatFiche',
        'ExtraitRegistre',
        'Historique',
        'FormGeneric',
        'FormGenerator.Champ',
        'Valeur',
        'Modele',
        'ModeleExtraitRegistre',
        'Extrait',
        'Service',
        'User',
        'TraitementRegistre'
    ];

    /**
     * La page d'accueil des fiches est celle du pannel général
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function index() {
        $this->redirect([
            'controller' => 'pannel',
            'action' => 'index'
        ]);
    }

    /**
     * Champs virtuels obligatoires et non paramétrables du formulaire de fiche.
     * @fixme à faire dans l'action edit aussi
     *
     * @var array
     */
    protected $_requiredFicheVirtualFields = array(
        'declarantraisonsociale',
        'declarantadresse',
        'declarantemail',
        'declarantsiret',
        'declarantape',
        'declaranttelephone',
        'personneresponsable',
        'fonctionresponsable',
        'emailresponsable',
        'telephoneresponsable',
        'personnecil',
        'emailcil',
        'declarantpersonnenom',
        'declarantpersonneemail',
        'outilnom',
        'finaliteprincipale'
    );

    /**
     * Gère l'ajout de fiches
     * 
     * @param int|null $id
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function add($id = null) {
        if ($this->Droits->authorized(ListeDroit::REDIGER_TRAITEMENT)) {

            $this->set('title', __d('fiche', 'fiche.titreCrationFiche'));

            //On récupére le CIL de la collectivité
            $userCil = $this->User->find('first', [
                'conditions' => [
                    'id' => $this->Session->read('Organisation.cil')
                ],
                'fields' => [
                    'nom',
                    'prenom',
                    'email'
                ]
            ]);
            $this->set('userCil', $userCil);

            $champs = $this->Champ->find('all', [
                'conditions' => [
                    'formulaires_id' => $id
                ],
                'order' => [
                    'colonne ASC',
                    'ligne ASC'
                ]
            ]);
            // @fixme factoriser dans une méthode à utiliser également dans l'édit
            foreach ($champs as $champ) {
                $details = json_decode(Hash::get($champ, 'Champ.details'));
                if ($details->obligatoire == true) {
                    $this->_requiredFicheVirtualFields[] = $details->name;
                }
            }

            $this->set(compact('champs'));
            $this->set('formulaireid', $id);

            if ($this->request->is('POST')) {
                $success = true;
                $this->Fiche->begin();

                $this->Fiche->create([
                    'user_id' => $this->Auth->user('id'),
                    'form_id' => $this->request->data['Fiche']['formulaire_id'],
                    'organisation_id' => $this->Session->read('Organisation.id')
                ]);

                $success = $success && false !== $this->Fiche->save();

                if ($success == true) {
                    $last = $this->Fiche->getLastInsertID();
                    $success = $success && false !== $this->Fichier->saveFichier($this->request->data, $last);

                    if ($success == true) {
                        foreach ($this->request->data['Fiche'] as $key => $value) {
                            if ($key != 'formulaire_id' && (!empty($value) || in_array($key, $this->_requiredFicheVirtualFields))) {
                                if (is_array($value)) {
                                    $value = json_encode($value);
                                }

                                $this->Valeur->create([
                                    'champ_name' => $key,
                                    'fiche_id' => $last,
                                    'valeur' => $value
                                ]);

                                $tmpSave = $this->Valeur->save();
                                if ($tmpSave == false) {
                                    $this->Fiche->invalidate($key, Hash::get($this->Valeur->validationErrors, 'valeur.0'));
                                }

                                $success = $success && false !== $tmpSave;
                            }
                        }

                        $this->Historique->create([
                            'Historique' => [
                                'content' => 'Création de la fiche par ' . $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom'),
                                'fiche_id' => $last
                            ]
                        ]);
                        $success = $success && false !== $this->Historique->save();

                        $this->EtatFiche->create([
                            'EtatFiche' => [
                                'fiche_id' => $last,
                                'etat_id' => 1,
                                'previous_user_id' => $this->Auth->user('id'),
                                'user_id' => $this->Auth->user('id')
                            ]
                        ]);
                        $success = $success && false !== $this->EtatFiche->save();
                    }
                }

                if ($success == true) {
                    $this->Fiche->commit();
                    $this->Session->setFlash(__d('fiche', 'fiche.flashsuccessTraitementEnregistrer'), 'flashsuccess');

                    $this->redirect([
                        'controller' => 'pannel',
                        'action' => 'index'
                    ]);
                } else {
                    $this->Fiche->rollback();
                    $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
                }
            }
        } else {
            $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');

            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Gère la suppression de traitement
     * 
     * @param int|null $id
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function delete($id = null) {
        if ($this->Droits->authorized(ListeDroit::REDIGER_TRAITEMENT) && $this->Droits->isOwner($id)) {
            if (!$this->Droits->isdeletable($id)) {
                $this->Session->setFlash(__d('fiche', 'fiche.flasherrorPasAccesTraitement'), 'flasherror');
                $this->redirect([
                    'controller' => 'pannel',
                    'action' => 'index'
                ]);
            }

            $success = true;
            $this->Fiche->begin();

            $success = $success && false !== $this->Fiche->delete($id);

            if ($success == true) {
                $this->Fiche->commit();
                $this->Session->setFlash(__d('fiche', 'fiche.flashsuccessTraitementSupprimer'), 'flashsuccess');
            } else {
                $this->Fiche->rollback();
                $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');
            }
        } else {
            $this->Session->setFlash(__d('fiche', 'fiche.flasherrorSupprimerTraitementImpossible'), 'flasherror');
        }

        $this->redirect([
            'controller' => 'pannel',
            'action' => 'index'
        ]);
    }

    /**
     * Gère l'édition de fiches
     * 
     * @param int|null $id
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function edit($id = null) {
        $nameTraiment = $this->Valeur->find('first', [
            'conditions' => [
                'fiche_id' => $id,
                'champ_name' => 'outilnom'
            ]
        ]);

        $this->set('title', __d('fiche', 'fiche.titreEditionFiche') . $nameTraiment['Valeur']['valeur']);

        if (!$id && !$this->request->data['Fiche']['id']) {
            $this->Session->setFlash(__d('default', 'default.flasherrorTraitementInexistant'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }

        if (!$id) {
            $id = $this->request->data['Fiche']['id'];
        }

        if (!$this->Droits->isEditable($id)) {
            $this->Session->setFlash(__d('fiche', 'fiche.flasherrorPasAccesTraitement'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }

        $idForm = $this->Fiche->find('first', [
            'conditions' => [
                'id' => $id
            ]
        ]);

        $champs = $this->Champ->find('all', [
            'conditions' => [
                'formulaires_id' => $idForm['Fiche']['form_id']
            ],
            'order' => [
                'colonne ASC',
                'ligne ASC'
            ]
        ]);

        foreach ($champs as $champ) {
            $details = json_decode(Hash::get($champ, 'Champ.details'));
            if ($details->obligatoire == true) {
                $this->_requiredFicheVirtualFields[] = $details->name;
            }
        }

        $this->set(compact('champs'));

        // Si on sauvegarde
        if ($this->request->is(['post', 'put'])) {
            $success = true;
            $this->Valeur->begin();

            $success = $success && $this->Fiche->updateAll([
                        'modified' => "'" . date("Y-m-d H:i:s") . "'"
                            ], [
                        'id' => $id
                            ]
                    ) !== false;

            $success = $success && $this->EtatFiche->updateAll([
                        'actif' => false
                            ], [
                        'fiche_id' => $id,
                        'etat_id' => [5, 9],
                        'actif' => true
                            ]
                    ) !== false;


            foreach ($this->request->data['Fiche'] as $key => $value) {
                $idsToDelete = array_keys($this->Valeur->find('list', [
                            'conditions' => [
                                'champ_name' => $key,
                                'fiche_id' => $id
                            ],
                            'contain' => false
                ]));

                if (empty($idsToDelete) == false) {
                    $success = $success && $this->Valeur->deleteAll([
                                'Valeur.id' => $idsToDelete
                    ]);
                }

                if ($key != 'formulaire_id' && (!empty($value) || in_array($key, $this->_requiredFicheVirtualFields))) {
                    if (is_array($value)) {
                        $value = json_encode($value);
                    }

                    $this->Valeur->create([
                        'champ_name' => $key,
                        'fiche_id' => $id,
                        'valeur' => $value
                    ]);

                    $tmpSave = $this->Valeur->save();
                    if ($tmpSave == false) {
                        $this->Fiche->invalidate($key, Hash::get($this->Valeur->validationErrors, 'valeur.0'));
                    }
                    $success = $success && false !== $tmpSave;
                }
            }

            $success = $success && false !== $this->Fichier->saveFichier($this->request->data, $id);

            if ($success == true) {
                $this->Historique->create([
                    'Historique' => [
                        'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' ' . __d('fiche', 'fiche.textModifierTraitement'),
                        'fiche_id' => $id
                    ]
                ]);
                $success = $success && false !== $this->Historique->save();
            }

            if (isset($this->request->data['delfiles']) && !empty($this->request->data['delfiles'])) {
                foreach ($this->request->data['delfiles'] as $val) {
                    $success = $success && $this->Fichier->deleteFichier($val, false);
                }
            }

            if ($success == true) {
                $this->Valeur->commit();
                $this->Session->setFlash(__d('fiche', 'fiche.flashsuccessTraitementModifier'), 'flashsuccess');
                $this->redirect([
                    'controller' => 'pannel',
                    'action' => 'index'
                ]);
            } else {
                $this->Valeur->rollback();
                $this->Session->setFlash(__d('fiche', 'Une erreur inattendue est survenue...'), 'flasherror');
            }
        } else {
            $files = $this->Fichier->find('all', [
                'conditions' => [
                    'fiche_id' => $id
                ]
            ]);

            $this->set(compact('files'));

            $valeurs = $this->Valeur->find('all', [
                'conditions' => [
                    'fiche_id' => $id
                ]
            ]);

            foreach ($valeurs as $key => $value) {
                if ($this->Fiche->isJson($value['Valeur']['valeur'])) {
                    $this->request->data['Fiche'][$value['Valeur']['champ_name']] = json_decode($value['Valeur']['valeur']);
                } else {
                    $this->request->data['Fiche'][$value['Valeur']['champ_name']] = $value['Valeur']['valeur'];
                }
            }
            $this->set(compact('valeurs'));
            $this->set('id', $id);
        }
    }

    /**
     * Gère l'affichage des fiches
     * 
     * @param int|null $id
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function show($id = null) {
        $nameTraiment = $this->Valeur->find('first', [
            'conditions' => [
                'fiche_id' => $id,
                'champ_name' => 'outilnom']
        ]);
        $this->set('title', __d('fiche', 'fiche.titreApercuFiche') . $nameTraiment['Valeur']['valeur']);

        if (!$id) {
            $this->Session->setFlash(__d('default', 'default.flasherrorTraitementInexistant'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }

        if (!$this->Droits->isReadable($id)) {
            $this->Session->setFlash(__d('fiche', 'fiche.flasherrorPasAccesTraitement'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }

        $idForm = $this->Fiche->find('first', ['conditions' => ['id' => $id]]);
        $champs = $this->Champ->find('all', [
            'conditions' => ['formulaires_id' => $idForm['Fiche']['form_id']],
            'order' => [
                'colonne ASC',
                'ligne ASC'
            ]
        ]);

        $valeurs = $this->Valeur->find('all', ['conditions' => ['fiche_id' => $id]]);

        foreach ($valeurs as $key => $value) {
            if ($this->Fiche->isJson($value['Valeur']['valeur'])) {
                $this->request->data['Fiche'][$value['Valeur']['champ_name']] = json_decode($value['Valeur']['valeur']);
            } else {
                $this->request->data['Fiche'][$value['Valeur']['champ_name']] = $value['Valeur']['valeur'];
            }
        }

        $this->requestAction(array(
            'controller' => 'pannel',
            'action' => 'supprimerLaNotif',
            $id
        ));

        $this->set(compact('valeurs'));
        $this->set(compact('champs'));
        $this->set('id', $id);
        $files = $this->Fichier->find('all', ['conditions' => ['fiche_id' => $id]]);
        $this->set(compact('files'));
    }

    /**
     * Gère le téléchargement des pieces jointes d'une fiche
     * 
     * @param int|null $url
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function download($url = null, $nomFile = 'file.odt') {
        $this->response->file(CHEMIN_PIECE_JOINT . $url, [
            'download' => true,
            'name' => $nomFile
        ]);

        return $this->response;
    }

    /**
     * Téléchargement du traitement verrouiller
     * 
     * @param int $fiche_id
     * @param type $numeroRegistre
     * 
     * @access public
     * @created 04/01/2016
     * @version V1.0.0
     */
    public function downloadFileTraitement($fiche_id, $numeroRegistre) {
        $data = $this->Valeur->find('all', [
            'conditions' => [
                'fiche_id' => $fiche_id]
        ]);

        $pdf = $this->TraitementRegistre->find('first', [
            'conditions' => ['fiche_id' => $fiche_id],
            'fields' => ['data']
        ]);

        header("content-type: application/pdf");
        header('Content-Disposition: attachment; filename="Traitement_' . $data[17]['Valeur']['valeur'] . '_' . $numeroRegistre . '.pdf"');

        echo($pdf['TraitementRegistre']['data']);
    }

    /**
     * Téléchargement de l'extrait de registre verrouiller
     * 
     * @param type $fiche_id
     * @param type $numeroRegistre
     * 
     * @access public
     * @created 09/01/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    public function downloadFileExtrait($fiche_id, $numeroRegistre) {
        $data = $this->Valeur->find('all', [
            'conditions' => [
                'fiche_id' => $fiche_id]
        ]);

        $pdf = $this->ExtraitRegistre->find('first', [
            'conditions' => ['fiche_id' => $fiche_id],
            'fields' => ['data']
        ]);

        header("content-type: application/pdf");
        header('Content-Disposition: attachment; filename="Extrait_' . $data[17]['Valeur']['valeur'] . '_' . $numeroRegistre . '.pdf"');
        echo($pdf['ExtraitRegistre']['data']);
    }

    /**
     * Genere le traitement de registre
     * 
     * @param int $id
     * @param char $numeroRegistre
     * @return data
     * 
     * @access public
     * @created 09/01/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    public function genereTraitement($id, $numeroRegistre) {
        $pdf = $this->Fiche->genereTraitement($id, $numeroRegistre);

        $this->response->disableCache();
        $this->response->body($pdf);
        $this->response->type('application/pdf');
        $this->response->download('Traitement_' . $numeroRegistre . '.pdf');

        $this->requestAction(array(
            'controller' => 'pannel',
            'action' => 'supprimerLaNotif',
            $id
        ));

        return $this->response;
    }

    /**
     * Genere l'extrait de registre
     * 
     * @param int $id
     * @param char $numeroRegistre
     * @return data
     * 
     * @access public
     * @created 09/01/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    public function genereExtrait($id, $numeroRegistre) {
        $modele = $this->ModeleExtraitRegistre->find('first', [
            'conditions' => [
                'organisations_id' => $this->Session->read('Organisation.id')
            ]
        ]);

        $pdf = $this->Fiche->genereExtrait($id, $numeroRegistre, $modele);

        $this->response->disableCache();
        $this->response->body($pdf);
        $this->response->type('application/pdf');
        $this->response->download('Extrait_' . $numeroRegistre . '.pdf');

        $this->requestAction(array(
            'controller' => 'pannel',
            'action' => 'supprimerLaNotif',
            $id
        ));

        return $this->response;
    }

}
