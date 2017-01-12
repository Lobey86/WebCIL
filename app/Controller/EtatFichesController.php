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
 * @since       webcil v0.9.0
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 * @version     v0.9.0
 * @package     App.Controller
 */
class EtatFichesController extends AppController {

    public $uses = [
        'EtatFiche',
        'Commentaire',
        'Fiche',
        'Organisation',
        'Historique',
        'User',
        'Pannel',
        'ModeleExtraitRegistre',
        'TraitementRegistre',
        'ExtraitRegistre'
    ];

    /**
     * Envoie ou renvoie une fiche en validation et crée les états
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public function sendValidation() {
        //On met EtatFiche.actif a false en fonction de l'id 
        $this->EtatFiche->updateAll([
            'actif' => false
                ], [
            'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                ]
        );

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
        $this->EtatFiche->save();
        $this->Notifications->add(2, $this->request->data['EtatFiche']['ficheNum'], $this->request->data['EtatFiche']['destinataire']);

        $this->Notification->updateAll([
            'Notification.afficher' => false
                ], [
            'Notification.fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
            'Notification.user_id' => $this->request->data['EtatFiche']['destinataire'],
        ]);

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
        $this->Historique->save();
        
        $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementEnvoyerValidation'), 'flashsuccess');

        $this->requestAction([
            'controller' => 'pannel',
            'action' => 'supprimerLaNotif',
            $this->request->data['EtatFiche']['ficheNum']
        ]);

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
     * @version V0.9.0
     */
    public function reorientation() {
        $this->EtatFiche->delete($this->request->data['EtatFiche']['etatFiche']);
        $this->EtatFiche->create([
            'EtatFiche' => [
                'fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
                'etat_id' => 2,
                'previous_user_id' => $this->Auth->user('id'),
                'user_id' => $this->request->data['EtatFiche']['destinataire']
            ]
        ]);
        $this->EtatFiche->save();

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
        $this->Historique->save();
        
        $this->Notifications->del(2, $this->request->data['EtatFiche']['ficheNum']);
        $this->Notifications->add(2, $this->request->data['EtatFiche']['ficheNum'], $this->request->data['EtatFiche']['destinataire']);

        $this->Notification->updateAll([
            'Notification.afficher' => false
                ], [
            'Notification.fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
            'Notification.user_id' => $this->request->data['EtatFiche']['destinataire']
        ]);

        $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementRedirige'), 'flashsuccess');

        $this->requestAction([
            'controller' => 'pannel',
            'action' => 'supprimerLaNotif',
            $this->request->data['EtatFiche']['ficheNum']
        ]);

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
     * @version V0.9.0
     */
    public function refuse() {
        $idEncoursValid = $this->EtatFiche->find('first', [
            'conditions' => [
                'EtatFiche.fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
                'EtatFiche.etat_id' => 2
            ],
            'fields' => 'id'
        ]);
        $id = $idEncoursValid['EtatFiche']['id'];

        $this->EtatFiche->id = $id;
        $this->EtatFiche->saveField('etat_id', 4);

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
        $this->Commentaire->save();

        $this->Historique->create([
            'Historique' => [
                'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' refuse la fiche',
                'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
            ]
        ]);
        $this->Historique->save();
        $this->Notifications->add(4, $this->request->data['EtatFiche']['ficheNum'], $idDestinataire);

        $this->Notification->updateAll([
            'Notification.afficher' => false
                ], [
            'Notification.fiche_id' => $idFiche,
            'Notification.user_id' => $idDestinataire,
        ]);

        $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementRefuse'), 'flashsuccess');

        $this->requestAction([
            'controller' => 'pannel',
            'action' => 'supprimerLaNotif',
            $idFiche
        ]);

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
     * @version V0.9.0
     */
    public function askAvis() {
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
            $this->EtatFiche->save();
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
            $this->Historique->save();
            $this->Notifications->add(1, $this->request->data['EtatFiche']['ficheNum'], $this->request->data['EtatFiche']['destinataire']);

            $this->Notification->updateAll([
                'Notification.afficher' => false
                    ], [
                'Notification.fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
                'Notification.user_id' => $this->request->data['EtatFiche']['destinataire'],
            ]);

            $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementEnvoyerAvis'), 'flashsuccess');

            $this->requestAction([
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $this->request->data['EtatFiche']['ficheNum']
            ]);

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
     * @version V0.9.0
     */
    public function answerAvis() {
        $idEncoursAnswer = $this->EtatFiche->find('first', [
            'conditions' => [
                'EtatFiche.id' => $this->request->data['EtatFiche']['etatFiche']
            ],
            'fields' => 'previous_etat_id'
        ]);

        $id = $idEncoursAnswer['EtatFiche']['previous_etat_id'];
        $this->EtatFiche->delete($this->request->data['EtatFiche']['etatFiche']);

        $this->Commentaire->create([
            'Commentaire' => [
                'etat_fiches_id' => $id,
                'content' => $this->request->data['EtatFiche']['commentaireRepondre'],
                'user_id' => $this->Auth->user('id'),
                'destinataire_id' => $this->request->data['EtatFiche']['previousUserId']
            ]
        ]);
        $this->Commentaire->save();

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
        $this->Historique->save();
        $this->Notifications->add(5, $this->request->data['EtatFiche']['ficheNum'], $this->request->data['EtatFiche']['previousUserId']);

        $this->Notification->updateAll([
            'Notification.afficher' => false
                ], [
            'Notification.fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
            'Notification.user_id' => $this->request->data['EtatFiche']['previousUserId'],
        ]);

        $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessCommentaireAjouter'), 'flashsuccess');

        $this->requestAction([
            'controller' => 'pannel',
            'action' => 'supprimerLaNotif',
            $this->request->data['EtatFiche']['ficheNum']
        ]);

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
     * @version V0.9.0
     */
    public function relaunch($id) {
        if (!$id) {
            $this->Session->setFlash(__d('default', 'default.flasherrorTraitementInexistant'), 'flasherror');
            $this->redirect(['controller' => 'pannel',
                'action' => 'index'
            ]);
        } else {
            $this->requestAction([
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $id
            ]);

            $this->EtatFiche->updateAll([
                'actif' => false
                    ], [
                'fiche_id' => $id
                    ]
            );

            $this->EtatFiche->save([
                'EtatFiche' => [
                    'fiche_id' => $id,
                    'etat_id' => 8,
                    'previous_user_id' => $this->Auth->user('id'),
                    'user_id' => $this->Auth->user('id')
                ]
            ]);

            if ($this->EtatFiche->save()) {
                $this->Historique->create([
                    'Historique' => [
                        'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' replace la fiche en rédaction',
                        'fiche_id' => $id
                    ]
                ]);
                $this->Historique->save();

                $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementReplacerRedaction'), 'flashsuccess');
                $this->redirect([
                    'controller' => 'pannel',
                    'action' => 'index'
                ]);
            }
        }
    }

    /**
     * Gère l'envoie en validation au CIL
     * 
     * @param int $id
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public function cilValid($id) {
        $this->EtatFiche->updateAll([
            'actif' => false
                ], [
            'fiche_id' => $id
                ]
        );

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

            $this->EtatFiche->save();

            $this->Historique->create([
                'Historique' => [
                    'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' envoie la fiche pour validation du CIL',
                    'fiche_id' => $id
                ]
            ]);

            $this->Historique->save();

            $this->Notifications->add(2, $id, $cil['Organisation']['cil']);

            $this->Notification->updateAll([
                'Notification.afficher' => false
                    ], [
                'Notification.fiche_id' => $id,
                'Notification.user_id' => $cil['Organisation']['cil']
            ]);

            $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementEnvoyerCIL'), 'flashsuccess');

            $this->requestAction([
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $id
            ]);

            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        } else {
            $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flasherrorAucunCIL'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Insère dans le registre le traiment
     * Gère la validation du CIL
     * 
     * @param int $id
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public function insertRegistre($id, $numero = null) {
        if(empty($id)) {
            throw new NotFoundException();
        }
        
        $success = true;
        $this->EtatFiche->begin();
        
        if (!empty($numero)) {           
            $success = $success && $this->Fiche->updateAll(
                [
                    'numero' => $numero
                ],[
                    'id' => $id
                ]
            ) !== false;
        }

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
            $success = $success && false !== $this->Notifications->add(3, $id, $idEncoursValid['Fiche']['user_id']);

            $success = $success && $this->Notification->updateAll([
                'Notification.afficher' => false
                ], [
                    'Notification.fiche_id' => $idEncoursValid['Fiche']['id'],
                    'Notification.user_id' => $idEncoursValid['Fiche']['user_id'],
                ]
            ) !== false;

            $this->Historique->create([
                'Historique' => [
                    'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' valide la fiche et l\'insère au registre',
                    'fiche_id' => $id
                ]
            ]);
            $success = $success && false !== $this->Historique->save();

            if($success == true) {
                $this->EtatFiche->commit();
                $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementEngregistreRegistre'), 'flashsuccess');
            
                $this->requestAction([
                    'controller' => 'pannel',
                    'action' => 'supprimerLaNotif',
                    $idEncoursValid['Fiche']['id']
                ]);
            } else {
                $this->EtatFiche->rollback();
                $this->Session->setFlash(__d('etat_fiche', 'oups! (FIXME msg)'), 'flasherror');
            }

            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        } else {
            $this->EtatFiche->rollback();
            $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flasherrorTraitementPasEnCourValidation'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Gère l'archivage des fiches
     * 
     * @param int $id
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public function archive($id, $numeroRegistre) {
        if (empty($id)) {
            $this->Session->setFlash(__d('default', 'default.flasherrorTraitementInexistant'), 'flasherror');
            $this->redirect([
                'controller' => 'registres',
                'action' => 'index'
            ]);
        } else {
            $pdfTraitement = $this->Fiche->genereTraitement($id, $numeroRegistre);

            $modele = $this->ModeleExtraitRegistre->find('first', [
                'conditions' => [
                    'organisations_id' => $this->Session->read('Organisation.id')
                ]
            ]);
            $pdfExtrait = $this->Fiche->genereExtrait($id, $numeroRegistre, $modele);

            // Si la génération n'est pas vide on enregistre les data du Traitement en base de données
            if (!empty($pdfTraitement)) {
                $this->TraitementRegistre->begin();
                $fileSave = $this->TraitementRegistre->save([
                    'id_fiche' => $id,
                    'data' => $pdfTraitement
                ]);

                if ($fileSave == true) {
                    $this->TraitementRegistre->commit();
                    $saveTraitement = true;
                } else {
                    $this->TraitementRegistre->rollback();
                    $saveTraitement = false;
                }
            }

            // Si la génération n'est pas vide on enregistre les data de l'Extrait de registre en base de données
            if (!empty($pdfExtrait)) {
                $this->ExtraitRegistre->begin();

                $fileSave = $this->ExtraitRegistre->save([
                    'id_fiche' => $id,
                    'data' => $pdfExtrait
                ]);

                if ($fileSave == true) {
                    $this->ExtraitRegistre->commit();
                    $saveExtrait = true;
                } else {
                    $this->ExtraitRegistre->rollback();
                    $saveExtrait = false;
                }
            }


            if ($saveTraitement == true && $saveExtrait == true) {
                $this->EtatFiche->updateAll([
                    'actif' => false
                    ],[
                        'fiche_id' => $id,
                        'etat_id' => [5, 9],
                        'actif' => true
                    ]
                );
                
                $this->EtatFiche->create([
                    'EtatFiche' => [
                        'fiche_id' => $id,
                        'etat_id' => 7,
                        'previous_user_id' => $this->Auth->user('id'),
                        'user_id' => $this->Auth->user('id')
                    ]
                ]);
                $this->EtatFiche->save();
                
                $this->Historique->create([
                    'Historique' => [
                        'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' archive la fiche',
                        'fiche_id' => $id
                    ]
                ]);
                $this->Historique->save();

                $this->Session->setFlash(__d('etat_fiche', 'etat_fiche.flashsuccessTraitementArchiver'), 'flashsuccess');
                $this->redirect(array(
                    'controller' => 'registres',
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash('ERREUR d\'enregistrement', 'flasherror');
                $this->redirect(array(
                    'controller' => 'registres',
                    'action' => 'index'
                ));
            }
        }
    }

}
