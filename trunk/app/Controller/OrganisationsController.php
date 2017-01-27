<?php

/**
 * OrganisationsController
 * Controller des organisations
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
class OrganisationsController extends AppController {

    public $uses = [
        'Organisation',
        'OrganisationUser',
        'Droit',
        'User',
        'Role',
        'RoleDroit'
    ];

    /**
     * Accueil de la page, listing des organisations
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function index() {
        $this->set('title', 'Les entités de l\'application');
        if ($this->Droits->isSu()) {
            $organisations = $this->Organisation->find('all');
            foreach ($organisations as $key => $value) {
                $organisations[$key]['Count'] = $this->OrganisationUser->find('count', ['conditions' => ['organisation_id' => $value['Organisation']['id']]]);
            }
            $this->set('organisations', $organisations);
        } elseif ($this->Droits->authorized([
                    '11',
                    '12'
                ])
        ) {
            $this->set('organisations', $this->OrganisationUser->find('all', [
                        'conditions' => [
                            'OrganisationUser.user_id' => $this->Auth->user('id')
                        ],
                        'contain' => [
                            'Organisation'
                        ]
            ]));
        } else {
            $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Gère l'ajout d'organisation
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function add() {
        $this->set('title', 'Créer une entité');
        if ($this->Droits->isSu()) {
            if ($this->request->is('post')) {
                $recup = $this->Organisation->saveAddEditForm($this->request->data);
                if (!is_array($recup)) {
                    $this->_insertRoles($this->Organisation->getInsertID());
                    $this->Session->setFlash(__d('organisation', 'organisation.flashsuccessEntiteEnregistrer'), 'flashsuccess');
                    $compte = $this->Organisation->find('count');
                    if ($compte > 1) {
                        $this->redirect([
                            'controller' => 'organisations',
                            'action' => 'index'
                        ]);
                    } else {
                        $this->redirect([
                            'controller' => 'users',
                            'action' => 'logout'
                        ]);
                    }
                } else {
                    $this->Session->setFlash(__d('organisation', 'organisation.flasherrorErreurEnregistrementSEF'), 'flasherror');
                    $this->set('error', $recup);
                }
            }
        } else {
            $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');
            $this->redirect(['controller' => 'pannel',
                'action' => 'index']);
        }
    }

    /**
     * 
     * @param int|null $id
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function delete($id = null) {
        if ($this->Droits->isSu()) {
            $this->Organisation->delete($id);
            $this->Session->setFlash(__d('organisation', 'organisation.flashsuccessEntiteSupprimer'), 'flashsuccess');
            $this->redirect([
                'controller' => 'organisations',
                'action' => 'index'
            ]);
        } else {
            $this->Session->setFlash(__d('default', 'default.flasherrorPasDroitPage'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Gère l'affichage des informations d'une organisation
     * 
     * @param type $id
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function show($id = null) {
        $this->set('title', 'Informations générales - ' . $this->Session->read('Organisation.raisonsociale'));
        if (!$id) {
            $this->Session->setFlash(__d('default', 'default.flasherrorTraitementInexistant'), 'flasherror');
            $this->redirect([
                'controller' => 'organisations',
                'action' => 'index'
            ]);
        } else {
            $users = $this->OrganisationUser->find('all', [
                'conditions' => [
                    'OrganisationUser.organisation_id' => $id
                ],
                'contain' => [
                    'User' => [
                        'id',
                        'nom',
                        'prenom'
                    ]
                ]
            ]);
            $array_users = [];
            foreach ($users as $key => $value) {
                $array_users[$value['User']['id']] = $value['User']['prenom'] . " " . $value['User']['nom'];
            }
            $this->set('users', $array_users);
            $organisation = $this->Organisation->find('first', [
                'conditions' => [
                    'Organisation.id' => $id
                ],
                'contain' => [
                    'Cil' => [
                        'id',
                        'nom',
                        'prenom'
                    ]
                ]
            ]);
            if (!$organisation) {
                $this->Session->setFlash(__d('organisation', 'organisation.flasherrorEntiteInexistant'), 'flasherror');
                $this->redirect([
                    'controller' => 'organisations',
                    'action' => 'index'
                ]);
            }
        }
        if (!$this->request->data) {
            $this->request->data = $organisation;
        }
    }

    /**
     * Gère l'édition d'une organisation
     * 
     * @param type $id
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function edit($id = null) {
        if ($id == $this->Session->read('Organisation.id')) {
            $this->set('title', __d('organisation', 'organisation.titreInfoGenerales') . $this->Session->read('Organisation.raisonsociale'));
        } else {
            $this->set('title', 'Editer une entité');
        }

        if (($this->Droits->authorized(12) && $this->Session->read('Organisation.id') == $id) || $this->Droits->isSu()) {
            if (!$id) {
                $this->Session->setFlash(__d('organisation', 'organisation.flasherrorEntiteInexistant'), 'flasherror');
                $this->redirect([
                    'controller' => 'organisations',
                    'action' => 'index'
                ]);
            } else {
                $organisation = $this->Organisation->findById($id);
                $users = $this->OrganisationUser->find('all', [
                    'conditions' => [
                        'OrganisationUser.organisation_id' => $id
                    ],
                    'contain' => [
                        'User' => [
                            'id',
                            'nom',
                            'prenom'
                        ]
                    ]
                ]);

                // Construction de la liste déroulante avec les utilisateurs de l'entitée
                $array_users = [];
                $idUsers = [];
                foreach ($users as $key => $value) {
                    $array_users[$value['User']['id']] = $value['User']['prenom'] . " " . $value['User']['nom'];
                    $idUsers[] = $value['User']['id'];
                }
                $this->set('users', $array_users);

                // On récupére en BDD tout les utilisateurs qui sont présent dans l'entitée
                $informationsUsers = $this->User->find('all', [
                    'conditions' => [
                        'id' => $idUsers
                    ],
                    'fields' => [
                        'id',
                        'nom',
                        'prenom',
                        'email'
                    ]
                ]);
                
                // On reformate le tableau
                $result = Hash::combine($informationsUsers, '{n}.User.id', '{n}.User');
                $result = Hash::remove($result, '{n}.id');
                $this->set('informationsUsers', $result);

                if (!$organisation) {
                    $this->Session->setFlash(__d('organisation', 'organisation.flasherrorEntiteInexistant'), 'flasherror');

                    $this->redirect([
                        'controller' => 'organisations',
                        'action' => 'index'
                    ]);
                } else {
                    if ($this->request->is([
                                'post',
                                'put'
                            ])
                    ) {
                        $this->Organisation->id = $id;
                        if ($this->Organisation->saveAddEditForm($this->request->data, $id)) {
                            $this->Session->setFlash(__d('organisation', 'organisation.flashsuccessEntiteModifier'), 'flashsuccess');
                            $this->redirect([
                                'controller' => 'pannel',
                                'action' => 'index'
                            ]);
                        } else {
                            $this->Session->setFlash(__d('organisation', 'organisation.flasherrorErreurMoficationEntite'), 'flasherror');
                        }
                    }
                }
            }
            if (!$this->request->data) {
                $this->request->data = $organisation;
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
     * Change l'organisation si besoin et redirige vers la bonne view
     * 
     * @param int|null $id
     * @param string|null $controller
     * @param string|null $action
     * @param int|0 $idFicheNotification
     * 
     * @access public
     * @created 08/01/2016
     * @version V1.0.0
     */
    public function changenotification($id = null, $controller = null, $action = null, $idFicheNotification = 0) {
        $idArray = $this->OrganisationUser->find('first', ['conditions' => ['OrganisationUser.user_id' => $this->Auth->user('id')]]);

        $this->Notification->updateAll([
            'Notification.vu' => true,
                ], [
            'Notification.user_id' => $this->Auth->user('id'),
            'Notification.fiche_id' => $idFicheNotification
        ]);

        if ($id != $idArray['OrganisationUser']['organisation_id']) {
            $redirect = 1;
            $this->Session->write('idFicheNotification', $idFicheNotification);
            $this->redirect([
                'controller' => 'organisations',
                'action' => 'change',
                $id,
                $redirect,
                $controller,
                $action
            ]);
        } else {
            $this->Session->write('idFicheNotification', $idFicheNotification);
            $this->redirect([
                'controller' => $controller,
                'action' => $action,
            ]);
        }
    }

    /**
     * Changement d'organisation
     * 
     * @param int|null $id
     * @param type|null $redirect
     * @param type|null $controller
     * @param type|null $action
     * 
     * @access public
     * @created 17/06/2015
     * @version V1.0.0
     */
    public function change($id = null, $redirect = 0, $controller = null, $action = null) {
        if ($id == null) {
            $idArray = $this->OrganisationUser->find('first', ['conditions' => ['OrganisationUser.user_id' => $this->Auth->user('id')]]);
            if (!empty($idArray)) {
                $id = $idArray['OrganisationUser']['organisation_id'];
            } else {
                if ($this->Droits->isSu()) {
                    $compte = $this->Organisation->find('count');
                    if ($compte == 0) {
                        $this->Session->setFlash(__d('organisation', 'organisation.flashwarningAucuneEntite'), 'flashwarning');
                        $this->redirect([
                            'controller' => 'organisations',
                            'action' => 'add'
                        ]);
                    } else {
                        $idOrga = $this->Organisation->find('first');
                        $id = $idOrga['Organisation']['id'];
                    }
                } else {
                    $this->Session->setFlash(__d('organisation', 'organisation.flasherrorUserAucuneEntite'), 'flasherror');
                    $this->redirect([
                        'controller' => 'users',
                        'action' => 'logout'
                    ]);
                }
            }
        }
        $change = $this->Organisation->find('first', ['conditions' => ['Organisation.id' => $id]]);
        $this->Session->write('Organisation', $change['Organisation']);

        $test = $this->Droit->find('all', [
            'conditions' => [
                'OrganisationUser.user_id' => $this->Auth->user('id'),
                'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id')
            ],
            'contain' => [
                'ListeDroit' => [
                    'value'
                ],
                'OrganisationUser' => [
                    'id'
                ]
            ]
        ]);
        $result = [];
        foreach ($test as $value) {
            array_push($result, $value['ListeDroit']['value']);
        }
        if (empty($result) && !$this->Droits->isSu()) {
            $this->Session->setFlash(__d('organisation', 'organisation.flasherrorAucunDroitEntite'), 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        } else {
            if ($redirect != 1) {
                $this->Session->write('Droit.liste', $result);
                $this->redirect([
                    'controller' => 'pannel',
                    'action' => 'index'
                ]);
            } else {
                $this->Session->write('Droit.liste', $result);
                if ($controller != null && $action != null) {
                    $this->redirect([
                        'controller' => $controller,
                        'action' => $action,
                    ]);
                } else {
                    $this->redirect($this->referer());
                }
            }
        }
    }

    /**
     * @param int|null $id
     * 
     * @access protected
     * @created 29/04/2015
     * @version V1.0.0
     */
    protected function _insertRoles($id = null) {
        if ($id != NULL) {
            $data = [
                [
                    'Role' => [
                        'libelle' => 'Rédacteur',
                        'organisation_id' => $id
                    ],
                    'Droit' => [
                        '1',
                        '4',
                        '7'
                    ]
                ],
                [
                    'Role' => [
                        'libelle' => 'Valideur',
                        'organisation_id' => $id
                    ],
                    'Droit' => [
                        '2',
                        '4',
                        '7'
                    ]
                ],
                [
                    'Role' => [
                        'libelle' => 'Consultant',
                        'organisation_id' => $id
                    ],
                    'Droit' => [
                        '3',
                        '4',
                        '7'
                    ]
                ],
                [
                    'Role' => [
                        'libelle' => 'Administrateur',
                        'organisation_id' => $id
                    ],
                    'Droit' => [
                        '1',
                        '2',
                        '3',
                        '4',
                        '5',
                        '6',
                        '7',
                        '8',
                        '9',
                        '10',
                        '11',
                        '12',
                        '13',
                        '14',
                        '15'
                    ]
                ]
            ];
            foreach ($data as $key => $value) {
                $this->Role->create($value['Role']);
                $this->Role->save();
                $last = $this->Role->getInsertID();
                foreach ($value['Droit'] as $valeur) {
                    $this->RoleDroit->create([
                        'RoleDroit' => [
                            'role_id' => $last,
                            'liste_droit_id' => $valeur
                        ]
                    ]);
                    $this->RoleDroit->save();
                }
            }
        }
    }

}
