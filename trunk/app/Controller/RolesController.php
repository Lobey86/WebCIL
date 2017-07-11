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
 * @since       webcil V1.0.0
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 * @version     V1.0.0
 * @package     App.Controller
 */
App::uses('ListeDroit', 'Model');

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
     * @version V1.0.0
     */
    public function index() {
        if (true !== $this->Droits->authorized([ListeDroit::CREER_PROFIL, ListeDroit::MODIFIER_PROFIL, ListeDroit::SUPPRIMER_PROFIL])) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->set('title', __d('role', 'role.titreListeProfil'));
               
        $roles = $this->Role->find('all', [
            'fields' => array_merge(
                $this->Role->fields(),
                array(
                    $this->Role->vfLinkedUser()
                )
            ),
            'conditions' => [
                'organisation_id' => $this->Session->read('Organisation.id')
            ]
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
    }

    /**
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function add() {
        if (true !== ($this->Droits->authorized(ListeDroit::CREER_PROFIL) || $this->Droits->isSu())) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }
        
        $this->set('title', __d('role', 'role.titreAjouterProfil'));
        
        if ($this->request->is('post')) {
            $this->request->data['Role']['organisation_id'] = $this->Session->read('Organisation.id');
            
            $success = true;
            $this->Role->begin();

            $this->Role->create($this->request->data);
            $success = $success && false !== $this->Role->save();

            if ($success == true) {
                foreach ($this->request->data['Droits'] as $key => $donnee) {
                    if ($success == true) {
                        if ($donnee) {
                            $this->RoleDroit->create([
                                'role_id' => $this->Role->getInsertID(),
                                'liste_droit_id' => $key
                            ]);

                            $success = $success && false !== $this->RoleDroit->save();
                        }
                    }
                }
            }

            if ($success == true) {
                $this->Role->commit();
                $this->Session->setFlash(__d('profil', 'profil.flashsuccessProfilEnregistrer'), 'flashsuccess');
                $this->redirect([
                    'controller' => 'roles',
                    'action' => 'index'
                ]);
            } else {
                $this->Role->rollback();
                $this->Session->setFlash(__d('profil', 'profil.flasherrorErreurEnregistrementProfil'), 'flasherror');
            }
        } else {
            $this->set('listedroit', $this->ListeDroit->find('all', [
                        'conditions' => [
                            'NOT' => ['ListeDroit.id' => ['11']]
                        ],
                        'order' => 'id'
            ]));
        }
    }

    /**
     * @param int $id
     * @throws NotFoundException
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function show($id) {
        $this->set('title', 'Voir un profil');

        if (($this->Droits->authorized(ListeDroit::CREER_PROFIL) && $this->Droits->currentOrgaRole($id)) || $this->Droits->isSu()) {
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
     * @version V1.0.0
     */
    public function edit($id = null) {
        if (($this->Droits->authorized(ListeDroit::MODIFIER_PROFIL) && $this->Droits->currentOrgaRole($id)) || $this->Droits->isSu()) {
            $this->set('title', __d('role', 'role.titreEditerProfil'));

            if (!$id) {
                throw new NotFoundException('Ce profil n\'existe pas');
            }

            $role = $this->Role->findById($id);
            
            if (!$role) {
                throw new NotFoundException('Ce profil n\'existe pas');
            }

            if ($this->request->is(['post', 'put'])) {
                $this->Role->id = $id;

                $success = true;
                $this->Role->begin();

                $success = $success && false !== $this->Role->save($this->request->data);

                if ($success == true) {
                    $success = $success && false !== $this->RoleDroit->deleteAll([
                                'role_id' => $id
                                    ], false
                    );

                    if ($success == true) {
                        foreach ($this->request->data['Droits'] as $key => $donnee) {
                            if ($success == true) {
                                if ($donnee) {
                                    $this->RoleDroit->create([
                                        'role_id' => $id,
                                        'liste_droit_id' => $key
                                    ]);
                                    $success = $success && false !== $this->RoleDroit->save();
                                }
                            }
                        }
                    }
                }

                if ($success == true) {
                    $this->Role->commit();
                    $this->Session->setFlash(__d('role', 'role.flashsuccessProfilModifier'), 'flashsuccess');
                } else {
                    $this->Role->rollback();
                    $this->Session->setFlash(__d('role', 'role.flasherrorErreurModificationProfil'), 'flasherror');
                }

                $this->redirect([
                    'controller' => 'Roles',
                    'action' => 'index'
                ]);
            } else {
                $this->set('listedroit', $this->ListeDroit->find('all', [
                    'conditions' => [
                        'NOT' => [
                            'ListeDroit.id' => [
                                ListeDroit::INSERER_TRAITEMENT_REGISTRE,
                                ListeDroit::MODIFIER_TRAITEMENT_REGISTRE,
                                ListeDroit::CREER_ORGANISATION
                            ]
                        ]
                    ]
                ]));
                
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
     * @version V1.0.0
     */
    public function delete($id) {
        if (true !== ($this->Droits->authorized(ListeDroit::SUPPRIMER_PROFIL) && $this->Droits->currentOrgaRole($id) || $this->Droits->isSu())) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }
        
        $role = $this->Role->find(
            'first',
            [
                'fields' => array(
                    'Role.id',
                    $this->Role->vfLinkedUser()
                ),
                'conditions' => [
                    'Role.id' => $id
                ]
            ]
        );

        if (true === empty($role)) {
            throw new NotFoundException(__d('role', 'role.exceptionProfilInexistant'));
        }

        if (true === $role['Role']['linked_user']) {
            throw new RuntimeException(__d('role', 'role.exceptionProfilNonSuppressible'), 500);
        }

        $this->Role->id = $id;

        $success = true;
        $this->Role->begin();

        $success = $success && false !== $this->Role->delete();

        if ($success == true) {
            $this->Role->commit();
            $this->Session->setFlash(__d('role', 'role.flashsuccessProfilSupprimer'), 'flashsuccess');
        } else {
            $this->Role->rollback();
            $this->Session->setFlash(__d('role', 'role.flasherrorErreurSupprimerProfil'), 'flasherror');
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Réattribuer des roles aux utilisateurs après modification d'un profil
     * FIXME ne devrait pas avoir besoin de reforcer les droits
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
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
                $success = true;
                $this->Droit->begin();

                // Pour chaque utilisateur utilisant le profil en question
                foreach ($usersRole as $userRole) {
                    // Suppression de tout les droits de l'utilisateur
                    $success = $success && false !== $this->Droit->deleteAll(
                                    [
                                'organisation_user_id' => $userRole['OrganisationUserRole']['organisation_user_id']
                                    ], false
                    );

                    if ($success == true) {
                        // Pour chaque droit du profil
                        foreach ($rolesDroit as $roleDroit) {
                            if ($success == true) {
                                $this->Droit->clear();

                                // Création dans la table Droit chaque droit du profil en fonction de l'id de l'organisation de l'utilisateur
                                $this->Droit->create([
                                    'organisation_user_id' => $userRole['OrganisationUserRole']['organisation_user_id'],
                                    'liste_droit_id' => $roleDroit['RoleDroit']['liste_droit_id']
                                ]);

                                //Sauvegarde en base de données des droits
                                $success = $success && false !== $this->Droit->save();
                            }
                        }
                    }
                }

                if ($success == true) {
                    // L'opération c'est bien passé
                    $this->Droit->commit();
                    $this->Session->setFlash(__d('role', 'role.flashsuccessProfilReattribuer'), 'flashsuccess');
                } else {
                    $this->Session->setFlash(__d('role', 'role.flasherrorErreurReattributionRole'), 'flasherror');
                    $this->Droit->rollback();
                }
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
