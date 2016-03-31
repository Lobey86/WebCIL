<?php

/**
 * RegistresController
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
class RegistresController extends AppController {

    public $uses = [
        'EtatFiche',
        'Fiche',
        'Valeur',
        'OrganisationUser',
        'Modification'
    ];

    /**
     * @access public
     * @created 21/09/2015
     * @version V0.9.0
     */
    public function index() {
        $this->Session->write('nameController', "registres");
        $this->Session->write('nameView', "index");
        
        $this->set('title', 'Registre ' . $this->Session->read('Organisation.raisonsociale'));
        
        $condition = [
            'EtatFiche.etat_id' => [
                5,
                7
            ],
            'Fiche.organisation_id' => $this->Session->read('Organisation.id')
        ];
        
        $search = false;
        if (!empty($this->request->data['Registre']['user'])) {
            $condition['Fiche.user_id'] = $this->request->data['Registre']['user'];
            $search = true;
        }
        $condition2 = null;
        if (!empty($this->request->data['Registre']['outil'])) {
            $condition2['valeur'] = [$this->request->data['Registre']['outil']];
            //$condition['Fiche.outilnom'] = $this->request->data['Registre']['outil'];
            $search = true;
        }
        
        if (isset($this->request->data['Registre']['archive']) && $this->request->data['Registre']['archive'] == 1) {
            $condition['EtatFiche.etat_id'] = 7;
            $search = true;
        }
        
        if (isset($this->request->data['Registre']['nonArchive']) && $this->request->data['Registre']['nonArchive'] == 1) {
            $condition['EtatFiche.etat_id'] = 5;
            $search = true;
        }

        if ($this->Droits->authorized([
                    '4',
                    '5',
                    '6'
                ])
        ) {
            $fichesValid = $this->EtatFiche->find('all', [
                'conditions' => $condition,
                'contain' => [
                    'Fiche' => [
                        'id',
                        'created',
                        'numero',
                        'User' => [
                            'nom',
                            'prenom'
                        ],
                        'Valeur'  => [
                            'conditions' => [
                                'champ_name' => [
                                    'outilnom',
                                    'finaliteprincipale'
                                ],
                                $condition2,
                            ],
                            'fields' => [
                                'champ_name',
                                'valeur'
                            ]
                        ],
                    ]
                ]
            ]);

            foreach ($fichesValid as $key => $value) {
                if ($this->Droits->isReadable($value['Fiche']['id'])) {
                    $fichesValid[$key]['Readable'] = true;
                } else {
                    $fichesValid[$key]['Readable'] = false;
                }
            }
            
            $this->set('search', $search);
            $this->set('fichesValid', $fichesValid);


            // Listing des utilisateurs de l'organisation
            $liste = $this->OrganisationUser->find('all', [
                'conditions' => [
                    'OrganisationUser.organisation_id' => $this->Session->read('Organisation.id')
                ],
                'contain' => [
                    'User' => [
                        'id',
                        'nom',
                        'prenom'
                    ]
                ]
            ]);
            
            $listeUsers = [];
            foreach ($liste as $key => $value) {
                $listeUsers[$value['User']['id']] = $value['User']['prenom'] . ' ' . $value['User']['nom'];
            }
            
            $this->set('listeUsers', $listeUsers);
        } else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * @access public
     * @created 21/09/2015
     * @version V0.9.0
     */
    public function edit() {
        $this->Modification->create([
            'fiches_id' => $this->request->data['Registre']['idEditRegistre'],
            'modif' => $this->request->data['Registre']['motif']
        ]);
        $this->Modification->save();
        $this->redirect([
            'controller' => 'fiches',
            'action' => 'edit',
            $this->request->data['Registre']['idEditRegistre']
        ]);
    }

    /**
     * @access public
     * @created 21/09/2015
     * @version V0.9.0
     */
    public function add() {
        if (isset($this->request->data['Registre']['numero']) && !empty($this->request->data['Registre']['numero'])) {
            $this->Fiche->updateAll(['numero' => $this->request->data['Registre']['numero']], ['id' => $this->request->data['Registre']['idfiche']]);
        }
        $this->redirect([
            'controller' => 'etat_fiches',
            'action' => 'insertRegistre',
            $this->request->data['Registre']['idfiche']
        ]);
    }

}
