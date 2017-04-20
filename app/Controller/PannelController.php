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
        'Organisation',
        'Valeur'
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

        $this->set('title', __d('pannel', 'pannel.titreTraitement'));

        $limiteTraitementRecupere = 5;

        if ($this->Droits->authorized(ListeDroit::REDIGER_TRAITEMENT)) {
            // Conditions pour récupére les traitements en cours de rédaction
            $db = $this->EtatFiche->getDataSource();
            $subQuery = $this->EtatFiche->sql(
                    array(
                        'alias' => 'etats_fiches2',
                        'fields' => ['etats_fiches2.fiche_id'],
                        'conditions' => [
                            'etats_fiches2.etat_id BETWEEN ' . EtatFiche::ENCOURS_VALIDATION . ' AND ' . EtatFiche::VALIDER_CIL,
                            'etats_fiches2.actif' => true
                        ]
                    )
            );

            $conditions[] = $db->conditions(
                    [
                'Fiche.user_id' => $this->Auth->user('id'),
                'Fiche.organisation_id' => $this->Session->read('Organisation.id'),
                'EtatFiche.fiche_id NOT IN (' . $subQuery . ')'
                    ], true, false
            );

            $conditions[] = $db->conditions([
                    [
                    'EtatFiche.etat_id' => [EtatFiche::ENCOURS_REDACTION, EtatFiche::REPLACER_REDACTION]
                ],
                'EtatFiche.actif' => true,
                'EtatFiche.user_id' => $this->Auth->user('id')
                    ], true, false
            );

            // Traitement en cours de rédaction
            $this->set('traitementEnCoursRedaction', $this->_traitementEnCoursRedaction($conditions, $limiteTraitementRecupere));
            $this->set('nbTraitementEnCoursRedaction', $this->_nbTraitementEnCoursRedaction($conditions));

            // Traitement en cours de validation
            $this->set('traitementEnCoursValidation', $this->_traitementEnCoursValidation($limiteTraitementRecupere));
            $this->set('nbTraitementEnCoursValidation', $this->_nbTraitementEnCoursValidation());


            $conditions = [];
            $conditions[] = array(
                'EtatFiche.etat_id' => EtatFiche::REFUSER,
                'EtatFiche.actif' => true
            );

            // Traitement refusés
            $this->set('traitementRefuser', $this->_traitementRefuser($conditions, $limiteTraitementRecupere));
            $this->set('nbTraitementRefuser', $this->_nbTraitementRefuser($conditions));

            // Mes traitements validés et insérés au registre
            $this->set('traitementArchives', $this->_traitementArchives($limiteTraitementRecupere));
            $this->set('nbTraitementArchives', $this->_nbTraitementArchives());
        }

        if ($this->Droits->authorized([ListeDroit::VALIDER_TRAITEMENT, ListeDroit::VISER_TRAITEMENT])) {
            // Tous les traitements passés en ma possession
            $this->set('traitementConnaissance', $this->_traitementConnaissance($limiteTraitementRecupere));
        }
        
        if ($this->Droits->authorized(ListeDroit::VALIDER_TRAITEMENT)) {
            // Traitement reçu pour validation
            $this->set('traitementRecuEnValidation', $this->_traitementRecuEnValidation($limiteTraitementRecupere));
            $this->set('nbTaitementRecuEnValidation', $this->_nbTraitementRecuEnValidation());
        }

        if ($this->Droits->authorized(ListeDroit::VISER_TRAITEMENT)) {
            // Traitement reçu pour consultation
            $this->set('traitementRecuEnConsultation', $this->_traitementRecuEnConsultation($limiteTraitementRecupere));
            $this->set('nbTraitementRecuEnConsultation', $this->_nbTraitementRecuEnConsultation());
        }

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

        $return = $this->_listValidants();
        $this->set('validants', $return['validants']);
        $this->set('consultants', $return['consultants']);
    }

    /**
     * Fonction qui récupère tous les traitements en cours de rédaction
     * 
     * @access public
     * @created 13/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    public function encours_redaction() {
        if (true !== $this->Droits->authorized(ListeDroit::REDIGER_TRAITEMENT)) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->set('title', __d('pannel', 'pannel.titreTraitementEnCoursRedaction'));

        $limiteTraitementRecupere = 0;

        // Conditions pour récupére les traitements en cours de rédaction
        $db = $this->EtatFiche->getDataSource();
        $subQuery = $this->EtatFiche->sql(
                array(
                    'alias' => 'etats_fiches2',
                    'fields' => ['etats_fiches2.fiche_id'],
                    'conditions' => [
                        'etats_fiches2.etat_id BETWEEN ' . EtatFiche::ENCOURS_VALIDATION . ' AND ' . EtatFiche::VALIDER_CIL,
                        'etats_fiches2.actif' => true
                    ]
                )
        );

        $conditions[] = $db->conditions(
                [
            'Fiche.user_id' => $this->Auth->user('id'),
            'Fiche.organisation_id' => $this->Session->read('Organisation.id'),
            'EtatFiche.fiche_id NOT IN (' . $subQuery . ')'
                ], true, false
        );

        $conditions[] = $db->conditions([
                [
                'EtatFiche.etat_id' => [EtatFiche::ENCOURS_REDACTION, EtatFiche::REPLACER_REDACTION]
            ],
            'EtatFiche.actif' => true,
            'EtatFiche.user_id' => $this->Auth->user('id')
                ], true, false
        );

        $this->set('traitementEnCoursRedaction', $this->_traitementEnCoursRedaction($conditions, $limiteTraitementRecupere));
        $this->set('nbTraitementEnCoursRedaction', $this->_nbTraitementEnCoursRedaction($conditions));

        $return = $this->_listValidants();
        $this->set('validants', $return['validants']);
        $this->set('consultants', $return['consultants']);
    }

    /**
     * Fonction qui récupère tous les traitements en attente
     * 
     * @access public
     * @created 13/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    public function attente() {
        if (true !== $this->Droits->authorized(ListeDroit::REDIGER_TRAITEMENT)) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->set('title', __d('pannel', 'pannel.titreTraitementEnAttente'));

        $limiteTraitementRecupere = 0;

        $this->set('traitementEnCoursValidation', $this->_traitementEnCoursValidation($limiteTraitementRecupere));
        $this->set('nbTraitementEnCoursValidation', $this->_nbTraitementEnCoursValidation());

        $return = $this->_listValidants();
        $this->set('validants', $return['validants']);
        $this->set('consultants', $return['consultants']);
    }

    /**
     * Fonction qui récupère tous les traitements refusés
     * 
     * @access public
     * @created 13/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    public function refuser() {
        if (true !== $this->Droits->authorized(ListeDroit::REDIGER_TRAITEMENT)) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->set('title', __d('pannel', 'pannel.titreTraitementRefuser'));

        $limiteTraitementRecupere = 0;

        $conditions = [];
        $conditions[] = array(
            'EtatFiche.etat_id' => EtatFiche::REFUSER,
            'EtatFiche.actif' => true
        );

        $this->set('traitementRefuser', $this->_traitementRefuser($conditions, $limiteTraitementRecupere));
        $this->set('nbTraitementRefuser', $this->_nbTraitementRefuser($conditions));

        $return = $this->_listValidants();
        $this->set('validants', $return['validants']);
        $this->set('consultants', $return['consultants']);
    }

    /**
     * Fonction qui récupère tous les traitements reçus pour validation
     * 
     * @access public
     * @created 13/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    public function recuValidation() {
        if (true !== $this->Droits->authorized(ListeDroit::VALIDER_TRAITEMENT)) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->set('title', __d('pannel', 'pannel.titreTraitementRecuValidation'));

        $limiteTraitementRecupere = 0;

        $this->set('traitementRecuEnValidation', $this->_traitementRecuEnValidation($limiteTraitementRecupere));
        $this->set('nbTaitementRecuEnValidation', $this->_nbTraitementRecuEnValidation());

        $return = $this->_listValidants();
        $this->set('validants', $return['validants']);
        $this->set('consultants', $return['consultants']);
    }

    /**
     * Fonction qui récupère tous les traitements reçus pour consultation
     * 
     * @access public
     * @created 13/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    public function recuConsultation() {
        if (true !== $this->Droits->authorized(ListeDroit::VISER_TRAITEMENT)) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->set('title', __d('pannel', 'pannel.titreTraitementConsultation'));

        $limiteTraitementRecupere = 0;

        $this->set('traitementRecuEnConsultation', $this->_traitementRecuEnConsultation($limiteTraitementRecupere));
        $this->set('nbTraitementRecuEnConsultation', $this->_nbTraitementRecuEnConsultation());

        $return = $this->_listValidants();
        $this->set('validants', $return['validants']);
        $this->set('consultants', $return['consultants']);
    }

    /**
     * Requète récupérant les fiches validées par le CIL
     * 
     * @access public
     * @created 02/12/2015
     * @version V1.0.0
     */
    public function archives() {
        if (true !== $this->Droits->authorized(ListeDroit::REDIGER_TRAITEMENT)) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->Session->write('nameController', "pannel");
        $this->Session->write('nameView', "archives");

        $this->set('title', __d('pannel', 'pannel.titreTraitementValidee'));

        $limiteTraitementRecupere = 0;

        $this->set('validees', $this->_traitementArchives($limiteTraitementRecupere));
        $this->set('nbTraitementArchives', $this->_nbTraitementArchives());
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
     * Fonction permettant d'afficher tout les traitements passer par le CIL 
     * ou le valideur ou l'administrateur
     * 
     * @access public
     * @created 10/05/2016
     * @version V1.0.0
     */
    public function consulte() {
        if (true !== $this->Droits->authorized([ListeDroit::VALIDER_TRAITEMENT, ListeDroit::VISER_TRAITEMENT])) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }
        
        $this->Session->write('nameController', "pannel");
        $this->Session->write('nameView', "consulte");

        $this->set('title', __d('pannel', 'pannel.titreTraitementVu'));

        $limiteTraitementRecupere = 0;

        $this->set('validees', $this->_traitementConnaissance($limiteTraitementRecupere));


        $return = $this->_listValidants();
        $this->set('validants', $return['validants']);
        $this->set('consultants', $return['consultants']);
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
        $this->Notification->begin();

        $success = $success && $this->Notification->deleteAll([
                    'Notification.user_id' => $this->Auth->user('id'),
                    false
        ]);

        if ($success == true) {
            $this->Notification->commit();
        } else {
            $this->Notification->rollback();
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

    /**
     * Requète récupérant les traitements en cours de rédaction
     * 
     * @param array() $conditions
     * @param int $limitRecuperation
     * @return array()
     * 
     * @access protected
     * @created 10/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    protected function _traitementEnCoursRedaction($conditions, $limitRecuperation) {
        $traitementEnCoursRedaction = $this->EtatFiche->find('all', [
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
            ],
            'limit' => $limitRecuperation
        ]);

        return ($traitementEnCoursRedaction);
    }

    /**
     * Calcule le nombre de traitements en cours de rédaction en base de données
     * 
     * @param array() $conditions
     * @return int
     * 
     * @access protected
     * @created 10/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    protected function _nbTraitementEnCoursRedaction($conditions) {
        $nbTraitementEnCoursRedaction = $this->EtatFiche->find('count', [
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
            ],
        ]);

        return ($nbTraitementEnCoursRedaction);
    }

    /**
     * Requète récupérant les traitements en cours de validation
     * 
     * @param int $limiteTraitementRecupere
     * @return array()
     * 
     * @access protected
     * @created 10/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    protected function _traitementEnCoursValidation($limiteTraitementRecupere) {
        $traitementEnCoursValidation = $this->EtatFiche->find('all', [
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
            ],
            'limit' => $limiteTraitementRecupere
        ]);

        return ($traitementEnCoursValidation);
    }

    /**
     * Calcule le nombre de traitements en cours de validation en base de données
     * 
     * @return int
     * 
     * @access protected
     * @created 10/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    protected function _nbTraitementEnCoursValidation() {
        $nbTraitementEnCoursValidation = $this->EtatFiche->find('count', [
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
            ],
        ]);

        return ($nbTraitementEnCoursValidation);
    }

    /**
     * Requète récupérant les traitements refusées par un validateur
     * 
     * @param array() $conditions
     * @param int $limiteTraitementRecupere
     * @return array()
     * 
     * @access protected
     * @created 10/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    protected function _traitementRefuser($conditions, $limiteTraitementRecupere) {
        $traitementRefuser = $this->EtatFiche->find('all', [
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
            ],
            'limit' => $limiteTraitementRecupere
        ]);

        return ($traitementRefuser);
    }

    /**
     * Calcule le nombre de traitements refusés en base de données
     * 
     * @param array() $conditions
     * @return int
     * 
     * @access protected
     * @created 10/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    protected function _nbTraitementRefuser($conditions) {
        $nbTraitementRefuser = $this->EtatFiche->find('count', [
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

        return ($nbTraitementRefuser);
    }

    /**
     * 
     * @param int $limiteTraitementRecupere
     * @return array()
     * 
     * @access protected
     * @created 10/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    protected function _traitementRecuEnValidation($limiteTraitementRecupere) {
        $traitementRecuEnValidation = $this->EtatFiche->find('all', [
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
            ],
            'limit' => $limiteTraitementRecupere
        ]);

        foreach ($traitementRecuEnValidation as $key => $traitement) {
            $remplieTypeDeclaration = $this->_typeDeclarationRemplie($traitement['Fiche']['id']);

            $traitementRecuEnValidation[$key]['Fiche']['typedeclaration'] = $remplieTypeDeclaration;
        }

        return ($traitementRecuEnValidation);
    }

    /**
     * 
     * @return int
     * 
     * @access protected
     * @created 10/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    protected function _nbTraitementRecuEnValidation() {
        $nbTraitementRecuEnValidation = $this->EtatFiche->find('count', [
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

        return ($nbTraitementRecuEnValidation);
    }

    /**
     * 
     * @param int $limiteTraitementRecupere
     * @return array()
     * 
     * @access protected
     * @created 10/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    protected function _traitementRecuEnConsultation($limiteTraitementRecupere) {
        // Requète récupérant les fiches qui demande un avis
        $traitementRecuEnConsultation = $this->EtatFiche->find('all', [
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
            ],
            'limit' => $limiteTraitementRecupere
        ]);

        return ($traitementRecuEnConsultation);
    }

    /**
     * 
     * @return int
     * 
     * @access protected
     * @created 10/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    protected function _nbTraitementRecuEnConsultation() {
        // Requète récupérant les fiches qui demande un avis
        $nbTraitementRecuEnConsultation = $this->EtatFiche->find('count', [
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

        return ($nbTraitementRecuEnConsultation);
    }

    /**
     * Requète récupérant les traitements validées par le CIL créé par l'utilisateur
     * 
     * @param int $limiteTraitementRecupere
     * @return array()
     * 
     * @access protected
     * @created 13/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    protected function _traitementArchives($limiteTraitementRecupere) {
        $traitementArchives = $this->EtatFiche->find('all', [
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
            ],
            'limit' => $limiteTraitementRecupere
        ]);

        return ($traitementArchives);
    }

    /**
     * Requète récupérant le nombre de traitement validées par le CIL et créé 
     * par l'utilisateur connecté
     * 
     * @return int $nbTraitementArchives --> nombre de traitement avec l'état
     * archivé 
     * 
     * @access protected
     * @created 13/02/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    protected function _nbTraitementArchives() {
        $nbTraitementArchives = $this->EtatFiche->find('count', [
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
        ]);

        return ($nbTraitementArchives);
    }

    /**
     * On récupéré tous les traitements ou l'utilisateur connecté a 
     * effectué une action dessus, qui font partie de l'organisation 
     * en cours en excluant les traitements ou l'utilisatuer connecté est
     * à l'origine avec une limite de 5 si c'est pour l'affichage de 
     * pannel/index sinon on récupére tous.
     * 
     * @param type $limiteTraitementRecupere
     * @return type
     * 
     * @access protected
     * @created 06/03/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop> 
     */
    protected function _traitementConnaissance($limiteTraitementRecupere) {
        $sq = array(
            'alias' => 'etat_fiches',
            'fields' => array('"etat_fiches"."fiche_id"'),
            'contain' => false,
            'limit' => 1
        );

        $traitementConnaissances = $this->EtatFiche->find('all', [
            $sq,
            'conditions' => [
                'EtatFiche.user_id' => $this->Auth->user('id'),
                'EtatFiche.fiche_id NOT IN ( ' . $this->EtatFiche->sql($sq) . ')',
                'Fiche.organisation_id' => $this->Session->read('Organisation.id'),
                'Fiche.user_id NOT IN ( ' . $this->Auth->user('id') . ')'
            ],
            'contain' => [
                'Fiche' => [
                    'fields' => [
                        'id',
                        'user_id',
                        'organisation_id',
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
            ],
          //  'limit' => $limiteTraitementRecupere
        ]);

        if (empty($traitementConnaissances)) {
            $commentairesUser = $this->Commentaire->find('all', [
                'order' => ['id' => 'desc'],
                'conditions' => [
                    'user_id' => $this->Auth->user('id')
                ],
                'fields' => 'etat_fiches_id'
            ]);

            if (!empty($commentairesUser)) {
                foreach ($commentairesUser as $commentaireUser){
                    $idEtatFiche[] = $commentaireUser['Commentaire']['etat_fiches_id'];
                }

                $idEtatFiche = array_unique($idEtatFiche);

                $traitementConnaissances = $this->EtatFiche->find('all', [
                    $sq,
                    'conditions' => [
                        'EtatFiche.id' => $idEtatFiche, 
                        'Fiche.organisation_id' => $this->Session->read('Organisation.id'),
                        'Fiche.user_id NOT IN ( ' . $this->Auth->user('id') . ')'
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
                            ],
                        ],
                        'User' => [
                            'fields' => [
                                'id',
                                'nom',
                                'prenom'
                            ]
                        ]
                    ],
                    'limit' => $limiteTraitementRecupere
                ]);
            }
        }

        $return = [];
        $inArray = [];
        $validees = [];
        foreach ($traitementConnaissances as $res) {
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
        
        if($limiteTraitementRecupere > 0){
            return (array_slice($validees, 0, $limiteTraitementRecupere, true));
        } else {
            return ($validees);
        }
    }

    /**
     * 
     * @param type $id
     * 
     * @access protected
     * @created 07/03/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    protected function _typeDeclarationRemplie($id) {
        $typeDeclaration = $this->Valeur->find('first', [
            'conditions' => [
                'fiche_id' => $id,
                'champ_name' => 'typedeclaration'
            ]
        ]);

        if (!empty($typeDeclaration)) {
            if ($typeDeclaration['Valeur']['valeur'] != ' ') {
                $remplie = 'true';
            } else {
                $remplie = 'false';
            }
        } else {
            $remplie = 'false';
        }

        return($remplie);
    }

}
