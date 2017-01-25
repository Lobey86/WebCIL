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
        'Admin',
        'AuthComponent'
    ];
    public $helpers = [
        'Controls'
    ];

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
        $this->set('title', __d('user', 'user.titreListeUser'));

        // Récupération du CIL de l'entité en cour
        $cil = $this->Organisation->find('first', [
            'conditions' => [
                'id' => $this->Session->read('Organisation.id')
            ],
            'fields' => [
                'cil'
            ]
        ]);
        // On récupére l'id du cil
        $cil = Hash::get($cil, 'Organisation.cil');
        $this->set('cil', $cil);

        if ($this->Droits->authorized([
                    '8',
                    '9',
                    '10'
                ])
        ) {
            $query = [
                'conditions' => [],
                'contain' => [
                    'User' => [
                        'id',
                        'username',
                        'nom',
                        'prenom',
                        'created'
                    ],
//                    'OrganisationUserService' => [
//                        'Service' => ['libelle']
//                    ],
                    'OrganisationUserRole' => [
                        'Role' => ['libelle']
                    ]
                ]
            ];

            if ($this->Droits->isSu()) {
                if ($this->request->is('post')) {
                    if ($this->request->data['users']['organisation'] != '') {
                        $query['conditions']['OrganisationUser.organisation_id'] = $this->request->data['users']['organisation'];
                    }

                    if ($this->request->data['users']['nom'] != '') {
                        $query['conditions']['OrganisationUser.user_id'] = $this->request->data['users']['nom'];
                        $query['limit'] = 1;
                    }
                } else {
                    $query['conditions'] = [
                        'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id')
                    ];
                }
            } else {
                $query['conditions'] = [
                    'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id'),
                    'OrganisationUser.user_id !=' => 1
                ];
            }

            $users = $this->OrganisationUser->find('all', $query);
            foreach ($users as $key => $value) {
                $orgausers = $this->OrganisationUser->find('all', [
                    'conditions' => [
                        'OrganisationUser.user_id' => $value['OrganisationUser']['user_id']
                    ]
                ]);

                foreach ($orgausers as $clef => $valeur) {
                    $orga = $this->Organisation->find('first', [
                        'conditions' => [
                            'Organisation.id' => $valeur['OrganisationUser']['organisation_id']
                        ],
                        'fields' => [
                            'raisonsociale'
                        ]
                    ]);
                    $users[$key]['Organisations'][] = $orga;
                }

                $orgaUserService = $this->OrganisationUserService->find('all', [
                    'conditions' => [
                        'organisation_user_id' => $users[$key]['OrganisationUser']['id']
                    ]
                ]);
                foreach ($orgaUserService as $valeur) {
                    $orgaService = $this->Service->find('first', [
                        'conditions' => [
                            'id' => $valeur['OrganisationUserService']['service_id']
                        ],
                        'fields' => [
                            'libelle'
                        ]
                    ]);
                    $users[$key]['OrganisationUserService'][] = $orgaService;
                }
            }
            $this->set('users', $users);

            //On récupére tout les services de l'entitée utilisé à l'instant T
            $services = $this->Service->find('all', [
                'conditions' => [
                    'organisation_id' => $this->Session->read('Organisation.id')
                ]
            ]);
            $this->set('services', $services);

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

            $this->set(
                    'utilisateurs', Hash::combine($utils, '{n}.User.id', array('%s %s', '{n}.User.prenom', '{n}.User.nom'))
            );
        } else {
            $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');
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

            $this->set('user', $this->User->read(null, $id));
        } else {
            $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');

            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Validation manuelle des champs "Entité" et "Profil": champs obligatoires
     * et occurences multiples.
     */
    protected function _validateEntiteProfil() {
        $success = true;

        $users_organisations = Hash::get($this->request->data, 'Organisation.Organisation_id');
        if (empty($users_organisations) == true) {
            $this->User->Organisation->invalidate('Organisation_id', __d('database', 'Validate::notEmpty'));
            $success = false;
        } else {
            foreach ($users_organisations as $organisation_id) {
                $value = Hash::get($this->request->data, "Role.{$organisation_id}");
                if (empty($value)) {
                    $this->User->Organisation->Role->invalidate($organisation_id, __d('database', 'Validate::notEmpty'));
                    $success = false;
                }
            }
        }

        return $success;
    }

    /**
     * Affiche le formulaire d'ajout d'utilisateur, ou enregistre l'utilisateur et ses droits
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function add() {
        $this->set('title', __d('user', 'user.titreAjouterUser'));

        if ($this->Droits->authorized(8) || $this->Droits->isSu()) {
            $this->set('idUser', $this->Auth->user('id'));

            if ($this->request->is('post')) {
                $success = true;
                $this->User->begin();

                $this->log(var_export($this->request->data, true));
                $this->User->create($this->request->data);

                $success = false !== $this->User->save() && $success;

                $success = false !== $this->_validateEntiteProfil() && $success;

                if ($success == true) {
                    $userId = $this->User->getInsertID();

                    foreach ($this->request->data['Organisation']['Organisation_id'] as $key => $value) {
                        $this->OrganisationUser->create([
                            'user_id' => $userId,
                            'organisation_id' => $value
                        ]);

                        $success = false !== $this->OrganisationUser->save() && $success;

                        $organisationUserId = $this->OrganisationUser->getInsertID();

                        if (isset($this->request->data['Service'][$value]) && $this->request->data['Service'][$value] != null) {
                            foreach ($this->request->data['Service'][$value] as $service) {
                                $this->OrganisationUserService->create([
                                    'organisation_user_id' => $organisationUserId,
                                    'service_id' => $service
                                ]);

                                $success = false !== $this->OrganisationUserService->save() && $success;
                            }
                        }

                        if (!empty($this->request->data['Role'][$value])) {
                            $this->OrganisationUserRole->create([
                                'organisation_user_id' => $organisationUserId,
                                'role_id' => $this->request->data['Role'][$value]
                            ]);
                            $success = false !== $this->OrganisationUserRole->save() && $success;

                            $droits = $this->RoleDroit->find('all', [
                                'conditions' => [
                                    'role_id' => $this->request->data['Role'][$value]
                                ]
                            ]);

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
                                    $success = false !== $this->Droit->save() && $success;
                                }
                            }
                        }
                    }

                    if ($success == true) {
                        $this->User->commit();
                        $this->Session->setFlash(__d('user', 'user.flashsuccessUserEnregistrer'), 'flashsuccess');
                    } else {
                        $this->User->rollback();
                        $this->Session->setFlash(__d('fiche', 'flasherrorErreurContacterAdministrateur'), 'flasherror');
                    }
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
            $this->User->rollback();
            $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }

        $this->set('options', $this->User->enums());
    }

    /**
     * Modification d'un utilisateur en tant qu'administrateur
     * 
     * @param int|null $id
     * @throws NotFoundException
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function edit($id = null) {
        if ($this->Droits->authorized(9) == true || $id == $this->Auth->user('id')) {
            $this->set('title', __d('user', 'user.titreEditerUser'));

            $this->User->id = $id;

            if (!$this->User->exists()) {
                throw new NotFoundException('User Invalide');
            }

            // Récupération de la liste des services de l'utilisateur en question sur l'entité en cours
            $listeServices = $this->Service->find('all', [
//                'conditions' => [
//                    'organisation_id' => $this->Session->read('Organisation.id')
//                ],
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

            if ($this->request->is('post') || $this->request->is('put')) {
                $success = true;
                $this->User->begin();
                
                // Si le nouveau mot de passe = verification du nouveau mot de passe
                if ($this->request->data['User']['new_password'] == $this->request->data['User']['new_passwd']) {
                    // Si le nouveau mot de passe est différent d'une chaine de caractère vide
                    if ($this->request->data['User']['new_password'] != '') {
                        $this->request->data['User']['password'] = $this->request->data['User']['new_password'];
                    }
                    
                    //$success = false !== $this->_validateEntiteProfil() && $success;
                    
                    if ($this->Droits->isSu()) {
                        // SuperAdmin --> Récupération de toutes les informations des entités
                        $orgas = $this->Organisation->find('all');
                    } else {
                        $countUserOrganistations = $this->OrganisationUser->find('count', [
                            'conditions' => [
                                'user_id' => $id
                            ]
                        ]);

                        if ($countUserOrganistations >= 2) {
                            // L'utilisateur est présent dans plusieurs entité
                            // Récupération des l'id des entités ou l'utilisateur appartient
                            $userEntites = $this->OrganisationUser->find('all', [
                                'conditions' => [
                                    'user_id' => $id
                                ],
                                'fields' => [
                                    'id',
                                    'user_id',
                                    'organisation_id'
                                ]
                            ]);

                            // On extrait les id des entités
                            $tableauUserEntites = [];
                            $tableauUserEntites = Hash::extract($userEntites, '{n}.OrganisationUser.organisation_id');

                            //On extrait les id des organisations de l'user
                            $organisationsUserIDs = [];
                            $organisationsUserIDs = Hash::extract($userEntites, '{n}.OrganisationUser.id');

                            // Récupération des infos des entités
                            $orgas = $this->Organisation->find('all', [
                                'conditions' => [
                                    'id' => $tableauUserEntites
                                ]
                            ]);

                            // On supprime l'id de l'organisation dans "$this->request->data" et on le remplace par les id des entités de l'utilisateur
                            $this->request->data = Hash::remove($this->request->data, 'Organisation.Organisation_id');
                            $this->request->data = Hash::insert($this->request->data, 'Organisation.Organisation_id', $tableauUserEntites);

                            // On supprime du tableau l'organisation en cour
                            $tableauUserEntites = Hash::remove($tableauUserEntites, $this->Session->read('Organisation.id'));

                            //ROLES
                            $query = array(
                                'fields' => array(
                                    'OrganisationUser.organisation_id',
                                    'OrganisationUserRole.role_id'
                                ),
                                'joins' => array(
                                    $this->OrganisationUser->join('OrganisationUserRole', array('type' => 'INNER'))
                                ),
                                'conditions' => array(
                                    'OrganisationUser.user_id' => $id,
                                    'OrganisationUser.organisation_id' => $tableauUserEntites
                                ),
                                'contain' => false
                            );
                            $results = $this->OrganisationUser->find('all', $query);

                            $roles = array();
                            foreach ($results as $result) {
                                $organisation_id = $result['OrganisationUser']['organisation_id'];
                                if (false === isset($roles[$organisation_id])) {
                                    $roles[$organisation_id] = array();
                                }
                                $roles[$organisation_id][] = $result['OrganisationUserRole']['role_id'];
                            }
                            $this->request->data['Role']['role_ida'] += $roles;


                            //SERVICE
                            $query = array();
                            $query = array(
                                'fields' => array(
                                    'OrganisationUser.organisation_id',
                                    'OrganisationUserService.service_id'
                                ),
                                'joins' => array(
                                    $this->OrganisationUser->join('OrganisationUserService', array('type' => 'INNER'))
                                ),
                                'conditions' => array(
                                    'OrganisationUser.user_id' => $id,
                                    'OrganisationUser.organisation_id' => $tableauUserEntites
                                ),
                                'contain' => false
                            );
                            $results = $this->OrganisationUser->find('all', $query);

                            $service = array();
                            foreach ($results as $result) {
                                $organisation_id = $result['OrganisationUser']['organisation_id'];
                                if (false === isset($service[$organisation_id])) {
                                    $service[$organisation_id] = array();
                                }
                                $service[$organisation_id][] = $result['OrganisationUserService']['service_id'];
                            }
                            $this->request->data['Service'] += $service;
                        } else {
                            // L'utilisateur est présent que dans une seul entité
                            // Récupération des informations de l'entité en cours
                            $orgas = $this->Organisation->find('all', [
                                'conditions' => [
                                    'id' => $this->Session->read('Organisation.id')
                                ]
                            ]);
                        }
                    }

                    
                    $success = false !== $this->User->save($this->request->data) && $success;
                    
                    if ($success == true) {
                        foreach ($orgas as $value) {
                            if (!in_array($value['Organisation']['id'], $this->request->data['Organisation']['Organisation_id'])) {
                                $id_user = $this->OrganisationUser->find('first', [
                                    'conditions' => [
                                        'user_id' => $id,
                                        'organisation_id' => $value['Organisation']['id']
                                    ]
                                ]);

                                /* On supprime dans la table "organisations_users" 
                                 * en base de données l'utilisateur en question 
                                 * et de l'organisation en cours.
                                 */
                                $success = $success && false !== $this->OrganisationUser->deleteAll([
                                    'user_id' => $id,
                                    'organisation_id' => $value['Organisation']['id']
                                ]);
                                
                                /* On supprime dans la table 
                                 * "organisation_user_roles" en base de données
                                 *  le role de l'utilisateur en question.
                                 */
                                $success = $success && false !== $this->OrganisationUserRole->deleteAll([
                                    'organisation_user_id' => $id_user
                                ]);

                                /* On supprime dans la table "droits" en base 
                                 * de données les droits de l'utilisateur en
                                 * question en fonction de son id de l'organisation
                                 */
                                $success = $success && false !== $this->Droit->deleteAll([
                                    'organisation_user_id' => $id_user
                                ]);

                                /* On supprime dans la table 
                                 * "organisation_user_services" en base de données
                                 * les services de l'utilisateur en question 
                                 * en fonction de son id de l'organisation
                                 */
                                $success = $success && false !== $this->OrganisationUserService->deleteAll([
                                    'organisation_user_id' => $id_user
                                ]);

                                if($success == false) {
                                    debug('lala');die;
                                    $this->User->rollback();
                                    $this->Session->setFlash(__d('fiche', 'flasherrorErreurContacterAdministrateur'), 'flasherror');

                                    $this->redirect([
                                        'controller' => 'users',
                                        'action' => 'index'
                                    ]);
                                }
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
                                    $success = false !== $this->OrganisationUser->save() && $success;
                                    
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

                                if($success == true){
                                    if (!empty($this->request->data['Role']['role_ida'][$value['Organisation']['id']])) {
                                        $success = false !== $this->OrganisationUserRole->deleteAll([
                                            'organisation_user_id' => $organisationUserId
                                        ]) && $success;

                                        if($success == true){
                                            debug($this->request->data['Role']['role_ida'][$value['Organisation']['id']]);
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
                                                    $success = false !== $this->OrganisationUserRole->save() && $success;

                                                    if ($success == true){
                                                        $droits = $this->RoleDroit->find('all', [
                                                            'conditions' => [
                                                                'role_id' => $donnee
                                                            ]
                                                        ]);

                                                        foreach ($droits as $val) {
                                                            if (empty($this->Droit->find('first', [
                                                                'conditions' => [
                                                                    'organisation_user_id' => $organisationUserId,
                                                                    'liste_droit_id' => $val['RoleDroit']['liste_droit_id']
                                                                ]]))
                                                            ) {
                                                                $this->Droit->create([
                                                                    'organisation_user_id' => $organisationUserId,
                                                                    'liste_droit_id' => $val['RoleDroit']['liste_droit_id']
                                                                ]);
                                                                $success = false !== $this->Droit->save() && $success;
                                                                
                                                                if($success == false){
                                                                    $this->User->rollback();
                                                                    $this->Session->setFlash(__d('fiche', 'flasherrorErreurContacterAdministrateur'), 'flasherror');

                                                                    $this->redirect([
                                                                        'controller' => 'users',
                                                                        'action' => 'index'
                                                                    ]);
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        $this->User->rollback();
                                                        $this->Session->setFlash(__d('fiche', 'flasherrorErreurContacterAdministrateur'), 'flasherror');

                                                        $this->redirect([
                                                            'controller' => 'users',
                                                            'action' => 'index'
                                                        ]);
                                                    }
                                                }
                                            }
                                        } else {
                                            $this->User->rollback();
                                            $this->Session->setFlash(__d('fiche', 'flasherrorErreurContacterAdministrateur'), 'flasherror');

                                            $this->redirect([
                                                'controller' => 'users',
                                                'action' => 'index'
                                            ]);
                                        }
                                    }
                                } else {
                                    $this->User->rollback();
                                    $this->Session->setFlash(__d('fiche', 'flasherrorErreurContacterAdministrateur'), 'flasherror');
                                        
                                    $this->redirect([
                                        'controller' => 'users',
                                        'action' => 'index'
                                    ]);
                                }

                                //Enregistrement du ou des service(s)
                                if (!empty($this->request->data['Service'][$value['Organisation']['id']])) {
                                    foreach ($this->request->data['Service'][$value['Organisation']['id']] as $value) {
                                        $this->OrganisationUserService->create([
                                            'organisation_user_id' => $organisationUserId,
                                            'service_id' => $value
                                        ]);
                                        $success = false !== $this->OrganisationUserService->save() && $success;
                                    }
                                }
                            }
                        }
                        
                        if($success == true) {
                            $this->User->commit();
                            $this->Session->setFlash(__d('user', 'user.flashsuccessUserEnregistrer'), "flashsuccess");
                        } else {
                            $this->User->rollback();
                            $this->Session->setFlash(__d('user', 'user.flasherrorErreurEnregistrementUser'), "flasherror");
                        }
                        
                        $this->redirect([
                            'controller' => 'users',
                            'action' => 'index'
                        ]);
                    } else {
                        debug('yoyoyo');die;
                        $this->User->rollback();
                        $this->Session->setFlash(__d('user', 'user.flasherrorErreurEnregistrementUser'), "flasherror");
                        $this->redirect([
                            'controller' => 'users',
                            'action' => 'index'
                        ]);
                    }
                } else {
                    $this->User->rollback();
                    $this->Session->setFlash(__d('user', 'user.flasherrorErreurEnregistrementUser'), "flasherror");
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
            $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
        
        $this->set('options', $this->User->enums());
    }

    /**
     * Modification du mot de passe par un utilisateur connecté
     * 
     * @param int|null $id
     * @throws NotFoundException
     * 
     * @access public
     * @created 03/02/2016
     * @version V0.9.0
     */
    public function changepassword($id = null) {
        $this->set('title', __d('user', 'user.titreModificationInfoUser'));

        if ($id == $this->Auth->user('id')) {
            $this->User->id = $id;

            if (!$this->User->exists()) {
                throw new NotFoundException('User Invalide');
            }

            $infoUser = $this->User->find('first', array(
                'conditions' => array('id' => $id)
            ));

            if ($this->request->is('post') || $this->request->is('put')) {

                if ($this->request->data['User']['old_password'] != "" && $this->request->data['User']['new_passwd'] != "" && $this->request->data['User']['new_password'] != "") {
                    if (AuthComponent::password($this->request->data['User']['old_password']) == $infoUser['User']['password']) {
                        if ($this->request->data['User']['new_password'] != "") {
                            if ($this->request->data['User']['new_password'] == $this->request->data['User']['new_passwd']) {
                                if ($this->request->data['User']['new_password'] != '') {
                                    $this->request->data['User']['password'] = $this->request->data['User']['new_password'];
                                }
                            } else {
                                $this->Session->setFlash(__d('user', 'user.flasherrorErreurNewPassword'), "flasherror");
                                $this->redirect([
                                    'controller' => 'users',
                                    'action' => 'changepassword',
                                    $id
                                ]);
                            }
                        } else {
                            $this->Session->setFlash(__d('user', 'user.flasherrorNewPasswordVide'), "flasherror");
                            $this->redirect([
                                'controller' => 'users',
                                'action' => 'changepassword',
                                $id
                            ]);
                        }
                    } else {
                        $this->Session->setFlash(__d('user', 'user.flasherrorPasswordInvalide'), "flasherror");
                        $this->redirect([
                            'controller' => 'users',
                            'action' => 'changepassword',
                            $id
                        ]);
                    }
                }

                if ($this->User->save($this->request->data)) {
                    $this->Session->setFlash(__d('user', 'user.flashsuccessUserEnregistrerReconnecter'), "flashsuccess");
                    $this->redirect([
                        'controller' => 'users',
                        'action' => 'logout'
                    ]);
                } else {
                    $this->Session->setFlash(__d('user', 'user.flasherrorErreurEnregistrementUser'), "flasherror");
                    $this->redirect([
                        'controller' => 'pannel',
                        'action' => 'index'
                    ]);
                }
            } else {
                $table = $this->_createTable($id);
                $this->set('tableau', $table['tableau']);
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
                        $this->Organisation->updateAll(['Organisation.cil' => null], ['Organisation.cil' => $id]);
                    }
                    if ($this->User->delete()) {
                        $this->Session->setFlash(__d('user', 'user.flashsuccessUserSupprimer'), 'flashsuccess');
                        $this->redirect(['action' => 'index']);
                    }
                }
            }
            $this->Session->setFlash(__d('user', 'user.flasherrorErreurSupprimerUser'), 'flasherror');
            $this->redirect(['action' => 'index']);
        } else {
            $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');
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
//        $hashpass = AuthComponent::password("theog");
//        debug($hashpass);
//        die;

        $this->layout = 'login';
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->_cleanSession();

                $su = $this->Admin->find('count', [
                    'conditions' => [
                        'user_id' => $this->Auth->user('id')
                    ]
                ]);

                if ($su) {
                    $this->Session->write('Su', true);
                } else {
                    $this->Session->write('Su', false);
                }

                $service = $this->OrganisationUser->find('all', [
                    'conditions' => [
                        'user_id' => $this->Auth->user('id')
                    ],
                    'contain' => [
                        'OrganisationUserService' => [
                            'Service'
                        ]
                    ]
                ]);

                $serviceUser = Hash::extract($service, '{n}.OrganisationUserService.Service');
                $serviceUser = Hash::combine($serviceUser, '{n}.id', '{n}.libelle');

                $this->Session->write('User.service', $serviceUser);

                $this->redirect([
                    'controller' => 'organisations',
                    'action' => 'change'
                ]);
            } else {
                $this->Session->setFlash(__d('user', 'user.flasherrorNameUserPasswordInvalide'), 'flasherror');
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
     * Fonction de création du tableau de droits pour le add et edit user
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
            $organisations = $this->Organisation->find('all', [
                'conditions' => [
                    'id' => $this->Session->read('Organisation.id')
                ]
            ]);
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

        if ($id != null) {
            $this->set("userid", $id);

            $organisationUser = $this->OrganisationUser->find('all', [
                'conditions' => [
                    'user_id' => $id
                ],
                'contain' => [
                    'Droit'
                ]
            ]);

            foreach ($organisationUser as $key => $value) {
                $tableau['Orgas'][] = $value['OrganisationUser']['organisation_id'];

                $userroles = $this->OrganisationUserRole->find('all', [
                    'conditions' => [
                        'OrganisationUserRole.organisation_user_id' => $value['OrganisationUser']['id']
                    ]
                ]);

                foreach ($userroles as $cle => $val) {
                    $tableau['UserRoles'][] = $val['OrganisationUserRole']['role_id'];
                }

                foreach ($value['Droit'] as $clef => $valeur) {
                    $tableau['User'][$value['OrganisationUser']['organisation_id']][] = $valeur['liste_droit_id'];
                }

                $servicesUsers = $this->OrganisationUserService->find('all', [
                    'conditions' => [
                        'OrganisationUserService.organisation_user_id' => $value['OrganisationUser']['id']
                    ]
                ]);

                if (!empty($servicesUsers)) {
                    foreach ($servicesUsers as $serviceUser) {
                        $tableau['UserService'][] = $serviceUser['OrganisationUserService']['service_id'];
                    }
                }
            }

            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }

        $listedroits = $this->ListeDroit->find('all', [
            'recursive' => -1
        ]);

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
