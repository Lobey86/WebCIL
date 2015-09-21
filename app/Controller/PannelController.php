<?php

    /**************************************************
     ************** Controller du pannel ***************
     **************************************************/
    class PannelController extends AppController
    {
        public $uses = [
            'Pannel',
            'Fiche',
            'Users',
            'OrganisationUser',
            'Droit',
            'EtatFiche',
            'Commentaire',
            'Notification',
            'Historique'
        ];

        public $components = ['FormGenerator.FormGen', 'Droits'];

        /**
         *** Accueil de la page, listing des fiches et de leurs catégories
         **/

        public function index()
        {
            if(!$this->Droits->authorized(1)) {
                $this->redirect(['controller' => 'pannel', 'action' => 'inbox']);
            }
            $this->set('title', 'Mes fiches');
            // Requète récupérant les fiches en cours de rédaction
            $db = $this->EtatFiche->getDataSource();
            $subQuery = $db->buildStatement([
                'fields'     => ['"EtatFiche2"."fiche_id"'],
                'table'      => $db->fullTableName($this->EtatFiche),
                'alias'      => 'EtatFiche2',
                'limit'      => NULL,
                'offset'     => NULL,
                'joins'      => [],
                'conditions' => ['EtatFiche2.etat_id BETWEEN 2 AND 5'],
                'order'      => NULL,
                'group'      => NULL
            ], $this->EtatFiche);
            $subQuery = '"Fiche"."user_id" = ' . $this->Auth->user('id') . ' AND "Fiche"."organisation_id" = ' . $this->Session->read('Organisation.id') . ' AND "EtatFiche"."fiche_id" NOT IN (' . $subQuery . ') ';
            $subQueryExpression = $db->expression($subQuery);

            $conditions[] = $subQueryExpression;
            $conditions[] = 'EtatFiche.etat_id = 1';
            $encours = $this->EtatFiche->find('all', [
                'conditions' => $conditions,
                'contain'    => [
                    'Fiche' => [
                        'fields' => [
                            'id',
                            'created',
                            'modified'
                        ],
                        'User'   => [
                            'fields' => [
                                'id',
                                'nom',
                                'prenom'
                            ]
                        ],
                        'Valeur' => [
                            'conditions' => [
                                'champ_name' => 'outilnom'
                            ],
                            'fields'     => [
                                'champ_name',
                                'valeur'
                            ]
                        ]
                    ],
                    'User'  => [
                        'fields' => [
                            'id',
                            'nom',
                            'prenom'
                        ]
                    ]
                ]

            ]);
            $this->set('encours', $encours);


            // Requète récupérant les fiches en cours de validation

            $requete = $this->EtatFiche->find('all', [
                    'conditions' => [
                        'EtatFiche.etat_id'     => 2,
                        'Fiche.user_id'         => $this->Auth->user('id'),
                        'Fiche.organisation_id' => $this->Session->read('Organisation.id')
                    ],
                    'contain'    => [
                        'Fiche' => [
                            'fields' => [
                                'id',
                                'created',
                                'modified'
                            ],
                            'Valeur' => [
                                'conditions' => [
                                    'champ_name' => 'outilnom'
                                ],
                                'fields'     => [
                                    'champ_name',
                                    'valeur'
                                ]
                            ],
                            'User'   => [
                                'fields' => [
                                    'id',
                                    'nom',
                                    'prenom'
                                ]
                            ]
                        ],
                        'User'  => [
                            'fields' => [
                                'id',
                                'nom',
                                'prenom'
                            ]
                        ]
                    ]
                ]

            );
            $this->set('encoursValidation', $requete);


            // Requète récupérant les fiches refusées par un validateur

            $requete = $this->EtatFiche->find('all', [
                'conditions' => [
                    'EtatFiche.etat_id'     => 4,
                    'Fiche.user_id'         => $this->Auth->user('id'),
                    'Fiche.organisation_id' => $this->Session->read('Organisation.id')
                ],
                'contain'    => [
                    'Fiche' => [
                        'fields' => [
                            'id',
                            'created',
                            'modified'
                        ],
                        'User'   => [
                            'fields' => [
                                'id',
                                'nom',
                                'prenom'
                            ]
                        ],
                        'Valeur' => [
                            'conditions' => [
                                'champ_name' => 'outilnom'
                            ],
                            'fields'     => [
                                'champ_name',
                                'valeur'
                            ]
                        ]
                    ],
                    'User'  => [
                        'fields' => [
                            'id',
                            'nom',
                            'prenom'
                        ]
                    ]
                ]
            ]);
            $this->set('refusees', $requete);
            $return = $this->_listValidants();
            $this->set('validants', $return['validants']);
            $this->set('consultants', $return['consultants']);
        }

        public function inbox()
        {
            if(!$this->Droits->authorized([2, 3, 5])) {
                $this->redirect($this->referer());
            }
            $this->set('title', 'Fiches reçues');
            // Requète récupérant les fiches qui demande une validation

            $requete = $this->EtatFiche->find('all', [
                'conditions' => [
                    'EtatFiche.etat_id'     => 2,
                    'EtatFiche.user_id'     => $this->Auth->user('id'),
                    'Fiche.organisation_id' => $this->Session->read('Organisation.id')
                ],
                'contain'    => [
                    'Fiche'        => [
                        'fields' => [
                            'id',
                            'created',
                            'modified'
                        ],
                        'User'   => [
                            'fields' => [
                                'id',
                                'nom',
                                'prenom'
                            ]
                        ],
                        'Valeur' => [
                            'conditions' => [
                                'champ_name' => 'outilnom'
                            ],
                            'fields'     => [
                                'champ_name',
                                'valeur'
                            ]
                        ]
                    ],
                    'User'         => [
                        'fields' => [
                            'id',
                            'nom',
                            'prenom'
                        ]
                    ],
                    'PreviousUser' => [
                        'fields' => [
                            'id',
                            'nom',
                            'prenom'

                        ]
                    ]
                ]
            ]);
            $this->set('dmdValid', $requete);


            // Requète récupérant les fiches qui demande un avis

            $requete = $this->EtatFiche->find('all', [
                'conditions' => [
                    'EtatFiche.etat_id'     => 6,
                    'EtatFiche.user_id'     => $this->Auth->user('id'),
                    'Fiche.organisation_id' => $this->Session->read('Organisation.id')
                ],
                'contain'    => [
                    'Fiche'        => [
                        'fields' => [
                            'id',
                            'created',
                            'modified'
                        ],
                        'User'   => [
                            'fields' => [
                                'id',
                                'nom',
                                'prenom'
                            ]
                        ],
                        'Valeur' => [
                            'conditions' => [
                                'champ_name' => 'outilnom'
                            ],
                            'fields'     => [
                                'champ_name',
                                'valeur'
                            ]
                        ]
                    ],
                    'User'         => [
                        'fields' => [
                            'id',
                            'nom',
                            'prenom'
                        ]
                    ],
                    'PreviousUser' => [
                        'fields' => [
                            'id',
                            'nom',
                            'prenom'

                        ]
                    ]
                ]
            ]);
            $this->set('dmdAvis', $requete);
            $return = $this->_listValidants();
            $this->set('validants', $return['validants']);
            $this->set('consultants', $return['consultants']);
        }


        public function archives()
        {
            $this->set('title', 'Fiches validées');
            // Requète récupérant les fiches validées par le CIL

            $requete = $this->EtatFiche->find('all', [
                    'conditions' => [
                        'EtatFiche.etat_id'     => 5,
                        'Fiche.user_id'         => $this->Auth->user('id'),
                        'Fiche.organisation_id' => $this->Session->read('Organisation.id')
                    ],
                    'contain'    => [
                        'Fiche' => [
                            'fields' => [
                                'id',
                                'created',
                                'modified'
                            ],
                            'User'   => [
                                'fields' => [
                                    'id',
                                    'nom',
                                    'prenom'
                                ]
                            ],
                            'Valeur' => [
                                'conditions' => [
                                    'champ_name' => 'outilnom'
                                ],
                                'fields'     => [
                                    'champ_name',
                                    'valeur'
                                ]
                            ]
                        ],
                        'User'  => [
                            'fields' => [
                                'id',
                                'nom',
                                'prenom'
                            ]
                        ]
                    ]
                ]

            );
            $this->set('validees', $requete);

        }

        // Fonction appelée pour le composant parcours, permettant d'afficher le parcours parcouru par une fiche et les commentaires liés (uniquement ceux visibles par l'utilisateur)

        public function parcours($id)
        {
            $parcours = $this->EtatFiche->find('all', [
                'conditions' => [
                    'EtatFiche.fiche_id' => $id
                ],
                'contain'    => [
                    'Fiche'       => [
                        'id',
                        'organisation_id',
                        'user_id',
                        'created',
                        'modified',
                        'User' => [
                            'id',
                            'nom',
                            'prenom'
                        ]
                    ],
                    'User'        => [
                        'id',
                        'nom',
                        'prenom'
                    ],
                    'Commentaire' => [
                        'conditions' => [
                            'OR' => [
                                'Commentaire.user_id'         => $this->Auth->user('id'),
                                'Commentaire.destinataire_id' => $this->Auth->user('id')
                            ]
                        ],
                        'User'       => [
                            'id',
                            'nom',
                            'prenom'
                        ]
                    ]
                ],
                'order'      => [
                    'EtatFiche.id DESC'
                ]
            ]);

            return $parcours;
        }

        public function getHistorique($id)
        {
            $historique = $this->Historique->find('all', [
                'conditions' => ['fiche_id' => $id],
                'order'      => [
                    'created DESC',
                    'id DESC'
                ]
            ]);

            return $historique;
        }


        // Fonction de suppression des notifications

        public function dropNotif()
        {
            $this->Notification->deleteAll([
                'Notification.user_id' => $this->Auth->user('id'),
                FALSE
            ]);
            $this->redirect($this->referer());
        }

        public function validNotif()
        {
            $this->Notification->updateAll([
                'Notification.vu'      => TRUE,
                'Notification.user_id' => $this->Auth->user('id')
            ]);
            $this->redirect($this->referer());
        }

        protected function _listValidants()
        {
            // Requète récupérant les utilisateurs ayant le droit de consultation

            $queryConsultants = [
                'fields'     => [
                    'User.id',
                    'User.nom',
                    'User.prenom'
                ],
                'joins'      => [
                    $this->Droit->join('OrganisationUser', ['type' => "INNER"]),
                    $this->Droit->OrganisationUser->join('User', ['type' => "INNER"])
                ],
                'recursive'  => -1,
                'conditions' => [
                    'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id'),
                    'User.id != ' . $this->Auth->user('id'),
                    'Droit.liste_droit_id'             => 3
                ],
            ];
            $consultants = $this->Droit->find('all', $queryConsultants);
            $consultants = Hash::combine($consultants, '{n}.User.id', [
                '%s %s',
                '{n}.User.prenom',
                '{n}.User.nom'
            ]);
            $return = ['consultants' => $consultants];


            // Requète récupérant les utilisateurs ayant le droit de validation
            if($this->Session->read('Organisation.cil') != NULL) {
                $cil = $this->Session->read('Organisation.cil');
            } else {
                $cil = 0;
            }


            $queryValidants = [
                'fields'     => [
                    'User.id',
                    'User.nom',
                    'User.prenom'
                ],
                'joins'      => [
                    $this->Droit->join('OrganisationUser', ['type' => "INNER"]),
                    $this->Droit->OrganisationUser->join('User', ['type' => "INNER"])
                ],
                'conditions' => [
                    'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id'),
                    'NOT'                              => [
                        'User.id' => [
                            $this->Auth->user('id'),
                            $cil
                        ]
                    ],
                    'Droit.liste_droit_id'             => 2
                ]
            ];
            $validants = $this->Droit->find('all', $queryValidants);
            $validants = Hash::combine($validants, '{n}.User.id', [
                '%s %s',
                '{n}.User.prenom',
                '{n}.User.nom'
            ]);
            $return['validants'] = $validants;

            return $return;
        }

    }