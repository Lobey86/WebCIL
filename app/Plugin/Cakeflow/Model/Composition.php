<?php

/**
 * Modèle des cmpositions
 */
class Composition extends CakeflowAppModel
{

    public $tablePrefix = 'wkf_';

    /*************************************************************************
     *    Associations
     *************************************************************************/
    public $belongsTo = array(
        'Cakeflow.Etape',
        CAKEFLOW_TRIGGER_MODEL => array(
            'className' => CAKEFLOW_TRIGGER_MODEL,
            'foreignKey' => 'trigger_id'
        )
    );

    /*************************************************************************
     *    Règles de validation
     *************************************************************************/
    public $validate = array(
        'etape_id' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Champ obligatoire'
            )
        ),
        'trigger_id' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Champ obligatoire'
            )
        ),
        'type_validation' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Champ obligatoire'
            )
        ),
        'type_composition' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Champ obligatoire'
            )
        )
    );

    /**
     * Retourne true si la validation de la composition est de type Signature
     * @param integer $etapeId id de l'étape de la composition
     * @param integer $userId id de l'utilisateur de la composition
     * @return boolean true si signature, false dans le cas contraire
     */
    function validationParSignature($etapeId, $userId)
    {
        $data = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('type_validation'),
            'conditions' => array(
                'etape_id' => $etapeId,
                'trigger_id' => $userId)));
        return ($data['Composition']['type_validation'] == 'S');
    }

    /**
     * Retourne la liste des types de validation
     * @return array code, libelle de la liste des type de composition
     */
    function listeTypeValidation()
    {
        return array(
            'V' => __('Visa', true),
            'S' => __('Signature', true),
            'D' => __('Délégation de validation', true));
    }

    /**
     * Retourne le libellé du type de validation
     * @param string $code_type lettre S ou V
     */
    function libelleTypeValidation($code_type)
    {
        $typesValid = $this->listeTypeValidation();
        return $typesValid[$code_type];
    }

    /**
     * Retourne la liste des types de composition
     * @return array code, libelle de la liste des types de composition
     */
    function listeTypes()
    {
        $ret = array(
            'USER' => __('Utilisateur de ', true) .' '. CAKEFLOW_APP
        );
        if (Configure::read('USE_PARAPHEUR')) {
            $ret['PARAPHEUR'] = __('Parapheur électronique', true);
        }
        return $ret;
    }
}
