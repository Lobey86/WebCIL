<?php

/**
 * Model DatabaseTableBehavior
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
 * @package     ModelBehavior
 */
class DatabaseTableBehavior extends ModelBehavior {

    /**
     * Transforme les $querydata d'un appel "find all" en requête SQL,
     * ce qui permet de faire des sous-requêtes moins dépendantes du SGBD.
     *
     * Les fields sont échappés.
     *
     * INFO: http://book.cakephp.org/view/74/Complex-Find-Conditions (Sub-queries)
     * 
     * @param Model $model
     * @param type $querydata
     * @return type
     * @throws Exception
     * 
     * @access public
     * @created 13/04/2015
     * @version V0.9.0
     */
    public function sq(Model $model, $querydata) {
        if ($model->useTable === false) {
            throw new Exception("Cannot generate a subquery for model \"{$model->alias}\" since it does not use a table.");
            return array();
        }

        $dbo = $model->getDataSource($model->useDbConfig);
        $fullTableName = $dbo->fullTableName($model, true, false);

        $defaults = array(
            'fields' => null,
            'order' => null,
            'group' => null,
            'limit' => null,
            'table' => $fullTableName,
            'alias' => $model->alias,
            'conditions' => array(),
        );

        $querydata = Set::merge($defaults, Hash::filter((array) $querydata));
        if (empty($querydata['fields'])) {
            $querydata['fields'] = $dbo->fields($model);
        } else {
            $querydata['fields'] = $dbo->fields($model, null, $querydata['fields']);
        }

        return $dbo->buildStatement($querydata, $model);
    }

    /**
     * Merges a mixed set of string/array conditions
     * 
     * @param type $query
     * @param type $assoc
     * @return type
     * 
     * @access protected
     * @created 13/04/2015
     * @version V0.9.0
     */
    protected function _mergeConditions($query, $assoc) {
        if (empty($assoc)) {
            return $query;
        }

        if (is_array($query)) {
            return array_merge((array) $assoc, $query);
        }

        if (!empty($query)) {
            $query = array($query);
            if (is_array($assoc)) {
                $query = array_merge($query, $assoc);
            } else {
                $query[] = $assoc;
            }
            return $query;
        }

        return $assoc;
    }

    /**
     * @param Model $model
     * @param type $needleModelName
     * @return type
     * 
     * @access protected
     * @created 13/04/2015
     * @version V0.9.0
     */
    protected function _whichHabtmModel(Model $model, $needleModelName) {
        foreach ($model->hasAndBelongsToMany as $habtmModel => $habtmAssoc) {
            if ($habtmAssoc['with'] == $needleModelName) {
                return $habtmModel;
            }
        }
    }

    /**
     * @param Model $model
     * @param type $assoc
     * @param type $params
     * @return type
     * @throws Exception
     * 
     * @access public
     * @created 13/04/2015
     * @version V0.9.0
     */
    public function join(Model $model, $assoc, $params = array(/* 'type' => 'INNER' */)) {
        // Is the assoc model really associated ?
        if (!isset($model->{$assoc})) {
            throw new Exception("Unknown association \"{$assoc}\" for model \"{$model->alias}\"");
            return array();
        }

        if ($model->useTable === false) {
            throw new Exception("Cannot generate a join from model \"{$model->alias}\" since it does not use a table.");
            return array();
        }

        if ($model->{$assoc}->useTable === false) {
            throw new Exception("Cannot generate a join to model \"{$model->{$assoc}->alias}\" since it does not use a table.");
            return array();
        }

        // Is the assoc model using the same DbConfig as the model's ?
        if ($model->useDbConfig != $model->{$assoc}->useDbConfig) {
            throw new Exception("Database configuration differs: \"{$model->alias}\" ({$model->useDbConfig}) and \"{$assoc}\" ({$model->{$assoc}->useDbConfig})");
            return array();
        }

        $dbo = $model->getDataSource($model->useDbConfig);

        // hasOne, belongsTo: OK
        $assocData = $model->getAssociated($assoc);
        $assocData = Set::merge($assocData, $params);

        // hasMany
        if (isset($assocData['association']) && $assocData['association'] == 'hasMany') {
            $assocData['association'] = 'hasOne';
        }
        // hasAndBelongsToMany
        else if (!isset($assocData['association'])) {
            $whichHabtmModel = $this->_whichHabtmModel($model, $assoc);

            if (!empty($whichHabtmModel)) {
                $habtmAssoc = $model->hasAndBelongsToMany[$whichHabtmModel];
                $newAssocData = array(
                    'className' => $habtmAssoc['with'],
                    'foreignKey' => $habtmAssoc['foreignKey'],
                    'conditions' => $habtmAssoc['conditions'],
// 							'fields' => '',
// 							'order' => '',
// 							'limit' => '',
// 							'offset' => '',
// 							'exclusive' => '',
// 							'finderQuery' => '',
// 							'counterQuery' => '',
                    'association' => 'hasOne'
                );

                $assocData = Set::merge($newAssocData, $assocData);
            }
        }

        if (empty($assocData)) {
            throw new Exception("Cannot generate a join from model \"{$model->alias}\" to model \"{$assoc}\".");
            return array();
        }

        return array(
            'table' => $dbo->fullTableName($model->{$assoc}, true, false),
            'alias' => $assoc,
            'type' => isset($assocData['type']) ? $assocData['type'] : 'LEFT',
            'conditions' => trim(
                    $dbo->conditions(
                            $this->_mergeConditions(
                                    @$assocData['conditions'], $dbo->getConstraint(
                                            @$assocData['association'], $model, $model->{$assoc}, $assoc, $assocData
                                    )
                            ), true, false, $model
                    )
            )
        );
    }

    /**
     * Retourne la liste des champs du modèle.
     * 
     * @param Model $model
     * @param type|false $virtualFields
     * @return type
     * @throws Exception
     * 
     * @access public
     * @created 13/04/2015
     * @version V0.9.0
     */
    public function fields(Model $model, $virtualFields = false) {
        if ($model->useTable === false) {
            throw new Exception("Cannot get fields for model \"{$model->alias}\" since it does not use a table.");
            return array();
        }

        $fields = array();
        foreach (array_keys($model->schema($virtualFields)) as $field) {
            $fields[] = "{$model->alias}.{$field}";
        }

        return $fields;
    }

    /**
     * Retourne une sous-requête permettant de trouver le dernier enregistrement du modèle passé en
     * paramètres. L'alias du modèle dans la sous-requête est le nom de la table.
     * 
     * @todo grep -nri "function sqDernier" app | grep -v "\.svn"
     * @todo grep -nri ">sqDerniereRgadr01" app | grep -v "\.svn"
     * 
     * @param Model $model
     * @param string $modelSubquery
     * @param string $sortField
     * @param type $conditions
     * @param type|true $null
     * @return type
     * 
     * @access public
     * @created 13/04/2015
     * @version V0.9.0
     */
    public function sqLatest(Model $model, $modelSubquery, $sortField, $conditions = array(), $null = true) {
        $modelAlias = Inflector::tableize($modelSubquery);

        $join = $this->join($model, $modelSubquery);

        $conditions = (array) $join['conditions'] + (array) $conditions;
        $conditions = array_words_replace($conditions, array($modelSubquery => $modelAlias));

        $sq = $model->{$modelSubquery}->sq(
                array(
                    'alias' => $modelAlias,
                    'fields' => array(
                        "{$modelAlias}.{$model->{$modelSubquery}->primaryKey}"
                    ),
                    'contain' => false,
                    'conditions' => $conditions,
                    'order' => array(
                        "{$modelAlias}.{$sortField} DESC",
                    ),
                    'limit' => 1
                )
        );

        if ($null) {
            $ds = $model->getDataSource();
            $alias = "{$ds->startQuote}{$modelSubquery}{$ds->endQuote}.{$ds->startQuote}{$model->{$modelSubquery}->primaryKey}{$ds->endQuote}";
            $sq = "( {$alias} IS NULL OR {$alias} IN ( {$sq} ) )";
        }

        return $sq;
    }

}