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
 * @since       webcil V1.0.0
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 * @version     V1.0.0
 * @package     App.Controller
 */
class ModelesController extends AppController {

    public $uses = array(
        'Modele',
        'Formulaire',
        'FormGenerator.Champ',
        'Fiche',
        'Valeur',
        'Organisation',
        'User'
    );

    /**
     * @access public
     * @created 18/06/2015
     * @version V1.0.0
     */
    public function index() {
        // Superadmin non autorisé
        if ($this->Droits->isSu() == true) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }
        
        $this->set('title', __d('modele', 'modele.titreListeModele'));
        $modeles = $this->Formulaire->find('all', array(
            'contain' => array('Modele'),
            'conditions' => array('organisations_id' => $this->Session->read('Organisation.id'))
        ));
        $this->set(compact('modeles'));
    }

    /**
     * @access public
     * @created 18/06/2015
     * @version V1.0.0
     */
    public function add() {
        // Superadmin non autorisé
        if ($this->Droits->isSu() == true) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }
        
        $saveFile = $this->Modele->saveFile($this->request->data, $this->request->data['Modele']['idUploadModele']);

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
            'controller' => 'modeles',
            'action' => 'index'
        ));
    }

    /**
     * @param type $file
     * @return type
     * 
     * @access public
     * @created 18/06/2015
     * @version V1.0.0
     */
    public function download($file, $nameFile) {
        $this->response->file(CHEMIN_MODELES . $file, array(
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
        $modeles = $this->Modele->find('all', array(
            'conditions' => array('fichier' => $file)
        ));
        
        if ($modeles) {
            $isDeleted = $this->Modele->deleteAll(array(
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

    /**
     * 
     * @access public
     * @created 26/04/2016
     * @version V1.0.2
     */
    public function infoVariable($idFormulaire = null) {
        $this->set('title', __d('modele', 'modele.titreInfoVariableModele'));

        //Information sur l'organisation
        $valeurOrganisations = $this->Organisation->find('all', array(
            'conditions' => array(
                'id' => $this->Session->read('Organisation.id')
            ),
            'fields' => array(
                'raisonsociale',
                'telephone',
                'fax',
                'adresse',
                'email',
                'sigle',
                'siret',
                'ape',
                'numerocil'
            )
        ));
        $valeurOrganisations = Hash::extract($valeurOrganisations, '{n}.Organisation');
        $this->set(compact('valeurOrganisations'));

        //Information sur le responsable de l'organisation
        $responsableOrganisations = $this->Organisation->find('all', array(
            'conditions' => array(
                'id' => $this->Session->read('Organisation.id')
            ),
            'fields' => array(
                'nomresponsable',
                'prenomresponsable',
                'emailresponsable',
                'telephoneresponsable',
                'fonctionresponsable'
            )
        ));
        $responsableOrganisations = Hash::extract($responsableOrganisations, '{n}.Organisation');
        $this->set(compact('responsableOrganisations'));

        //Information sur le CIL de l'entité
        $cilOrganisation = $this->Organisation->find('all', array(
            'conditions' => array(
                'id' => $this->Session->read('Organisation.id')
            )
        ));
        $cilOrganisation = Hash::extract($cilOrganisation, '{n}.Organisation.cil');

        $userCIL = $this->User->find('all', [
            'conditions' => [
                'id' => $cilOrganisation
            ],
            'fields' => [
                'civilite',
                'nom',
                'prenom',
                'email'
            ]
        ]);
        $userCIL = Hash::extract($userCIL, '{n}.User');
        $this->set(compact('userCIL'));

        if ($idFormulaire != null) {
            //information sur les champs du formulaire
            $variables = $this->Champ->find('all', array(
                'conditions' => array(
                    'formulaires_id' => $idFormulaire,
                ),
                'fields' => array("type", "details")
            ));
            $variables = Hash::extract($variables, '{n}.Champ');
            $this->set(compact('variables'));
        }
        
    }

}
