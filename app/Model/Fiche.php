<?php

/**
 * Model Fiche
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
 * @package     AppModel
 */
App::uses('AppModel', 'Model');

require_once APP . 'Vendor' . DS . 'phpgedooo_client' . DS . 'src' . DS . 'GDO_PartType.php';
require_once APP . 'Vendor' . DS . 'phpgedooo_client' . DS . 'src' . DS . 'GDO_IterationType.php';
require_once APP . 'Vendor' . DS . 'phpgedooo_client' . DS . 'src' . DS . 'GDO_FieldType.php';
require_once APP . 'Vendor' . DS . 'phpgedooo_client' . DS . 'src' . DS . 'GDO_ContentType.php';
require_once APP . 'Vendor' . DS . 'phpgedooo_client' . DS . 'src' . DS . 'GDO_FusionType.php';

class Fiche extends AppModel {

    public $name = 'Fiche';

    /**
     * validate associations
     *
     * @var array
     *
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public $validate = [
        'declarantservice' => [
            'notEmpty' => [
                'rule' => ['notEmpty'],
                'allowEmpty' => false,
            ]
        ]
    ];

    /**
     * hasOne associations
     *
     * @var array
     *
     * @access public
     * @created 04/01/2016
     * @version V1.0.0
     */
    public $hasOne = array(
        'ExtraitRegistre' => array(
            'className' => 'ExtraitRegistre',
            'foreignKey' => 'fiche_id'
        ),
        'Fichier' => array(
            'className' => 'Fichier',
            'foreignKey' => 'fiche_id',
            'dependent' => true,
        )
    );

    /**
     * belongsTo associations
     *
     * @var array
     *
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public $belongsTo = array(
        'Organisation' => array(
            'className' => 'Organisation',
            'foreignKey' => 'organisation_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     *
     * @access public
     * @created 29/04/2015
     * @version V1.0.0
     */
    public $hasMany = array(
        'EtatFiche' => array(
            'className' => 'EtatFiche',
            'foreignKey' => 'fiche_id',
            'dependent' => true,
        ),
        'Notification' => array(
            'className' => 'Notification',
            'foreignKey' => 'fiche_id',
            'dependent' => true
        ),
        'Historique' => array(
            'className' => 'Historique',
            'foreignKey' => 'fiche_id',
            'dependant' => true
        ),
        'Valeur' => array(
            'className' => 'Valeur',
            'foreignKey' => 'fiche_id',
            'dependant' => true
        ),
        'TraitementRegistre' => array(
            'className' => 'TraitementRegistre',
            'foreignKey' => 'fiche_id',
            'dependant' => true
        ),
        'ExtraitRegistre' => array(
            'className' => 'ExtraitRegistre',
            'foreignKey' => 'fiche_id',
            'dependant' => true
        )
    );

    /**
     * @param int|null $idUser
     * @param type|null $fiche
     * @return boolean
     *
     * @access public
     * @created 09/04/2015
     * @version V1.0.0
     */
    public function isOwner($idUser = null, $fiche = null) {
        if ($idUser == $fiche['Fiche']['user_id']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param type $string
     * @return type
     *
     * @access public
     * @created 26/06/2015
     * @version V1.0.0
     */
    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

//    /**
//     *
//     * @param int $id
//     * @param char $numeroRegistre
//     * @param bool $save
//     * @return type
//     */
//    public function genereTraitement($id, $numeroRegistre) {
//        $annexe = false;
//
//        //On chercher si le traitement comporte des annexe(s)
//        $fileAnnexes = $this->Fichier->find('all', [
//            'conditions' => [
//                'fiche_id' => $id
//            ]
//        ]);
//
//        if (!empty($fileAnnexes)) {
//            $annexe = true;
//        }
//
//        $data = $this->Valeur->find('all', [
//            'conditions' => [
//                'fiche_id' => $id
//            ]
//        ]);
//
//        $fiche = $this->find('first', [
//            'conditions' => [
//                'id' => $id
//            ]
//        ]);
//
//        $modele = ClassRegistry::init('Modele')->find('first', [
//            'conditions' => [
//                'formulaires_id' => $fiche['Fiche']['form_id']
//            ]
//        ]);
//
//        if (!empty($modele)) {
//            $file = $modele['Modele']['fichier'];
//        } else {
//            $file = '1.odt';
//        }
//
//        $cheminFile = CHEMIN_MODELES;
//
//        /**
//         * On recupere les champs 'deroulant', 'checkboxes', 'radios' qui
//         * sont dans le formulaire associer a la fiche
//         */
//        $typeChamps = ['deroulant', 'checkboxes', 'radios'];
//        $idForm = $this->find('first', [
//            'conditions' => ['id' => $id]
//        ]);
//
//        $champs = ClassRegistry::init('FgChamp')->find('all', [
//            'conditions' => [
//                'formulaires_id' => $idForm['Fiche']['form_id'],
//                'type' => $typeChamps,
//            ],
//        ]);
//
//        /**
//         * On decode les infos du champ details pour ensuite faire
//         * un tableau avec le name du champs et les valeurs
//         */
//        $choixChampMultiple = [];
//        $checkBoxField = [];
//        foreach ($champs as $value) {
//            $options = json_decode($value['FgChamp']['details'], true);
//
//            if ($value['FgChamp']['type'] != 'checkboxes') {
//                $choixChampMultiple[$options['name']] = $options['options'];
//            } else {
//                $checkBoxField[$options['name']] = $options['options'];
//            }
//        }
//
//        /**
//         * On vérifie que le tableau qu'on a créé juste au dessus existe.
//         * Si il exite on on prend la valeur de l'id choisit dans le tableau,
//         * sinon on prend directement la valeur enregistré dans la table Valeur.
//         */
//        $donnees = [];
//        foreach ($data as $key => $value) {
//            if (!empty($choixChampMultiple[$value['Valeur']['champ_name']])) {
//                $donnees['Valeur'][$value['Valeur']['champ_name']] = $choixChampMultiple[$value['Valeur']['champ_name']][intval($value['Valeur']['valeur'])];
//            } elseif (!empty($checkBoxField[$value['Valeur']['champ_name']])) {
//                $choixCheckbox = json_decode($value["Valeur"]["valeur"]);
//                $nombreChoixCheckbox = sizeof($choixCheckbox);
//
//                $tampon = null;
//                for ($compteur = 0; $compteur < $nombreChoixCheckbox; $compteur++) {
//                    if ($compteur === 0) {
//                        $tampon = $checkBoxField[$value['Valeur']['champ_name']][$compteur];
//                    } else if ($compteur < $nombreChoixCheckbox && $compteur != 0) {
//                        $tampon = $tampon . ' , ' . $checkBoxField[$value['Valeur']['champ_name']][$compteur];
//                    }
//                }
//                $donnees['Valeur'][$value['Valeur']['champ_name']] = $tampon;
//            } else {
//                $donnees['Valeur'][$value['Valeur']['champ_name']] = $value['Valeur']['valeur'];
//            }
//        }
//        unset($donnees['Valeur']['fichiers']);
//
//        $types = [];
//        foreach ($donnees['Valeur'] as $key => $value) {
//            $types['valeur_' . $key] = 'text';
//        }
//
//        $correspondances = [];
//        foreach ($donnees['Valeur'] as $key => $value) {
//            $correspondances['valeur_' . $key] = 'Valeur.' . $key;
//        }
//
//        // On donne le numéro d'enregistrement au registre du traitement
//        $donnees['Valeur']['numenregistrement'] = $numeroRegistre;
//        $types['valeur_numenregistrement'] = 'text';
//        $correspondances['valeur_numenregistrement'] = 'Valeur.numenregistrement';
//
//        // Si il y a une annexe on ajoute les données au fichier au info envoyer a GEDOOO
//        if ($annexe == true) {
//            $compteur = 1;
//            foreach ($fileAnnexes as $fileAnnexe) {
//                $donnees['Valeur']['annexe' . $compteur] = file_get_contents(
//                        CHEMIN_PIECE_JOINT . $fileAnnexe['Fichier']['url']
//                );
//                $types['valeur_annexe' . $compteur] = "file";
//                $correspondances['valeur_annexe' . $compteur] = 'Valeur.annexe' . $compteur;
//                $compteur ++;
//            }
//        }
//
//
//        $pdf = $this->genereFusion($file, $cheminFile, $donnees, $types, $correspondances);
//
//        return $pdf;
//    }

    public function preparationGeneration($tabId, $file, $cheminFile, $idOrganisation, $historique = false) {
        $donnees = [];

        // On récupère et met en forme les informations de l'organisation
        $donnees = $this->_preparationGenerationOrganisation($idOrganisation, $donnees);

        // On récupère et met en forme les numéro d'enregistrement du traitement
        $donnees = $this->_preparationGenerationNumeroEnregistrement($tabId, $donnees);

        /* On met en forme les valeurs du traitement (informations de
         * l'organisation + champ propre au formulaire + informations du CIL +
         * information sur le déclarant au moment de la création du traitement
         */
        foreach ((array)json_decode($tabId) as $key => $id) {
            $donnees['traitement'][$key] = $this->_preparationGenerationValeurTraitement($id, $donnees['traitement'][$key],$historique);
        }

        if ($historique == true) {
            $id = json_decode($tabId);

            $donnees = $this->_preparationGenerationHistorique($id, $donnees);
        }
//        var_dump($donnees);
//        die;
        // On effectue la génération
        $pdf = $this->genereFusion($file, $cheminFile, $donnees);
        return $pdf;
    }

    /**
     * Récupération les inforations à l'instant T de l'organisation puis
     * mise en forme des informations pour l'envoyer à la génération
     *
     * @param int $idOrganisation -> id de l'organisation en cours (en session)
     * @param array() $donnees -> tableau des valeurs déjà en forme pour la génération
     * @return array()
     *
     * @access private
     * @created 04/04/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    private function _preparationGenerationOrganisation($idOrganisation, $donnees) {
        // On récupère en BDD les informations à l'instant T de l'organisation en cours
        $organisation = $this->Organisation->find('first', [
            'conditions' => [
                'id' => $idOrganisation
            ],
            'fields' => [
                'raisonsociale',
                'telephone',
                'fax',
                'adresse',
                'email',
                'sigle',
                'siret',
                'ape',
                'nomresponsable',
                'prenomresponsable',
                'emailresponsable',
                'telephoneresponsable',
                'fonctionresponsable',
                'cil',
                'numerocil',
            ]
        ]);

        // On récupère en BDD les informations sur l'utilisateur qui est CIL
        $cil = $this->User->find('first', [
            'conditions' => [
                'id' => $organisation['Organisation']['cil']
            ],
            'fields' => [
                'civilite',
                'nom',
                'prenom',
                'email'
            ]
        ]);

        /* On remplace dans $organisation l'id de l'utilisateur CIL par
         * sa civilité + prénom + nom
         */
        $organisation['Organisation']['cil'] = $cil['User']['civilite'] . $cil['User']['prenom'] . ' ' . $cil['User']['nom'];
        // On ajoute l'id du CIL
        $organisation['Organisation']['emailcil'] = $cil['User']['email'];

        // On met en forme les informations de l'organisation
        $donnees = [
            'organisation_raisonsociale' => [
                'value' => $organisation['Organisation']['raisonsociale'],
                'type' => 'text'
            ],
            'organisation_telephone' => [
                'value' => $organisation['Organisation']['telephone'],
                'type' => 'text'
            ],
            'organisation_fax' => [
                'value' => $organisation['Organisation']['fax'],
                'type' => 'text'
            ],
            'organisation_adresse' => [
                'value' => $organisation['Organisation']['adresse'],
                'type' => 'text'
            ],
            'organisation_email' => [
                'value' => $organisation['Organisation']['email'],
                'type' => 'text'
            ],
            'organisation_sigle' => [
                'value' => $organisation['Organisation']['sigle'],
                'type' => 'text'
            ],
            'organisation_siret' => [
                'value' => $organisation['Organisation']['siret'],
                'type' => 'text'
            ],
            'organisation_ape' => [
                'value' => $organisation['Organisation']['ape'],
                'type' => 'text'
            ],
            'organisation_nomresponsable' => [
                'value' => $organisation['Organisation']['nomresponsable'],
                'type' => 'text'
            ],
            'organisation_prenomresponsable' => [
                'value' => $organisation['Organisation']['prenomresponsable'],
                'type' => 'text'
            ],
            'organisation_emailresponsable' => [
                'value' => $organisation['Organisation']['emailresponsable'],
                'type' => 'text'
            ],
            'organisation_telephoneresponsable' => [
                'value' => $organisation['Organisation']['telephoneresponsable'],
                'type' => 'text'
            ],
            'organisation_fonctionresponsable' => [
                'value' => $organisation['Organisation']['fonctionresponsable'],
                'type' => 'text'
            ],
            'organisation_cil' => [
                'value' => $organisation['Organisation']['cil'],
                'type' => 'text'
            ],
            'organisation_numerocil' => [
                'value' => $organisation['Organisation']['numerocil'],
                'type' => 'text'
            ],
            'organisation_emailcil' => [
                'value' => $organisation['Organisation']['emailcil'],
                'type' => 'text'
            ]
        ];

        return ($donnees);
    }

    /**
     * Récupération du numéro d'enregistrement du traitement au registre et
     * mise en forme des valeurs pour l'envoyer à la génération
     *
     * @param json $tabId -> id des traitements à générer
     * @param array() $donnees -> tableau des valeurs déjà en forme pour la génération
     * @return array()
     *
     * @access private
     * @created 04/04/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    private function _preparationGenerationNumeroEnregistrement($tabId, $donnees) {
        /* On récupére les valeurs du/des traitement(s) + numéro
         * d'enregistrement du traitement
         */
        $arrayId = (array)json_decode($tabId);
        $numeroEnregistrements = [];
        foreach ($arrayId as $key => $id) {
            $numeroEnregistrements[$key] = $this->find('first', [
                'conditions' => [
                    'id' => $id
                ],
                'fields' => [
                    'numero'
                ]
            ]);
        }

        // On met en forme le numéro d'enregistrement du traitement au registre
        foreach ($numeroEnregistrements as $key => $numeroEnregistrement) {
            $donnees['traitement'][$key]['valeur_numeroenregistrement'] = [
                'value' => $numeroEnregistrement['Fiche']['numero'],
                'type' => 'text'
            ];
        }

        return ($donnees);
    }

    /**
     * On récupére les valeurs du/des traitement(s) + numéro
     * d'enregistrement du traitement
     *
     * @param json $tabId -> id des traitements à générer
     * @param array() $donnees -> tableau des valeurs déjà en forme pour la génération
     * @return array()
     *
     * @access private
     * @created 04/04/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    private function _preparationGenerationValeurTraitement($id, $donnees,$historique) {
        $data = $this->Valeur->find('all', [
            'conditions' => [
                'fiche_id' => $id
            ]
        ]);

        /**
         * On recupere les champs 'deroulant', 'checkboxes', 'radios' qui
         * sont dans le formulaire associer a la fiche
         */
        $idForm = $this->find('first', [
            'conditions' => ['id' => $id]
        ]);

        $typeChamps = ['deroulant', 'checkboxes', 'radios'];
        $champs = ClassRegistry::init('FgChamp')->find('all', [
            'conditions' => [
                'formulaires_id' => $idForm['Fiche']['form_id'],
                'type' => $typeChamps,
            ],
        ]);

        /**
         * On decode les infos du champ details pour ensuite faire
         * un tableau avec le name du champs et les valeurs
         */
        $choixChampMultiple = [];
        $checkBoxField = [];
        foreach ($champs as $value) {
            $options = json_decode($value['FgChamp']['details'], true);

            if ($value['FgChamp']['type'] != 'checkboxes') {
                $choixChampMultiple[$options['name']] = $options['options'];
            } else {
                $checkBoxField[$options['name']] = $options['options'];
            }
        }

        /**
         * On vérifie que le tableau qu'on a créé juste au dessus existe.
         * Si il exite on on prend la valeur de l'id choisit dans le tableau,
         * sinon on prend directement la valeur enregistré dans la table Valeur.
         */
        foreach ($data as $key => $value) {
            if (!empty($choixChampMultiple[$value['Valeur']['champ_name']])) {
                $donnees['valeur_' . $value['Valeur']['champ_name']] = [
                    'value' => $choixChampMultiple[$value['Valeur']['champ_name']][intval($value['Valeur']['valeur'])],
                    'type' => 'text'
                ];
            } elseif (!empty($checkBoxField[$value['Valeur']['champ_name']])) {
                $choixCheckbox = json_decode($value["Valeur"]["valeur"]);

                foreach ($choixCheckbox as $key => $choix) {
                    $donnees['checkboxes'][$key]['valeur_' . $value['Valeur']['champ_name']] = [
                        'value' => $checkBoxField[$value['Valeur']['champ_name']][$choix],
                        'type' => 'text'
                    ];
                }
            } else {
                $donnees['valeur_' . $value['Valeur']['champ_name']] = [
                    'value' => $value['Valeur']['valeur'],
                    'type' => 'text'
                ];
            }
        }
        unset($donnees['valeur_fichiers']);

        if ($historique == true) {
            $donnees = $this->_preparationAnnexe($id, $donnees);
        }

        return ($donnees);
    }

    private function _preparationAnnexe($id, $donnees) {
        $annexe = false;

        //On chercher si le traitement comporte des annexe(s)
        $fileAnnexes = $this->Fichier->find('all', [
            'conditions' => [
                'fiche_id' => $id
            ]
        ]);

        if (!empty($fileAnnexes)) {
            $annexe = true;
        }

        if ($annexe == true) {
            foreach ($fileAnnexes as $key => $fileAnnexe) {
                $donnees['fichiers'][$key]['valeur_annexe'] = [
                    'value' => file_get_contents(
                            CHEMIN_PIECE_JOINT . $fileAnnexe['Fichier']['url']
                    ),
                    'type' => 'file'
                ];
            }
        }

        return ($donnees);
    }

    /**
     *
     * @param type $id
     * @param array $donnees
     * @return type
     *
     * @access private
     * @created 04/04/2017
     * @version V1.0.0
     * @author Théo GUILLON <theo.guillon@libriciel.coop>
     */
    private function _preparationGenerationHistorique($id, $donnees) {
        $historiques = $this->Historique->find('all', [
            'conditions' => [
                'fiche_id' => $id
            ],
            'fields' => [
                'content',
                'created'
            ]
        ]);

        foreach ($historiques as $historique) {
            $donnees['historiques'][] = [
                'created' => ['value' => $historique['Historique']['created'], 'type' => 'date'],
                'content' => ['value' => $historique['Historique']['content'], 'type' => 'text']
            ];
        }

        return ($donnees);
    }

    /**
     * Génération PDF à la volée
     *
     * @param type $file
     * @param type $cheminFile
     * @param type $donnees
     * @return type
     *
     * @access public
     * @created 04/01/2016
     * @version V1.0.0
     */
    private function genereFusion($file, $cheminFile, $donnees) {
        App::uses('FusionConvBuilder', 'FusionConv.Utility');
        App::uses('FusionConvDebugger', 'FusionConv.Utility');
        App::uses('FusionConvConverterCloudooo', 'FusionConv.Utility/Converter');

        $MainPart = new phpgedooo_client\GDO_PartType();

//        echo '<pre>'.var_export($donnees, true).'</pre>';
        $data = array();
        $types = array();
        $correspondances = array();

        $this->_format($MainPart, $donnees, $data, $types, $correspondances);

        $Document = FusionConvBuilder::main($MainPart, $data, $types, $correspondances);

        $sMimeType = 'application/vnd.oasis.opendocument.text';

        $Template = new phpgedooo_client\GDO_ContentType("", 'model.odt', "application/vnd.oasis.opendocument.text", "binary", file_get_contents($cheminFile . $file));
        $Fusion = new phpgedooo_client\GDO_FusionType($Template, $sMimeType, $Document);

        $Fusion->process();
        $pdf = FusionConvConverterCloudooo::convert($Fusion->getContent()->binary);

        return $pdf;
    }

    /**
     *
     * @param type $MainPart
     * @param type $aData
     * @param type $data
     * @param type $types
     * @param type $correspondances
     */
    private function _format(&$MainPart, &$aData, &$data, &$types, &$correspondances) {
        if (!empty($aData)) {
            foreach ($aData as $key => $value) {
                if (is_array($value) && !empty($value[0])) {
                    $this->_formatIteration($MainPart, $key, $value);
                } elseif (isset($value['value']) && isset($value['type'])) {
                    $data[$key] = $value['value'];
                    $types[$key] = $value['type'];
                    $correspondances[$key] = $key;
                }
            }
        }
    }

    /**
     *
     * @param type $MainPart
     * @param type $iteration
     * @param type $aData
     */
    private function _formatIteration(&$MainPart, $iteration, &$aData) {
        foreach ($aData as $key => $value) {
            $dataIteration = array();
            foreach ($value as $keyIteration => $valueIteration) {
                if (isset($valueIteration[0])) {
                    $this->_formatIterationChild($dataIteration, $keyIteration, $valueIteration, $typesIteration, $correspondancesIteration);
                } elseif (isset($valueIteration['type'])) {
                    $dataIteration[$keyIteration] = $valueIteration['value'];
                    $typesIteration[$iteration . '.' . $keyIteration] = $valueIteration['type'];
                    $correspondancesIteration[$iteration . '.' . $keyIteration] = $keyIteration;
                }
            }
            $aDataIteration[$iteration][] = $dataIteration;
        }

        FusionConvBuilder::iteration($MainPart, $iteration, $aDataIteration, $typesIteration, $correspondancesIteration);
    }

    /**
     *
     * @param type $dataIteration
     * @param type $iteration
     * @param type $aData
     * @param type $typesIteration
     * @param type $correspondancesIteration
     */
    private function _formatIterationChild(&$dataIteration, $iteration, $aData, &$typesIteration, &$correspondancesIteration) {
        foreach ($aData as $key => $value) {
            $dataIterationchild = array();
            foreach ($value as $keyIteration => $valueIteration) {
                if (isset($valueIteration[0])) {
                    $this->_formatIterationChild($dataIterationchild, $keyIteration, $valueIteration, $typesIteration, $correspondancesIteration);
                } elseif (isset($valueIteration['type'])) {
                    $dataIterationchild[$keyIteration] = $valueIteration['value'];
                    $typesIteration[$iteration . '.' . $keyIteration] = $valueIteration['type'];
                    $correspondancesIteration[$iteration . '.' . $keyIteration] = $keyIteration;
                }
            }
            $dataIteration[$iteration][] = $dataIterationchild;
        }
    }

}
