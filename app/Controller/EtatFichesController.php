<?php

/**
 * EtatFichesController
 * Controller de l'état de validation des fiches
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
class EtatFichesController extends AppController {

    public $uses = [
        'Commentaire',
        'EtatFiche',
        'ExtraitRegistre',
        'Fiche',
        'Historique',
        'ModeleExtraitRegistre',
        'Notification',
        'Organisation',
        'Pannel',
        'TraitementRegistre',
        'User'
    ];

    /**
     * Envoie ou renvoie d'un traitement en validation et crée les états
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function sendValidation() {
        $success = true;
        $this->EtatFiche->begin();

        //On met EtatFiche.actif a false en fonction de l'id 
        $success = $success && $this->EtatFiche->updateAll([
                    'actif' => false
                        ], [
                    'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                        ]
                ) !== false;

        if ($success == true) {
            $idEncoursValid = $this->EtatFiche->find('first', [
                'conditions' => [
                    'EtatFiche.fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
                    'EtatFiche.etat_id' => 2
                ],
                'fields' => 'id'
            ]);

            if (!empty($idEncoursValid)) {
                $id = $idEncoursValid['EtatFiche']['id'];
                $this->EtatFiche->id = $id;
                $this->EtatFiche->saveField('etat_id', 3);
            }

            $this->EtatFiche->create([
                'EtatFiche' => [
                    'fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
                    'etat_id' => 2,
                    'previous_user_id' => $this->Auth->user('id'),
                    'user_id' => $this->request->data['EtatFiche']['destinataire']
                ]
            ]);
            $success = $success && false !== $this->EtatFiche->save();

            if ($success == true) {
                $this->Notification->create([
                    'Notification' => [
                        'user_id' => $this->request->data['EtatFiche']['destinataire'],
                        'content' => 2,
                        'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                    ]
                ]);
                $success = $success && false !== $this->Notification->save();

                if ($success == true) {
                    $destinataire = $this->User->find('first', [
                        'conditions' => [
                            'id' => $this->request->data['EtatFiche']['destinataire']
                        ]
                    ]);

                    $this->Historique->create([
                        'Historique' => [
                            'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' envoie la fiche à ' . $destinataire['User']['prenom'] . ' ' . $destinataire['User']['nom'] . ' pour validation',
                            'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                        ]
                    ]);
                    $success = $success && false !== $this->Historique->save();
                }
            }
        }

        if ($success == true) {
            $this->EtatFiche->commit();
            $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementEnvoyerValidation'), 'flashsuccess');

            $this->requestAction([
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $this->request->data['EtatFiche']['ficheNum']
            ]);
        } else {
            $this->EtatFiche->rollback();
            $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
        }

        $this->redirect([
            'controller' => 'pannel',
            'action' => 'index'
        ]);
    }

    /**
     * Envoie une fiche en réorientation
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function reorientation() {
        $success = true;
        $this->EtatFiche->begin();

        $success = $success && false !== $this->EtatFiche->delete($this->request->data['EtatFiche']['etatFiche']);
        $this->EtatFiche->create([
            'EtatFiche' => [
                'fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
                'etat_id' => 2,
                'previous_user_id' => $this->Auth->user('id'),
                'user_id' => $this->request->data['EtatFiche']['destinataire']
            ]
        ]);
        $success = $success && false !== $this->EtatFiche->save();

        if ($success == true) {
            $destinataire = $this->User->find('first', [
                'conditions' => [
                    'id' => $this->request->data['EtatFiche']['destinataire']
                ]
            ]);

            $this->Historique->create([
                'Historique' => [
                    'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' réoriente la fiche vers ' . $destinataire['User']['prenom'] . ' ' . $destinataire['User']['nom'] . ' pour validation',
                    'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                ]
            ]);
            $success = $success && false !== $this->Historique->save();

            if ($success == true) {
                $success = $success && false !== $this->Notifications->del(2, $this->request->data['EtatFiche']['ficheNum']);

                if ($success == true) {
                    $this->Notification->create([
                        'Notification' => [
                            'user_id' => $this->request->data['EtatFiche']['destinataire'],
                            'content' => 2,
                            'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                        ]
                    ]);
                    $success = $success && false !== $this->Notification->save();
                }
            }
        }

        if ($success == true) {
            $this->EtatFiche->commit();
            $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementRedirige'), 'flashsuccess');

            $this->requestAction([
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $this->request->data['EtatFiche']['ficheNum']
            ]);
        } else {
            $this->EtatFiche->rollback();
            $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
        }

        $this->redirect([
            'controller' => $this->Session->read('nameController'),
            'action' => $this->Session->read('nameView')
        ]);
    }

    /**
     * Gère le refus de validation et le commentaire associé
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function refuse() {
        $success = true;
        $this->EtatFiche->begin();

        $idEncoursValid = $this->EtatFiche->find('first', [
            'conditions' => [
                'EtatFiche.fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
                'EtatFiche.etat_id' => 2
            ],
            'fields' => 'id'
        ]);
        $id = $idEncoursValid['EtatFiche']['id'];

        $this->EtatFiche->id = $id;
        $success = $success && false !== $this->EtatFiche->saveField('etat_id', 4);

        if ($success == true) {
            $idDestinataire = $this->Fiche->find('first', [
                'conditions' => [
                    'Fiche.id' => $this->request->data['EtatFiche']['ficheNum']
                ],
                'fields' => ['id'],
                'contain' => [
                    'User' => array('id')
                ]
            ]);
            $idFiche = $idDestinataire['Fiche']['id'];
            $idDestinataire = $idDestinataire['User']['id'];

            $this->Commentaire->create([
                'Commentaire' => [
                    'etat_fiches_id' => $id,
                    'content' => $this->request->data['EtatFiche']['content'],
                    'user_id' => $this->Auth->user('id'),
                    'destinataire_id' => $idDestinataire
                ]
            ]);
            $success = $success && false !== $this->Commentaire->save();

            if ($success == true) {
                $this->Historique->create([
                    'Historique' => [
                        'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' refuse la fiche',
                        'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                    ]
                ]);
                $success = $success && false !== $this->Historique->save();

                if ($success == true) {
                    $this->Notification->create([
                        'Notification' => [
                            'user_id' => $idDestinataire,
                            'content' => 4,
                            'fiche_id' => $idFiche
                        ]
                    ]);
                    $success = $success && false !== $this->Notification->save();
                }
            }
        }

        if ($success == true) {
            $this->EtatFiche->commit();
            $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementRefuse'), 'flashsuccess');

            $this->requestAction([
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $idFiche
            ]);
        } else {
            $this->EtatFiche->rollback();
            $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
        }

        $this->redirect([
            'controller' => 'pannel',
            'action' => 'index'
        ]);
    }

    /**
     * Gère l'envoie de la demande d'avis
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function askAvis() {
        $success = true;
        $this->EtatFiche->begin();

        $count = $this->EtatFiche->find('count', [
            'conditions' => [
                'previous_user_id' => $this->Auth->user('id'),
                'user_id' => $this->request->data['EtatFiche']['destinataire'],
                'previous_etat_id' => $this->request->data['EtatFiche']['etatFiche'],
                'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
            ]
        ]);

        if ($count > 0) {
            $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashwarningTraitementDejaAttenteUser'), 'flashwarning');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        } else {
            $this->EtatFiche->create([
                'EtatFiche' => [
                    'etat_id' => 6,
                    'previous_user_id' => $this->Auth->user('id'),
                    'user_id' => $this->request->data['EtatFiche']['destinataire'],
                    'previous_etat_id' => $this->request->data['EtatFiche']['etatFiche'],
                    'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                ]
            ]);
            $success = $success && false !== $this->EtatFiche->save();

            if ($success == true) {
                $destinataire = $this->User->find('first', [
                    'conditions' => [
                        'id' => $this->request->data['EtatFiche']['destinataire']
                    ]
                ]);

                $this->Historique->create([
                    'Historique' => [
                        'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' demande l\'avis de ' . $destinataire['User']['prenom'] . ' ' . $destinataire['User']['nom'],
                        'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                    ]
                ]);
                $success = $success && false !== $this->Historique->save();

                if ($success == true) {
                    $this->Notification->create([
                        'Notification' => [
                            'user_id' => $this->request->data['EtatFiche']['destinataire'],
                            'content' => 1,
                            'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                        ]
                    ]);
                    $success = $success && false !== $this->Notification->save();
                }
            }

            if ($success == true) {
                $this->EtatFiche->commit();
                $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementEnvoyerAvis'), 'flashsuccess');

                $this->requestAction([
                    'controller' => 'pannel',
                    'action' => 'supprimerLaNotif',
                    $this->request->data['EtatFiche']['ficheNum']
                ]);
            } else {
                $this->EtatFiche->rollback();
                $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
            }

            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Gère la réponse à une demande d'avis
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function answerAvis() {
        $success = true;
        $this->EtatFiche->begin();

        $idEncoursAnswer = $this->EtatFiche->find('first', [
            'conditions' => [
                'EtatFiche.id' => $this->request->data['EtatFiche']['etatFiche']
            ],
            'fields' => 'previous_etat_id'
        ]);
        $id = $idEncoursAnswer['EtatFiche']['previous_etat_id'];

        $success = $success && false !== $this->EtatFiche->delete($this->request->data['EtatFiche']['etatFiche']);

        if ($success == true) {
            $this->Commentaire->create([
                'Commentaire' => [
                    'etat_fiches_id' => $id,
                    'content' => $this->request->data['EtatFiche']['commentaireRepondre'],
                    'user_id' => $this->Auth->user('id'),
                    'destinataire_id' => $this->request->data['EtatFiche']['previousUserId']
                ]
            ]);
            $success = $success && false !== $this->Commentaire->save();

            if ($success == true) {
                $destinataire = $this->User->find('first', [
                    'conditions' => [
                        'id' => $this->request->data['EtatFiche']['previousUserId']
                    ]
                ]);

                $this->Historique->create([
                    'Historique' => [
                        'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' répond à la demande d\'avis de ' . $destinataire['User']['prenom'] . ' ' . $destinataire['User']['nom'],
                        'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                    ]
                ]);
                $success = $success && false !== $this->Historique->save();

                if ($success == true) {
                    $this->Notification->create([
                        'Notification' => [
                            'user_id' => $this->request->data['EtatFiche']['previousUserId'],
                            'content' => 5,
                            'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                        ]
                    ]);
                    $success = $success && false !== $this->Notification->save();
                }
            }
        }

        if ($success == true) {
            $this->EtatFiche->commit();
            $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessCommentaireAjouter'), 'flashsuccess');

            $this->requestAction([
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $this->request->data['EtatFiche']['ficheNum']
            ]);
        } else {
            $this->EtatFiche->rollback();
            $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
        }

        $this->redirect([
            'controller' => 'pannel',
            'action' => 'index'
        ]);
    }

    /**
     * Gère la remise en rédaction d'une fiche refusée
     * 
     * @param int $id
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function relaunch($id) {
        if (!$id) {
            $this->Session->setFlash(__d('default', 'default.flasherrorTraitementInexistant'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        } else {
            $success = true;
            $this->EtatFiche->begin();

            $this->requestAction([
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $id
            ]);

            $success = $success && $this->EtatFiche->updateAll([
                        'actif' => false
                            ], [
                        'fiche_id' => $id
                            ]
                    ) !== false;

            if ($success == true) {
                $this->EtatFiche->create([
                    'EtatFiche' => [
                        'fiche_id' => $id,
                        'etat_id' => 8,
                        'previous_user_id' => $this->Auth->user('id'),
                        'user_id' => $this->Auth->user('id')
                    ]
                ]);
                $success = $success && false !== $this->EtatFiche->save();

                if ($success == true) {
                    $this->Historique->create([
                        'Historique' => [
                            'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' replace la fiche en rédaction',
                            'fiche_id' => $id
                        ]
                    ]);
                    $success = $success && false !== $this->Historique->save();
                }
            }
        }

        if ($success == true) {
            $this->EtatFiche->commit();
            $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementReplacerRedaction'), 'flashsuccess');
        } else {
            $this->EtatFiche->rollback();
            $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
        }

        $this->redirect([
            'controller' => 'pannel',
            'action' => 'index'
        ]);
    }

    /**
     * Gère l'envoie en validation au CIL
     * 
     * @param int $id
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function cilValid($id) {
        $success = true;
        $this->EtatFiche->begin();

        $success = $success && $this->EtatFiche->updateAll([
                    'actif' => false
                        ], [
                    'fiche_id' => $id
                        ]
                ) !== false;

        if ($success == true) {
            $cil = $this->Organisation->find('first', [
                'conditions' => [
                    'Organisation.id' => $this->Session->read('Organisation.id')
                ],
                'fields' => [
                    'cil'
                ]
            ]);

            if ($cil['Organisation']['cil'] != null) {
                $idEncoursValid = $this->EtatFiche->find('first', [
                    'conditions' => [
                        'EtatFiche.fiche_id' => $id,
                        'EtatFiche.etat_id' => 2
                    ],
                    'fields' => 'id'
                ]);

                if (!empty($idEncoursValid)) {
                    $etatId = $idEncoursValid['EtatFiche']['id'];
                    $this->EtatFiche->id = $etatId;
                    $this->EtatFiche->saveField('etat_id', 3);
                }

                $this->EtatFiche->create([
                    'EtatFiche' => [
                        'fiche_id' => $id,
                        'etat_id' => 2,
                        'previous_user_id' => $this->Auth->user('id'),
                        'user_id' => $cil['Organisation']['cil']
                    ]
                ]);
                $success = $success && false !== $this->EtatFiche->save();

                if ($success == true) {
                    $this->Historique->create([
                        'Historique' => [
                            'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' envoie la fiche pour validation du CIL',
                            'fiche_id' => $id
                        ]
                    ]);
                    $success = $success && false !== $this->Historique->save();

                    if ($success == true) {
                        $this->Notification->create([
                            'Notification' => [
                                'user_id' => $cil['Organisation']['cil'],
                                'content' => 2,
                                'fiche_id' => $id
                            ]
                        ]);
                        $success = $success && false !== $this->Notification->save();
                    }
                }
            }
        }

        if ($success == true) {
            $this->EtatFiche->commit();
            $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementEnvoyerCIL'), 'flashsuccess');

            $this->requestAction([
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $id
            ]);
        } else {
            $this->EtatFiche->rollback();
            $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
        }

        $this->redirect([
            'controller' => 'pannel',
            'action' => 'index'
        ]);
    }

    /**
     * Insère dans le registre le traiment
     * Gère la validation du CIL
     * 
     * @param int $id
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function insertRegistre($id, $numero = null) {
        if (empty($id)) {
            throw new NotFoundException();
        }

        $success = true;
        $this->EtatFiche->begin();

        if (!empty($numero)) {
            $success = $success && $this->Fiche->updateAll(
                            [
                        'numero' => $numero
                            ], [
                        'id' => $id
                            ]
                    ) !== false;
        }

        if ($success == true) {
            $idEncoursValid = $this->EtatFiche->find('first', [
                'conditions' => [
                    'EtatFiche.fiche_id' => $id,
                    'EtatFiche.etat_id' => 2
                ],
                'fields' => ['id'],
                'contain' => [
                    'Fiche' => [
                        'user_id'
                    ]
                ]
            ]);

            if (!empty($idEncoursValid)) {
                $id_etat = $idEncoursValid['EtatFiche']['id'];
                $this->EtatFiche->id = $id_etat;

                $success = $success && false !== $this->EtatFiche->saveField('etat_id', 5);

                if ($success == true) {
                    $this->Notification->create([
                        'Notification' => [
                            'user_id' => $idEncoursValid['Fiche']['user_id'],
                            'content' => 3,
                            'fiche_id' => $id
                        ]
                    ]);
                    $success = $success && false !== $this->Notification->save();

                    if ($success == true) {
                        $this->Historique->create([
                            'Historique' => [
                                'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' valide la fiche et l\'insère au registre',
                                'fiche_id' => $id
                            ]
                        ]);
                        $success = $success && false !== $this->Historique->save();
                    }
                }
            }
        }

        if ($success == true) {
            $this->EtatFiche->commit();
            $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementEngregistreRegistre'), 'flashsuccess');

            $this->requestAction([
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $idEncoursValid['Fiche']['id']
            ]);

            $this->redirect([
                'controller' => 'registres',
                'action' => 'index'
            ]);
        } else {
            $this->EtatFiche->rollback();
            $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
        }

        $this->redirect([
            'controller' => 'pannel',
            'action' => 'inbox'
        ]);
    }

    /**
     * Gère l'archivage des fiches
     * 
     * @param int $id
     * 
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public function archive($id, $numeroRegistre) {
        if (empty($id)) {
            $this->Session->setFlash(__d('default', 'default.flasherrorTraitementInexistant'), 'flasherror');

            $this->redirect([
                'controller' => 'registres',
                'action' => 'index'
            ]);
        } else {
            $success = true;
            $this->EtatFiche->begin();

            $pdfTraitement = $this->Fiche->genereTraitement($id, $numeroRegistre);

            $modele = $this->ModeleExtraitRegistre->find('first', [
                'conditions' => [
                    'organisations_id' => $this->Session->read('Organisation.id')
                ]
            ]);
            $pdfExtrait = $this->Fiche->genereExtrait($id, $numeroRegistre, $modele);

            // Si la génération n'est pas vide on enregistre les data du Traitement en base de données
            if (!empty($pdfTraitement)) {
                $this->TraitementRegistre->create([
                    'fiche_id' => $id,
                    'data' => $pdfTraitement
                ]);
                $success = $success && false !== $this->TraitementRegistre->save();
            }

            if ($success == true) {
                // Si la génération n'est pas vide on enregistre les data de l'Extrait de registre en base de données
                if (!empty($pdfExtrait)) {
                    $this->ExtraitRegistre->create([
                        'fiche_id' => $id,
                        'data' => $pdfExtrait
                    ]);
                    $success = $success && false !== $this->ExtraitRegistre->save();
                }

                if ($success == true) {
                    $success = $success && $this->EtatFiche->updateAll([
                                'actif' => false
                                    ], [
                                'fiche_id' => $id,
                                'etat_id' => [5, 9],
                                'actif' => true
                                    ]
                            ) !== false;

                    if ($success == true) {
                        $this->EtatFiche->create([
                            'EtatFiche' => [
                                'fiche_id' => $id,
                                'etat_id' => 7,
                                'previous_user_id' => $this->Auth->user('id'),
                                'user_id' => $this->Auth->user('id')
                            ]
                        ]);
                        $success = $success && false !== $this->EtatFiche->save();

                        if ($success == true) {
                            $this->Historique->create([
                                'Historique' => [
                                    'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' archive la fiche',
                                    'fiche_id' => $id
                                ]
                            ]);
                            $success = $success && false !== $this->Historique->save();
                        }
                    }
                }
            }

            if ($success == true) {
                $this->EtatFiche->commit();
                $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementArchiver'), 'flashsuccess');
            } else {
                $this->EtatFiche->rollback();
                $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
            }

            $this->redirect(array(
                'controller' => 'registres',
                'action' => 'index'
            ));
        }
    }

}
