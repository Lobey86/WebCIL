<?php

/**
 * RolesController
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
class RolesController extends AppController {

    public $uses = [
        'Role',
        'ListeDroit',
        'RoleDroit',
        'OrganisationUserRole',
    ];

    /**
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function index() {
        $this->set('title', __d('role', 'role.titreListeProfil'));
        if ($this->Droits->authorized([
                    '13',
                    '14',
                    '15'
                ])
        ) {
            $roles = $this->Role->find('all', [
                'conditions' => ['organisation_id' => $this->Session->read('Organisation.id')]
            ]);
            foreach ($roles as $key => $value) {
                $test = $this->RoleDroit->find('all', [
                    'conditions' => ['role_id' => $value['Role']['id']],
                    'contain' => ['ListeDroit' => ['libelle']],
                    'fields' => 'id'
                ]);
                $roles[$key]['Droits'] = $test;
            }
            $this->set('roles', $roles);
        } else {
            $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function add() {
        $this->set('title', __d('role', 'role.titreAjouterProfil'));
        if ($this->Droits->authorized(13) || $this->Droits->isSu()) {
            if ($this->request->is('post')) {
                $this->Role->create($this->request->data);
                if ($this->Role->save()) {
                    foreach ($this->request->data['Droits'] as $key => $donnee) {
                        if ($donnee) {
                            $this->RoleDroit->create([
                                'role_id' => $this->Role->getInsertID(),
                                'liste_droit_id' => $key
                            ]);
                            $this->RoleDroit->save();
                        }
                    }
                    $this->Session->setFlash(__d('profil', 'profil.flashsuccessProfilEnregistrer'), 'flashsuccess');
                    $this->redirect([
                        'controller' => 'roles',
                        'action' => 'index'
                    ]);
                } else {
                    $this->Session->setFlash(__d('profil', 'profil.flasherrorErreurEnregistrementProfil'), 'flasherror');
                    $this->redirect([
                        'controller' => 'roles',
                        'action' => 'index'
                    ]);
                }
            } else {
                $this->set('listedroit', $this->ListeDroit->find('all', ['conditions' => ['NOT' => ['ListeDroit.id' => ['11']]], 'order' => 'id']));
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
     * @param int $id
     * @throws NotFoundException
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function show($id) {
        $this->set('title', 'Voir un profil');
        if (($this->Droits->authorized(13) && $this->Droits->currentOrgaRole($id)) || $this->Droits->isSu()) {
            if (!$id) {
                throw new NotFoundException('Ce profil n\'existe pas');
            }
            $role = $this->Role->findById($id);
            if (!$role) {
                throw new NotFoundException('Ce profil n\'existe pas');
            }

            $this->set('listedroit', $this->ListeDroit->find('all', ['conditions' => ['NOT' => ['ListeDroit.id' => ['11']]]]));
            $resultat = $this->RoleDroit->find('all', [
                'conditions' => ['role_id' => $id],
                'fields' => 'liste_droit_id'
            ]);
            $result = [];
            foreach ($resultat as $donnee) {
                array_push($result, $donnee['RoleDroit']['liste_droit_id']);
            }
            $this->set('tableDroits', $result);
        }
        if (!$this->request->data) {
            $this->request->data = $role;
        } else {
            $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * @param int|null $id
     * @throws NotFoundException
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function edit($id = null) {
        $this->set('title', __d('role', 'role.titreEditerProfil'));
        if (($this->Droits->authorized(14) && $this->Droits->currentOrgaRole($id)) || $this->Droits->isSu()) {
            if (!$id) {
                throw new NotFoundException('Ce profil n\'existe pas');
            }
            $role = $this->Role->findById($id);
            if (!$role) {
                throw new NotFoundException('Ce profil n\'existe pas');
            }
            if ($this->request->is([
                        'post',
                        'put'
                    ])
            ) {
                $this->Role->id = $id;
                if ($this->Role->save($this->request->data)) {
                    $this->RoleDroit->deleteAll(['role_id' => $id], false);
                    foreach ($this->request->data['Droits'] as $key => $donnee) {
                        if ($donnee) {
                            $this->RoleDroit->create([
                                'role_id' => $id,
                                'liste_droit_id' => $key
                            ]);
                            $this->RoleDroit->save();
                        }
                    }
                    $this->Session->setFlash(__d('role', 'role.flashsuccessProfilModifier'), 'flashsuccess');
                    $this->redirect([
                        'controller' => 'Roles',
                        'action' => 'index'
                    ]);
                }
                $this->Session->setFlash(__d('role', 'role.flasherrorErreurModificationProfil'), 'flasherror');
                $this->redirect([
                    'controller' => 'Roles',
                    'action' => 'index'
                ]);
            } else {
                $this->set('listedroit', $this->ListeDroit->find('all', ['conditions' => ['NOT' => ['ListeDroit.id' => ['11']]]]));
                $resultat = $this->RoleDroit->find('all', [
                    'conditions' => ['role_id' => $id],
                    'fields' => 'liste_droit_id'
                ]);
                $result = [];
                foreach ($resultat as $donnee) {
                    array_push($result, $donnee['RoleDroit']['liste_droit_id']);
                }
                $this->set('tableDroits', $result);
            }
            if (!$this->request->data) {
                $this->request->data = $role;
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
     * Suppression d'un rôle
     * 
     * @param int|null $id
     * @return type
     * @throws NotFoundException
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function delete($id = null) {
        if (($this->Droits->authorized(15) && $this->Droits->currentOrgaRole($id)) || $this->Droits->isSu()) {
            $this->Role->id = $id;
            if (!$this->Role->exists()) {
                throw new NotFoundException(__d('profil', 'profil.exceptionProfilInexistant'));
            }
            if ($this->Role->delete()) {
                $this->Session->setFlash(__d('profil', 'profil.flashsuccessProfilSupprimer'), 'flashsuccess');

                return $this->redirect(['action' => 'index']);
            }
            $this->Session->setFlash(__d('profil', 'profil.flasherrorErreurSupprimerProfil'), 'flasherror');

            return $this->redirect(['action' => 'index']);
        } else {
            $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Réattribuer des roles aux utilisateurs après modification d'un profil
     * FIXME ne devrait pas avoir besoin de reforcer les droits
     * @author Théo GUILLON <theo.guillon@adullact-projet.coop>
     * @access public
     * @created 14/11/2016
     * @version V1.0.0
     */
    public function reattributionRoles($idRole = null) {
        if ($idRole != null) {
            // Récupération des droit en fonction de l'id du profil
            $rolesDroit = $this->RoleDroit->find('all', [
                'conditions' => [
                    'role_id' => $idRole
                ]
            ]);

            // Récupération des utilisateurs qui utilise le profil e, fonction de l'id du profil
            $usersRole = $this->OrganisationUserRole->find('all', [
                'conditions' => [
                    'role_id' => $idRole
                ]
            ]);

            // Si des utilisateur utilise le profil en question
            if (!empty($usersRole)) {
                // Pour chaque utilisateur utilisant le profil en question
                foreach ($usersRole as $userRole) {
                    $this->Droit->begin();
                    // Suppression de tout les droits de l'utilisateur
                    $this->Droit->deleteAll(
                        ['organisation_user_id' => $userRole['OrganisationUserRole']['organisation_user_id']], 
                        false
                    );

                    // Pour chaque droit du profil
                    foreach ($rolesDroit as $roleDroit) {
                        $this->Droit->clear();

                        // Création dans la table Droit chaque droit du profil en fonction de l'id de l'organisation de l'utilisateur
                        $this->Droit->create([
                            'organisation_user_id' => $userRole['OrganisationUserRole']['organisation_user_id'],
                            'liste_droit_id' => $roleDroit['RoleDroit']['liste_droit_id']
                        ]);

                        //Sauvegarde en base de données des droits
                        $result = $this->Droit->save();
                        if (!$result) {
                            $this->Session->setFlash(__d('role', 'role.flasherrorErreurReattributionRole'), 'flasherror');
                            $this->Droit->rollback();
                            $this->redirect([
                                'controller' => 'roles',
                                'action' => 'index'
                            ]);
                        }
                    }
                }

                //L'opération c'est bien passé
                $this->Session->setFlash(__d('role', 'role.flashsuccessProfilReattribuer'), 'flashsuccess');
                $this->Droit->commit();
            } else {
                //Aucun utilisateur n'utilise le profil en question
                $this->Session->setFlash(__d('role', 'role.flashwarningAucunUtilisateurUtiliseProfil'), 'flashwarning');
            }
        } else {
            //L'id du role n'est pas présent
            $this->Session->setFlash(__d('role', 'role.flasherrorErreurReattributionRole'), 'flasherror');
        }

        //Redirection sur la page d'accueil : roles/index
        $this->redirect([
            'controller' => 'roles',
            'action' => 'index'
        ]);
    }

}
