<?php

class CakeflowAppModel extends AppModel {

    /**
     * Retourne le libellé d'un booléen sous la forme 'Oui' 'Non'
     * @param boolean $bouleen valeur du booléen
     * @param string $libelleTrue libelle a retourner pour la valeur true ('Oui' par défaut)
     * @param string $libelleFalse libelle a retourner pour la valeur false ('Non' par défaut)
     */
    function boolToString($bouleen, $libelleTrue = 'Oui', $libelleFalse = 'Non') {
        return ($bouleen ? __($libelleTrue, true) : __($libelleFalse, true));
    }

    /**     * *******************************************************************
     *       Permet de supprimer toutes les associations d'un modèle donné
     *       INFO: http://bakery.cakephp.org/articles/view/unbindall
     * ** ****************************************************************** */
    function unbindModelAll($reset = true) {
        $unbind = array();
        foreach ($this->belongsTo as $model => $info) {
            $unbind['belongsTo'][] = $model;
        }
        foreach ($this->hasOne as $model => $info) {
            $unbind['hasOne'][] = $model;
        }
        foreach ($this->hasMany as $model => $info) {
            $unbind['hasMany'][] = $model;
        }
        foreach ($this->hasAndBelongsToMany as $model => $info) {
            $unbind['hasAndBelongsToMany'][] = $model;
        }
        parent::unbindModel($unbind, $reset);
    }

}

?>
