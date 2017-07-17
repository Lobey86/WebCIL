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
 * @since       webcil V1.0.0
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 * @version     V1.0.0
 * @package     App.Controller
 */
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('EtatFiche', 'Model');
App::uses('ListeDroit', 'Model');

class RegistresController extends AppController {

    public $uses = [
        'EtatFiche',
        'Fiche',
        'Valeur',
        'Organisation',
        'OrganisationUser',
        'Modification',
        'TraitementRegistre'
    ];

    /**
     * @access public
     * @created 21/09/2015
     * @version V1.0.0
     */
    public function index() {
        if (true !== $this->Droits->authorized(ListeDroit::CONSULTER_REGISTRE)) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->Session->write('nameController', "registres");
        $this->Session->write('nameView', "index");

        $this->set('title', __d('registre', 'registre.titreRegistre') . $this->Session->read('Organisation.raisonsociale'));

        $idCil = $this->Organisation->find('first', [
            'conditions' => [
                'id' => $this->Session->read('Organisation.id')
            ],
            'fields' => [
                'cil'
            ]
        ]);
        $this->set('idCil', $idCil);

        $condition = [
            'EtatFiche.etat_id' => [
                EtatFiche::VALIDER_CIL,
                EtatFiche::ARCHIVER,
                EtatFiche::MODIFICATION_TRAITEMENT_REGISTRE
            ],
            'EtatFiche.actif' => true,
            'Fiche.organisation_id' => $this->Session->read('Organisation.id')
        ];

        $search = false;
        $conditionValeur = [];

        // Filtre sur l'utilisateur à l'origine du traitement
        if (!empty($this->request->data['Registre']['user'])) {
            $condition['Fiche.user_id'] = $this->request->data['Registre']['user'];
            $search = true;
        }

        // Filtre sur le nom du traitement
        if (!empty($this->request->data['Registre']['outil'])) {
            $conditionValeur[] = [
                'Valeur.champ_name' => 'outilnom',
                'NOACCENTS_UPPER( Valeur.valeur ) LIKE' => '%'.str_replace(
                    '*',
                    '%',
                    trim(noaccents_upper($this->request->data['Registre']['outil']))
                ).'%'
            ];
            $search = true;
        }

        // Filtre sur le service à l'origine du traitement
        if (!empty($this->request->data['Registre']['service'])) {
            $conditionValeur[] = [
                'Valeur.champ_name' => 'declarantservice',
                'Valeur.valeur' => $this->request->data['Registre']['service']
            ];
            $search = true;
        }

        // Filtre sur le traitement verrouillées
        if (isset($this->request->data['Registre']['archive']) && $this->request->data['Registre']['archive'] == 1) {
            $condition['EtatFiche.etat_id'] = EtatFiche::ARCHIVER;
            $search = true;
        }

        // Filtre sur le traitement non verrouillées
        if (isset($this->request->data['Registre']['nonArchive']) && $this->request->data['Registre']['nonArchive'] == 1) {
            $condition['EtatFiche.etat_id'] = EtatFiche::VALIDER_CIL;
            $search = true;
        }

        if (false === empty($conditionValeur)) {
            $subQuery = [
                'alias' => 'valeurs',
                'fields' => ['valeurs.fiche_id'],
                'conditions' => $conditionValeur,
                'contain' => false
            ];

            $sql = words_replace($this->Fiche->Valeur->sql($subQuery), [
                'Valeur' => 'valeurs'
            ]);
            $condition[] = "Fiche.id IN ( {$sql} )";
        }

        $query = [
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
                    'Valeur' => [
                        'fields' => [
                            'champ_name',
                            'valeur'
                        ],
                        'conditions' => [
                            'champ_name' => [
                                'outilnom',
                                'finaliteprincipale',
                                'declarantservice'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $fichesValid = $this->EtatFiche->find('all', $query);

        foreach ($fichesValid as $key => $value) {
            if ($value['EtatFiche']['etat_id'] == EtatFiche::ARCHIVER) {
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


        // Listing des service de l'organisation
        $services = $this->Service->find('all', [
            'conditions' => [
                'organisation_id' => $this->Session->read('Organisation.id')
            ]
        ]);

        // Service
        $listeServices = [];
        foreach ($services as $key => $service) {
            $listeServices[$service['Service']['libelle']] = $service['Service']['libelle'];
        }
        $this->set('listeServices', $listeServices);
    }

    /**
     * Permet la modification d'un traitement inséré dans le registre
     *
     * @param type $idFiche
     *
     * @access public
     * @created 21/09/2015
     * @version V1.0.0
     */
    public function edit() {
        $success = true;
        $this->Modification->begin();

        $success = $success && $this->EtatFiche->updateAll([
                    'actif' => false
                        ], [
                    'fiche_id' => $this->request->data['Registre']['idEditRegistre'],
                    'etat_id' => [EtatFiche::VALIDER_CIL, EtatFiche::MODIFICATION_TRAITEMENT_REGISTRE],
                    'actif' => true
                ]) !== false;

        if ($success == true) {
            $this->EtatFiche->create([
                'EtatFiche' => [
                    'fiche_id' => $this->request->data['Registre']['idEditRegistre'],
                    'etat_id' => EtatFiche::MODIFICATION_TRAITEMENT_REGISTRE,
                    'previous_user_id' => $this->Auth->user('id'),
                    'user_id' => $this->Auth->user('id')
                ]
            ]);

            $success = false !== $this->EtatFiche->save() && $success;

            if ($success == true) {
                $idEtatFiche = $this->EtatFiche->find('first', [
                    'conditions' => [
                        'fiche_id' => $this->request->data['Registre']['idEditRegistre'],
                        'actif' => true
                    ]
                ]);

                $this->Modification->create([
                    'etat_fiches_id' => $idEtatFiche['EtatFiche']['id'],
                    'modif' => $this->request->data['Registre']['motif']
                ]);

                $success = false !== $this->Modification->save() && $success;
            }
        }

        if ($success == true) {
            $this->Modification->commit();

            $this->redirect([
                'controller' => 'fiches',
                'action' => 'edit',
                $this->request->data['Registre']['idEditRegistre']
            ]);
        } else {
            $this->Modification->rollback();
            $this->Session->setFlash(__d('fiche', 'flasherrorErreurContacterAdministrateur'), 'flasherror');

            $this->redirect([
                'controller' => 'registres',
                'action' => 'index',
                $this->request->data['Registre']['idEditRegistre']
            ]);
        }
    }

    /**
     * @access public
     * @created 21/09/2015
     * @version V1.0.0
     */
    public function add() {
        if (true !== $this->Droits->authorized($this->Droits->isCil())) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->redirect([
            'controller' => 'etat_fiches',
            'action' => 'insertRegistre',
            $this->request->data['Registre']['idfiche'],
            //Hash::get($this->request->data, 'Registre.numero'),
            $this->request->data['Registre']['numero'] ?: 'null',
            $this->request->data['Registre']['typedeclaration']
        ]);
    }

    /**
     * Permet d'imprimer tout les traitements selectionné et vérouillé au
     * registrer en mes mettent l'un a la suite
     *
     * @param int|null $tabId
     *
     * @access public
     * @created 13/05/2016
     * @version V1.0.0
     */
    public function imprimer($tabId = null) {
        if (true !== $this->Droits->authorized(ListeDroit::TELECHARGER_TRAITEMENT_REGISTRE)) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $tabId = json_decode($tabId);

        if ($tabId != null) {
            // On verifie si le dossier file existe. Si c'est pas le cas on le cree
            if (!file_exists(APP . FICHIER)) {
                $dir = new Folder(APP . FICHIER, true, 0755);
                $dir = new Folder(APP . FICHIER . PIECE_JOINT, true, 0755);
                $dir = new Folder(APP . FICHIER . MODELES, true, 0755);
                $dir = new Folder(APP . FICHIER . REGISTRE, true, 0755);
            } else {
                if (!file_exists(APP . FICHIER . REGISTRE)) {
                    $dir = new Folder(APP . FICHIER . REGISTRE, true, 0755);
                }
            }

            $folder = TMP . "imprimerRegistre";
            $date = date('d-m-Y_H-i');

            //on verifie si le dossier existe. Si c'est pas le cas on le cree
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            $files_concat = '';
            foreach ($tabId as $ficheID) {
                //On recupere en BDD le flux de donnee qui a ete enregistre
                //au verrouillage du traitement en fonction de ID
                $pdf = $this->TraitementRegistre->find('first', [
                    'conditions' => ['fiche_id' => $ficheID],
                    'fields' => ['data']
                ]);

                /**
                 * On cree un fichier .pdf avec le flux de donnee de la BDD
                 * qu'on enregistre dans /var/www/webcil/app/tmp/imprimerRegistre
                 *
                 */
                $monPDF = fopen($folder . DS . $ficheID . '.pdf', 'a');
                fputs($monPDF, $pdf['TraitementRegistre']['data']);
                fclose($monPDF);

                //On concatene le chemin du fichier .pdf
                $files_concat .= $folder . DS . $ficheID . '.pdf ';
            }

            /**
             * On concatene tout les PDFs qu'on a cree et on enregistre
             * la concatenation dans /var/www/webcil/app/files/registre
             */
            shell_exec('pdftk' . ' ' . $files_concat . 'cat output ' . CHEMIN_REGISTRE . 'Registre_' . $date . '.pdf');

            //On supprime de dossier imprimerRegistre dans /tmp
            shell_exec('rm -rf ' . realpath($folder));

            //On propose le telechargement
            $this->response->file(CHEMIN_REGISTRE . 'Registre_' . $date . '.pdf', array(
                'download' => true,
                'name' => 'Registre_' . $date . '.pdf'
            ));
            return $this->response;
        } else {
            $this->Session->setFlash(__d('registre', 'registre.flasherrorAucunTraitementSelectionner'), 'flasherror');

            $this->redirect(array(
                'controller' => 'registres',
                'action' => 'index'
            ));
        }
    }

}
