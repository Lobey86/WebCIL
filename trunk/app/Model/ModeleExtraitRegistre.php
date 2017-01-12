<?php

/**
 * Model ModeleExtraitRegistre
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
App::uses('AppModel', 'ModeleExtraitRegistre');

class ModeleExtraitRegistre extends AppModel {

    public $name = 'ModeleExtraitRegistre';

    /**
     * belongsTo associations
     * 
     * @var array
     * 
     * @access public
     * @created 18/06/2015
     * @version V0.9.0
     */
    public $belongsTo = array(
        'Organisation' => array(
            'className' => 'Organisation',
            'foreignKey' => 'organisations_id'
        )
    );
    
    /**
     * @param type $data
     * @param int|null $id
     * @return boolean
     * 
     * @access public
     * @created 26/12/2016
     * @version V1.0.0
     */
    public function saveFile($data, $id = null) {
        if (isset($data['modeleExtraitRegistre']['modeleExtraitRegistre']) && !empty($data['modeleExtraitRegistre']['modeleExtraitRegistre'])) {
            $file = $data['modeleExtraitRegistre']['modeleExtraitRegistre'];
            $success = true;

            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if ($extension == 'odt') {
                if (!empty($file['name'])) {
                    $this->begin();

                    // On verifie si le dossier file existe. Si c'est pas le cas on le cree
                    if (!file_exists(APP . FICHIER)) {
                        mkdir(APP . FICHIER, 0777, true);
                        mkdir(APP . FICHIER . PIECE_JOINT, 0777, true);
                        mkdir(APP . FICHIER . MODELES, 0777, true);
                        mkdir(CHEMIN_MODELES_EXTRAIT, 0777, true);
                        mkdir(APP . FICHIER . REGISTRE, 0777, true);
                    } else {
                        if (!file_exists(APP . FICHIER . MODELES)) {
                            mkdir(APP . FICHIER . MODELES, 0777, true);
                        }
                        
                        if (!file_exists(CHEMIN_MODELES_EXTRAIT)) {
                            mkdir(CHEMIN_MODELES_EXTRAIT, 0777, true);
                        }
                    }
                    if (!empty($file['tmp_name'])) {
                        $url = time();
                        $success = $success && move_uploaded_file($file['tmp_name'], CHEMIN_MODELES_EXTRAIT . $url . '.' . $extension);
                        if ($success) {
                            $this->deleteAll(array('organisations_id' => $id));
                            $this->create(array(
                                'fichier' => $url . '.' . $extension,
                                'organisations_id' => $id,
                                'name_modele' => $file['name']
                            ));
                            $success = $success && $this->save();
                        }
                    } else {
                        $success = false;
                    }
                } else {
                    $success = false;
                }

                if ($success) {
                    $this->commit();
                    return (0);
                } else {
                    $this->rollback();
                    return (1);
                }
            } else {
                return (2);
            }
        }

        return (3);
    }
}
