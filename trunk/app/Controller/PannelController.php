<?php

/**
 * PannelController
 * Controller du pannel
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
App::uses('EtatFiche', 'Model');
App::uses('ListeDroit', 'Model');

class PannelController extends AppController {

    public $uses = [
        'Pannel',
        'Fiche',
        'Users',
        'OrganisationUser',
        'Droit',
        'EtatFiche',
        'Commentaire',
        'Modification',
        'Notification',
        'Historique',
        'Organisation'
    ];
    public $components = [
        'FormGenerator.FormGen',
        'Droits',
    ];

    /**
     * Accueil de la page, listing des fiches et de leurs catégories
     * 
     * @access public
     * @created 02/12/2015
     * @version V1.0.0
     */
    public function index() {
        $this->Session->write('nameController', "pannel");
        $this->Session->write('nameView', "index");

        if (!$this->Droits->authorized(ListeDroit::REDIGER_TRAITEMENT)) {
            $this->redirect(['controller' => 'pannel', 'action' => 'inbox']);
        }

        $this->set('title', __d('pannel', 'pannel.titreTraitement'));

        // Requète récupérant les traitements en cours de rédaction
        $db = $this->EtatFiche->getDataSource();
        $subQuery = $this->EtatFiche->sql(
                array(
                    'alias' => 'etats_fiches2',
                    'fields' => ['etats_fiches2.fiche_id'],
                    'conditions' => ['etats_fiches2.etat_id BETWEEN ' . EtatFiche::ENCOURS_VALIDATION . ' AND ' . EtatFiche::VALIDER_CIL]
                )
        );

        $conditions[] = $db->conditions(
                [
            'Fiche.user_id' => $this->Auth->user('id'),
            'Fiche.organisation_id' => $this->Session->read('Organisation.id'),
            'EtatFiche.fiche_id NOT IN (' . $subQuery . ')'
                ], true, false
        );

        $conditions[] = $db->conditions(
                [
            'OR' => [
                'EtatFiche.etat_id' => EtatFiche::ENCOURS_REDACTION,
                    [
                    'EtatFiche.etat_id' => EtatFiche::REPLACER_REDACTION,
                    'EtatFiche.actif' => true,
                    'EtatFiche.user_id' => $this->Auth->user('id')
                ]
            ]
                ], true, false
        );

        $encours = $this->EtatFiche->find('all', [
            'conditions' => $conditions,
            'contain' => [
                'Fiche' => [
                    'fields' => [
                        'id',
                        'created',
                        'modified'
                    ],
                    'User' => [
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
                        'fields' => [
                            'champ_name',
                            'valeur'
                        ]
                    ]
                ],
                'User' => [
                    'fields' => [
                        'id',
                        'nom',
                        'prenom'
                    ]
                ]
            ]
        ]);
        $this->set('encours', $encours);

        // Requète récupérant les traitements en cours de validation
        $requete = $this->EtatFiche->find('all', [
            'conditions' => [
                'EtatFiche.etat_id' => EtatFiche::ENCOURS_VALIDATION,
                'Fiche.user_id' => $this->Auth->user('id'),
                'Fiche.organisation_id' => $this->Session->read('Organisation.id')
            ],
            'contain' => [
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
                        'fields' => [
                            'champ_name',
                            'valeur'
                        ]
                    ],
                    'User' => [
                        'fields' => [
                            'id',
                            'nom',
                            'prenom'
                        ]
                    ]
                ],
                'User' => [
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

        $conditions = [];
        $conditions[] = array(
            'EtatFiche.etat_id' => EtatFiche::REFUSER,
            'EtatFiche.actif' => true
        );

        // Requète récupérant les traitements refusées par un validateur
        $requete = $this->EtatFiche->find('all', [
            'conditions' => [
                $conditions,
                'Fiche.user_id' => $this->Auth->user('id'),
                'Fiche.organisation_id' => $this->Session->read('Organisation.id')
            ],
            'contain' => [
                'Fiche' => [
                    'fields' => [
                        'id',
                        'created',
                        'modified'
                    ],
                    'User' => [
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
                        'fields' => [
                            'champ_name',
                            'valeur'
                        ]
                    ]
                ],
                'User' => [
                    'fields' => [
                        'id',
                        'nom',
                        'prenom'
                    ]
                ]
            ]
        ]);

        $notifications = $this->Notification->find('all', array(
            'conditions' => array(
                'Notification.user_id' => $this->Auth->user('id'),
                'Notification.vu' => false,
                'Notification.afficher' => false
            ),
            'contain' => array(
                'Fiche' => array(
                    'Valeur' => array(
                        'conditions' => array(
                            'champ_name' => 'outilnom'
                        ),
                        'fields' => array('champ_name', 'valeur')
                    )
                )
            ),
            'order' => array(
                'Notification.content'
            )
        ));
        $this->set('notifications', $notifications);

        $nameOrganisation = [];

        foreach ($notifications as $key => $value) {
            $nameOrganisation[$key] = $this->Organisation->find('first', [
                'conditions' => ['id' => $value['Fiche']['organisation_id']],
                'fields' => ['raisonsociale']
            ]);
        }
        $this->set('nameOrganisation', $nameOrganisation);

        $this->set('refusees', $requete);
        $return = $this->_listValidants();
        $this->set('validants', $return['validants']);
        $this->set('consultants', $return['consultants']);
    }

    /**
     * @access public
     * @created 02/12/2015
     * @version V1.0.0
     */
    public function inbox() {
        $this->Session->write('nameController', "pannel");
        $this->Session->write('nameView', "inbox");

        if (!$this->Droits->authorized([
                    ListeDroit::VALIDER_TRAITEMENT,
                    ListeDroit::VISER_TRAITEMENT,
                    ListeDroit::INSERER_TRAITEMENT_REGISTRE
                ])) {
            $this->redirect($this->referer());
        }
        $this->set('title', __d('pannel', 'pannel.titreTraitementRecue'));
        // Requète récupérant les fiches qui demande une validation

        $requete = $this->EtatFiche->find('all', [
            'conditions' => [
                'EtatFiche.etat_id' => EtatFiche::ENCOURS_VALIDATION,
                'EtatFiche.user_id' => $this->Auth->user('id'),
                'Fiche.organisation_id' => $this->Session->read('Organisation.id')
            ],
            'contain' => [
                'Fiche' => [
                    'fields' => [
                        'id',
                        'created',
                        'modified'
                    ],
                    'User' => [
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
                        'fields' => [
                            'champ_name',
                            'valeur'
                        ]
                    ]
                ],
                'User' => [
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

        $notifications = $this->Notification->find('all', array(
            'conditions' => array(
                'Notification.user_id' => $this->Auth->user('id'),
                'Notification.vu' => false,
                'Notification.afficher' => false
            ),
            'contain' => array(
                'Fiche' => array(
                    'Valeur' => array(
                        'conditions' => array(
                            'champ_name' => 'outilnom'
                        ),
                        'fields' => array('champ_name', 'valeur')
                    )
                )
            ),
            'order' => array(
                'Notification.content'
            )
        ));
        $this->set('notifications', $notifications);

        $nameOrganisation = [];

        foreach ($notifications as $key => $value) {
            $nameOrganisation[$key] = $this->Organisation->find('first', [
                'conditions' => ['id' => $value['Fiche']['organisation_id']],
                'fields' => ['raisonsociale']
            ]);
        }
        $this->set('nameOrganisation', $nameOrganisation);

        // Requète récupérant les fiches qui demande un avis
        $requete = $this->EtatFiche->find('all', [
            'conditions' => [
                'EtatFiche.etat_id' => EtatFiche::DEMANDE_AVIS,
                'EtatFiche.user_id' => $this->Auth->user('id'),
                'Fiche.organisation_id' => $this->Session->read('Organisation.id')
            ],
            'contain' => [
                'Fiche' => [
                    'fields' => [
                        'id',
                        'created',
                        'modified'
                    ],
                    'User' => [
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
                        'fields' => [
                            'champ_name',
                            'valeur'
                        ]
                    ]
                ],
                'User' => [
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

    /**
     * @access public
     * @created 02/12/2015
     * @version V1.0.0
     */
    public function archives() {
        $this->Session->write('nameController', "pannel");
        $this->Session->write('nameView', "archives");

        $this->set('title', __d('pannel', 'pannel.titreTraitementValidee'));
        // Requète récupérant les fiches validées par le CIL

        $requete = $this->EtatFiche->find('all', [
            'conditions' => [
                'EtatFiche.etat_id' => EtatFiche::VALIDER_CIL,
                'Fiche.user_id' => $this->Auth->user('id'),
                'Fiche.organisation_id' => $this->Session->read('Organisation.id')
            ],
            'contain' => [
                'Fiche' => [
                    'fields' => [
                        'id',
                        'created',
                        'modified'
                    ],
                    'User' => [
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
                        'fields' => [
                            'champ_name',
                            'valeur'
                        ]
                    ]
                ],
                'User' => [
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

    /**
     * Fonction appelée pour le composant parcours, permettant d'afficher le parcours parcouru par une fiche et les commentaires liés (uniquement ceux visibles par l'utilisateur)
     * 
     * @param int $id
     * @return type
     * 
     * @access public
     * @created 02/12/2015
     * @version V1.0.0
     */
    public function parcours($id) {
        $parcours = $this->EtatFiche->find('all', [
            'conditions' => [
                'EtatFiche.fiche_id' => $id,
            ],
            'contain' => [
                'Modification' => [
                    'id',
                    'modif',
                    'created'
                ],
                'Fiche' => [
                    'id',
                    'organisation_id',
                    'user_id',
                    'created',
                    'modified',
                    'User' => [
                        'id',
                        'nom',
                        'prenom'
                    ],
                ],
                'User' => [
                    'id',
                    'nom',
                    'prenom'
                ],
                'Commentaire' => [
                    'User' => [
                        'id',
                        'nom',
                        'prenom'
                    ]
                ],
            ],
            'order' => [
                'EtatFiche.id DESC'
            ]
        ]);

        return $parcours;
    }

    /**
     * Fonction permettant d'afficher tout les traitements passer par le CIL ou le valideur ou l'administrateur
     * 
     * @access public
     * @created 10/05/2016
     * @version V1.0.2
     */
    public function consulte() {
        $this->Session->write('nameController', "pannel");
        $this->Session->write('nameView', "consulte");

//        if (!$this->Droits->authorized([
//                    ListeDroit::VALIDER_TRAITEMENT,
//                    ListeDroit::INSERER_TRAITEMENT_REGISTRE,
//                ])) {
//            $this->redirect($this->referer());
//        }

        $this->set('title', __d('pannel', 'pannel.titreTraitementVu'));

        $sq = array(
            'alias' => 'etat_fiches',
            'fields' => array('"etat_fiches"."fiche_id"'),
            'contain' => false,
            'conditions' => array(
                '"etat_fiches"."actif"' => true,
                '"etat_fiches"."etat_id"' => EtatFiche::VALIDER_CIL,
                '"etat_fiches"."fiche_id" = "EtatFiche"."fiche_id"'
            ),
            'limit' => 1
        );

        $requete = $this->EtatFiche->find('all', [
            'conditions' => [
                'AND' => [
                    'EtatFiche.etat_id IN' => [
                        EtatFiche::ENCOURS_VALIDATION,
                        EtatFiche::VALIDER,
                        EtatFiche::REFUSER,
                        EtatFiche::DEMANDE_AVIS,
                        EtatFiche::REPLACER_REDACTION
                    ],
                    'EtatFiche.user_id' => $this->Auth->user('id'),
                    'EtatFiche.fiche_id NOT IN ( ' . $this->EtatFiche->sql($sq) . ')',
                ]],
            'contain' => [
                'Fiche' => [
                    'fields' => [
                        'id',
                        'created',
                        'modified'
                    ],
                    'User' => [
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
                        'fields' => [
                            'champ_name',
                            'valeur'
                        ]
                    ],
                ],
                'User' => [
                    'fields' => [
                        'id',
                        'nom',
                        'prenom'
                    ]
                ]
            ]
        ]);

        if (empty($requete)) {
            $test = $this->Commentaire->find('first', [
                'order' => ['id' => 'desc'],
                'conditions' => [
                    'user_id' => $this->Auth->user('id')
                ]
            ]);

            if (!empty($test)) {
                $requete = $this->EtatFiche->find('all', [
                    'conditions' => [
                        'AND' => [
                            'EtatFiche.id' => $test['Commentaire']['etat_fiches_id'],
                            'EtatFiche.fiche_id NOT IN ( ' . $this->EtatFiche->sql($sq) . ')',
                        ]],
                    'contain' => [
                        'Fiche' => [
                            'fields' => [
                                'id',
                                'created',
                                'modified'
                            ],
                            'User' => [
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
                                'fields' => [
                                    'champ_name',
                                    'valeur'
                                ]
                            ],
                        ],
                        'User' => [
                            'fields' => [
                                'id',
                                'nom',
                                'prenom'
                            ]
                        ]
                    ]
                ]);
            }
        }

        $return = [];
        $inArray = [];
        $validees = [];
        foreach ($requete as $res) {
            if (!in_array($res['EtatFiche']['fiche_id'], $inArray)) {
                array_push($inArray, $res['EtatFiche']['fiche_id']);
                array_push($return, $res);
            }
        }

        $etatFicheActuels = [];
        foreach ($return as $key => $ret) {
            //On récupére l'état actuel de la fiche
            $etatFicheActuels[] = current($this->EtatFiche->find('all', [
                        'conditions' => [
                            'fiche_id' => $ret['EtatFiche']['fiche_id'],
                            'actif' => true
                        ]
            ]));

            //On met a jour l'état
            if ($ret['EtatFiche']['fiche_id'] == $etatFicheActuels[$key]['EtatFiche']['fiche_id']) {
                $ret['EtatFiche']['etat_id'] = $etatFicheActuels[$key]['EtatFiche']['etat_id'];
                $ret['EtatFiche']['user_id_actuel'] = $etatFicheActuels[$key]['EtatFiche']['user_id'];
                $ret['EtatFiche']['id'] = $etatFicheActuels[$key]['EtatFiche']['id'];
            }

            $validees[] = $ret;
        }

        $this->set('validees', $validees);
        $listValidante = $this->_listValidants();
        $this->set('validants', $listValidante['validants']);
    }

    /**
     * @param int $id
     * @return type
     * 
     * @access public
     * @created 02/12/2015
     * @version V1.0.0
     */
    public function getHistorique($id) {
        $historique = $this->Historique->find('all', [
            'conditions' => ['fiche_id' => $id],
            'order' => [
                'created DESC',
                'id DESC'
            ]
        ]);

        return $historique;
    }

    /**
     * Fonction de suppression de toute les notifications d'un utilisateur
     * 
     * @access public
     * @created 02/12/2015
     * @version V1.0.0
     */
    public function dropNotif() {
        $success = true;
        $this->User->begin();

        $success = $success && $this->Notification->deleteAll([
                    'Notification.user_id' => $this->Auth->user('id'),
                    false
        ]);

        if ($success == true) {
            $this->User->commit();
        } else {
            $this->User->rollback();
        }

        $this->redirect($this->referer());
    }

    /**
     * Fonction de suppression d'une notification d'un utilisateur
     * 
     * @access public
     * @created 20/01/2016
     * @version V1.0.0
     */
    public function supprimerLaNotif($idFiche) {
        $success = true;
        $this->Notification->begin();

        $success = $success && $this->Notification->deleteAll([
                    'Notification.fiche_id' => $idFiche,
                    'Notification.user_id' => $this->Auth->user('id')
        ]);

        if ($success == true) {
            $this->Notification->commit();
        } else {
            $this->Notification->rollback();
        }
    }

    /**
     * Permet de mettre dans la base de donner les notifications deja afficher 
     * quand on fermer la pop-up avec le bouton FERMER
     * 
     * @access public
     * @created 02/12/2015
     * @version V1.0.0
     */
    public function validNotif() {
        $success = true;
        $this->Notification->begin();

        $success = $success && $this->Notification->updateAll([
                    'Notification.afficher' => true
                        ], [
                    'Notification.user_id' => $this->Auth->user('id')
                ]) !== false;

        if ($success == true) {
            $this->Notification->commit();
        } else {
            $this->Notification->rollback();
        }

        $this->redirect($this->referer());
    }

    /**
     * Permet de mettre en base les notifs deja afficher
     * 
     * @param int $idFicheEnCourAffigage
     * 
     * @access public
     * @created 20/01/2016
     * @version V1.0.0
     */
    public function notifAfficher($idFicheEnCourAffigage = 0) {
        $success = true;
        $this->Notification->begin();

        $success = $success && $this->Notification->updateAll([
                    'Notification.afficher' => true
                        ], [
                    'Notification.user_id' => $this->Auth->user('id'),
                    'Notification.fiche_id' => $idFicheEnCourAffigage
                ]) !== false;

        if ($success == true) {
            $this->Notification->commit();
        } else {
            $this->Notification->rollback();
        }
    }

    /**
     * @return type
     * 
     * @access protected
     * @created 02/12/2015
     * @version V1.0.0
     */
    protected function _listValidants() {
        // Requète récupérant les utilisateurs ayant le droit de consultation
        $queryConsultants = [
            'fields' => [
                'User.id',
                'User.nom',
                'User.prenom'
            ],
            'joins' => [
                $this->Droit->join('OrganisationUser', ['type' => "INNER"]),
                $this->Droit->OrganisationUser->join('User', ['type' => "INNER"])
            ],
            'recursive' => -1,
            'conditions' => [
                'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id'),
                'User.id != ' . $this->Auth->user('id'),
                'Droit.liste_droit_id' => ListeDroit::VISER_TRAITEMENT
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
        if ($this->Session->read('Organisation.cil') != null) {
            $cil = $this->Session->read('Organisation.cil');
        } else {
            $cil = 0;
        }

        $queryValidants = [
            'fields' => [
                'User.id',
                'User.nom',
                'User.prenom'
            ],
            'joins' => [
                $this->Droit->join('OrganisationUser', ['type' => "INNER"]),
                $this->Droit->OrganisationUser->join('User', ['type' => "INNER"])
            ],
            'conditions' => [
                'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id'),
                'NOT' => [
                    'User.id' => [
                        $this->Auth->user('id'),
                        $cil
                    ]
                ],
                'Droit.liste_droit_id' => ListeDroit::VALIDER_TRAITEMENT
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
