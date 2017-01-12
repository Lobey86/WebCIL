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

class Fiche extends AppModel {

    public $name = 'Fiche';

    /**
     * hasOne associations
     * 
     * @var array
     * 
     * @access public
     * @created 04/01/2016
     * @version V0.9.0
     */
    public $hasOne = array(
        'Extrait' => array(
            'className' => 'Extrait',
            'foreignKey' => 'fiche_id'
        ),
    );

    /**
     * belongsTo associations
     * 
     * @var array
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
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
     * @version V0.9.0
     */
    public $hasMany = array(
        'Fichier' => array(
            'className' => 'Fichier',
            'foreignKey' => 'fiche_id',
            'dependent' => true,
        ),
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
     * @version V0.9.0
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
     * @version V0.9.0
     */
    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    
    
    
       /**
     * 
     * @param int $id
     * @param char $numeroRegistre
     * @param bool $save
     * @return type
     */
    public function genereTraitement($id, $numeroRegistre ) {
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

        $data = $this->Valeur->find('all', [
            'conditions' => [
                'fiche_id' => $id
            ]
        ]);

        $fiche = $this->find('first', [
            'conditions' => [
                'id' => $id
            ]
        ]);

        $modele = ClassRegistry::init('Modele')->find('first', [
            'conditions' => [
                'formulaires_id' => $fiche['Fiche']['form_id']
            ]
        ]);

        if (!empty($modele)) {
            $file = $modele['Modele']['fichier'];
        } else {
            $file = '1.odt';
        }

        $cheminFile = CHEMIN_MODELES;

        /**
         * On recupere les champs 'deroulant', 'checkboxes', 'radios' qui 
         * sont dans le formulaire associer a la fiche
         */
        $typeChamps = ['deroulant', 'checkboxes', 'radios'];
        $idForm = $this->find('first', [
            'conditions' => ['id' => $id]
        ]);

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
        $donnees = [];
        foreach ($data as $key => $value) {
            if (!empty($choixChampMultiple[$value['Valeur']['champ_name']])) {
                $donnees['Valeur'][$value['Valeur']['champ_name']] = $choixChampMultiple[$value['Valeur']['champ_name']][intval($value['Valeur']['valeur'])];
            } elseif (!empty($checkBoxField[$value['Valeur']['champ_name']])) {
                $choixCheckbox = json_decode($value["Valeur"]["valeur"]);
                $nombreChoixCheckbox = sizeof($choixCheckbox);

                $tampon = null;
                for ($compteur = 0; $compteur < $nombreChoixCheckbox; $compteur++) {
                    if ($compteur === 0) {
                        $tampon = $checkBoxField[$value['Valeur']['champ_name']][$compteur];
                    } else if ($compteur < $nombreChoixCheckbox && $compteur != 0) {
                        $tampon = $tampon . ' , ' . $checkBoxField[$value['Valeur']['champ_name']][$compteur];
                    }
                }
                $donnees['Valeur'][$value['Valeur']['champ_name']] = $tampon;
            } else {
                $donnees['Valeur'][$value['Valeur']['champ_name']] = $value['Valeur']['valeur'];
            }
        }
        unset($donnees['Valeur']['fichiers']);

        $types = [];
        foreach ($donnees['Valeur'] as $key => $value) {
            $types['valeur_' . $key] = 'text';
        }

        $correspondances = [];
        foreach ($donnees['Valeur'] as $key => $value) {
            $correspondances['valeur_' . $key] = 'Valeur.' . $key;
        }

        // On donne le numéro d'enregistrement au registre du traitement
        $donnees['Valeur']['numenregistrement'] = $numeroRegistre;
        $types['valeur_numenregistrement'] = 'text';
        $correspondances['valeur_numenregistrement'] = 'Valeur.numenregistrement';

        // Si il y a une annexe on ajoute les données au fichier au info envoyer a GEDOOO
        if ($annexe == true) {
            $compteur = 1;
            foreach ($fileAnnexes as $fileAnnexe) {
                $donnees['Valeur']['annexe' . $compteur] = file_get_contents(
                        CHEMIN_PIECE_JOINT . $fileAnnexe['Fichier']['url']
                );
                $types['valeur_annexe' . $compteur] = "file";
                $correspondances['valeur_annexe' . $compteur] = 'Valeur.annexe' . $compteur;
                $compteur ++;
            }
        }
       

        $pdf = $this->genereFusion($file, $cheminFile, $donnees, $types, $correspondances);

        return $pdf;
    }

    public function genereExtrait($id, $numeroRegistre, $modele ) {
        $data = $this->Valeur->find('all', [
            'conditions' => [
                'fiche_id' => $id
            ]
        ]);

        if (!empty($modele)) {
            $file = $modele['ModeleExtraitRegistre']['fichier'];
        } else {
            $file = '1.odt';
        }

        $cheminFile = CHEMIN_MODELES_EXTRAIT;

        /**
         * On recupere les champs 'deroulant', 'checkboxes', 'radios' qui 
         * sont dans le formulaire associer a la fiche
         */
        $typeChamps = ['deroulant', 'checkboxes', 'radios'];
        $idForm = $this->find('first', [
            'conditions' => ['id' => $id]
        ]);

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
        $donnees = [];
        foreach ($data as $key => $value) {
            if (!empty($choixChampMultiple[$value['Valeur']['champ_name']])) {
                $donnees['Valeur'][$value['Valeur']['champ_name']] = $choixChampMultiple[$value['Valeur']['champ_name']][intval($value['Valeur']['valeur'])];
            } elseif (!empty($checkBoxField[$value['Valeur']['champ_name']])) {
                $choixCheckbox = json_decode($value["Valeur"]["valeur"]);
                $nombreChoixCheckbox = sizeof($choixCheckbox);

                $tampon = null;
                for ($compteur = 0; $compteur < $nombreChoixCheckbox; $compteur++) {
                    if ($compteur === 0) {
                        $tampon = $checkBoxField[$value['Valeur']['champ_name']][$compteur];
                    } else if ($compteur < $nombreChoixCheckbox && $compteur != 0) {
                        $tampon = $tampon . ' , ' . $checkBoxField[$value['Valeur']['champ_name']][$compteur];
                    }
                }
                $donnees['Valeur'][$value['Valeur']['champ_name']] = $tampon;
            } else {
                $donnees['Valeur'][$value['Valeur']['champ_name']] = $value['Valeur']['valeur'];
            }
        }
        unset($donnees['Valeur']['fichiers']);

        $types = [];
        foreach ($donnees['Valeur'] as $key => $value) {
            $types['valeur_' . $key] = 'text';
        }

        $correspondances = [];
        foreach ($donnees['Valeur'] as $key => $value) {
            $correspondances['valeur_' . $key] = 'Valeur.' . $key;
        }

        // On donne le numéro d'enregistrement au registre du traitement
        $donnees['Valeur']['numenregistrement'] = $numeroRegistre;
        $types['valeur_numenregistrement'] = 'text';
        $correspondances['valeur_numenregistrement'] = 'Valeur.numenregistrement';

        $pdf = $this->genereFusion($file, $cheminFile, $donnees, $types, $correspondances);

        return $pdf;
    }
    
        /**
     * Génération PDF à la volée
     * 
     * @param int $id
     * @param type|false $save
     * @param type $numeroRegistre
     * @return type
     * 
     * @access public
     * @created 04/01/2016
     * @version V0.9.0
     */
    private function genereFusion($file, $cheminFile, $donnees, $types, $correspondances) {
        App::uses('FusionConvBuilder', 'FusionConv.Utility');
        App::uses('FusionConvConverterCloudooo', 'FusionConv.Utility/Converter');

        $MainPart = new GDO_PartType();

        $Document = FusionConvBuilder::main($MainPart, $donnees, $types, $correspondances);

        $sMimeType = 'application/vnd.oasis.opendocument.text';

        $Template = new GDO_ContentType("", 'model.odt', "application/vnd.oasis.opendocument.text", "binary", file_get_contents($cheminFile . $file));
        $Fusion = new GDO_FusionType($Template, $sMimeType, $Document);

        $Fusion->process();
        $pdf = FusionConvConverterCloudooo::convert($Fusion->getContent()->binary);

        return $pdf;
    }

}
