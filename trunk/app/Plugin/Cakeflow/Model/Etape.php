<?php

class Etape extends CakeflowAppModel
{
    public $tablePrefix = 'wkf_';
    public $displayField = 'nom';

    /**
     * @var array Associations
     */
    public $belongsTo = array(
        'Cakeflow.Circuit',
        'CreatedUser' => array(
            'className' => CAKEFLOW_USER_MODEL,
            'foreignKey' => 'created_user_id'
        ),
        'ModifiedUser' => array(
            'className' => CAKEFLOW_USER_MODEL,
            'foreignKey' => 'modified_user_id'
        )
    );

    public $hasMany = array(
        'Cakeflow.Composition'
    );

    /**
     * @var array Règles de validation
     */
    public $validate = array(
        'circuit_id' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Champ obligatoire'
            )
        ),
        'nom' => array(
            array(
                'rule' => array('maxLength', '250'),
                'message' => 'Maximum 250 caractères'
            ),
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Champ obligatoire'
            )
        ),
        'type' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Champ obligatoire'
            )
        ),
        'ordre' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Champ obligatoire'
            )
        )
    );

    /**
     * @var array types liste des types des étapes
     */
    public $types = array(
        CAKEFLOW_SIMPLE => 'Simple',
        CAKEFLOW_CONCURRENT => 'Concurrent [OU]',
        CAKEFLOW_COLLABORATIF => 'Collaboratif [ET]'
    );

    /**
     * @param $id
     * @return bool
     */
    function moveUp($id)
    {
        $item = $this->find('first',array(
            'recursive' => -1,
            'conditions' => array('Etape.id' => $id)
        ));
        if (!empty($item)) {
            if ($item['Etape']['ordre'] > 1) {
                $swap = $this->find(
                    'first', array(
                        'conditions' => array(
                            'Etape.ordre' => $item['Etape']['ordre'] - 1,
                            'Etape.circuit_id' => $item['Etape']['circuit_id']
                        ),
                        'recursive' => -1
                    )
                );
                if (!empty($swap)) {
                    $swap['Etape']['ordre'] = $swap['Etape']['ordre'] + 1;
                    $item['Etape']['ordre'] = $item['Etape']['ordre'] - 1;

                    //Le compteur de retard de l'étape après ne doit pas être inférieur
                    if ($swap['Etape']['cpt_retard'] > $item['Etape']['cpt_retard'])
                        $swap['Etape']['cpt_retard'] = $item['Etape']['cpt_retard'];

                    $saved = true;
                    $this->begin();

                    $saved = $this->save($swap) && $saved;
                    $saved = $this->save($item) && $saved;

                    if ($saved) {
                        $this->commit();
                    } else {
                        $this->rollback();
                    }
                    return $saved;
                }
            }
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    function moveDown($id)
    {
        $item = $this->find('first',array(
            'recursive' => -1,
            'conditions' => array('Etape.id' => $id)
        ));
        if (!empty($item)) {
            $swap = $this->find(
                'first', array(
                    'conditions' => array(
                        'Etape.ordre' => $item['Etape']['ordre'] + 1,
                        'Etape.circuit_id' => $item['Etape']['circuit_id']
                    ),
                    'recursive' => -1
                )
            );
            if (!empty($swap)) {
                $swap['Etape']['ordre'] = $swap['Etape']['ordre'] - 1;
                $item['Etape']['ordre'] = $item['Etape']['ordre'] + 1;

                //Le compteur de retard de l'étape avant ne doit pas être supérieur
                if ($swap['Etape']['cpt_retard'] < $item['Etape']['cpt_retard'])
                    $swap['Etape']['cpt_retard'] = $item['Etape']['cpt_retard'];

                $saved = true;
                $this->begin();

                $saved = $this->save($swap) && $saved;
                $saved = $this->save($item) && $saved;

                if ($saved) {
                    $this->commit();
                } else {
                    $this->rollback();
                }
                return $saved;
            }
        }
        return false;
    }

    /**
     * @param $circuit_id
     * @return array
     */
    function getNbEtapes($circuit_id)
    {
        return ($this->find('count', array('circuit_id' => "$circuit_id")));
    }

    /**
     * @param $circuit_id
     * @return bool
     */
    function _reorder($circuit_id)
    {
        $items = $this->find(
            'all',
            array(
                'conditions' => array('Etape.circuit_id' => $circuit_id),
                'order' => array('ordre ASC'),
                'recursive' => -1
            )
        );
        if (!empty($items)) {
            $i = 1;
            $saved = true;
            foreach ($items as $item) {
                $item['Etape']['ordre'] = $i;
                $this->create($item);
                $saved = $this->save() && $saved;
                $i++;
            }
        }
        return true;
    }

    /**
     * @param integer $id
     * @param bool $cascade
     * @return bool
     */
    function del($id = NULL, $cascade = true)
    {
        $this->begin();
        if ($item = $this->findById($id, null, null, -1)) {
            $return = parent::del($id, $cascade);
            $return = $this->_reorder($item['Etape']['circuit_id']) && $return;
            if ($return) {
                $this->commit();
            } else {
                $this->rollback();
            }
            return $return;
        }
        return false;
    }

    /**
     * Détermine si une étape est la dernière d'un circuit
     * @param integer $etapeId id de l'étape que l'on veut tester
     * @return boolean true si dernière étape, false dans le cas contraire
     */
    function estDerniereEtape($etapeId)
    {
        $etape = $this->find('first', array(
            'conditions' => array('Etape.id' => $etapeId),
            'recursive' => -1,
            'fields' => array('Etape.circuit_id', 'Etape.ordre')));

        $etapeSuivanteOrdre = $etape['Etape']['ordre'] + 1;

        $etapeSuivante = $this->find('first', array(
            'conditions' => array(
                'Etape.circuit_id' => $etape['Etape']['circuit_id'],
                'Etape.ordre' => $etapeSuivanteOrdre),
            'recursive' => -1,
            'fields' => array('Etape.id')));

        return empty($etapeSuivante);
    }

    /**
     * Détermine si une étape est la dernière d'un circuit
     * @param integer $circuitId id du circuit
     * @return integer identifiant de la dernière étape
     */
    function getDerniereEtape($circuitId)
    {
        $etape = $this->find('first', array(
            'conditions' => array('circuit_id' => $circuitId),
            'recursive' => -1,
            'order' => array('Etape.ordre DESC')));

        return $etape['Etape']['id'];
    }
    /**
     * Retourne l'id de l'étape suivante d'une étape
     * @param integer $etapeId id de l'étape
     * @return integer id de l'étape suivante, 0 si pas d'étape suivante
     */
    function etapeSuivante($etapeId)
    {
        $etape = $this->find('first', array(
            'conditions' => array(
                'Etape.id' => $etapeId),
            'recursive' => -1));

        $etapeSuivanteOrdre = $etape['Etape']['ordre'] + 1;

        $etapeSuivante = $this->find('first', array(
            'conditions' => array(
                'Etape.circuit_id' => $etape['Etape']['circuit_id'],
                'Etape.ordre' => $etapeSuivanteOrdre),
            'recursive' => -1,
            'fields' => array('Etape.id')));

        return empty($etapeSuivante) ? 0 : $etapeSuivante['Etape']['id'];
    }

    /**
     * Indique si une étape pour un traitement est validée ou pas (ie peut-on passer à l'étape suivante)
     * @param integer $traitementId id de l'étape
     * @param integer $etapeId id de l'étape
     * @param $numeroTraitement
     * @return bool true si l'étape est validée et false dans le cas contraire
     */
    function etapeValidee($traitementId, $etapeId, $numeroTraitement)
    {
        // Initialisation
        $validee = false;
        // Lecture de l'étape
        $etape = $this->find('first', array(
            'conditions' => array(
                'Etape.id' => $etapeId),
            'recursive' => -1));
        // recherche de ou des compositions
        $compositions = $this->Composition->find('all', array(
            'conditions' => array(
                'Composition.etape_id' => $etape['Etape']['id']),
            'recursive' => -1));
        // on teste en fonction du type de l'étape la présence d'un visa pour chaque composition
        foreach ($compositions as $composition) {
            $visa = $this->Visa->find('first', array(
                'conditions' => array(
                    'Visa.traitement_id' => $traitementId,
                    'Visa.composition_id' => $composition['Composition']['id'],
                    'Visa.numero_traitement' => $numeroTraitement),
                'recursive' => -1));
            $validee = !empty($visa);
            if ($etape['Etape']['type'] == CAKEFLOW_SIMPLE) {
                break;
            } elseif ($etape['Etape']['type'] == CAKEFLOW_CONCURRENT) {
                if ($validee) break;
            } elseif ($etape['Etape']['type'] == CAKEFLOW_COLLABORATIF) {
                if (!$validee) break;
            }
        }
        return $validee;
    }

    /**
     * Vérifie si l'on peut rajouter un composant à cette étape, à savoir:
     * étape pas de type simple, ou nombre de composants égale à 0.
     */
    public function canAdd($id)
    {
        $nbrCompositionsEtape = $this->Composition->find(
            'count',
            array(
                'conditions' => array('Composition.etape_id' => $id)
            )
        );
        $type = $this->field('type', array('Etape.id' => $id));
        return (($type != CAKEFLOW_SIMPLE) || ($nbrCompositionsEtape < 1));
    }

    /**
     * retourne la liste des étapes précédentes
     * @param integer $etapeId id de l'étape courante
     * @return array tableau associatif id-nom des étapes précédentes
     */
    function listeEtapesPrecedentes($etapeId)
    {
        $etape = $this->find('first', array(
            'conditions' => array('Etape.id' => $etapeId),
            'recursive' => -1));
        if (empty($etape) || $etape['Etape']['ordre'] == 1)
            return array();
        else
            return $this->find('list', array(
                'conditions' => array(
                    'Etape.circuit_id' => $etape['Etape']['circuit_id'],
                    'Etape.ordre <' => $etape['Etape']['ordre']),
                'order' => array('Etape.ordre')));
    }

    /**
     * Retourne le libellé du soustype d'étape
     * @param string $code_soustype
     */
    function libelleSousType($code_soustype)
    {
        $soustypes = $this->listeSousTypesParapheur();
        return $soustypes[$code_soustype];
    }

    /**
     * Détermine si une instance peut être supprimée tout en respectant l'intégrité référentielle
     * Paramètre : id
     */
    function isDeletable($id)
    {
        // Existence de l'instance en base
        if (!$this->find('count', array('recursive' => -1, 'conditions' => array('id' => $id))))
            return false;

        // Existence d'une composition liée
        if ($this->Composition->find('count', array('recursive' => -1, 'conditions' => array('etape_id' => $id))))
            return false;

        return true;
    }

    /**
     * Retourne la liste des sous types du parapheurs associés au type défini dans le fichier de config
     * @return array soustypes
     */
    function listeSousTypesParapheur()
    {
        App::uses('Signature','Lib');
        $Signature = new Signature();
        return $Signature->listCircuits();
    }

    /**
     * Calcule la date avant retard selon la séance délibérante moins le nombre de jours avant retard
     * @param $cptRetard nombre de jours avant retard
     * @param $targetId identifiant du projet
     * @return string date avant retard ou null si aucune séance délibérante rattachée
     */
    function computeDateRetard($cptRetard, $targetId){
        if (CAKEFLOW_APP === 'WEBDELIB'){
            $seance_id = $this->Circuit->Traitement->{CAKEFLOW_TARGET_MODEL}->getSeanceDeliberanteId($targetId);
            if (!empty($seance_id)){
                $seance = $this->Circuit->Traitement->{CAKEFLOW_TARGET_MODEL}->Seance->find('first', array(
                    'recursive' => -1,
                    'conditions' => array('Seance.id' => $seance_id),
                    'fields' => array('Seance.date')
                ));
                if (!empty($seance['Seance']['date'])){
                    if (empty($cptRetard)){
                        return $seance['Seance']['date'];
                    }else{
                        return date('Y-m-d H:i:s', strtotime($seance['Seance']['date']. " - $cptRetard days"));
                    }
                }
            }
            //Si pas de séance délibérante : ne rien renvoyer
            //Pourrait renvoyer date d'aujourd'hui + compteur retard
        }
        return null;
    }

}
