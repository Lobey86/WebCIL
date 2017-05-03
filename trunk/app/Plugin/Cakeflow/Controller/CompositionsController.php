<?php

class CompositionsController extends CakeflowAppController {

    public $name = 'Compositions';
    public $helpers = array('Text', 'Cakeflow.Myhtml');
    public $components = array('Paginator','Cakeflow.VueDetaillee');
    // Gestion des droits
    public $aucunDroit;

    function index($etape_id = null) { // FIXME: Composition.etape_id
        // lecture de l'étape
        $this->{$this->modelClass}->Etape->Behaviors->attach('Containable');
        $etape = $this->Composition->Etape->find('first', array(
            'fields' => array('id', 'nom'),
            'contain' => array('Circuit.nom', 'Circuit.id'),
            'conditions' => array('Etape.id' => $etape_id)));
        if (!empty($etape)) {
            $this->paginate = array(
                'recursive' => -1,
                'fields' => array('id', 'type_validation', 'trigger_id', 'soustype'),
                'conditions' => array('Composition.etape_id' => $etape_id));
            $compositions = $this->Paginator->paginate('Composition');

            //Si le circuit est vide, rediriger vers la vue d'ajout d'étape
            if (empty($compositions) && stripos($this->previous, 'compositions/add') === false)
                $this->redirect(array('action' => 'add', $etape_id));

            // mise en forme pour la vue
            foreach ($compositions as $i => $data) {
                $compositions[$i][$this->modelClass]['typeValidationLibelle'] = $this->{$this->modelClass}->libelleTypeValidation($compositions[$i][$this->modelClass]['type_validation']);
                if ($compositions[$i][$this->modelClass]['trigger_id'] == -1) { //Cas d'une compo délégation parapheur
                    $compositions[$i][$this->modelClass]['typeValidationLibelle'] .= " (" . Configure::read('IPARAPHEUR_TYPE') . " / " . $this->{$this->modelClass}->Etape->libelleSousType($compositions[$i][$this->modelClass]['soustype']) . ")";
                }
                $compositions[$i][$this->modelClass]['triggerLibelle'] = $this->formatLinkedModel('Trigger', $compositions[$i][$this->modelClass]['trigger_id']);
            }

            // Peut-on ajouter une composition à cette étape ?
            $canAdd = $this->Composition->Etape->canAdd($etape_id);
            $this->set(compact('compositions', 'etape', 'canAdd'));
        } else {
            $this->Session->setFlash(__d('default','default.flasherrorEtapeIntrouvable'), 'flasherror');
            $this->redirect($this->referer());
        }
    }

    /**
     * Vue détaillée des compositions d'une étape
     */
    function view($id = null) {
        $this->{$this->modelClass}->Behaviors->attach('Containable');
        $this->data = $this->{$this->modelClass}->find('first', array(
            'contain' => array('Etape.id', 'Etape.nom', 'Etape.Circuit.nom'),
            'conditions' => array('Composition.id' => $id)));
        if (empty($this->data)) {
            $this->Session->setFlash(__(__d('composition','composition.flasherrorInvalideID'), true) . ' ' . __(__d('composition','composition.flasherrorComposition'), true) . ' : ' . __(__d('composition','composition.flasherrorAffichageVueImpossible'), true), 'flasherror', array('type' => 'important'));
            $this->redirect(array('action' => 'index'));
        } else {
            $this->pageTitle = Configure::read('appName') . ' : ' . __(__d('composition','composition.readCompositionCircuitTraitement'), true) . ' : ' . __(__d('composition','composition.readVueDetailler'), true);

            // préparation des informations à afficher dans la vue détaillée
            $maVue = new $this->VueDetaillee(__d('composition','composition.newVueDetaillerCompositionEtape') 
                    . $this->data['Etape']['nom'] 
                    . __d('composition','composition.newDuCircuit') 
                    . $this->data['Etape']['Circuit']['nom'] 
                    . __(__d('composition','composition.newRetourListeComposition'), true), array('action' => 'index', $this->data['Etape']['id']));
            
            $maVue->ajouteSection(__('Informations principales', true));
            $maVue->ajouteLigne(__('Identifiant interne (id)', true), $this->data[$this->modelClass]['id']);
            if ($this->data[$this->modelClass]['trigger_id'] == -1) {
                $maVue->ajouteLigne("Déclencheur", "Parapheur électronique");
            } else {
                $maVue->ajouteLigne(CAKEFLOW_TRIGGER_TITLE, $this->formatLinkedModel('Trigger', $this->data[$this->modelClass]['trigger_id']));
            }
            $maVue->ajouteLigne(__('Type', true), $this->{$this->modelClass}->libelleTypeValidation($this->data[$this->modelClass]['type_validation']));
            if ($this->data[$this->modelClass]['soustype'] != '')
                $maVue->ajouteLigne(__('Sous-Type', true), $this->{$this->modelClass}->Etape->libelleSousType($this->data[$this->modelClass]['soustype']));
            $maVue->ajouteSection(__('Création / Modification', true));
            $maVue->ajouteLigne(__('Date de création', true), $this->data[$this->modelClass]['created']);
            $maVue->ajouteElement(__('Par', true), $this->formatUser($this->data[$this->modelClass]['created_user_id']));
            $maVue->ajouteLigne(__('Date de dernière modification', true), $this->data[$this->modelClass]['modified']);
            $maVue->ajouteElement(__('Par', true), $this->formatUser($this->data[$this->modelClass]['modified_user_id']));

            $this->set('contenuVue', $maVue->getContenuVue());
        }
    }

    /**
     *
     */
    function add($etape_id) {
        $this->set('request', 'Ajout');
        $this->_add_edit($etape_id);
    }

    /**
     *
     */
    function edit($id = null) {
        $this->set('request', 'Edition');
        $this->_add_edit($id);
    }

    /**
     *
     */
    function _add_edit($id = null) {
        if (!empty($this->data)) {
            $this->setCreatedModifiedUser($this->request->data);
            if ($this->request->data['Composition']['type_composition'] == 'PARAPHEUR'){
                $this->Composition->Etape->id = $this->request->data['Composition']['etape_id'];
                $this->Composition->Etape->saveField('soustype', $this->request->data['Composition']['soustype']);
            }else
                $this->request->data['Composition']['soustype'] = NULL;
            
            $this->Composition->create($this->request->data);
            if ($this->Composition->validates($this->request->data)) {
                if ($this->Composition->save()) {
                    $this->Session->setFlash(__(__d('default','default.flashsuccessEnregistrementEffectuer'), true), 'flashsuccess');
                    return $this->redirect(array('action' => 'index', $this->data['Composition']['etape_id']));
                } else {
                    $this->Session->setFlash(__d('default','default.flasherrorErreurEnregistrement'), 'flasherror');
                }
            }
            $etape_id = $this->data['Composition']['etape_id'];
        } else if ($this->action == 'edit') {
            $this->request->data = $this->Composition->read(null, $id);
            $etape_id = $this->request->data['Composition']['etape_id'];
            $this->set('canAddParapheur', (Configure::read('USE_PARAPHEUR') && !$this->Composition->hasAny(array('etape_id' => $etape_id, 'trigger_id' => -1, 'id !=' => $id))));
        } else if ($this->action == 'add') {
            $canAdd = $this->Composition->Etape->canAdd($id);
            if (!$canAdd) {
                $this->Session->setFlash(__d('composition','composition.flasherrorAjouterCompositionImpossibleEtape'), 'flasherror');
                return $this->redirect($this->referer());
            }
            $this->request->data['Composition']['etape_id'] = $id;
            $etape_id = $id;
            $this->set('canAddParapheur', (Configure::read('USE_PARAPHEUR') && !$this->Composition->hasAny(array('etape_id' => $id, 'trigger_id' => -1))));
        }
        $this->set('typeCompositions', $this->Composition->listeTypes());
        // lecture de la liste des déclencheurs
        $triggers = $this->listLinkedModel('Trigger');
        $this->set('triggers', $triggers);
        $this->set('typeValidations', $this->Composition->listeTypeValidation());
        $this->set('canAdd', $this->Composition->Etape->canAdd($etape_id));
        if (Configure::read('USE_PARAPHEUR')) {
            try{
                $this->set('soustypes', $this->Composition->Etape->listeSousTypesParapheur());
            }catch(Exception $e){
                $this->Session->setFlash($e->getMessage(), 'flasherror');
                $this->set('soustypes', array());
                $this->set('canAddParapheur', false);
            }
        }
        $this->render('add_edit');
    }

    /**
     *
     */
    function delete($id = null) {
        if ($this->Composition->delete($id)) {
            $this->Session->setFlash(__(__d('default','default.flashsuccessSuppressionEffectuer'), true), 'flashsuccess');
        } else {
            $this->Session->setFlash(__d('default','default.flasherrorErreurSuppression'), "flasherror");
        }
        return $this->redirect($this->referer());
    }

}
