<?php

/**
 * ServicesController
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
class ServicesController extends AppController {

    public $uses = array(
        'Service',
        'OrganisationUserService'
    );

    /**
     * @access public
     * @created 18/06/2015
     * @version V1.0.0
     */
    public function index() {
        $this->set('title', __d('service','service.titreService') . $this->Session->read('Organisation.raisonsociale'));
        $serv = $this->Service->find('all', array('conditions' => array('organisation_id' => $this->Session->read('Organisation.id'))));
        foreach ($serv as $key => $value) {
            $count = $this->OrganisationUserService->find('count', array('conditions' => array('service_id' => $value['Service']['id'])));
            $serv[$key]['count'] = $count;
        }
        $this->set('serv', $serv);
    }

    /**
     * @access public
     * @created 18/06/2015
     * @version V1.0.0
     */
    public function add() {
        $this->set('title', __d('service','service.titreAjouterService'));
        if ($this->request->is('post')) {
            $this->Service->create($this->request->data);
            if ($this->Service->save()) {
                $this->Session->setFlash(__d('service','service.flashsuccessServiceEnregistrer'), 'flashsuccess');
                $this->redirect(array(
                    'controller' => 'services',
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__d('service','service.flasherrorErreurEnregistrementService'), 'flasherror');
                $this->redirect(array(
                    'controller' => 'services',
                    'action' => 'index'
                ));
            }
        }
    }

    /**
     * @param int|null $id
     * 
     * @access public
     * @created 18/06/2015
     * @version V1.0.0
     */
    public function edit($id = null) {
        $this->set('title', 'Modification d\'un service');
        $this->Service->id = $id;
        $servi = $this->Service->findById($id);
        if ($this->Service->exists()) {
            if ($this->request->is('post') || $this->request->is('put')) {
                if ($this->Service->save($this->request->data)) {
                    $this->Session->setFlash(__d('service','service.flashsuccessServiceEnregistrer'), "flashsuccess");
                    $this->redirect(array(
                        'controller' => 'services',
                        'action' => 'index'
                    ));
                } else {
                    $this->Session->setFlash(__d('service','service.flasherrorErreurEnregistrementService'), 'flasherror');
                }
            }
            if (!$this->request->data) {
                $this->request->data = $servi;
            }
        } else {
            $this->Session->setFlash(__d('service','service.flasherrorServiceInexistant'), "flasherror");
            $this->redirect(array(
                'controller' => 'services',
                'action' => 'index'
            ));
        }
    }

    /**
     * @param int|null $id
     * 
     * @access public
     * @created 18/06/2015
     * @version V1.0.0
     */
    public function delete($id = null) {
        $this->set('title', 'Supprimer un service');
        $this->Service->id = $id;
        if ($this->Service->exists()) {
            $this->Service->delete($id, false);
            $this->Session->setFlash(__d('service','service.flashsuccessServiceSupprimer'), 'flashsuccess');
            $this->redirect(array(
                'controller' => 'services',
                'action' => 'index'
            ));
        } else {
            $this->Session->setFlash(__d('service','service.flasherrorErreurSupprimerService'), 'flasherror');
            $this->redirect(array(
                'controller' => 'services',
                'action' => 'index'
            ));
        }
    }

}
