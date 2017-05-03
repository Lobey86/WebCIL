<?php

/**
 * EtapesController
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
class EtapesController extends CakeflowAppController {

    public $components = array(
        'Cakeflow.VueDetaillee', 
        'Paginator'
    );
    
    public $helpers = array(
        'Text',
        'Cakeflow.Myhtml'
    );
    
    public $uses = array(
        'Cakeflow.Circuit',
        'Cakeflow.Etape',
        'Cakeflow.Visa',
        'Cakeflow.Composition',
        'Cakeflow.Traitement'
    );
    
    // Gestion des droits
    public $aucunDroit;

    /**
     * @param integer|null $circuit_id
     * @return render|redirect
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function index($circuit_id = null) { // FIXME: Circuit.service_id
        $this->Etape->Circuit->id = $circuit_id;
        
        if ($this->Etape->Circuit->exists($circuit_id)) {
            $this->set('circuit', $this->Etape->Circuit->field('nom'));
            $this->Etape->Behaviors->attach('Containable');
            $this->paginate = array(
                'fields' => array(
                    'Etape.ordre',
                    'Etape.nom',
                    'Etape.description',
                    'Etape.type',
                    'Etape.soustype',
                    'Etape.cpt_retard'
                ),
                'contain' => array('Composition.trigger_id'),
                'order' => array('Etape.ordre' => 'ASC')
            );
            
            $etapes = $this->Paginator->paginate('Etape', array('Etape.circuit_id' => $circuit_id));
            
            //Si le circuit est vide, rediriger vers la vue d'ajout d'étape
            if (empty($etapes) && stripos($this->previous, 'etapes/add') === false){
                $this->redirect(array('action' => 'add', $circuit_id));
            }

            // Mise en forme pour chaque ligne
            foreach ($etapes as &$etape) {
                $etape['ListeActions']['delete'] = $this->Etape->isDeletable($etape['Etape']['id']);
                $etape['Etape']['libelleType'] = $this->Etape->types[$etape['Etape']['type']];
                $etape['Etape']['libelleSousType'] = '';
                
                foreach ($etape['Composition'] as &$composition) {
                    $composition['libelleTrigger'] = $this->formatLinkedModel('Trigger', $composition['trigger_id']);
                }
            }
            
            $nbrEtapes = $this->Etape->find('count', array('conditions' => array('Etape.circuit_id' => $circuit_id)));
            $this->set(compact('etapes', 'nbrEtapes'));
        } else {
            $this->Session->setFlash(__d('etape', 'etape.flasherrorCircuitIntrouvable'), 'flasherror');

            return $this->redirect(array(
                        'controller' => 'circuit',
                        'action' => 'index'
            ));
        }
    }

    /**
     * @param integer $id
     * @return render|redirect
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function view($id = null) {
        $this->request->data = $this->Etape->find('first', array(
            'conditions' => array('id' => $id),
            'recursive' => -1
        ));
        
        if (empty($this->data)) {
            $this->Session->setFlash(__(__d('etape', 'etape.flasherrorInvalideID'), true) . ' ' . __('étape', true) . ' : ' . __(__d('etape', 'etape.flasherrorAfficherVueImpossible'), true), 'flasherror', array('type' => 'important'));
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->pageTitle = Configure::read('appName') . ' : ' . __('Etapes des circuits de traitement', true) . ' : ' . __('vue d&eacute;taill&eacute;e', true);
            // lecture du circuit de l'étape
            $circuit = $this->Etape->Circuit->find('first', array(
                'recursive' => -1,
                'fields' => array('id', 'nom'),
                'conditions' => array('id' => $this->data['Etape']['circuit_id'])));

            // préparation des informations é afficher dans la vue détaillée
            $maVue = new $this->VueDetaillee(
                    __('Vue d&eacute;taill&eacute;e de l\'etape \'', true) . $this->data['Etape']['nom'] . '\' du circuit \'' . $circuit['Circuit']['nom'] . '\'', __('Retour &agrave; la liste des étapes', true), array('action' => 'index', $circuit['Circuit']['id']));
            $maVue->ajouteSection(__('Informations principales', true));
            $maVue->ajouteLigne(__('Identifiant interne (id)', true), $this->data['Etape']['id']);
            $maVue->ajouteLigne(__('Nom', true), $this->data['Etape']['nom']);
            $maVue->ajouteLigne(__('Description', true), $this->data['Etape']['description']);
            $maVue->ajouteLigne(__('Ordre', true), $this->data['Etape']['ordre']);
            $maVue->ajouteLigne(__('Type', true), $this->Etape->types[$this->data['Etape']['type']]);
            $maVue->ajouteSection(__('Création / Modification', true));
            $maVue->ajouteLigne(__('Date de cr&eacute;ation', true), $this->data['Etape']['created']);
            $maVue->ajouteElement(__('Par', true), $this->formatUser($this->data['Etape']['created_user_id']));
            $maVue->ajouteLigne(__('Date de derni&egrave;re modification', true), $this->data['Etape']['modified']);
            $maVue->ajouteElement(__('Par', true), $this->formatUser($this->data['Etape']['modified_user_id']));

            // Affichage des compositions
            $compositions = $this->Etape->Composition->find('all', array(
                'conditions' => array('Composition.etape_id' => $id),
                'fields' => array('Composition.trigger_id', 'Composition.type_validation')
            ));
            
            if (!empty($compositions)) {
                $maVue->ajouteSection(__('Composition de l\'étape', true));
                
                foreach ($compositions as $composition) {
                    $maVue->ajouteLigne($this->formatLinkedModel('Trigger', $composition['Composition']['trigger_id']), $this->Etape->Composition->libelleTypeValidation($composition['Composition']['type_validation']));
                }
            }
            $this->set('contenuVue', $maVue->getContenuVue());
        }
    }

    /**
     * @param integer $circuit_id
     * @return redirect|render
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function add($circuit_id = null) {
        if (empty($circuit_id) || !$this->Etape->Circuit->exists($circuit_id)) {
            $this->Session->setFlash(__d('etape', 'etape.flasherrorCircuitIntrouvable'), 'flasherror');
            return $this->redirect(array(
                'controller' => 'circuits',
                'action' => 'index'
            ));
        }
        
        $nbrEtapes = $this->Etape->find('count', array('conditions' => array('Etape.circuit_id' => $circuit_id)));
        
        $etapeMinCptRetard = $this->Etape->find('first', array(
            'conditions' => array('Etape.circuit_id' => $circuit_id),
            'fields' => array('MIN(Etape.cpt_retard) as retardmax')
        ));
        
        $retardMax = $etapeMinCptRetard[0]['retardmax'];
        $this->set('retard_max', $retardMax);
        
        if (!empty($this->data)) {
            //Vérification cpt_retard valide
            if (!empty($retardMax) && $this->request->data['Etape']['cpt_retard'] > $retardMax) {
                $this->Session->setFlash(__d('etape', 'etape.flasherrorErreurEnregistrementCompteur'), 'flasherror');
            } else {
                $this->request->data['Etape']['ordre'] = $nbrEtapes + 1;
                $this->Etape->create();
                $this->setCreatedModifiedUser($this->request->data, 'Etape');
                
                if ($this->Etape->save($this->request->data)) {
                    $this->Session->setFlash(__d('default', 'default.flashsuccessEnregistrementEffectuer'), 'flashsuccess');
                    return $this->redirect(array('action' => 'index', $circuit_id));
                } else {
                    $this->Session->setFlash(__d('default', 'default.flasherrorErreurEnregistrement'), 'flasherror');
                }
            }
        } else {
            $this->request->data['Etape']['circuit_id'] = $circuit_id;
        }
        
        $this->set('types', $this->Etape->types);
        $this->render('add_edit');
    }

    /**
     * 
     * 
     * @param integer $id identifiant de l'étape à modifier
     * @return redirect | render
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function edit($id = null) {
        if (empty($id) || !$this->Etape->exists($id)) {
            $this->Session->setFlash(__d('default','default.flasherrorEtapeIntrouvable'), 'flasherror');
            
            return $this->redirect(array(
                'controller' => 'circuits',
                'action' => 'index'
            ));
        }

        $etape = $this->Etape->find('first', array(
            'recursive' => -1,
            'conditions' => array('Etape.id' => $id)
        ));
        
        $recordRetardMax = $this->Etape->find('first', array(
            'conditions' => array(
                'Etape.circuit_id' => $etape['Etape']['circuit_id'],
                'Etape.ordre <' => $etape['Etape']['ordre'],
                'Etape.cpt_retard >=' => 0
            ),
            'fields' => array('MIN(Etape.cpt_retard) as retardmax')
        ));
        
        $this->set('retard_max', $recordRetardMax[0]['retardmax']);
        
        $recordRetardInf = $this->Etape->find('first', array(
            'conditions' => array(
                'Etape.circuit_id' => $etape['Etape']['circuit_id'],
                'Etape.ordre <' => $etape['Etape']['ordre'],
                'Etape.cpt_retard !=' => 0
            ),
            'fields' => array('MAX(Etape.cpt_retard) as retardinf')
        ));
        
        $this->set('retard_inf', $recordRetardInf[0]['retardinf']);

        if (!empty($this->data)) {
            //Vérification cpt_retard valide
            if ($recordRetardMax[0]['retardmax'] != null && $this->request->data['Etape']['cpt_retard'] > $recordRetardMax[0]['retardmax']) {
                $this->Session->setFlash(__d('etape','etape.flasherrorErreurEnregistrementCompteur'), 'flasherror');
            } else {
                $this->Etape->begin();
                try {
                    // Si nouvelle affectation ou diminution de cpt_retard
                    if ($etape['Etape']['cpt_retard'] > $this->request->data['Etape']['cpt_retard'] || (empty($etape['Etape']['cpt_retard']) && !empty($this->request->data['Etape']['cpt_retard']))) {
                        $etapeSuivante = $this->Etape->find('first', array(
                            'recursive' => -1,
                            'fields' => array('Etape.cpt_retard'),
                            'conditions' => array(
                                'Etape.circuit_id' => $this->request->data['Etape']['circuit_id'],
                                'Etape.ordre' => $this->request->data['Etape']['ordre'] + 1
                            )
                        ));
                        
                        if (!empty($etapeSuivante)) {
                            $decalage = $etapeSuivante['Etape']['cpt_retard'] - $this->request->data['Etape']['cpt_retard'];
                            
                            if ($decalage > 0) {
                                $etapesSuivante = $this->Etape->find('all', array(
                                    'recursive' => -1,
                                    'conditions' => array(
                                        'Etape.circuit_id' => $this->request->data['Etape']['circuit_id'],
                                        'Etape.ordre >' => $this->request->data['Etape']['ordre']
                                    ),
                                    'order' => 'Etape.ordre ASC'
                                ));
                                
                                foreach ($etapesSuivante as $etapeSuivante) {
                                    if (!empty($etapeSuivante['Etape']['cpt_retard'])) {
                                        $etapeSuivante['Etape']['cpt_retard'] -= $decalage;
                                        
                                        if ($etapeSuivante['Etape']['cpt_retard'] < 0) {
                                            $etapeSuivante['Etape']['cpt_retard'] = 0;
                                        }
                                        
                                        $this->Etape->id = $etapeSuivante['Etape']['id'];
                                        $this->setCreatedModifiedUser($etapeSuivante, 'Etape');
                                        
                                        if (!$this->Etape->save($etapeSuivante)) {
                                            throw new Exception(__d('etape','etape.exceptionErreurModificationCompteur'));
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    $this->Etape->id = $id;
                    $this->setCreatedModifiedUser($this->request->data, 'Etape');
                    
                    if ($this->Etape->save($this->request->data)) {
                        $this->Etape->commit();
                        $this->Session->setFlash(__d('default', 'default.flashsuccessEnregistrementEffectuer'), 'flashsuccess');
                        
                        return $this->redirect(array('action' => 'index', $etape['Etape']['circuit_id']));
                    } else {
                        throw new Exception(__d('default', 'default.flasherrorErreurEnregistrement'));
                    }
                } catch (Exception $e) {
                    $this->Etape->rollback();
                    $this->Session->setFlash($e->getMessage(), 'flasherror');
                }
            }
        } else {
            $this->request->data = $etape;
        }
        
        $this->set('types', $this->Etape->types);
        $this->render('add_edit');
    }

    /**
     * Supprimer une étape
     * 
     * @param integer $id
     * @return redirect
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function delete($id = null) {  // FIXME: !empty find + service_id
        $etape = $this->Etape->find('first', array(
            'conditions' => array('Etape.id' => $id),
            'recursive' => -1,
            'fields' => array('Etape.circuit_id', 'Etape.ordre')
        ));
        
        if ($this->Etape->delete($id)) {
            $etapes = $this->Etape->find('all', array(
                'conditions' => array(
                    'Etape.circuit_id' => $etape['Etape']['circuit_id'],
                    'Etape.ordre >' => $etape['Etape']['ordre']),
                'fields' => array('Etape.id', 'Etape.ordre'),
                'recursive' => -1));
            
            foreach ($etapes as $etape) {
                $this->Etape->id = $etape['Etape']['id'];
                $this->Etape->saveField('ordre', $etape['Etape']['ordre'] - 1);
            }
            
            $this->Session->setFlash(__d('default','default.flashsuccessSuppressionEffectuer'), 'flashsuccess');
        } else {
            $this->Session->setFlash(__d('default','default.flasherrorErreurSuppression'), 'flasherror');
        }
        return $this->redirect($this->referer());
    }

    /**
     * Monter l'ordre d'une étape
     * 
     * @param integer $id
     * @return redirect
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function moveUp($id = null) {  // FIXME: !empty find + service_id
        if (!$this->Etape->moveUp($id)) {
            $this->Session->setFlash(__d('etape','etape.flasherrorImpossibleChangerOrdreEtape'), 'flasherror');
        }
        
        return $this->redirect($this->referer());
    }

    /**
     * Decendre l'ordre d'une étape
     * 
     * @param integer $id
     * @return redirect
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function moveDown($id = null) {  // FIXME: !empty find + service_id
        if (!$this->Etape->moveDown($id)) {
            $this->Session->setFlash(__d('etape','etape.flasherrorImpossibleChangerOrdreEtape'), 'flasherror');
        }
        
        return $this->redirect($this->referer());
    }

}
