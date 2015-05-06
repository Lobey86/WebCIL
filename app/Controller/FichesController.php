<?php


/**************************************************
 ************** Controller des fiches **************
 **************************************************/
class FichesController extends AppController
{

    public $helpers = array(
        'Html',
        'Form',
        'Session'
    );
    public $uses = array(
        'Fiche',
        'Organisation',
        'File',
        'EtatFiche',
        'Historique'
    );


    /**
     *** La page d'accueil des fiches est celle du pannel général
     **/

    public function index()
    {
        $this->redirect(array(
            'controller' => 'pannel',
            'action' => 'index'
        ));
    }


    /**
     *** Gère l'ajout de fiches
     **/

    public function add()
    {
        if ( $this->Droits->authorized(1) ) {
            $organisation = $this->Organisation->findById($this->Session->read('Organisation.id'));
            $this->request->data[ 'Fiche' ][ 'declarantraisonsociale' ] = $organisation[ 'Organisation' ][ 'raisonsociale' ];
            $this->request->data[ 'Fiche' ][ 'declarantadresse' ] = $organisation[ 'Organisation' ][ 'adresse' ];
            $this->request->data[ 'Fiche' ][ 'declarantraisonsociale' ] = $organisation[ 'Organisation' ][ 'raisonsociale' ];
            $this->request->data[ 'Fiche' ][ 'declarantemail' ] = $organisation[ 'Organisation' ][ 'email' ];
            $this->request->data[ 'Fiche' ][ 'declarantsiret' ] = $organisation[ 'Organisation' ][ 'siret' ];
            $this->request->data[ 'Fiche' ][ 'declarantape' ] = $organisation[ 'Organisation' ][ 'ape' ];
            $this->request->data[ 'Fiche' ][ 'declaranttelephone' ] = $organisation[ 'Organisation' ][ 'telephone' ];
            $this->request->data[ 'Fiche' ][ 'declarantfax' ] = $organisation[ 'Organisation' ][ 'fax' ];
            $this->request->data[ 'Fiche' ][ 'declarantemail' ] = $organisation[ 'Organisation' ][ 'email' ];
            $this->request->data[ 'Fiche' ][ 'declarantpersonnenom' ] = $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom');
            $this->request->data[ 'Fiche' ][ 'declarantpersonneemail' ] = $this->Auth->user('email');
            $this->request->data[ 'Fiche' ][ 'outilnom' ] = ' ';
            $this->request->data[ 'Fiche' ][ 'organisation_id' ] = $this->Session->read('Organisation.id');
            $this->request->data[ 'Fiche' ][ 'user_id' ] = $this->Auth->user('id');

            $this->Fiche->create($this->request->data);
            if ( $this->Fiche->save() ) {
                $last = $this->Fiche->getLastInsertID();
                $this->Historique->create(array(
                    'Historique' => array(
                        'content' => 'Création de la fiche par ' . $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom'),
                        'fiche_id' => $last
                    )
                ));
                $this->Historique->save();
                $this->EtatFiche->create(array(
                    'EtatFiche' => array(
                        'fiche_id' => $last,
                        'etat_id' => 1,
                        'previous_user_id' => $this->Auth->user('id'),
                        'user_id' => $this->Auth->user('id')
                    )
                ));
                if ( $this->EtatFiche->save() ) {
                    if ( $this->File->saveFile($this->request->data, $last) ) {
                        $this->redirect(array(
                            'controller' => 'fiches',
                            'action' => 'edit',
                            $last
                        ));
                    }
                }
            }
            $this->Session->setFlash('La fiche n\'a pas été enregistrée', 'flasherror');
            $this->redirect($this->referer());
        }
        else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
    }


    /**
     *** Gère la suppression de fiches
     **/

    public function delete($id = null)
    {
        if ( $this->Droits->authorized(1) && $this->Droits->isOwner($id) ) {
            if ( !$this->Droits->isdeletable($id) ) {
                $this->Session->setFlash('Vous n\'avez pas accès à cette fiche', 'flasherror');
                $this->redirect(array(
                    'controller' => 'pannel',
                    'action' => 'index'
                ));
            }
            $this->Fiche->delete($id);
            $this->Session->setFlash('La fiche a bien été supprimée', 'flashsuccess');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
        else {
            $this->Session->setFlash('Vous n\'avez pas le droit d\'acceder à cette page', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
    }


    /**
     *** Gère l'édition de fiches
     **/

    public function edit($id = null)
    {
        if ( !$id ) {
            $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
            $this->redirect(array(
                'controller' => 'pannel',
                'action' => 'index'
            ));
        }
        else {

            $fiche = $this->Fiche->find('first', array(
                'conditions' => array('Fiche.id' => $id),
                'contain' => array('File')
            ));
            if ( !$fiche ) {
                $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
                $this->redirect(array(
                    'controller' => 'pannel',
                    'action' => 'index'
                ));
            }
            else {
                if ( !$this->Droits->isEditable($id) ) {
                    $this->Session->setFlash('Vous n\'avez pas accès à cette fiche', 'flasherror');
                    $this->redirect(array(
                        'controller' => 'pannel',
                        'action' => 'index'
                    ));
                }
                if ( $this->request->is(array(
                    'post',
                    'put'
                ))
                ) {
                    $this->Fiche->id = $id;

                    $count = $this->Historique->find('count', array(
                        'conditions' => array(
                            'fiche_id' => $id,
                            'created' => date('Y-m-d'),
                            'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' modifie la fiche'
                        )
                    ));
                    if ( $count == 0 ) {
                        $this->Historique->create(array(
                            'Historique' => array(
                                'content' => $this->Auth->user('prenom') . ' ' . $this->Auth->user('nom') . ' modifie la fiche',
                                'fiche_id' => $id
                            )
                        ));
                        $this->Historique->save();

                    }
                    if ( $this->Fiche->save($this->request->data) ) {
                        if ( $this->File->saveFile($this->request->data, $id) ) {
                            if ( array_key_exists('FileDelete', $this->request->data) ) {
                                foreach ( $this->request->data[ 'FileDelete' ] as $key => $value ) {
                                    $fichier = $this->File->find('first', array(
                                        'conditions' => array('File.id' => $value),
                                        'recursive' => -1
                                    ));
                                    unlink(WWW_ROOT . 'files/' . $fichier[ 'File' ][ 'url' ]);
                                    $this->File->delete($fichier[ 'File' ][ 'id' ]);
                                }
                            }
                        }
                        $this->Session->setFlash('La fiche a été modifiée', 'flashsuccess');
                        $this->redirect(array(
                            'controller' => 'pannel',
                            'action' => 'index'
                        ));
                    }
                    $this->Session->setFlash('La modification a échoué.', 'flasherror');
                    $this->redirect(array(
                        'controller' => 'pannel',
                        'action' => 'index'
                    ));
                }
                else {
                    $this->set('id', $id);
                }
            }
        }
        if ( !$this->request->data ) {
            $this->set('organisation', $this->Organisation->findById($this->Session->read('Organisation.id')));
            $this->request->data = $fiche;
        }
    }

    /**
     *** Gère l'affichage des fiches
     **/

    public function show($id = null)
    {
        if ( !$id ) {
            $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
            $this->redirect($this->referer());
        }
        if ( !$this->Droits->isReadable($id) ) {
            $this->Session->setFlash('Vous n\'avez pas accès à cette fiche', 'flasherror');
            $this->redirect($this->referer());
        }
        else {
            $fiche = $this->Fiche->find('first', array(
                'conditions' => array('Fiche.id' => $id),
                'contain' => array('File')
            ));
            if ( !$fiche ) {
                $this->Session->setFlash('Cette fiche n\'existe pas', 'flasherror');
                $this->redirect($this->referer());
            }
            else {
                if ( !($this->Fiche->isOwner($this->Auth->user('id'), $fiche) || $this->Droits->isSu() || $this->Droits->isReadable($id)) ) {
                    $this->Session->setFlash('Vous n\'avez pas accès à cette fiche', 'flasherror');
                    $this->redirect($this->referer());
                }
            }
        }
        if ( !$this->request->data ) {
            $this->request->data = $fiche;
        }
    }


    /**
     *** Gère le téléchargement des pieces jointes d'une fiche
     **/

    public function download($url = null)
    {
        $this->response->file(WWW_ROOT . 'files/' . $url, array(
            'download' => true,
            'name' => 'file'
        ));
    }


    /**
     *** Génération PDF à la volée
     **/
    function genereFusion($id)
    {
        App::uses('FusionConvBuilder', 'FusionConv.Utility');
        $data = $this->Fiche->find('first', array('conditions' => array('id' => $id)));

        $types = array();
        foreach ( $data[ 'Fiche' ] as $key => $value ) {
            $types[ 'Fiche.' . $key ] = 'text';
        }

        $correspondances = array();
        foreach ( $data[ 'Fiche' ] as $key => $value ) {
            $correspondances[ 'Fiche_' . $key ] = 'Fiche.' . $key;
        }
        $MainPart = new GDO_PartType();

        $Document = FusionConvBuilder::main($MainPart, $data, $types, $correspondances);

        $sMimeType = 'application/vnd.oasis.opendocument.text';

        $Template = new GDO_ContentType("", 'model.odt', "application/vnd.oasis.opendocument.text", "binary", file_get_contents(WWW_ROOT . '/files/modeles/' . $data[ 'Fiche' ][ 'organisation_id' ] . '.odt'));
        $Fusion = new GDO_FusionType($Template, $sMimeType, $Document);

        $Fusion->process();

        $this->response->disableCache();
        $this->response->body($Fusion->getContent()->binary);
        $this->response->type($sMimeType);
        $this->response->download($data[ 'Fiche' ][ 'id' ] . '.odt');
        return $this->response;
    }
}