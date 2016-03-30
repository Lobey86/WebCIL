<?php

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

/**
 * ModelesController
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
class ModelesController extends AppController {

    public $uses = array(
        'Modele',
        'Formulaire'
    );

    /**
     * @access public
     * @created 18/06/2015
     * @version V0.9.0
     */
    public function index() {
        $this->set('title', 'Liste des modèles');
        $modeles = $this->Formulaire->find('all', array(
            'contain' => array('Modele'),
            'conditions' => array('organisations_id' => $this->Session->read('Organisation.id'))
        ));
        $this->set(compact('modeles'));
    }

    /**
     * @access public
     * @created 18/06/2015
     * @version V0.9.0
     */
    public function add() {
        if ($this->Modele->saveFile($this->request->data, $this->request->data['Modele']['idUploadModele'])) {
            $this->Session->setFlash('Modele enregistré', 'flashsuccess');
            $this->redirect(array(
                'controller' => 'modeles',
                'action' => 'index'
            ));
        }
    }

    /**
     * @param type $file
     * @return type
     * 
     * @access public
     * @created 18/06/2015
     * @version V0.9.0
     */
    public function download($file) {
        $this->response->file('files/modeles/' . $file, array(
            'download' => true,
            'name' => $file
        ));
        return $this->response;
    }

    /**
     * Permet de supprimer en bdd de le model associer par qu'ontre on ne 
     * supprime dans aucun cas le fichier enregistré
     * 
     * @param type $file --> c'est le nom du model (en générale 15614325.odt)
     * qui est enregistré dans app/webroot/files/models
     */
    public function delete($file) {
        $modeles = $this->Modele->find('all', array(
            'conditions' => array('fichier' => $file)
        ));
        if ($modeles) {
            $isDeleted = $this->Modele->deleteAll(array(
                'fichier' => $file
            ));

            if ($isDeleted) {
                $this->Session->setFlash('Le model a été supprimé', 'flashsuccess');
            } else {
                $this->Session->setFlash('Impossible de supprimer le model', 'flasherror');
            }
        } else {
            $this->Session->setFlash('Ce model n\'existe pas', 'flasherror');
        }
        
        $this->redirect($this->referer());
    }
}
