<?php

/**
 * Model File
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

class File extends AppModel {

    public $name = 'File';

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
        'Fiche' => array(
            'className' => 'Fiche',
            'foreignKey' => 'fiche_id'
        )
    );

    /**
     * @param type $data
     * @param int|null $id
     * @return boolean
     * 
     * @access public
     * @created 29/04/2015
     * @version V0.9.0
     */
    public function saveFile($data, $id = null) {

        if (isset($data['Fiche']['fichiers']) && !empty($data['Fiche']['fichiers'])) {

            foreach ($data['Fiche']['fichiers'] as $key => $file) {
                $success = true;
                if (!empty($file['name'])) {
                    $this->begin();
                    $folder = WWW_ROOT . 'files';
                    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $name = $file['name'];
                    if (!empty($file['tmp_name'])) {
                        $url = time();
                        $success = $success && move_uploaded_file($file['tmp_name'], $folder . '/' . $url . $key . '.' . $extension);
                        if ($success) {
                            $this->create(array(
                                'nom' => $name,
                                'url' => $url . $key . '.' . $extension,
                                'fiche_id' => $id
                            ));
                            $success = $success && $this->save();
                        }
                    } else {
                        $success = false;
                    }
                }

                if ($success) {
                    $this->commit();
                } else {
                    $this->rollback();
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param int $id
     * @return boolean
     * 
     * @access public
     * @created 26/06/2015
     * @version V0.9.0
     */
    public function deleteFile($id) {
        $success = true;
        $this->begin();
        $fichier = $this->find('first', array('conditions' => array('id' => $id)));
        debug($fichier);
        debug($id);
        $success = $success && unlink(WWW_ROOT . 'files/' . $fichier['File']['url']);
        $success = $success && $this->delete($id);
        if ($success) {
            $this->commit();
            return true;
        } else {
            $this->rollback();
            return false;
        }
    }

}
