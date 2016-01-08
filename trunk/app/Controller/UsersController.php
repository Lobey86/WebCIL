<?php

/**
 * UsersController
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
class UsersController extends AppController {

    public $uses = [
        'User',
        'Organisation',
        'Role',
        'ListeDroit',
        'OrganisationUser',
        'Droit',
        'RoleDroit',
        'OrganisationUserRole',
        'Service',
        'OrganisationUserService',
        'Admin'
    ];
    public $helpers = ['Controls'];

    /**
     * Récupère le beforefilter de AppController (login)
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function beforeFilter() {
        parent::beforeFilter();
    }

    /**
     * Index des utilisateurs. Liste les utilisateurs enregistrés
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function index() {
        $this->set('title', 'Liste des utilisateurs');
        if ($this->Droits->authorized([
                    '8',
                    '9',
                    '10'
                ])
        ) {
            if ($this->Droits->isSu()) {
                $conditions = [];
                if ($this->request->is('post')) {
                    if ($this->request->data['users']['organisation'] != '') {
                        $conditions['OrganisationUser.organisation_id'] = $this->request->data['users']['organisation'];
                    }
                    if ($this->request->data['users']['nom'] != '') {
                        $conditions['OrganisationUser.user_id'] = $this->request->data['users']['nom'];
                        $users = $this->OrganisationUser->find('all', [
                            'conditions' => $conditions,
                            'contain' => [
                                'User' => [
                                    'id',
                                    'username',
                                    'nom',
                                    'prenom',
                                    'created'
                                ]
                            ],
                            'limit' => 1
                        ]);
                    } else {
                        $users = $this->OrganisationUser->find('all', [
                            'conditions' => $conditions,
                            'contain' => [
                                'User' => [
                                    'id',
                                    'username',
                                    'nom',
                                    'prenom',
                                    'created'
                                ]
                            ]
                        ]);
                    }
                } else {
                    $users = $this->OrganisationUser->find('all', [
                        'conditions' => [
                            'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id')
                        ],
                        'contain' => [
                            'User' => [
                                'id',
                                'username',
                                'nom',
                                'prenom',
                                'created'
                            ]
                        ]
                    ]);
                }
            } else {
                $users = $this->OrganisationUser->find('all', [
                    'conditions' => [
                        'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id'),
                        'OrganisationUser.user_id !=' => 1
                    ],
                    'contain' => [
                        'User' => [
                            'id',
                            'username',
                            'nom',
                            'prenom',
                            'created'
                        ]
                    ]
                ]);
            }
            foreach ($users as $key => $value) {
                $orgausers = $this->OrganisationUser->find('all', ['conditions' => ['OrganisationUser.user_id' => $value['OrganisationUser']['user_id']]]);
                foreach ($orgausers as $clef => $valeur) {
                    $orga = $this->Organisation->find('first', [
                        'conditions' => ['Organisation.id' => $valeur['OrganisationUser']['organisation_id']],
                        'fields' => ['raisonsociale']
                    ]);
                    $users[$key]['Organisations'][] = $orga;
                }
            }
            $this->set('users', $users);
            $orgas = $this->Organisation->find('all', [
                'fields' => [
                    'Organisation.raisonsociale',
                    'id'
                ]
            ]);
            $organisations = [];
            foreach ($orgas as $value) {
                $organisations[$value['Organisation']['id']] = $value['Organisation']['raisonsociale'];
            }
            $this->set('orgas', $organisations);

            $utils = $this->User->find('all', [
                'fields' => [
                    'User.nom',
                    'User.prenom',
                    'User.id'
                ]
            ]);
            $utilisateurs = [];
            foreach ($utils as $value) {
                $utilisateurs[$value['User']['id']] = $value['User']['prenom'] . ' ' . $value['User']['nom'];
            }
            $this->set('utilisateurs', $utilisateurs);
        } else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Affiche les informations sur un utilisateur
     * 
     * @param int|null $id
     * @throws NotFoundException
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function view($id = null) {
        $this->set('title', 'Voir l\'utilisateur');
        if ($this->Droits->authorized([
                    '8',
                    '9',
                    '10'
                ])
        ) {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException('User invalide');
            }
            $this->set('user', $this->User->read(NULL, $id));
        } else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Affiche le formulaire d'ajout d'utilisateur, ou enregistre l'utilisateur et ses droits
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function add() {
        $this->set('title', 'Ajouter un utilisateur');
        if ($this->Droits->authorized(8) || $this->Droits->isSu()) {
            $this->set('idUser', $this->Auth->user('id'));
            if ($this->request->is('post')) {
                $this->User->create($this->request->data);
                if ($this->User->save()) {
                    $userId = $this->User->getInsertID();
                    foreach ($this->request->data['Organisation']['Organisation_id'] as $value) {
                        $this->OrganisationUser->create([
                            'user_id' => $userId,
                            'organisation_id' => $value
                        ]);
                        $this->OrganisationUser->save();
                        $organisationUserId = $this->OrganisationUser->getInsertID();
                        if (isset($this->request->data['Service'][$value]) && $this->request->data['Service'][$value] != NULL) {
                            $this->OrganisationUserService->create([
                                'organisation_user_id' => $organisationUserId,
                                'service_id' => $this->request->data['Service'][$value]
                            ]);
                            $this->OrganisationUserService->save();
                        }
                        if (!empty($this->request->data['Role'][$value])) {
                            foreach ($this->request->data['Role'][$value] as $key => $donnee) {
                                if ($donnee) {
                                    $this->OrganisationUserRole->create([
                                        'organisation_user_id' => $organisationUserId,
                                        'role_id' => $donnee
                                    ]);
                                    $this->OrganisationUserRole->save();
                                    $droits = $this->RoleDroit->find('all', ['conditions' => ['role_id' => $donnee]]);
                                    foreach ($droits as $val) {
                                        if (empty($this->Droit->find('first', [
                                                            'conditions' => [
                                                                'organisation_user_id' => $organisationUserId,
                                                                'liste_droit_id' => $val['RoleDroit']['liste_droit_id']
                                                            ]
                                                ]))
                                        ) {
                                            $this->Droit->create([
                                                'organisation_user_id' => $organisationUserId,
                                                'liste_droit_id' => $val['RoleDroit']['liste_droit_id']
                                            ]);
                                            $this->Droit->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $this->Session->setFlash('L\'utilisateur a été sauvegardé', 'flashsuccess');
                    $this->redirect([
                        'controller' => 'users',
                        'action' => 'index'
                    ]);
                } else {
                    $table = $this->_createTable();
                    $this->set('tableau', $table['tableau']);
                    $this->set('listedroits', $table['listedroits']);
                }
            } else {
                $table = $this->_createTable();
                $this->set('tableau', $table['tableau']);
                $this->set('listedroits', $table['listedroits']);
                $listeServices = $this->Service->find('all', [
                    'fields' => [
                        'id',
                        'libelle',
                        'organisation_id'
                    ]
                ]);
                $listserv = [];
                foreach ($listeServices as $key => $value) {
                    $listserv[$value['Service']['organisation_id']][$value['Service']['id']] = $value['Service']['libelle'];
                }

                $this->set('listeservices', $listserv);
            }
        } else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Modification d'un utilisateur
     * 
     * @param int|null $id
     * @throws NotFoundException
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function edit($id = null) {
        $this->set('title', 'Editer un utilisateur');
        if ($this->Droits->authorized(9) || $id == $this->Auth->user('id')) {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException('User Invalide');
            }
            if ($this->request->is('post') || $this->request->is('put')) {
                if ($this->request->data['User']['new_password'] == $this->request->data['User']['new_passwd']) {
                    if ($this->request->data['User']['new_password'] != '') {
                        $this->request->data['User']['password'] = $this->request->data['User']['new_password'];
                    }
                    if ($this->User->save($this->request->data)) {
                        if ($this->Droits->isSu()) {
                            $orgas = $this->Organisation->find('all');
                        } else {
                            $orgas = $this->Organisation->find('all', ['conditions' => ['id' => $this->Session->read('Organisation.id')]]);
                        }

                        foreach ($orgas as $value) {

                            if (!in_array($value['Organisation']['id'], $this->request->data['Organisation']['Organisation_id'])) {
                                $id_user = $this->OrganisationUser->find('first', ['conditions' => ['user_id' => $id,
                                        'organisation_id' => $value['Organisation']['id']]]);
                                $this->OrganisationUser->deleteAll([
                                    'user_id' => $id,
                                    'organisation_id' => $value['Organisation']['id']
                                ]);
                                $this->OrganisationUserRole->deleteAll([
                                    'organisation_user_id' => $id_user
                                ]);
                                $this->Droit->deleteAll([
                                    'organisation_user_id' => $id_user
                                ]);
                            } else {
                                $count = $this->OrganisationUser->find('count', [
                                    'conditions' => [
                                        'organisation_id' => $value['Organisation']['id'],
                                        'user_id' => $id
                                    ]
                                ]);


                                if ($count == 0) {
                                    $this->OrganisationUser->create([
                                        'user_id' => $id,
                                        'organisation_id' => $value['Organisation']['id']
                                    ]);
                                    $this->OrganisationUser->save();
                                    $organisationUserId = $this->OrganisationUser->getInsertID();
                                } else {
                                    $id_orga = $this->OrganisationUser->find('first', [
                                        'conditions' => [
                                            'organisation_id' => $value['Organisation']['id'],
                                            'user_id' => $id
                                        ]
                                    ]);

                                    $organisationUserId = $id_orga['OrganisationUser']['id'];
                                }


                                if (!empty($this->request->data['Role']['role_ida'][$value['Organisation']['id']])) {
                                    $this->OrganisationUserRole->deleteAll(['organisation_user_id' => $organisationUserId]);

                                    foreach ($this->request->data['Role']['role_ida'][$value['Organisation']['id']] as $key => $donnee) {
                                        if ($this->Role->find('count', [
                                                    'conditions' => [
                                                        'Role.organisation_id' => $value['Organisation']['id'],
                                                        'Role.id' => $donnee
                                                    ]
                                                ]) > 0
                                        ) {
                                            $this->OrganisationUserRole->create([
                                                'organisation_user_id' => $organisationUserId,
                                                'role_id' => $donnee
                                            ]);
                                            $this->OrganisationUserRole->save();
                                            $droits = $this->RoleDroit->find('all', ['conditions' => ['role_id' => $donnee]]);
                                            foreach ($droits as $val) {
                                                if (empty($this->Droit->find('first', [
                                                                    'conditions' => [
                                                                        'organisation_user_id' => $organisationUserId,
                                                                        'liste_droit_id' => $val['RoleDroit']['liste_droit_id']
                                                                    ]
                                                        ]))
                                                ) {
                                                    $this->Droit->create([
                                                        'organisation_user_id' => $organisationUserId,
                                                        'liste_droit_id' => $val['RoleDroit']['liste_droit_id']
                                                    ]);
                                                    $this->Droit->save();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $this->Session->setFlash('L\'utilisateur a été sauvegardé', "flashsuccess");
                        $this->redirect([
                            'controller' => 'users',
                            'action' => 'index'
                        ]);
                    } else {
                        $this->Session->setFlash('L\'utilisateur n\'a pas été sauvegardé. Merci de réessayer.', "flasherror");
                        $this->redirect([
                            'controller' => 'users',
                            'action' => 'index'
                        ]);
                    }
                } else {
                    $this->Session->setFlash('L\'utilisateur n\'a pas été sauvegardé. Merci de réessayer.', "flasherror");
                    $this->redirect([
                        'controller' => 'users',
                        'action' => 'index'
                    ]);
                }
            } else {
                $table = $this->_createTable($id);
                $this->set('tableau', $table['tableau']);
                $this->set('listedroits', $table['listedroits']);
            }
        } else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Suppression d'un utilisateur
     * 
     * @param int|null $id
     * @throws NotFoundException
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function delete($id = null) {
        if ($this->Droits->authorized(10)) {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException('User invalide');
            }
            if ($id != 1) {
                if ($this->OrganisationUser->deleteAll([
                            'user_id' => $id
                        ])
                ) {
                    if ($this->Droits->isCil()) {
                        $this->Organisation->updateAll(['Organisation.cil' => NULL], ['Organisation.cil' => $id]);
                    }
                    if ($this->User->delete()) {
                        $this->Session->setFlash('Utilisateur supprimé', 'flashsuccess');
                        $this->redirect(['action' => 'index']);
                    }
                }
            }
            $this->Session->setFlash('L\'utilisateur n\'a pas été supprimé', 'flasherror');
            $this->redirect(['action' => 'index']);
        } else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Page de login
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function login() {
        $this->layout = 'login';
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->_cleanSession();
                $su = $this->Admin->find('count', ['conditions' => ['user_id' => $this->Auth->user('id')]]);
                if ($su) {
                    $this->Session->write('Su', TRUE);
                } else {
                    $this->Session->write('Su', FALSE);
                }
                $service = $this->OrganisationUser->find('first', [
                    'conditions' => ['user_id' => $this->Auth->user('id')],
                    'contain' => ['OrganisationUserService' => ['Service']]
                ]);
                $this->Session->write('User.service', $service['OrganisationUserService']['Service']['libelle']);

                $this->redirect([
                    'controller' => 'organisations',
                    'action' => 'change'
                ]);
            } else {
                $this->Session->setFlash('Nom d\'utilisateur ou mot de passe invalide, réessayer', 'flasherror');
            }
        } else {
            if ($this->Session->check('Auth.User.id')) {
                $this->redirect([
                    'controller' => 'pannel',
                    'action' => 'index'
                ]);
            }
        }
    }

    /**
     * Page de deconnexion
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function logout() {
        $this->_cleanSession();
        $this->redirect($this->Auth->logout());
    }

    /**
     * Fonction de suppression du cache (sinon pose des problemes lors du login)
     * 
     * @access protected
     * @created 17/06/2015
     * @version V0.9.0
     */
    protected function _cleanSession() {
        $this->Session->delete('Organisation');
    }

    /**
     * Fonction de création du tableau de droits pour le add and edit user
     * 
     * @param int|null $id
     * @return type
     * 
     * @access protected
     * @created 17/06/2015
     * @version V0.9.0
     */
    protected function _createTable($id = null) {
        $tableau = ['Organisation' => []];
        if ($this->Droits->isSu()) {
            $organisations = $this->Organisation->find('all');
        } else {
            $organisations = $this->Organisation->find('all', ['conditions' => ['id' => $this->Session->read('Organisation.id')]]);
        }
        foreach ($organisations as $key => $value) {
            $tableau['Organisation'][$value['Organisation']['id']]['infos'] = [
                'raisonsociale' => $value['Organisation']['raisonsociale'],
                'id' => $value['Organisation']['id']
            ];
            $roles = $this->Role->find('all', [
                'recursive' => -1,
                'conditions' => ['organisation_id' => $value['Organisation']['id']]
            ]);
            $tableau['Organisation'][$value['Organisation']['id']]['roles'] = [];
            foreach ($roles as $clef => $valeur) {
                $tableau['Organisation'][$value['Organisation']['id']]['roles'][$valeur['Role']['id']] = [
                    'infos' => [
                        'id' => $valeur['Role']['id'],
                        'libelle' => $valeur['Role']['libelle'],
                        'organisation_id' => $valeur['Role']['organisation_id']
                    ]
                ];
                $droitsRole = $this->RoleDroit->find('all', [
                    'recursive' => -1,
                    'conditions' => ['role_id' => $valeur['Role']['id']]
                ]);
                foreach ($droitsRole as $k => $val) {
                    $tableau['Organisation'][$value['Organisation']['id']]['roles'][$valeur['Role']['id']]['droits'][$val['RoleDroit']['id']] = $val['RoleDroit'];
                }
            }
        }
        if ($id != NULL) {
            $this->set("userid", $id);

            $organisationUser = $this->OrganisationUser->find('all', [
                'conditions' => ['user_id' => $id],
                'contain' => ['Droit']
            ]);

            foreach ($organisationUser as $key => $value) {
                $tableau['Orgas'][] = $value['OrganisationUser']['organisation_id'];

                $userroles = $this->OrganisationUserRole->find('all', ['conditions' => ['OrganisationUserRole.organisation_user_id' => $value['OrganisationUser']['id']]]);
                foreach ($userroles as $cle => $val) {
                    $tableau['UserRoles'][] = $val['OrganisationUserRole']['role_id'];
                }
                foreach ($value['Droit'] as $clef => $valeur) {
                    $tableau['User'][$value['OrganisationUser']['organisation_id']][] = $valeur['liste_droit_id'];
                }
            }
            $this->request->data = $this->User->read(NULL, $id);
            unset($this->request->data['User']['password']);
        }
        $listedroits = $this->ListeDroit->find('all', ['recursive' => -1]);
        $ld = [];
        foreach ($listedroits as $c => $v) {
            $ld[$v['ListeDroit']['value']] = $v['ListeDroit']['libelle'];
        }
        $retour = [
            'tableau' => $tableau,
            'listedroits' => $ld
        ];

        return $retour;
    }

}
