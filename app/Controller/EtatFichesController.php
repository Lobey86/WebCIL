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

    public $uses = array(
        'EtatFiche',
        'Commentaire',
        'Fiche',
        'Organisation',
        'Historique',
        'User',
        'Pannel'
    );

    /**
     * Envoie ou renvoie une fiche en validation et crée les états
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public function sendValidation() {
        //On met EtatFiche.actif a false en fonction de l'id 
        $this->EtatFiche->updateAll(array(
            'actif' => false
                ), array(
            'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                )
        );

        $idEncoursValid = $this->EtatFiche->find('first', array(
            'conditions' => array(
                'EtatFiche.fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
                'EtatFiche.etat_id' => 2
            ),
            'fields' => 'id'
        ));
        if (!empty($idEncoursValid)) {
            $id = $idEncoursValid['EtatFiche']['id'];
            $this->EtatFiche->id = $id;
            $this->EtatFiche->saveField('etat_id', 3);
        }
        $this->EtatFiche->create(array(
            'EtatFiche' => array(
                'fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
                'etat_id' => 2,
                'previous_user_id' => $this->Auth->user('id'),
                'user_id' => $this->request->data['EtatFiche']['destinataire']
            )
        ));
        $this->EtatFiche->save();
        $this->Notifications->add(2, $this->request->data['EtatFiche']['ficheNum'], $this->request->data['EtatFiche']['destinataire']);

        $this->Notification->updateAll([
            'Notification.afficher' => false
                ], [
            'Notification.fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
            'Notification.user_id' => $this->request->data['EtatFiche']['destinataire'],
        ]);

        $destinataire = $this->User->find('first', array(
            'conditions' => array(
                'id' => $this->request->data['EtatFiche']['destinataire']
            )
        ));
        $this->Historique->create(array(
            'Historique' => array(
                'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' envoie la fiche à ' . $destinataire['User']['prenom'] . ' ' . $destinataire['User']['nom'] . ' pour validation',
                'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
            )
        ));

        $this->Historique->save();
        $this->Session->setFlash('La fiche a été envoyée en validation', 'flashsuccess');

        $this->requestAction(array(
            'controller' => 'pannel',
            'action' => 'supprimerLaNotif',
            $this->request->data['EtatFiche']['ficheNum']
        ));

        $this->redirect(array(
            'controller' => 'pannel',
            'action' => 'index'
        ));
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
        $this->EtatFiche->create(array(
            'EtatFiche' => array(
                'fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
                'etat_id' => 2,
                'previous_user_id' => $this->Auth->user('id'),
                'user_id' => $this->request->data['EtatFiche']['destinataire']
            )
        ));
        $this->EtatFiche->save();
        $destinataire = $this->User->find('first', array(
            'conditions' => array(
                'id' => $this->request->data['EtatFiche']['destinataire']
            )
        ));
        $this->Historique->create(array(
            'Historique' => array(
                'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' réoriente la fiche vers ' . $destinataire['User']['prenom'] . ' ' . $destinataire['User']['nom'] . ' pour validation',
                'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
            )
        ));
        $this->Historique->save();
        $this->Notifications->del(2, $this->request->data['EtatFiche']['ficheNum']);
        $this->Notifications->add(2, $this->request->data['EtatFiche']['ficheNum'], $this->request->data['EtatFiche']['destinataire']);

        $this->Notification->updateAll([
            'Notification.afficher' => false
                ], [
            'Notification.fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
            'Notification.user_id' => $this->request->data['EtatFiche']['destinataire']
        ]);

        $this->Session->setFlash('La fiche a été redirigée', 'flashsuccess');

        $this->requestAction(array(
            'controller' => 'pannel',
            'action' => 'supprimerLaNotif',
            $this->request->data['EtatFiche']['ficheNum']
        ));
        
        $this->redirect(array(
            'controller' => $this->Session->read('nameController'),
            'action' => $this->Session->read('nameView')
        ));
    }

    /**
     * Gère le refus de validation et le commentaire associé
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public function refuse() {
        $idEncoursValid = $this->EtatFiche->find('first', array(
            'conditions' => array(
                'EtatFiche.fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
                'EtatFiche.etat_id' => 2
            ),
            'fields' => 'id'
        ));
        $id = $idEncoursValid['EtatFiche']['id'];
        $this->EtatFiche->id = $id;
        $this->EtatFiche->saveField('etat_id', 4);
        $idDestinataire = $this->Fiche->find('first', array(
            'conditions' => array('Fiche.id' => $this->request->data['EtatFiche']['ficheNum']),
            'fields' => array('id'),
            'contain' => array('User' => array('id'))
        ));
        $idFiche = $idDestinataire['Fiche']['id'];
        $idDestinataire = $idDestinataire['User']['id'];

        $this->Commentaire->create(array(
            'Commentaire' => array(
                'etat_fiches_id' => $id,
                'content' => $this->request->data['EtatFiche']['content'],
                'user_id' => $this->Auth->user('id'),
                'destinataire_id' => $idDestinataire
            )
        ));
        $this->Commentaire->save();
        $this->Historique->create(array(
            'Historique' => array(
                'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' refuse la fiche',
                'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
            )
        ));
        $this->Historique->save();
        $this->Notifications->add(4, $this->request->data['EtatFiche']['ficheNum'], $idDestinataire);

        $this->Notification->updateAll([
            'Notification.afficher' => false
                ], [
            'Notification.fiche_id' => $idFiche,
            'Notification.user_id' => $idDestinataire,
        ]);

        $this->Session->setFlash('La fiche a été refusée', 'flashsuccess');

        $this->requestAction(array(
            'controller' => 'pannel',
            'action' => 'supprimerLaNotif',
            $idFiche
        ));

        $this->redirect(array(
            'controller' => 'pannel',
            'action' => 'index'
        ));
    }

    /**
     * Gère l'envoie de la demande d'avis
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public function askAvis() {
        $count = $this->EtatFiche->find('count', array(
            'conditions' => array(
                'previous_user_id' => $this->Auth->user('id'),
                'user_id' => $this->request->data['EtatFiche']['destinataire'],
                'previous_etat_id' => $this->request->data['EtatFiche']['etatFiche'],
                'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
            )
        ));
        if ($count > 0) {
            $this->Session->setFlash('La fiche est déjà en attente d\'avis de la part de cet utilisateur', 'flashwarning');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        } else {
            $this->EtatFiche->create(array(
                'EtatFiche' => array(
                    'etat_id' => 6,
                    'previous_user_id' => $this->Auth->user('id'),
                    'user_id' => $this->request->data['EtatFiche']['destinataire'],
                    'previous_etat_id' => $this->request->data['EtatFiche']['etatFiche'],
                    'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                )
            ));
            $this->EtatFiche->save();
            $destinataire = $this->User->find('first', array(
                'conditions' => array(
                    'id' => $this->request->data['EtatFiche']['destinataire']
                )
            ));
            $this->Historique->create(array(
                'Historique' => array(
                    'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' demande l\'avis de ' . $destinataire['User']['prenom'] . ' ' . $destinataire['User']['nom'],
                    'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
                )
            ));
            $this->Historique->save();
            $this->Notifications->add(1, $this->request->data['EtatFiche']['ficheNum'], $this->request->data['EtatFiche']['destinataire']);

            $this->Notification->updateAll([
                'Notification.afficher' => false
                    ], [
                'Notification.fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
                'Notification.user_id' => $this->request->data['EtatFiche']['destinataire'],
            ]);

            $this->Session->setFlash('La fiche a été envoyée pour avis', 'flashsuccess');

            $this->requestAction(array(
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $this->request->data['EtatFiche']['ficheNum']
            ));

            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
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
        $idEncoursAnswer = $this->EtatFiche->find('first', array(
            'conditions' => array('EtatFiche.id' => $this->request->data['EtatFiche']['etatFiche']),
            'fields' => 'previous_etat_id'
        ));
        $id = $idEncoursAnswer['EtatFiche']['previous_etat_id'];
        $this->EtatFiche->delete($this->request->data['EtatFiche']['etatFiche']);

        $this->Commentaire->create(array(
            'Commentaire' => array(
                'etat_fiches_id' => $id,
                'content' => $this->request->data['EtatFiche']['commentaireRepondre'],
                'user_id' => $this->Auth->user('id'),
                'destinataire_id' => $this->request->data['EtatFiche']['previousUserId']
            )
        ));
        $this->Commentaire->save();

        $destinataire = $this->User->find('first', array(
            'conditions' => array(
                'id' => $this->request->data['EtatFiche']['previousUserId']
            )
        ));
        $this->Historique->create(array(
            'Historique' => array(
                'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' répond à la demande d\'avis de ' . $destinataire['User']['prenom'] . ' ' . $destinataire['User']['nom'],
                'fiche_id' => $this->request->data['EtatFiche']['ficheNum']
            )
        ));
        $this->Historique->save();
        $this->Notifications->add(5, $this->request->data['EtatFiche']['ficheNum'], $this->request->data['EtatFiche']['previousUserId']);

        $this->Notification->updateAll([
            'Notification.afficher' => false
                ], [
            'Notification.fiche_id' => $this->request->data['EtatFiche']['ficheNum'],
            'Notification.user_id' => $this->request->data['EtatFiche']['previousUserId'],
        ]);

        $this->Session->setFlash('Le commentaire a été ajouté', 'flashsuccess');

        $this->requestAction(array(
            'controller' => 'pannel',
            'action' => 'supprimerLaNotif',
            $this->request->data['EtatFiche']['ficheNum']
        ));

        $this->redirect(array(
            'controller' => 'pannel',
            'action' => 'index'
        ));
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
            $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        } else {
            $this->requestAction(array(
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $id
            ));

            $this->EtatFiche->updateAll(array(
                'actif' => false
                    ), array(
                'fiche_id' => $id
                    )
            );

            $this->EtatFiche->save(array(
                'EtatFiche' => array(
                    'fiche_id' => $id,
                    'etat_id' => 8,
                    'previous_user_id' => $this->Auth->user('id'),
                    'user_id' => $this->Auth->user('id')
                )
            ));

            if ($this->EtatFiche->save()) {
                $this->Historique->create(array(
                    'Historique' => array(
                        'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' replace la fiche en rédaction',
                        'fiche_id' => $id
                    )
                ));
                $this->Historique->save();
                $this->Session->setFlash('La fiche a bien été replacée en rédaction', 'flashsuccess');
                $this->redirect(array(
                    'controller' => 'pannel',
                    'action' => 'index'
                ));
            }
            //}
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
        $this->EtatFiche->updateAll(array(
            'actif' => false
                ), array(
            'fiche_id' => $id
                )
        );
        
        $cil = $this->Organisation->find('first', array(
            'conditions' => array('Organisation.id' => $this->Session->read('Organisation.id')),
            'fields' => array('cil')
        ));
        if ($cil['Organisation']['cil'] != null) {
            $idEncoursValid = $this->EtatFiche->find('first', array(
                'conditions' => array(
                    'EtatFiche.fiche_id' => $id,
                    'EtatFiche.etat_id' => 2
                ),
                'fields' => 'id'
            ));
            
            if (!empty($idEncoursValid)) {
                $etatId = $idEncoursValid['EtatFiche']['id'];
                $this->EtatFiche->id = $etatId;
                $this->EtatFiche->saveField('etat_id', 3);
            }
            
            $this->EtatFiche->create(array(
                'EtatFiche' => array(
                    'fiche_id' => $id,
                    'etat_id' => 2,
                    'previous_user_id' => $this->Auth->user('id'),
                    'user_id' => $cil['Organisation']['cil']
                )
            ));
            
            $this->EtatFiche->save();
            
            $this->Historique->create(array(
                'Historique' => array(
                    'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' envoie la fiche pour validation du CIL',
                    'fiche_id' => $id
                )
            ));
            
            $this->Historique->save();
            
            $this->Notifications->add(2, $id, $cil['Organisation']['cil']);

            $this->Notification->updateAll([
                'Notification.afficher' => false
                    ], [
                'Notification.fiche_id' => $id,
                'Notification.user_id' => $cil['Organisation']['cil']
            ]);

            $this->Session->setFlash('La fiche a été envoyée au CIL', 'flashsuccess');

            $this->requestAction(array(
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $id
            ));

            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        } else {
            $this->Session->setFlash('Aucun CIL n\'a été défini pour cette entité', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
    }

    /**
     * Gère la validation du CIL
     * 
     * @param int $id
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public function insertRegistre($id) {
        $idEncoursValid = $this->EtatFiche->find('first', array(
            'conditions' => array(
                'EtatFiche.fiche_id' => $id,
                'EtatFiche.etat_id' => 2
            ),
            'fields' => array('id'),
            'contain' => array('Fiche' => array('user_id'))
        ));
        if (!empty($idEncoursValid)) {
            $id_etat = $idEncoursValid['EtatFiche']['id'];
            $this->EtatFiche->id = $id_etat;
            $this->EtatFiche->saveField('etat_id', 5);
            $this->Notifications->add(3, $id, $idEncoursValid['Fiche']['user_id']);

            $this->Notification->updateAll([
                'Notification.afficher' => false
                    ], [
                'Notification.fiche_id' => $idEncoursValid['Fiche']['id'],
                'Notification.user_id' => $idEncoursValid['Fiche']['user_id'],
            ]);

            $this->Session->setFlash('La fiche a été enregistrée dans le registre', 'flashsuccess');

            $this->requestAction(array(
                'controller' => 'pannel',
                'action' => 'supprimerLaNotif',
                $idEncoursValid['Fiche']['id']
            ));

            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
            $this->Historique->create(array(
                'Historique' => array(
                    'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' valide la fiche et l\'insère au registre',
                    'fiche_id' => $id
                )
            ));
        } else {
            $this->Session->setFlash('Cette fiche n\'est pas en cours de validation', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
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
    public function archive($id) {
        if (!$id) {
            $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
            $this->redirect(array(
                'controller' => 'registres',
                'action' => 'index'
            ));
        } else {
            $this->EtatFiche->deleteAll(array('EtatFiche.fiche_id' => $id), false);
            $this->EtatFiche->create(array(
                'EtatFiche' => array(
                    'fiche_id' => $id,
                    'etat_id' => 7,
                    'previous_user_id' => $this->Auth->user('id'),
                    'user_id' => $this->Auth->user('id')
                )
            ));
            $this->EtatFiche->save();
            $this->Historique->create(array(
                'Historique' => array(
                    'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' archive la fiche',
                    'fiche_id' => $id
                )
            ));

            $this->Session->setFlash('La fiche a été archivée', 'flashsuccess');
            $this->redirect(array(
                'controller' => 'Fiches',
                'action' => 'genereFusion',
                $id,
                true
            ));
        }
    }

}
