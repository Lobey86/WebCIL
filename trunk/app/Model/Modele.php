<?php

/**
 * Model Modele
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

class Modele extends AppModel {

    public $name = 'Modele';

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
        'Formulaire' => array(
            'className' => 'Formulaire',
            'foreignKey' => 'formulaires_id'
        )
    );

    /**
     * @param type $data
     * @param int|null $id
     * @return boolean
     * 
     * @access public
     * @created 18/06/2015
     * @version V0.9.0
     */
    public function saveFile($data, $id = null) {
        if (isset($data['Modele']['modele']) && !empty($data['Modele']['modele'])) {
            $file = $data['Modele']['modele'];
            $success = true;

            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if ($extension == 'odt') {
                if (!empty($file['name'])) {
                    $this->begin();
                    $folder = WWW_ROOT . 'files/modeles';

                    if (!empty($file['tmp_name'])) {
                        $url = time();
                        $success = $success && move_uploaded_file($file['tmp_name'], $folder . '/' . $url . '.' . $extension);
                        if ($success) {
                            $adel = $this->find('all', array('conditions' => array('formulaires_id' => $id)));
                            foreach ($adel as $value) {
                                //unlink($folder . '/' . $value['Modele']['fichier']);
                            }
                            $this->deleteAll(array('formulaires_id' => $id));
                            $this->create(array(
                                'fichier' => $url . '.' . $extension,
                                'formulaires_id' => $id,
                                'name_fichier' => $file['name']
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
