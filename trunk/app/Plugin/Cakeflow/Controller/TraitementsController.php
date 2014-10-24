<?php

class TraitementsController extends CakeflowAppController {

    // Gestion des droits
    public $aucunDroit;

    /**
     * Affiche graphique du traitement de la cible $targetId
     * @param integer $targetId identifiant de la cible
     */
    function visuTraitement($targetId = null) {
        // lecture du traitement
        $traitement = $this->{$this->modelClass}->find('first', array(
            'recursive' => -1,
            'fields' => array('id', 'numero_traitement', 'circuit_id'),
            'conditions' => array('target_id' => $targetId)));

        // lecture des visas
        $visas = $this->{$this->modelClass}->Visa->find('all', array(
            'recursive' => -1,
            'fields' => array('id', 'etape_id', 'etape_nom', 'etape_type', 'trigger_id', 'action', 'numero_traitement', 'type_validation', 'date'),
            'conditions' => array('traitement_id' => $traitement['Traitement']['id']),
            'order' => array('numero_traitement ASC', 'date DESC')));

        // consitution du tableau pour l'affichage
        $aff = array();
        $ntCourant = 0;
        foreach ($visas as &$visa) {
            if ($visa['Visa']['numero_traitement'] != $ntCourant) {
                $ntCourant = $visa['Visa']['numero_traitement'];
                $iVisa = 0;
                $aff[$ntCourant]['Etape']['nom'] = $visa['Visa']['etape_nom'];
                $aff[$ntCourant]['Etape']['libelleType'] = $this->{$this->modelClass}->Circuit->Etape->types[$visa['Visa']['etape_type']];
                $aff[$ntCourant]['Etape']['courante'] = ($visa['Visa']['numero_traitement'] == $traitement['Traitement']['numero_traitement']);
            }
            $aff[$ntCourant]['Visa'][$iVisa]['libelleTypeValidation'] = $this->{$this->modelClass}->Circuit->Etape->Composition->libelleTypeValidation($visa['Visa']['type_validation']);
            if($visa['Visa']['trigger_id'] == -1){
                $etape = $this->{$this->modelClass}->Circuit->Etape->find('first', array(
                    'recursive' => -1,
                    'fields' => array('id', 'soustype'),
                    'conditions' => array(
                        'id' => $visa['Visa']['etape_id'],
                    )));
                try{
                    $aff[$ntCourant]['Visa'][$iVisa]['libelleTrigger'] = '<a class="infobulle delegation" data-placement="right" data-toggle="tooltip" title="'.Configure::read('IPARAPHEUR_TYPE')." / ".$this->{$this->modelClass}->Circuit->Etape->libelleSousType($etape['Etape']['soustype']).'">'.$this->formatLinkedModel('Trigger', $visa['Visa']['trigger_id'])."</a>";
                }catch (Exception $e){
                    $tooltip = $e->getMessage();
                    $aff[$ntCourant]['Visa'][$iVisa]['libelleTrigger'] = '<a class="infobulle delegation" data-placement="right" data-toggle="tooltip" title="'.$tooltip.'"><i class="fa fa-warning"></i> Erreur</a>';
                    $aff[$ntCourant]['Visa'][$iVisa]['libelleTrigger'] .= '<input type="hidden" class="parapheur_error" value="true" />';
                    $this->Session->setFlash("Problème de connexion au parapheur", 'flasherror');
                }
            }else{
                $aff[$ntCourant]['Visa'][$iVisa]['libelleTrigger'] = $this->formatLinkedModel('Trigger', $visa['Visa']['trigger_id']);
            }
            $aff[$ntCourant]['Visa'][$iVisa]['action'] = $visa['Visa']['action'];
            $aff[$ntCourant]['Visa'][$iVisa]['libelleAction'] = $this->{$this->modelClass}->Visa->libelleActionHistorique($visa['Visa']['action']);
            $aff[$ntCourant]['Visa'][$iVisa]['date'] = $visa['Visa']['date'];
            $iVisa++;
        }
        $this->set('etapes', $aff);
    }

    function majTraitementsParapheur($id = null, $redirect = false) {
        try {
            $ret = $this->Traitement->majTraitementsParapheur($id);
        } catch(Exception $e){
            $this->log($e, 'parapheur');
            $ret = 'TRAITEMENT_TERMINE_ALERTE';
        }
        if (!$redirect){
            echo $ret;
            die;
        }
    }

    function traiterDelegationsPassees($traitement_id, $etape = null, $action = 'view') {
        $ret = $this->Traitement->traiterDelegationsPassees($traitement_id, $etape);
        if ($ret){
            $this->Session->setFlash(__('L\'état du visa a été mis à jour.', true), 'flashsuccess');
        }else{
            $this->Session->setFlash(__('L\'état du visa n\'a pas été mis à jour.', true), 'flasherror');
        }
        $traitement = $this->Traitement->findById($traitement_id);
        $this->redirect('/'.strtolower(CAKEFLOW_TARGET_MODEL.'s').'/'.$action.'/'.$traitement['Traitement']['target_id']);
    }

}
