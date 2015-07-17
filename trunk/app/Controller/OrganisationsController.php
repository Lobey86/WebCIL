<?php

    /**************************************************
     ********** Controller des organisations ***********
     **************************************************/
    class OrganisationsController extends AppController
    {
        public $uses = [
            'Organisation',
            'OrganisationUser',
            'Droit',
            'User',
            'Role',
            'RoleDroit'
        ];


        /**
         *** Accueil de la page, listing des organisations
         **/

        public function index()
        {
            $this->set('title', 'Les entités de l\'application');
            if($this->Droits->isSu()) {
                $organisations = $this->Organisation->find('all');
                foreach($organisations as $key => $value) {
                    $organisations[$key]['Count'] = $this->OrganisationUser->find('count', ['conditions' => ['organisation_id' => $value['Organisation']['id']]]);
                }
                $this->set('organisations', $organisations);
            } elseif($this->Droits->authorized([
                '11',
                '12'
            ])
            ) {
                $this->set('organisations', $this->OrganisationUser->find('all', [
                    'conditions' => [
                        'OrganisationUser.user_id' => $this->Auth->user('id')
                    ],
                    'contain'    => [
                        'Organisation'
                    ]
                ]));
            } else {
                $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
                $this->redirect([
                    'controller' => 'pannel',
                    'action'     => 'index'
                ]);
            }
        }


        /**
         *** Gère l'ajout d'organisation
         **/

        public function add()
        {
            $this->set('title', 'Créer une entité');
            if($this->Droits->isSu()) {
                if($this->request->is('post')) {
                    $recup = $this->Organisation->saveAddEditForm($this->request->data);
                    if(!is_array($recup)) {
                        $this->_insertRoles($this->Organisation->getInsertID());
                        $this->Session->setFlash('L\'entité a été enregistrée', 'flashsuccess');
                        $compte = $this->Organisation->find('count');
                        if($compte > 1) {
                            $this->redirect([
                                'controller' => 'organisations',
                                'action'     => 'index'
                            ]);
                        } else {
                            $this->redirect([
                                'controller' => 'users',
                                'action'     => 'logout'
                            ]);
                        }

                    } else {
                        $this->Session->setFlash('Une erreur s\'est produite lors de l\'enregistrement SEF', 'flasherror');
                        $this->set('error', $recup);
                    }
                }
            } else {
                $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
                $this->redirect(['controller' => 'pannel',
                                 'action'     => 'index']);
            }
        }


        /**
         *** Gère la suppression d'une organisation
         **/

        public
        function delete($id = NULL)
        {
            if($this->Droits->isSu()) {
                $this->Organisation->delete($id);
                $this->Session->setFlash('L\'entité a été supprimée', 'flashsuccess');
                $this->redirect([
                    'controller' => 'organisations',
                    'action'     => 'index'
                ]);
            } else {
                $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
                $this->redirect([
                    'controller' => 'pannel',
                    'action'     => 'index'
                ]);
            }
        }


        /**
         *** Gère l'affichage des informations d'une organisation
         **/

        public
        function show($id = NULL)
        {
            $this->set('title', 'Informations générales - ' . $this->Session->read('Organisation.raisonsociale'));
            if(!$id) {
                $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
                $this->redirect([
                    'controller' => 'organisations',
                    'action'     => 'index'
                ]);
            } else {
                $users = $this->OrganisationUser->find('all', [
                    'conditions' => [
                        'OrganisationUser.organisation_id' => $id
                    ],
                    'contain'    => [
                        'User' => [
                            'id',
                            'nom',
                            'prenom'
                        ]
                    ]
                ]);
                $array_users = [];
                foreach($users as $key => $value) {
                    $array_users[$value['User']['id']] = $value['User']['prenom'] . " " . $value['User']['nom'];
                }
                $this->set('users', $array_users);
                $organisation = $this->Organisation->find('first', [
                    'conditions' => [
                        'Organisation.id' => $id
                    ],
                    'contain'    => [
                        'Cil' => [
                            'id',
                            'nom',
                            'prenom'
                        ]
                    ]
                ]);
                if(!$organisation) {
                    $this->Session->setFlash('Cette organisation n\'existe pas', 'flasherror');
                    $this->redirect([
                        'controller' => 'organisations',
                        'action'     => 'index'
                    ]);
                }
            }
            if(!$this->request->data) {
                $this->request->data = $organisation;
            }
        }


        /**
         *** Gère l'édition d'une organisation
         **/

        public
        function edit($id = NULL)
        {
            if($id == $this->Session->read('Organisation.id')) {
                $this->set('title', 'Informations générales - ' . $this->Session->read('Organisation.raisonsociale'));
            } else {
                $this->set('title', 'Editer une entité');
            }

            if(($this->Droits->authorized(12) && $this->Session->read('Organisation.id') == $id) || $this->Droits->isSu()) {
                if(!$id) {
                    $this->Session->setFlash('Cette entité n\'existe pas', 'flasherror');
                    $this->redirect([
                        'controller' => 'organisations',
                        'action'     => 'index'
                    ]);
                } else {
                    $organisation = $this->Organisation->findById($id);
                    $users = $this->OrganisationUser->find('all', [
                        'conditions' => [
                            'OrganisationUser.organisation_id' => $id
                        ],
                        'contain'    => [
                            'User' => [
                                'id',
                                'nom',
                                'prenom'
                            ]
                        ]
                    ]);
                    $array_users = [];
                    foreach($users as $key => $value) {
                        $array_users[$value['User']['id']] = $value['User']['prenom'] . " " . $value['User']['nom'];
                    }
                    $this->set('users', $array_users);
                    if(!$organisation) {
                        $this->Session->setFlash('Cette entité n\'existe pas', 'flasherror');
                        $this->redirect([
                            'controller' => 'organisations',
                            'action'     => 'index'
                        ]);
                    } else {
                        if($this->request->is([
                            'post',
                            'put'
                        ])
                        ) {
                            $this->Organisation->id = $id;
                            if($this->Organisation->saveAddEditForm($this->request->data, $id)) {
                                $this->Session->setFlash('L\'entité a été modifiée', 'flashsuccess');
                                $this->redirect([
                                    'controller' => 'organisations',
                                    'action'     => 'index'
                                ]);
                            } else {
                                $this->Session->setFlash('La modification a échoué.', 'flasherror');
                            }
                        }
                    }
                }
                if(!$this->request->data) {
                    $this->request->data = $organisation;
                }
            } else {
                $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
                $this->redirect([
                    'controller' => 'pannel',
                    'action'     => 'index'
                ]);
            }
        }


        /**
         ** Changement d'organisation
         **/

        public
        function change($id = NULL)
        {
            if($id == NULL) {
                $idArray = $this->OrganisationUser->find('first', ['conditions' => ['OrganisationUser.user_id' => $this->Auth->user('id')]]);
                if(!empty($idArray)) {
                    $id = $idArray['OrganisationUser']['organisation_id'];
                } else {
                    if($this->Droits->isSu()) {
                        $compte = $this->Organisation->find('count');
                        if($compte == 0) {
                            $this->Session->setFlash('Il n\'existe aucune entité. Vous devez en créer une pour utiliser l\'application', 'flashwarning');
                            $this->redirect([
                                'controller' => 'organisations',
                                'action'     => 'add'
                            ]);
                        } else {
                            $idOrga = $this->Organisation->find('first');
                            $id = $idOrga['Organisation']['id'];
                        }
                    } else {
                        $this->Session->setFlash('Vous n\'appartenez à aucune entité', 'flasherror');
                        $this->redirect([
                            'controller' => 'users',
                            'action'     => 'logout'
                        ]);
                    }
                }
            }
            $change = $this->Organisation->find('first', ['conditions' => ['Organisation.id' => $id]]);
            $this->Session->write('Organisation', $change['Organisation']);

            $test = $this->Droit->find('all', [
                'conditions' => [
                    'OrganisationUser.user_id'         => $this->Auth->user('id'),
                    'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id')
                ],
                'contain'    => [
                    'ListeDroit'       => [
                        'value'
                    ],
                    'OrganisationUser' => [
                        'id'
                    ]
                ]
            ]);
            $result = [];
            foreach($test as $value) {
                array_push($result, $value['ListeDroit']['value']);
            }
            if(empty($result) && !$this->Droits->isSu()) {
                $this->Session->setFlash('Vous n\'avez pas de droit sur cette entité', 'flasherror');
                $this->redirect([
                    'controller' => 'pannel',
                    'action'     => 'index'
                ]);
            } else {
                $this->Session->write('Droit.liste', $result);
                $this->redirect([
                    'controller' => 'pannel',
                    'action'     => 'index'
                ]);
            }
        }

        protected
        function _insertRoles($id = NULL)
        {
            if($id != NULL) {
                $data = [
                    [
                        'Role'  => [
                            'libelle'         => 'Rédacteur',
                            'organisation_id' => $id
                        ],
                        'Droit' => [
                            '1',
                            '4',
                            '7'
                        ]
                    ],
                    [
                        'Role'  => [
                            'libelle'         => 'Valideur',
                            'organisation_id' => $id
                        ],
                        'Droit' => [
                            '2',
                            '4',
                            '7'
                        ]
                    ],
                    [
                        'Role'  => [
                            'libelle'         => 'Consultant',
                            'organisation_id' => $id
                        ],
                        'Droit' => [
                            '3',
                            '4',
                            '7'
                        ]
                    ],
                    [
                        'Role'  => [
                            'libelle'         => 'Administrateur',
                            'organisation_id' => $id
                        ],
                        'Droit' => [
                            '4',
                            '7',
                            '8',
                            '9',
                            '10',
                            '12',
                            '13',
                            '14',
                            '15'
                        ]
                    ]
                ];
                foreach($data as $key => $value) {
                    $this->Role->create($value['Role']);
                    $this->Role->save();
                    $last = $this->Role->getInsertID();
                    foreach($value['Droit'] as $valeur) {
                        $this->RoleDroit->create([
                            'RoleDroit' => [
                                'role_id'        => $last,
                                'liste_droit_id' => $valeur
                            ]
                        ]);
                        $this->RoleDroit->save();
                    }
                }
            }
        }


    }