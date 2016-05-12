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
 * @since       webcil v0.9.0
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 * @version     v0.9.0
 * @package     App.Controller
 */
class AdminsController extends AppController {

    public $uses = [
        'Admin',
        'User'
    ];

    /**
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function index() {
        $this->set('title', __d('admin','admin.titreSuperAdministrateur'));
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
     * @version V0.9.0
     */
    public function add() {
        if ($this->request->data['Admin']['user'] != '') {
            $this->Admin->create(['user_id' => $this->request->data['Admin']['user']]);
            $count = $this->Admin->find('count', ['conditions' => ['user_id' => $this->request->data['Admin']['user']]]);
            if (!$count) {
                if ($this->Admin->save()) {
                    $this->Session->setFlash(__d('admin','admin.flashsuccessUserRecuPrivilege'), 'flashsuccess');
                } else {
                    $this->Session->setFlash(__d('admin','admin.flasherrorErreurEnregistrement'), 'flasherror');
                }
            } else {
                $this->Session->setFlash(__d('amin','admin.flasherrorUserDejaAdmin'), 'flasherror');
            }
        } else {
            $this->Session->setFlash(__d('admin','admin.flasherrorUserNonSelectionne'), 'flasherror');
        }
        $this->redirect([
            'controller' => 'admins',
            'action' => 'index'
        ]);
    }

    /**
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function delete($id) {
        if ($this->Admin->exists($id)) {
            if ($this->Admin->delete($id)) {
                $this->Session->setFlash(__d('admin','admin.flashsuccessPrivilegeRetire'), 'flashsuccess');
            } else {
                $this->Session->setFlash(__d('admin','admin.flasherrorErreurEnregistrement'), 'flasherror');
            }
        } else {
            $this->Session->setFlash(__d('admin','admin.flasherrorUserInexistant'), 'flasherror');
        }
        $this->redirect([
            'controller' => 'admins',
            'action' => 'index'
        ]);
    }

}
