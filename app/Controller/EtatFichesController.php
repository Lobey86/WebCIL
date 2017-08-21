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
        'User',
        'Valeur'
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
                $messageHistorique = __d('historique', 'historique.valideEnvoieTraitement');
            } else {
                $messageHistorique = __d('historique', 'historique.envoieTraitement');
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
                            'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' ' . $messageHistorique . ' ' . $destinataire['User']['prenom'] . ' ' . $destinataire['User']['nom'] . ' ' . __d('historique', 'historique.validation'),
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
                    'content' => $this->request->data['EtatFiche']['commentaireRepondre'],
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
                    'etat_id' => EtatFiche::DEMANDE_AVIS,
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
                        'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' ' . __d('historique', 'historique.demandeAvis') . ' ' . $destinataire['User']['prenom'] . ' ' . $destinataire['User']['nom'],
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
                        'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' ' . __d('historique', 'historique.repondDemandeAvis') . ' ' . $destinataire['User']['prenom'] . ' ' . $destinataire['User']['nom'],
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

    public function repondreCommentaire() {
        if ($this->request->is('POST')) {
            $success = true;
            $this->Commentaire->begin();

            $this->Commentaire->create([
                'Commentaire' => [
                    'etat_fiches_id' => $this->request->data['EtatFiche']['etat_fiche_id'],
                    'content' => $this->request->data['EtatFiche']['commentaire'],
                    'user_id' => $this->Auth->user('id'),
                    'destinataire_id' => $this->Auth->user('id')
                ]
            ]);
            $success = $success && false !== $this->Commentaire->save();

            if ($success == true) {

                $this->Historique->create([
                    'Historique' => [
                        'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' répond à l\'avis',
                        'fiche_id' => $this->request->data['EtatFiche']['fiche_id']
                    ]
                ]);
                $success = $success && false !== $this->Historique->save();

                if ($success == true) {
                    foreach (json_decode($this->request->data['EtatFiche']['idUserCommentaire']) as $idUserCommentaire) {
                        $this->Notification->create([
                            'Notification' => [
                                'user_id' => $idUserCommentaire,
                                'content' => 5,
                                'fiche_id' => $this->request->data['EtatFiche']['fiche_id']
                            ]
                        ]);
                        $success = $success && false !== $this->Notification->save();
                    }
                }
            }

            if ($success == true) {
                $this->EtatFiche->commit();
                $this->Session->setFlash("Commentaire enregistrée", 'flashsuccess');
            } else {
                $this->EtatFiche->rollback();
                $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
            }

            $this->redirect($this->referer());
        }
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
                            'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' ' . __d('historique', 'historique.valideEnvoieTraitementCIL'),
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
    public function insertRegistre($id, $numero = null, $typeDeclaration = null) {
        if (empty($id)) {
            throw new NotFoundException();
        }
        
        if (true !== $this->Droits->authorized($this->Droits->isCil())) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        if ($numero == null || $numero === 'null') {
            $numero = 'CIL' . $id;
        }

        $success = true;
        $this->EtatFiche->begin();

        $this->Fiche->id = $id;
        $success = $success && false !== $this->Fiche->saveField('numero', $numero);

        if ($typeDeclaration != null && $success == true) {
            $this->Valeur->create([
                'champ_name' => 'typedeclaration',
                'fiche_id' => $id,
                'valeur' => $typeDeclaration
            ]);

            $success = $success && false !== $this->Valeur->save();
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
        } else {
            $this->EtatFiche->rollback();
            $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
        }

        $this->redirect([
            'controller' => 'registres',
            'action' => 'index'
        ]);
    }

}
