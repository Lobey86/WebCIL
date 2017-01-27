<?php
/**
 * ModeleExtraitRegistreController
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

class ModeleExtraitRegistresController extends AppController {

    public $uses = array(
        'ModeleExtraitRegistre',
    );

    /**
     * @access public
     * @created 26/12/2016
     * @version V1.0.0
     */
    public function add() {
        $saveFile = $this->ModeleExtraitRegistre->saveFile($this->request->data, $this->Session->read('Organisation.id'));

        if ($saveFile == 0) {
            $this->Session->setFlash(__d('modele', 'modele.flashsuccessModeleEnregistrer'), 'flashsuccess');
        } elseif ($saveFile == 1) {
            $this->Session->setFlash(__d('modele', 'modele.flasherrorFicherTropLourd'), 'flasherror');
        } elseif ($saveFile == 2) {
            $this->Session->setFlash(__d('modele', 'modele.flasherrorExtensionNonValide'), 'flasherror');
        } elseif ($saveFile == 3) {
            $this->Session->setFlash(__d('modele', 'modele.flashwarningAucunFichier'), 'flashwarning');
        }

        $this->redirect(array(
            'controller' => 'modeleExtraitRegistres',
            'action' => 'index'
        ));
    }
    
    /**
     * Fonction pour téléchargé le modele de l'extrait de registre
     * 
     * @param type $file
     * @return type
     * 
     * @access public
     * @created 28/12/2016
     * @version V1.0.0
     */
    public function download($file, $nameFile) {
        $this->response->file(CHEMIN_MODELES_EXTRAIT . $file, array(
            'download' => true,
            'name' => $nameFile
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
        $modeles = $this->ModeleExtraitRegistre->find('all', array(
            'conditions' => array('fichier' => $file)
        ));
        
        if ($modeles) {
            $isDeleted = $this->ModeleExtraitRegistre->deleteAll(array(
                'fichier' => $file
            ));

            if ($isDeleted) {
                $this->Session->setFlash(__d('modele', 'modele.flashsuccessModeleSupprimer'), 'flashsuccess');
            } else {
                $this->Session->setFlash(__d('modele', 'modele.flasherrorErreurSupprimerModele'), 'flasherror');
            }
        } else {
            $this->Session->setFlash(__d('modele', 'modele.flasherrorModeleInexistant'), 'flasherror');
        }

        $this->redirect($this->referer());
    }
    
    public function index() {
        $this->set('title', __d('modele', 'modele.titreModeleExtraitRegistre'));

        $modelesExtrait = $this->ModeleExtraitRegistre->find('all', [
            'conditions' => [
                'organisations_id' => $this->Session->read('Organisation.id')
            ]
        ]);
        $this->set(compact('modelesExtrait'));
    }

}
