<?php

class ModelesController extends AppController
{
    public $uses = array(
        'Modele',
        'Formulaire'
    );

    public function index()
    {
        $this->set('title', 'Liste des modèles');
        $modeles = $this->Formulaire->find('all', array(
            'contain' => array('Modele'),
            'conditions' => array('organisations_id' => $this->Session->read('Organisation.id'))
        ));
        $this->set(compact('modeles'));
    }

    public function add()
    {
        if($this->Modele->saveFile($this->request->data, $this->request->data['Modele']['idUploadModele'])) {
            $this->Session->setFlash('Modele enregistré', 'flashsuccess');
            $this->redirect(array(
                'controller' => 'modeles',
                'action' => 'index'
            ));
        }
    }

    public function download($file)
    {
        $this->response->file('files/modeles/' . $file, array(
            'download' => true,
            'name' => $file
        ));
        return $this->response;
    }
}