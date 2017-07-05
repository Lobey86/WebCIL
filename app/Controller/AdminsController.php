<?php

/**
 * AdminsController
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

class AdminsController extends AppController {

    public $uses = [
        'Admin',
        'User'
    ];

    /**
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function index() {
        if (true !== $this->Droits->isSu()) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->set('title', __d('admin', 'admin.titreSuperAdministrateur'));
        $admins = $this->Admin->find('all', [
            'contain' => [
                'User'
            ]
        ]);
        $this->set('admins', $admins);
        $users = $this->User->find('all', [
            'fields' => [
                'id',
                'prenom',
                'nom'
            ]
        ]);
        $listeuser = [];
        foreach ($users as $value) {
            $listeuser[$value['User']['id']] = $value['User']['prenom'] . ' ' . $value['User']['nom'];
        }
        $this->set('listeusers', $listeuser);
    }

    /**
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
//    public function add() {
//        if ($this->request->data['Admin']['user'] != '') {
//            $success = true;
//            $this->Admin->begin();
//
//            $this->Admin->create([
//                'user_id' => $this->request->data['Admin']['user']
//            ]);
//
//            $count = $this->Admin->find('count', [
//                'conditions' => [
//                    'user_id' => $this->request->data['Admin']['user']
//                ]
//            ]);
//
//            if (!$count) {
//                $success = $success && false !== $this->Admin->save();
//
//                if ($success == true) {
//                    $this->Admin->commit();
//                    $this->Session->setFlash(__d('admin', 'admin.flashsuccessUserRecuPrivilege'), 'flashsuccess');
//                } else {
//                    $this->EtatFiche->rollback();
//                    $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
//                }
//            } else {
//                $this->Session->setFlash(__d('amin', 'admin.flasherrorUserDejaAdmin'), 'flasherror');
//            }
//        } else {
//            $this->Session->setFlash(__d('admin', 'admin.flasherrorUserNonSelectionne'), 'flasherror');
//        }
//
//        $this->redirect([
//            'controller' => 'admins',
//            'action' => 'index'
//        ]);
//    }
    
    /**
     * Affiche le formulaire d'ajout d'utilisateur, ou enregistre l'utilisateur et ses droits
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function add() {
        if (true !== ($this->Droits->isSu())) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->set('title', __d('admin', 'admin.titreAjouterSuperAdmin'));

        $options = [
            'M.' => 'Monsieur',
            'Mme.' => 'Madame'
        ];
        $this->set('options', $options);
        
        if ($this->request->is('post')) {
            if('Cancel' === Hash::get($this->request->data, 'submit')) {
                $this->redirect(array('action' => 'index'));
            }
            
            debug($this->request->data);

            $success = true;
            $this->Admin->begin();

            $this->User->create($this->request->data['Admin']);

            $success = false !== $this->User->save() && $success;
            
            if ($success == true) {
                $userId = $this->User->getInsertID();
                
                $this->Admin->create([
                    'Admin' => [
                        'user_id' => $userId
                    ]
                ]);
                $success = $success && false !== $this->Admin->save();
            }
            
            if ($success == true) {
                $this->Admin->commit();
                $this->Session->setFlash(__d('admin', 'admin.flashsuccessSuperAdminEnregistrer'), 'flashsuccess');
                    
                $this->redirect([
                    'controller' => 'admins',
                    'action' => 'index'
                ]);
            } else {
                $this->Admin->rollback();
                $this->Session->setFlash(__d('admin', 'admin.flasherrorErreurSuperAdminEnregistrer'), 'flasherror');
            }
        }
    }

    /**
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function delete($idAdmin, $idUser) {
        if ($this->Admin->exists($idAdmin)) {
            $success = true;
            $this->Admin->begin();

            $success = $success && false !== $this->Admin->delete($idAdmin);
            
            if ($success == true) {
                $success = $success && false !== $this->User->delete($idUser);
            }

            if ($success == true) {
                $this->Admin->commit();
                $this->Session->setFlash(__d('admin', 'admin.flashsuccessPrivilegeRetire'), 'flashsuccess');
            } else {
                $this->EtatFiche->rollback();
                $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
            }
        } else {
            $this->Session->setFlash(__d('admin', 'admin.flasherrorUserInexistant'), 'flasherror');
        }
        $this->redirect([
            'controller' => 'admins',
            'action' => 'index'
        ]);
    }

}
