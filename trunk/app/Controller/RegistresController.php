<?php

    class RegistresController extends AppController
    {
        public $uses = [
            'EtatFiche',
            'Fiche',
            'OrganisationUser',
            'Modification'
        ];


        public function index()
        {
            $this->set('title', 'Registre ' . $this->Session->read('Organisation.raisonsociale'));
            $condition = [
                'EtatFiche.etat_id'     => [
                    5,
                    7
                ],
                'Fiche.organisation_id' => $this->Session->read('Organisation.id')
            ];
            $search = FALSE;
            if(!empty($this->request->data['Registre']['user'])) {
                $condition['Fiche.user_id'] = $this->request->data['Registre']['user'];
                $search = TRUE;
            }
            if(!empty($this->request->data['Registre']['outil'])) {
                $condition['Fiche.outilnom'] = $this->request->data['Registre']['outil'];
                $search = TRUE;
            }
            if(isset($this->request->data['Registre']['archive']) && $this->request->data['Registre']['archive'] == 1) {
                $condition['EtatFiche.etat_id'] = 7;
                $search = TRUE;
            }
            if(isset($this->request->data['Registre']['nonArchive']) && $this->request->data['Registre']['nonArchive'] == 1) {
                $condition['EtatFiche.etat_id'] = 5;
                $search = TRUE;
            }

            if($this->Droits->authorized([
                '4',
                '5',
                '6'
            ])
            ) {
                $fichesValid = $this->EtatFiche->find('all', [
                    'conditions' => $condition,
                    'contain'    => [
                        'Fiche' => [
                            'id',
                            'created',
                            'numero',
                            'User'   => [
                                'nom',
                                'prenom'
                            ],
                            'Valeur' => [
                                'conditions' => [
                                    'champ_name' => [
                                        'outilnom',
                                        'finaliteprincipale'
                                    ]
                                ],
                                'fields'     => [
                                    'champ_name',
                                    'valeur'
                                ]
                            ]
                        ]
                    ]
                ]);
                foreach($fichesValid as $key => $value) {
                    if($this->Droits->isReadable($value['Fiche']['id'])) {
                        $fichesValid[$key]['Readable'] = TRUE;
                    } else {
                        $fichesValid[$key]['Readable'] = FALSE;
                    }
                }
                $this->set('search', $search);
                $this->set('fichesValid', $fichesValid);


                // Listing des utilisateurs de l'organisation
                $liste = $this->OrganisationUser->find('all', [
                    'conditions' => [
                        'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id')
                    ],
                    'contain'    => [
                        'User' => [
                            'id',
                            'nom',
                            'prenom'
                        ]
                    ]
                ]);
                $listeUsers = [];
                foreach($liste as $key => $value) {
                    $listeUsers[$value['User']['id']] = $value['User']['prenom'] . ' ' . $value['User']['nom'];
                }
                $this->set('listeUsers', $listeUsers);
            } else {
                $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
                $this->redirect([
                    'controller' => 'pannel',
                    'action'     => 'index'
                ]);
            }
        }

        public function edit()
        {
            debug($this->request->data);
            $this->Modification->create([
                'fiches_id' => $this->request->data['Registre']['idEditRegistre'],
                'modif'     => $this->request->data['Registre']['motif']
            ]);
            $this->Modification->save();
            $this->redirect([
                'controller' => 'fiches',
                'action'     => 'edit',
                $this->request->data['Registre']['idEditRegistre']
            ]);
        }

        public function add()
        {
            if(isset($this->request->data['Registre']['numero']) && !empty($this->request->data['Registre']['numero'])) {
                $this->Fiche->updateAll(['numero' => $this->request->data['Registre']['numero']], ['id' => $this->request->data['Registre']['idfiche']]);
            }
            $this->redirect([
                'controller' => 'etat_fiches',
                'action'     => 'insertRegistre',
                $this->request->data['Registre']['idfiche']
            ]);
        }
    }