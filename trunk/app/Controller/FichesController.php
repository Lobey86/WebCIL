<?php

/**
 * FichesController
 * Controller des fiches
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
class FichesController extends AppController {

    public $helpers = [
        'Html',
        'Form',
        'Session'
    ];
    public $uses = [
        'Fiche',
        'Organisation',
        'File',
        'EtatFiche',
        'Historique',
        'FormGeneric',
        'FormGenerator.Champ',
        'Valeur',
        'Modele',
        'Extrait'
    ];

    /**
     * La page d'accueil des fiches est celle du pannel général
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function index() {
        $this->redirect([
            'controller' => 'pannel',
            'action' => 'index'
        ]);
    }

    /**
     * Gère l'ajout de fiches
     * 
     * @param int|null $id
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function add($id = null) {

        $this->set('title', 'Création d\'une fiche');
        if ($this->Droits->authorized(1)) {
            if ($this->request->is('POST')) {
                $this->Fiche->create([
                    'user_id' => $this->Auth->user('id'),
                    'form_id' => $this->request->data['Fiche']['formulaire_id'],
                    'organisation_id' => $this->Session->read('Organisation.id')
                ]);
                if ($this->Fiche->save()) {
                    $last = $this->Fiche->getLastInsertID();
                    if ($this->File->saveFile($this->request->data, $last)) {
                        foreach ($this->request->data['Fiche'] as $key => $value) {
                            if ($key != 'formulaire_id') {
                                if (is_array($value)) {
                                    $value = json_encode($value);
                                }
                                $this->Valeur->create([
                                    'champ_name' => $key,
                                    'fiche_id' => $last,
                                    'valeur' => $value
                                ]);
                                $this->Valeur->save();
                            }
                        }
                        $this->Historique->create([
                            'Historique' => [
                                'content' => 'Création de la fiche par ' . $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom'),
                                'fiche_id' => $last
                            ]
                        ]);
                        $this->Historique->save();
                        $this->EtatFiche->create([
                            'EtatFiche' => [
                                'fiche_id' => $last,
                                'etat_id' => 1,
                                'previous_user_id' => $this->Auth->user('id'),
                                'user_id' => $this->Auth->user('id')
                            ]
                        ]);
                        if ($this->EtatFiche->save()) {
                            $this->Session->setFlash('La fiche a été enregistrée', 'flashsuccess');
                            $this->redirect([
                                'action' => 'index'
                            ]);
                        }
                    }
                }
            } else {
                $champs = $this->Champ->find('all', [
                    'conditions' => ['formulaires_id' => $id],
                    'order' => [
                        'colonne ASC',
                        'ligne ASC'
                    ]
                ]);
                $this->set(compact('champs'));
                $this->set('formulaireid', $id);
            }
        } else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Gère la suppression de fiches
     * 
     * @param int|null $id
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function delete($id = null) {
        if ($this->Droits->authorized(1) && $this->Droits->isOwner($id)) {
            if (!$this->Droits->isdeletable($id)) {
                $this->Session->setFlash('Vous n\'avez pas accès à cette fiche', 'flasherror');
                $this->redirect([
                    'controller' => 'pannel',
                    'action' => 'index'
                ]);
            }
            $this->Fiche->delete($id);
            $this->Session->setFlash('La fiche a bien été supprimée', 'flashsuccess');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        } else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
    }

    /**
     * Gère l'édition de fiches
     * 
     * @param int|null $id
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function edit($id = null) {
        $this->set('title', 'Edition d\'une fiche');
        if (!$id && !$this->request->data['Fiche']['id']) {
            $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
        if (!$id) {
            $id = $this->request->data['Fiche']['id'];
        }
        if (!$this->Droits->isEditable($id)) {
            $this->Session->setFlash('Vous n\'avez pas accès à cette fiche', 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
        if ($this->request->is([
                    'post',
                    'put'
                ])
        ) {

            foreach ($this->request->data['Fiche'] as $key => $value) {
                $this->Valeur->begin();
                $this->Valeur->deleteAll([
                    'champ_name' => $key,
                    'fiche_id' => $id
                ]);
                if ($key != 'formulaire_id') {
                    if (is_array($value)) {
                        $value = json_encode($value);
                    }
                    $this->Valeur->create([
                        'champ_name' => $key,
                        'fiche_id' => $id,
                        'valeur' => $value
                    ]);
                    $this->Valeur->save();
                    $this->Valeur->commit();
                }
            }
            if ($this->File->saveFile($this->request->data, $id)) {
                $this->Historique->create([
                    'Historique' => [
                        'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' modifie la fiche',
                        'fiche_id' => $id
                    ]
                ]);
                $this->Historique->save();
            }
            if (isset($this->request->data['delfiles']) && !empty($this->request->data['delfiles'])) {
                foreach ($this->request->data['delfiles'] as $val) {
                    $this->File->deleteFile($val);
                }
            }
            $this->Session->setFlash('La fiche a été modifiée', 'flashsuccess');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        } else {
            $idForm = $this->Fiche->find('first', ['conditions' => ['id' => $id]]);
            $champs = $this->Champ->find('all', [
                'conditions' => ['formulaires_id' => $idForm['Fiche']['form_id']],
                'order' => [
                    'colonne ASC',
                    'ligne ASC'
                ]
            ]);
            $files = $this->File->find('all', ['conditions' => ['fiche_id' => $id]]);
            $this->set(compact('files'));
            $valeurs = $this->Valeur->find('all', ['conditions' => ['fiche_id' => $id]]);
            foreach ($valeurs as $key => $value) {
                if ($this->Fiche->isJson($value['Valeur']['valeur'])) {
                    $this->request->data['Fiche'][$value['Valeur']['champ_name']] = json_decode($value['Valeur']['valeur']);
                } else {
                    $this->request->data['Fiche'][$value['Valeur']['champ_name']] = $value['Valeur']['valeur'];
                }
            }
            $this->set(compact('valeurs'));
            $this->set(compact('champs'));
            $this->set('id', $id);
        }
    }

    /**
     * Gère l'affichage des fiches
     * 
     * @param int|null $id
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function show($id = null) {
        $this->set('title', 'Apercu d\'une fiche');
        if (!$id) {
            $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
        if (!$this->Droits->isReadable($id)) {
            $this->Session->setFlash('Vous n\'avez pas accès à cette fiche', 'flasherror');
            $this->redirect([
                'controller' => 'pannel',
                'action' => 'index'
            ]);
        }
        $idForm = $this->Fiche->find('first', ['conditions' => ['id' => $id]]);
        $champs = $this->Champ->find('all', [
            'conditions' => ['formulaires_id' => $idForm['Fiche']['form_id']],
            'order' => [
                'colonne ASC',
                'ligne ASC'
            ]
        ]);
        $valeurs = $this->Valeur->find('all', ['conditions' => ['fiche_id' => $id]]);
        foreach ($valeurs as $key => $value) {
            if ($this->Fiche->isJson($value['Valeur']['valeur'])) {
                $this->request->data['Fiche'][$value['Valeur']['champ_name']] = json_decode($value['Valeur']['valeur']);
            } else {
                $this->request->data['Fiche'][$value['Valeur']['champ_name']] = $value['Valeur']['valeur'];
            }
        }
        $this->set(compact('valeurs'));
        $this->set(compact('champs'));
        $this->set('id', $id);
        $files = $this->File->find('all', ['conditions' => ['fiche_id' => $id]]);
        $this->set(compact('files'));
    }

    /**
     * Gère le téléchargement des pieces jointes d'une fiche
     * 
     * @param int|null $url
     * 
     * @access public
     * @created 17/06/2015
     * @version V0.9.0
     */
    public function download($url = null) {
        $this->response->file(WWW_ROOT . 'files/' . $url, [
            'download' => TRUE,
            'name' => 'file'
        ]);
    }

    /**
     * Gère le téléchargement des extraits de registre
     * 
     * @param int $id_fiche
     * 
     * @access public
     * @created 04/01/2016
     * @version V0.9.0
     */
    public function downloadFile($id_fiche) {
        $data = $this->Valeur->find('all', ['conditions' => ['fiche_id' => $id_fiche]]);

        $pdf = $this->Extrait->find('first', [
            'conditions' => ['id_fiche' => $id_fiche],
            'fields' => ['data']
        ]);

        header("content-type: application/pdf");
        header('Content-Disposition: attachment; filename="' . $data[11]['Valeur']['valeur'] . '_CIL00' . $id_fiche . '.pdf"');
        echo($pdf['Extrait']['data']);
    }

    /**
     * Génération PDF à la volée
     * 
     * @param int $id
     * @param type|false $save
     * @return type
     * 
     * @access public
     * @created 04/01/2016
     * @version V0.9.0
     */
    public function genereFusion($id, $save = false) {
        App::uses('FusionConvBuilder', 'FusionConv.Utility');

        $data = $this->Valeur->find('all', ['conditions' => ['fiche_id' => $id]]);
        $fiche = $this->Fiche->find('first', ['conditions' => ['id' => $id]]);
        $modele = $this->Modele->find('first', ['conditions' => ['formulaires_id' => $fiche['Fiche']['form_id']]]);

        if (!empty($modele)) {
            $file = $modele['Modele']['fichier'];
        } else {
            $file = '1.odt';
        }

        $donnees = [];
        foreach ($data as $key => $value) {
            $donnees['Valeur'][$value['Valeur']['champ_name']] = $value['Valeur']['valeur'];
        }
        unset($donnees['Valeur']['fichiers']);


        $types = [];
        foreach ($donnees['Valeur'] as $key => $value) {
            $types['Valeur.' . $key] = 'text';
        }

        $correspondances = [];
        foreach ($donnees['Valeur'] as $key => $value) {
            $correspondances['valeur_' . $key] = 'Valeur.' . $key;
        }

        $MainPart = new GDO_PartType();

        $Document = FusionConvBuilder::main($MainPart, $donnees, $types, $correspondances);

        $sMimeType = 'application/vnd.oasis.opendocument.text';

        $Template = new GDO_ContentType("", 'model.odt', "application/vnd.oasis.opendocument.text", "binary", file_get_contents(WWW_ROOT . '/files/modeles/' . $file));
        $Fusion = new GDO_FusionType($Template, $sMimeType, $Document);

        $Fusion->process();
        App::uses('FusionConvConverterCloudooo', 'FusionConv.Utility/Converter');
        $pdf = FusionConvConverterCloudooo::convert($Fusion->getContent()->binary);

        if ($save == false) {
            $this->response->disableCache();
            $this->response->body($pdf);
            $this->response->type('application/pdf');
            $this->response->download($data[11]['Valeur']['valeur'] . '_' . 'CIL00' . $id . '.pdf');

            return $this->response;
        } else {
            $this->Extrait->save(['id_fiche' => $id, 'data' => $pdf]);

            $this->redirect(array(
                'controller' => 'registres',
                'action' => 'index'
            ));
        }
    }

}