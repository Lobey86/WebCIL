<?php
App::uses('CakeflowAppModel', 'Cakeflow.Model');
class Visa extends CakeflowAppModel
{
    public $tablePrefix = 'wkf_';
    public $belongsTo = array(
        CAKEFLOW_TRIGGER_MODEL => array(
            'className' => CAKEFLOW_TRIGGER_MODEL,
            'foreignKey' => 'trigger_id'
        ),
        'Cakeflow.Traitement',
        'Cakeflow.Etape',
    );
//    public $hasOne = array('Cakeflow.Signature');

    public function enregistre($traitement_id, $trigger_id, $etape_id, $commentaire = null, $date, $numero_traitement)
    {
        $composition = $this->Composition->find('first', array(
            'conditions' => array(
                'Composition.etape_id' => $etape_id,
                'Composition.trigger_id' => $trigger_id)));
        if (!empty($composition)) {
            $visa = $this->create();
            $visa['Visa']['traitement_id'] = $traitement_id;
            $visa['Visa']['trigger_id'] = $trigger_id;
            $visa['Visa']['etape_id'] = $etape_id;
            $visa['Visa']['composition_id'] = $composition['Composition']['id'];
            $visa['Visa']['commentaire'] = $commentaire;
            $visa['Visa']['date'] = $date;
            $visa['Visa']['numero_traitement'] = $numero_traitement;
            return ($this->save($visa['Visa']));
        } else {
            return false;
        }
    }

    /**
     * Retourne la liste des actions lors de la validation : Accepter(A), Refuser(R), Etape antèrieures (E)
     * @return array code, libelle des actions des traitements
     */
    public function listeAction()
    {
        return array(
            'RI' => __('Indéterminé', true),
            'OK' => __('Accepter', true),
            'KO' => __('Refuser', true),
            'IL' => __('Insérer un lacet', true),
            'IP' => __('Ajouter des étapes', true),
            'JP' => __('Retourner à une étape précédente', true),
            'JS' => __('Aller à une étape suivante', true),
            'ST' => __('Terminer le traitement', true),
            'IN' => __('Inserer dans le circuit de traitement', true),
            'VF' => __('Validation finale', true)
        );
    }

    /**
     * Retourne la liste des actions à afficher dans l'historique
     * @return array code, libelle des actions des traitements
     */
    public function listeActionEffectuee()
    {
        return array(
            'RI' => __('Indéterminé', true),
            'OK' => __('Accepté', true),
            'KO' => __('Refusé', true),
            'IL' => __('Lacet inséré', true),
            'IP' => __('Etape ajoutée', true),
            'JP' => __('Retour à une étape précédente', true),
            'JS' => __('Saut d\'étape', true),
            'ST' => __('Traitement terminé', true),
            'IN' => __('Inséré dans le circuit de traitement', true),
            'VF' => __('Validation finale', true)
        );
    }

    /**
     * Retourne la liste des actions lors de la validation : Accepter(A), Refuser(R)
     * @return array code, libelle des actions des traitements
     */
    public function listeActionAR()
    {
        return array(
            'OK' => __('Accepter', true),
            'KO' => __('Refuser', true));
    }

    /**
     * Retourne le libellé du type de validation
     * @param string $code_type lettre S ou V
     */
    public function libelleAction($code_type)
    {
        $actions = $this->listeAction();
        return $actions[$code_type];
    }

    /**
     * Retourne le libellé du type de validation
     * @param string $code_type lettre S ou V
     */
    public function libelleActionHistorique($code_type)
    {
        $actions = $this->listeActionEffectuee();
        return $actions[$code_type];
    }

    /**
     * Enregistre toutes les compositions d'un circuit dans la table visas
     * @param int $circuit_id : Identificant du circuit
     * @return bool true
     */
    public function injectArchive($circuit_id)
    {
        $etapes = $this->Etape->find('all', array('conditions' => array('Etape.circuit_id' => $circuit_id)));
        foreach ($etapes as $etape) {
            $compositions = $this->Composition->find('all', array('conditions' => array('Composition.etape_id' => $etape['Etape']['id'])));
            foreach ($compositions as $composition) {
                $visa = $this->create();
                $visa['Visa']['traitement_id'] = 999;
                $visa['Visa']['trigger_id'] = $composition[CAKEFLOW_TRIGGER_MODEL]['id'];
                $visa['Visa']['etape_id'] = $composition['Etape']['id'];
                $visa['Visa']['composition_id'] = $composition['Composition']['id'];
                $visa['Visa']['commentaire'] = 'in process...';
                $visa['Visa']['date'] = 0;
                $this->save($visa['Visa']);
            }
        }
        return true;
    }

    /**
     * @param $target_id
     * @param null $user_id si null, 0 remplacé par l'id du rédacteur (premier visa)
     * @return bool
     */
    public function replaceDynamicTrigger($target_id, $user_id = null)
    {
        $traitement = $this->Traitement->find('first', array(
            'conditions' => array('Traitement.target_id' => $target_id),
            'fields' => array('Traitement.id'),
            'recursive' => -1));

        $visas = $this->find('all', array(
            'fields' => array('id', 'trigger_id', 'numero_traitement'),
            'conditions' => array('Visa.traitement_id' => $traitement['Traitement']['id']),
            'recursive' => -1,
            'order' => array('Visa.numero_traitement' => 'ASC')));

        $redac = empty($user_id) ? $visas[0]['Visa']['trigger_id'] : $user_id;
        foreach ($visas as $visa) {
            if ($visa['Visa']['trigger_id'] == 0) {
                $this->id = $visa['Visa']['id'];
                $this->saveField('trigger_id', $redac);
            }
        }
        return true;
    }

    public function getAutresVisasEtape($visa){
        return $this->find('all', array(
            'recursive' => -1,
            'fields' => array('id', 'action'),
            'conditions' => array(
                'traitement_id' => $visa['Visa']['traitement_id'],
                'numero_traitement' => $visa['Visa']['numero_traitement'],
                'id !=' => $visa['Visa']['id']
            )
        ));
    }

    public function visasParallelesValides($visa){
        if ($visa['Visa']['etape_type'] == CAKEFLOW_COLLABORATIF) {
            $visasParalleles = $this->getAutresVisasEtape($visa);
            foreach ($visasParalleles as $v) {
                if ($v['Visa']['action'] == 'RI'){
                    return false;
                }
            }
        }
        return true;
    }

    public function isLastEtape($visa){
        return !$this->hasAny(array(
            'traitement_id' => $visa['Visa']['traitement_id'],
            'numero_traitement >' => $visa['Visa']['numero_traitement'],
        ));
    }
}
