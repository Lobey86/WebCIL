<?php

class CircuitsController extends CakeflowAppController {

    public $name = 'Circuits';
    public $components = array(
        'Cakeflow.VueDetaillee',
        'Paginator'
    );
    public $helpers = array(
        'Text'
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
     * Liste des circuits de traitement
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function index() {
        $this->pageTitle = Configure::read('appName') . ' : ' . __('Circuits de traitement', true) . ' : ' . __('liste', true);
        $this->paginate = array(
            'recursive' => -1,
            'page' => 1,
            'order' => array('Circuit.nom' => 'asc'),
            'fields' => array('id', 'nom', 'description', 'actif', 'defaut'),
        );
        $this->request->data = $this->Paginator->paginate($this->modelClass);

        // lecture des étapes dans l'odre 'ordre'
        foreach ($this->request->data as &$circuit) {
            $circuit['Etape'] = $this->{$this->modelClass}->Etape->find('all', array(
                'recursive' => -1,
                'conditions' => array('circuit_id' => $circuit[$this->modelClass]['id']),
                'fields' => array('nom', 'type'),
                'order' => array('ordre')));
        }

        // mise en forme pour la vue
        foreach ($this->data as $i => $data) {
            $this->request->data[$i]['ListeActions']['view'] = true;
            $this->request->data[$i]['ListeActions']['visuCircuit'] = true;
            $this->request->data[$i]['ListeActions']['edit'] = true;
            $this->request->data[$i]['ListeActions']['delete'] = $this->{$this->modelClass}->isDeletable($data[$this->modelClass]['id']);
            $this->request->data[$i][$this->modelClass]['actifLibelle'] = $this->{$this->modelClass}->boolToString($data[$this->modelClass]['actif']);
            $this->request->data[$i][$this->modelClass]['defautLibelle'] = $this->{$this->modelClass}->boolToString($data[$this->modelClass]['defaut']);
        }

        // types des étapes
        $this->set('listeType', $this->{$this->modelClass}->Etape->types);
    }

    /**
     * Vue détaillée des circuits de traitement
     * 
     * @param int|null $id
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function view($id = null) {
        $this->request->data = $this->{$this->modelClass}->find('first', array(
            'conditions' => array('Circuit.id' => $id),
            'recursive' => -1));
        if (empty($this->data)) {
            $this->Session->setFlash(__(__d('circuit','circuit.flasherrorInvalideID'), true) . __(__d('circuit','circuit.flasherrorCircuitTraitement'), true) . ' : ' . __(__d('circuit','circuit.flasherrorAffichageVueImpossible'), true), 'flasherror', array('type' => 'important'));
            $this->redirect(array('action' => 'index'));
        } else {
            $this->pageTitle = Configure::read('appName') . ' : ' . __('Circuits de traitement', true) . ' : ' . __('vue détaillée', true);

            // préparation des informations à afficher dans la vue détaillée
            $maVue = new $this->VueDetaillee(
                    __('Vue détaillée du circuit', true) . ' : ' . $this->data[$this->modelClass]['nom'], __('Retour à la liste des circuits de traitement', true));

            $maVue->ajouteSection(__('Informations principales', true));
            $maVue->ajouteLigne(__('Identifiant interne (id)', true), $this->data[$this->modelClass]['id']);
            $maVue->ajouteLigne(__('Nom', true), $this->data[$this->modelClass]['nom']);
            $maVue->ajouteLigne(__('Description', true), $this->data[$this->modelClass]['description']);

            if (CAKEFLOW_GERE_DEFAUT) {
                $maVue->ajouteLigne(__('Défaut', true), $this->{$this->modelClass}->boolToString($this->data[$this->modelClass]['defaut']));
            }

            $maVue->ajouteLigne(__('Actif', true), $this->{$this->modelClass}->boolToString($this->data[$this->modelClass]['actif']));
            $maVue->ajouteSection(__('Création / Modification', true));
            $maVue->ajouteLigne(__('Date de création', true), $this->data[$this->modelClass]['created']);
            $maVue->ajouteElement(__('Par', true), $this->formatUser($this->data[$this->modelClass]['created_user_id']));
            $maVue->ajouteLigne(__('Date de dernière modification', true), $this->data[$this->modelClass]['modified']);
            $maVue->ajouteElement(__('Par', true), $this->formatUser($this->data[$this->modelClass]['modified_user_id']));

            // Affichage des étapes liées
            $this->{$this->modelClass}->Etape->Behaviors->attach('Containable');

            $etapes = $this->{$this->modelClass}->Etape->find('all', array(
                'conditions' => array('Etape.circuit_id' => $id),
                'contain' => array('Composition.type_validation', 'Composition.trigger_id'),
                'fields' => array('Etape.nom', 'Etape.type'),
                'order' => array('Etape.ordre'),
            ));

            if (!empty($etapes)) {
                $maVue->ajouteSection(__('Etapes du circuit', true));
                foreach ($etapes as $etape) {
                    $maVue->ajouteLigne($etape['Etape']['nom'] . ' (' . $this->{$this->modelClass}->Etape->types[$etape['Etape']['type']] . ')', '', 'viewEtapes');
                    // Affichage des utilisateurs
                    foreach ($etape['Composition'] as $i => $composition) {
                        $maVue->ajouteLigne('', 'Composition ' . ($i + 1) . ' : ' . $this->formatLinkedModel('Trigger', $composition['trigger_id']) . ' par ' . $this->Etape->Composition->libelleTypeValidation($composition['type_validation']));
                    }
                }
            }

            $this->set('contenuVue', $maVue->getContenuVue());
        }
    }

    /**
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function add() {
        $this->_add_edit();
    }

    /**
     * 
     * @param int|null $id
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function edit($id = null) {
        $this->_add_edit($id);
    }

    /**
     * 
     * @param int|null $id
     * @return type
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function _add_edit($id = null) {
        if (!empty($this->data)) {
            $this->setCreatedModifiedUser($this->request->data);
            $this->Circuit->create($this->data);

            if ($this->Circuit->validates($this->data)) {
                if ($this->Circuit->save()) {
                    if (empty($id)) {
                        $this->Session->setFlash(__(__d('circuit', 'circuit.flashsuccessLeCircuit'), true) . ' \'' . $this->data[$this->modelClass]['nom'] . '\' ' . __(__d('circuit', 'circuit.flashsuccessAjouter'), true), 'flashsuccess');
                    } else {
                        $this->Session->setFlash(__(__d('circuit', 'circuit.flashsuccessLeCircuit'), true) . ' \'' . $this->data[$this->modelClass]['nom'] . '\' ' . __(__d('circuit', 'circuit.flashsuccessModifier'), true), 'flashsuccess');
                    }

                    return $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__d('circuit', 'circuit.flasherrorErreurEnregistrementCircuit'), 'flasherror', array('type' => 'erreur'));
                }
            } else {
                $this->Session->setFlash(__(__d('circuit', 'circuit.flasherrorErreurFormulaireCircuit'), true), 'flasherror', array('type' => 'erreur'));
            }
        } else if ($this->action == 'add') {
            $this->request->data['Circuit']['actif'] = true;
        } else if ($this->action == 'edit') {
            $this->request->data = $this->Circuit->read(null, $id);
        }

        $this->render('add_edit');
    }

    /**
     * Suppression d'un circuit de traitement
     * 
     * @param int|null $id
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function delete($id = null) {
        $eleASupprimer = $this->{$this->modelClass}->find('first', array(
            'conditions' => array('Circuit.id' => $id),
            'recursive' => 0
        ));

        if (empty($eleASupprimer)) {
            $this->Session->setFlash(__(__d('circuit','circuit.flasherrorInvalideID'), true) . ' ' . __(__d('circuit','circuit.flasherrorCircuitTraitement'), true) . ' : ' . __(__d('circuit','circuit.flasherrorSupprimerImpossible'), true), 'flasherror', array('type' => 'important'));
        } elseif (!$this->{$this->modelClass}->isDeletable($id)) {
            $this->Session->setFlash(__(__d('circuit','circuit.flasherrorLeCircuitTraitement'), true) . ' \'' . $eleASupprimer[$this->modelClass]['nom'] . '\' ' . __(__d('circuit','circuit.flasherrorNePeutPasSupprimer'), true), 'flasherror');
        } elseif (!$this->{$this->modelClass}->delete($id, true)) {
            $this->Session->setFlash(__(__d('circuit','circuit.flasherrorErreurPendantSuppression'), true), 'flasherror', array('type' => 'erreur'));
        } else {
            $this->Session->setFlash(__(__d('circuit','circuit.flasherrorLeCircuitTraitement'), true) . ' \'' . $eleASupprimer[$this->modelClass]['nom'] . '\' ' . __(__d('circuit','circuit.flasherrorAEteSupprimer'), true), 'flashsuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    /**
     * Affiche graphique un circuit de validation
     * 
     * @param type $circuit_id
     * 
     * @created 24/10/2014
     * @version V0.9.0
     */
    function visuCircuit($circuit_id) {
        // lecture des étapes du circuit
        $this->{$this->modelClass}->Etape->Behaviors->attach('Containable');

        $etapes = $this->Circuit->Etape->find('all', array(
            'fields' => array('Etape.nom', 'Etape.ordre', 'Etape.type', 'Etape.soustype'),
            'contain' => array('Composition.type_validation', 'Composition.trigger_id', 'Composition.soustype'),
            'conditions' => array('Etape.circuit_id' => $circuit_id),
            'order' => array('Etape.ordre ASC')));

        foreach ($etapes as &$etape) {
            $etape['Etape']['libelleType'] = $this->{$this->modelClass}->Etape->types[$etape['Etape']['type']];
            foreach ($etape['Composition'] as &$composition) {
                $composition['libelleTypeValidation'] = $this->{$this->modelClass}->Etape->Composition->libelleTypeValidation($composition['type_validation']);
                if ($composition['type_validation'] == 'D' && $composition['soustype'] !== null) {
                    try {
                        $tooltip = Configure::read('IPARAPHEUR_TYPE') . " / " . $this->{$this->modelClass}->Etape->libelleSousType($composition['soustype']);
                        $composition['libelleTrigger'] = '<a class="infobulle" data-placement="right" data-toggle="tooltip" title="' . $tooltip . '">' . $this->formatLinkedModel('Trigger', $composition['trigger_id']) . "</a>";
                    } catch (Exception $e) {
                        $tooltip = $e->getMessage();
                        $composition['libelleTrigger'] = '<a class="infobulle" data-placement="right" data-toggle="tooltip" title="' . $tooltip . '"><i class="fa fa-warning"></i> Erreur</a>';
                        $composition['libelleTrigger'] .= '<input type="hidden" class="parapheur_error" value="true" />';
                        $this->Session->setFlash(__d('default', 'default.flasherrorConnexionImpossibleParapheur'), 'flasherror');
                    }
                } else {
                    $composition['libelleTrigger'] = $this->formatLinkedModel('Trigger', $composition['trigger_id']);
                }
            }
        }
        $this->set('etapes', $etapes);
    }

}
