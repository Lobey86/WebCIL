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
App::uses('ListeDroit', 'Model');

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
        if (true !== $this->Droits->authorized([ListeDroit::CREER_UTILISATEUR, ListeDroit::MODIFIER_UTILISATEUR, ListeDroit::SUPPRIMER_UTILISATEUR, ListeDroit::CREER_ORGANISATION, ListeDroit::MODIFIER_ORGANISATION, ListeDroit::CREER_PROFIL, ListeDroit::MODIFIER_PROFIL, ListeDroit::SUPPRIMER_PROFIL])) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->set('title', __d('service', 'service.titreService') . $this->Session->read('Organisation.raisonsociale'));

        $serv = $this->Service->find('all', array(
            'conditions' => array(
                'organisation_id' => $this->Session->read('Organisation.id')
            )
        ));

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
        if (true !== $this->Droits->authorized([ListeDroit::CREER_UTILISATEUR, ListeDroit::MODIFIER_UTILISATEUR, ListeDroit::SUPPRIMER_UTILISATEUR, ListeDroit::CREER_ORGANISATION, ListeDroit::MODIFIER_ORGANISATION, ListeDroit::CREER_PROFIL, ListeDroit::MODIFIER_PROFIL, ListeDroit::SUPPRIMER_PROFIL])) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->set('title', __d('service', 'service.titreAjouterService'));

        if ($this->request->is('post')) {
            $success = true;
            $this->Service->begin();

            $this->Service->create($this->request->data);

            if (false !== $this->Service->save()) {
                $this->Service->commit();
                $this->Session->setFlash(__d('service', 'service.flashsuccessServiceEnregistrer'), 'flashsuccess');
                $this->redirect($this->Referers->get());
            } else {
                $this->Service->rollback();
                $this->Session->setFlash(__d('default', 'default.flasherrorErreurEnregistrementService'), 'flasherror');
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
        if (true !== $this->Droits->authorized([ListeDroit::CREER_UTILISATEUR, ListeDroit::MODIFIER_UTILISATEUR, ListeDroit::SUPPRIMER_UTILISATEUR, ListeDroit::CREER_ORGANISATION, ListeDroit::MODIFIER_ORGANISATION, ListeDroit::CREER_PROFIL, ListeDroit::MODIFIER_PROFIL, ListeDroit::SUPPRIMER_PROFIL])) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->set('title', __d('service', 'service.titreModifierrService'));
        $this->Service->id = $id;
        $service = $this->Service->findById($id);

        if (true === empty($service)) {
            $this->Session->setFlash(__d('service', 'service.flasherrorServiceInexistant'), "flasherror");
            $this->redirect($this->Referers->get());
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $success = true;
            $this->Service->begin();

            $this->request->data['Service']['id'] = $id;
            $this->Service->create($this->request->data);

            if (false !== $this->Service->save()) {
                $this->Service->commit();
                $this->Session->setFlash(__d('service', 'service.flashsuccessServiceEnregistrer'), "flashsuccess");
                $this->redirect($this->Referers->get());
            } else {
                $this->Service->rollback();
                $this->Session->setFlash(__d('service', 'service.flasherrorErreurEnregistrementService'), 'flasherror');
            }
        } else {
            $this->request->data = $service;
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
        if (true !== $this->Droits->authorized([ListeDroit::CREER_UTILISATEUR, ListeDroit::MODIFIER_UTILISATEUR, ListeDroit::SUPPRIMER_UTILISATEUR, ListeDroit::CREER_ORGANISATION, ListeDroit::MODIFIER_ORGANISATION, ListeDroit::CREER_PROFIL, ListeDroit::MODIFIER_PROFIL, ListeDroit::SUPPRIMER_PROFIL])) {
            throw new ForbiddenException(__d('default', 'default.flasherrorPasDroitPage'));
        }

        $this->set('title', 'Supprimer un service');
        $this->Service->id = $id;

        if ($this->Service->exists() == true) {
            $success = true;
            $this->Service->begin();

            $success = $success && false !== $this->Service->delete($id, false);

            if ($success == true) {
                $this->Service->commit();
                $this->Session->setFlash(__d('service', 'service.flashsuccessServiceSupprimer'), 'flashsuccess');
            } else {
                $this->Service->rollback();
                $this->Session->setFlash(__d('default', 'default.flasherrorEnregistrementErreur'), 'flasherror');
            }
        } else {
            $this->Session->setFlash(__d('service', 'service.flasherrorErreurSupprimerService'), 'flasherror');
        }

        $this->redirect($this->Referers->get());
    }

}
