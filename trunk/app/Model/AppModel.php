<?php

/**
 * Model AppModel
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
 * @package     AppModel
 */
App::uses('Model', 'Model');
App::uses('FrValidation', 'Validation');

class AppModel extends Model {

    public $recursive = -1;
    public $actsAs = array(
        //'DatabaseTable',
        'Containable',
        'Database.DatabaseTable',
        'Database.DatabaseFormattable',
        'Database.DatabaseAutovalidate',
        'Postgres.PostgresAutovalidate'
    );

    /**
     * Cache "live", notamment utilisé par la méthode enums.
     *
     * @var array
     */
    protected $_appModelCache = array();

    /**
     * Retourne la liste des options venant des champs possédant la règle de
     * validation inList.
     *
     * @return array
     */
    public function enums() {
        $cacheKey = $this->useDbConfig . '_' . __CLASS__ . '_enums_' . $this->alias;

        // Dans le cache "live" ?
        if (false === isset($this->_appModelCache[$cacheKey])) {
            $this->_appModelCache[$cacheKey] = Cache::read($cacheKey);

            // Dans le cache CakePHP ?
            if (false === $this->_appModelCache[$cacheKey]) {
                $this->_appModelCache[$cacheKey] = array();

                $domain = Inflector::underscore($this->alias);

                // D'autres champs avec la règle inList ?
                foreach ($this->validate as $field => $validate) {
                    foreach ($validate as $ruleName => $rule) {
                        if (( $ruleName === 'inList' ) && !isset($this->_appModelCache[$cacheKey][$this->alias][$field])) {
                            $fieldNameUpper = strtoupper($field);

                            $tmp = $rule['rule'][1];
                            $list = array();

                            foreach ($tmp as $value) {
                                $list[$value] = __d($domain, "ENUM::{$fieldNameUpper}::{$value}");
                            }

                            $this->_appModelCache[$cacheKey][$this->alias][$field] = $list;
                        }
                    }
                }

                Cache::write($cacheKey, $this->_appModelCache[$cacheKey]);
            }
        }

        return (array) $this->_appModelCache[$cacheKey];
    }

    /**
     * Vérifie qu'un enregistrement soit bien unique avec les valeurs de plusieurs
     * colonnes (comme lorsdqu'une table possède un INDEX UNIQUE sur plusieurs
     * colonnes).
     *
     * @param array $data Les données du champ envoyées à la validation
     * @param array $fieldNames La liste des champs à contrôler
     * @return type
     */
    public function isUniqueMultiple(array $data, array $fieldNames) {
        $query = [
            'fields' => ["{$this->alias}.{$this->primaryKey}"],
            'recursive' => -1,
            'conditions' => []
        ];

        foreach ($fieldNames as $fieldName) {
            $fieldName = "{$this->alias}.{$fieldName}";
            $query['conditions'][$fieldName] = Hash::get($this->data, $fieldName);
        }

        $primaryKey = Hash::get($this->data, "{$this->alias}.{$this->primaryKey}");
        $primaryKey = true === empty($primaryKey) ? $this->{$this->primaryKey} : $primaryKey;

        if (false === empty($primaryKey)) {
            $query['conditions']['NOT'] = ["{$this->alias}.{$this->primaryKey}" => $primaryKey];
        }

        return [] === $this->find('first', $query);
    }

}
