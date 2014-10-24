<?php
App::uses('CakeflowAppModel', 'Cakeflow.Model');
class Circuit extends CakeflowAppModel
{
    public $tablePrefix = 'wkf_';
    public $displayField = 'nom';

    /** ********************************************************************
     *    Associations
     *** *******************************************************************/
    public $belongsTo = array(
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
        'Cakeflow.Etape',
        'Cakeflow.Traitement'
    );

    /** ********************************************************************
     *    Règles de validation
     *** *******************************************************************/
    public $validate = array(
        'nom' => array(
            array(
                'rule' => array('maxLength', '250'),
                'message' => 'Maximum 250 caractères'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'Valeur déjà utilisée'
            ),
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Champ obligatoire'
            )
        )
    );

    /*
     * les regles de controle et les messages d'erreurs de la variable de classe 'validate'
     * sont initialisées ici car on utilise les fonctions d'internationalisation __()
     * que l'on ne peut pas utiliser lors de la déclaration de la variable
     */
    function beforeValidate($options = array())
    {
        // nom : unique et obligatoire
        $this->validate['nom']['obligatoire'] = array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => __('Information obligatoire.', true) . ' ' . __('Veuillez saisir un nom.', true)
        );
        $this->validate['nom']['unique'] = array(
            'rule' => 'isUnique',
            'message' => __('Ce nom est deja utilise.', true) . ' ' . __('Veuillez saisir un autre nom.', true)
        );
        // actif : au moins un actif
        $this->validate['actif']['auMoinsUnActif'] = array(
            'rule' => 'verifActif',
            'message' => __('Il faut au moins un circuit de traitement actif.', true) . ' ' . __('Veuillez cocher ce circuit actif.', true)
        );


        return true;
    }

    /**
     * Vérifie qu'au moins un circuit est actif
     */
    function verifActif()
    {
        return (
            $this->data[$this->name]['actif']
            ||
            $this->find('count', array(
                'conditions' => array(
                    'Circuit.id <>' => $this->data[$this->name]['id'],
                    'Circuit.actif' => true),
                'recursive' => -1)));
    }


    /** ********************************************************************
     *    INFO: ne servait qu'avec UserDelegue ? à vérifier
     *** *******************************************************************/
    /*function afterFind( $results, $primary = false ) {
        $compositions = Set::extract( $results, '/Etape/Composition' );
        if( $primary && !empty( $compositions ) ) {
            foreach( $results as $i => $result ) {
                foreach( $result['Etape'] as $j => $etape ) {
                    foreach( $etape['Composition'] as $k => $composition ) {
                        $user = $this->Etape->Composition->User->find(
                            'first',
                            array(
                                'fields' => array( 'User.nom', 'User.prenom' ),
                                'conditions' => array( 'User.id' => $composition['trigger_id'] ),
                                'recursive' => -1
                            )
                        );
                        $results[$i]['Etape'][$j]['Composition'][$k] = Set::merge( $results[$i]['Etape'][$j]['Composition'][$k], $user );

                        $userDelegue = $this->Etape->Composition->UserDelegue->find(
                            'first',
                            array(
                                'fields' => array( 'UserDelegue.nom', 'UserDelegue.prenom' ),
                                'conditions' => array( 'UserDelegue.id' => $composition['user_delegue_id'] ),
                                'recursive' => -1
                            )
                        );
                        $results[$i]['Etape'][$j]['Composition'][$k] = Set::merge( $results[$i]['Etape'][$j]['Composition'][$k], $userDelegue );
                    }
                }
            }
        }
        return $results;
    }*/

    /**
     * Détermine si une instance peut être supprimée tout en respectant l'intégrité référentielle
     * Paramètre : id
     */
    function isDeletable($id)
    {
        // Existence de l'instance en base
        if (!$this->find('count', array('recursive' => -1, 'conditions' => array('id' => $id))))
            return false;

        // Existence d'une étape liée
        if ($this->Etape->find('count', array('recursive' => -1, 'conditions' => array('circuit_id' => $id))))
            return false;

        return true;
    }

    /**
     * Mise à jour du champ défaut
     */
    function afterSave($created, $options=array())
    {
        if (CAKEFLOW_GERE_DEFAUT && $this->data[$this->name]['defaut'])
            $this->updateAll(
                array('Circuit.defaut' => 'false'),
                array('Circuit.id <>' => $this->id));
    }

    /**
     * Retourne l'id du circuit actif par défaut
     * @return integer id du circuit actif par défaut
     */
    function getDefautId()
    {
        $data = $this->find('first', array(
            'conditions' => array('actif' => true, 'defaut' => true),
            'fields' => array('id'),
            'recursive' => -1));
        if (empty($data))
            return null;
        else
            return $data['Circuit']['id'];
    }

    /**
     * Retourne les id des circuits actifs et ayant au moins une étape
     * @return array liste des id
     */
    function actifsAuMoinsUneEtape()
    {
        // initialisations
        $ret = null;
        // lecture de tous les circuits actifs
        $circuits = $this->find('all', array(
            'conditions' => array('actif' => true),
            'fields' => array('id'),
            'recursive' => -1));
        foreach ($circuits as $circuit) {
            if ($this->Etape->hasAny(array('circuit_id' => $circuit['Circuit']['id'])))
                $ret[] = $circuit['Circuit']['id'];
        }

        return $ret;
    }

    function getList()
    {
        return $this->find('list', array('conditions' => array('actif' => 1),
            'order' => array('nom')));
    }

    function listeCircuitsParUtilisateur($user_id)
    {
        $circuits = array();
        $compositions = $this->Etape->Composition->find('all', array('conditions' => array('Composition.trigger_id' => $user_id)));
        foreach ($compositions as $composition) {
            if (!empty($composition['Etape']['circuit_id']))
                array_push($circuits, $composition['Etape']['circuit_id']);
        }
        return implode($circuits, ',');
    }

    function getLibelle($circuit_id = null)
    {
        if ($circuit_id == null)
            return '';
        $circuit = $this->find('first', array('conditions' => "Circuit.id = $circuit_id",
            'recursive' => '-1',
            'field' => 'nom'));
        return $circuit['Circuit']['nom'];
    }

    function userInCircuit($user_id, $circuit_id)
    {
        $etapes = $this->Etape->find('all', array('conditions' => array('Etape.circuit_id' => $circuit_id)));
        foreach ($etapes as $etape) {
            foreach ($etape['Composition'] as $composition)
                if ($composition['trigger_id'] == $user_id)
                    return true;
        }
        return false;
    }

    /**
     * Insert une cible ($targetId) dans le circuit de traitement $circuitId
     * @param integer $circuitId identifiant du circuit
     * @param integer $targetId identifiant de la cible (objet à faire traiter dans le circuit)
     * @param integer $createdUserId idenifiant de l'utilisateur connecté a l'origine de la création
     * @return bool
     */

    function insertDansCircuit($circuitId, $targetId, $createdUserId = null)
    {
        // on vérifie l'existence du circuit
        if (!$this->find('count', array('recursive' => -1, 'conditions' => array('id' => $circuitId))))
            return false;
        // on vérifie l'existence de la cible
        if (!$this->Traitement->{CAKEFLOW_TARGET_MODEL}->find('count', array('recursive' => -1, 'conditions' => array('id' => $targetId))))
            return false;
        // on vérifie que le traitement n'existe pas déjà en base
        if ($this->Traitement->find('count', array('recursive' => -1, 'conditions' => array('target_id' => $targetId))))
            return false;

        // ajout d'une occurence dans la table traitement
        $traitement = $this->Traitement->create();
        $traitement['Traitement']['circuit_id'] = $circuitId;
        $traitement['Traitement']['target_id'] = $targetId;
        $traitement['Traitement']['numero_traitement'] = 1;
        $traitement['Traitement']['treated'] = false;
        $traitement['Traitement']['created_user_id'] = $createdUserId;
        $traitement['Traitement']['modified_user_id'] = $createdUserId;
        $this->Traitement->save($traitement);
        $traitement['Traitement']['id'] = $this->Traitement->id;

        // ajout des occurences dans la table visa
        $this->Etape->Behaviors->attach('Containable');
        $etapes = $this->Etape->find('all', array(
            'fields' => array('Etape.id', 'Etape.nom', 'Etape.type', 'Etape.ordre', 'Etape.cpt_retard'),
            'contain' => array('Composition.trigger_id', 'Composition.type_validation'),
            'conditions' => array('Etape.circuit_id' => $circuitId),
            'order' => array('Etape.ordre ASC')
        ));
        foreach ($etapes as $etape) {
            foreach ($etape['Composition'] as $composition) {
                $visa = $this->Traitement->Visa->create();
                $visa['Visa']['traitement_id'] = $traitement['Traitement']['id'];
                $visa['Visa']['trigger_id'] = $composition['trigger_id'];
                $visa['Visa']['signature_id'] = 0;
                $visa['Visa']['etape_nom'] = $etape['Etape']['nom'];
                $visa['Visa']['etape_id'] = $etape['Etape']['id'];
                $visa['Visa']['etape_type'] = $etape['Etape']['type'];
                $visa['Visa']['date_retard'] = $this->Etape->computeDateRetard($etape['Etape']['cpt_retard'], $targetId);
                $visa['Visa']['action'] = 'RI';
                $visa['Visa']['commentaire'] = '';
                $visa['Visa']['date'] = null;
                $visa['Visa']['numero_traitement'] = $etape['Etape']['ordre'];
                $visa['Visa']['type_validation'] = $composition['type_validation'];
                $this->Traitement->Visa->save($visa);
            }
        }

        return true;
    }

    /**
     * Ajoute au traitement terminé de la cible $targetId les étapes du circuit $circuitId
     * @param integer $circuitId identifiant du circuit
     * @param integer $targetId identifiant de la cible (objet à faire traiter dans le circuit)
     * @param integer $createdUserId idenifiant de l'utilisateur connecté a l'origine de la création
     * @return bool
     */
    function ajouteCircuit($circuitId, $targetId, $createdUserId = null)
    {
        // on vérifie l'existence du circuit
        if (!$this->find('count', array('recursive' => -1, 'conditions' => array('id' => $circuitId))))
            return false;
        // on vérifie l'existence de la cible
        if (!$this->Traitement->{CAKEFLOW_TARGET_MODEL}->find('count', array('recursive' => -1, 'conditions' => array('id' => $targetId))))
            return false;
        // on vérifie que le traitement existe
        if (!$this->Traitement->find('count', array('recursive' => -1, 'conditions' => array('target_id' => $targetId))))
            return false;

        // lecture du traitement
        $traitement = $this->Traitement->find('first', array(
            'recursive' => -1,
            'fields' => array('id', 'numero_traitement'),
            'conditions' => array(
                'target_id' => $targetId,
                'treated' => true)));
        if (empty($traitement))
            return false;

        // ajout des occurences dans la table visa
        $this->Etape->Behaviors->attach('Containable');
        $etapes = $this->Etape->find('all', array(
            'fields' => array('Etape.id', 'Etape.nom', 'Etape.type', 'Etape.ordre', 'Etape.cpt_retard'),
            'contain' => array('Composition.trigger_id', 'Composition.type_validation'),
            'conditions' => array('Etape.circuit_id' => $circuitId),
            'order' => array('Etape.ordre ASC')
        ));
        foreach ($etapes as $etape) {
            foreach ($etape['Composition'] as $composition) {
                $visa = $this->Traitement->Visa->create();
                $visa['Visa']['traitement_id'] = $traitement['Traitement']['id'];
                $visa['Visa']['trigger_id'] = $composition['trigger_id'];
                $visa['Visa']['signature_id'] = 0;
                $visa['Visa']['etape_nom'] = $etape['Etape']['nom'];
                $visa['Visa']['etape_id'] = $etape['Etape']['id'];
                $visa['Visa']['etape_type'] = $etape['Etape']['type'];
                $visa['Visa']['date_retard'] = $this->Etape->computeDateRetard($etape['Etape']['cpt_retard'], $targetId);
                $visa['Visa']['action'] = 'RI';
                $visa['Visa']['commentaire'] = '';
                $visa['Visa']['date'] = null;
                $visa['Visa']['numero_traitement'] = $traitement['Traitement']['numero_traitement'] + $etape['Etape']['ordre'];
                $visa['Visa']['type_validation'] = $composition['type_validation'];
                $this->Traitement->Visa->save($visa);
            }
        }

        // mise à jour du traitement
        $traitement['Traitement']['circuit_id'] = $circuitId;
        $traitement['Traitement']['modified_user_id'] = $createdUserId;
        $traitement['Traitement']['treated'] = false;
        $traitement['Traitement']['numero_traitement']++;
        $this->Traitement->save($traitement);

        return true;
    }

    public function hasEtapeDelegation($circuit_id){
        $this->Etape->Behaviors->attach('Containable');
        $etapes = $this->Etape->find('all', array(
            'conditions' => array(
                'Etape.circuit_id' => $circuit_id
            ),
            'contain' => array('Composition.type_validation'),
        ));
        $compositions = Hash::extract($etapes, '{n}.Composition.{n}.type_validation');
        return in_array('D', $compositions);
    }
}
