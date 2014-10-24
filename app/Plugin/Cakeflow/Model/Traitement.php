<?php
App::uses('CakeflowAppModel', 'Cakeflow.Model');

class Traitement extends CakeflowAppModel {
    public $tablePrefix = 'wkf_';
    public $belongsTo = array(
        'Cakeflow.Circuit',
        CAKEFLOW_TARGET_MODEL => array(
            'className' => CAKEFLOW_TARGET_MODEL,
            'foreignKey' => 'target_id')
    );
    public $hasMany = array(
        'Visa' => array(
            'className' => 'Cakeflow.Visa',
            'foreignKey' => 'traitement_id',
            'dependent' => true
        )
    );

    /**
     * retourne un tableau contenant les informations permettant de localiser l'archive dans un circuit
     * @param integer $circuit_id id du circuit
     * @param integer $target_id id du projet
     * @return array  $infos contenant l'etape_id et traitement_id courant
     * @return integer  -1 si aucune initialisation
     * @return integer  -2 si dossier a fini son parcours
     */
    function getLocalisation($circuit_id, $target_id) {
        $infos = array();
        $traitement = $this->find('first', array(
            'conditions' => array('Traitement.circuit_id' => $circuit_id,
                'Traitement.target_id' => $target_id)
        ));
        // Aucun traitement pour cette archive => il faut l'initialiser
        if (empty($traitement))
            return -1;
        $validee = $this->Circuit->Etape->etapeValidee($traitement['Traitement']['id'], $traitement['Traitement']['etape_id'], $traitement['Traitement']['numero_traitement']);
        if (!$validee) {
            $infos['etape_id'] = $traitement['Etape']['id'];
            $infos['traitement_id'] = $traitement['Traitement']['id'];
            $infos['numero_traitement'] = $traitement['Traitement']['numero_traitement'];
            return $infos;
        }

        //Tous les traitements ont été visés => on renvoie la derniere etape
        return $infos;
    }

    /**
     * retourne true si l'utilisateur $userId est le dernier dans le traitement $traitementId
     * @param integer $traitementId id du traitement
     * @param integer $userId id de l'utilisateur
     * @return bool true si dernier à traiter et false dans le cas contraire
     */
    function dernierUserATraiterCircuit($traitementId, $userId) {
        // lecture du traitement
        $traitement = $this->find('first', array(
            'conditions' => array('Traitement.id' => $traitementId),
            'recursive' => -1));

        if ($this->Circuit->Etape->estDerniereEtape($traitement['Traitement']['etape_id'])) {
            $etape = $this->Circuit->Etape->find('first', array(
                'conditions' => array('Etape.id' => $traitement['Traitement']['etape_id']),
                'recursive' => -1));
            if ($etape['Etape']['type'] == CAKEFLOW_SIMPLE)
                return true;
            elseif ($etape['Etape']['type'] == CAKEFLOW_CONCURRENT)
                return true;
            elseif ($etape['Etape']['type'] == CAKEFLOW_COLLABORATIF) {
                // liste des compositions de l'étape sauf celle de $userId
                $compositions = $this->Circuit->Etape->Composition->find('all', array(
                    'conditions' => array(
                        'Composition.etape_id' => $traitement['Traitement']['etape_id'],
                        'Composition.user_id <>' => $userId),
                    'recursive' => -1));
                // trouve-t-on tous les visas correspondants?
                $dernierATraiter = true;
                foreach ($compositions as $composition) {
                    $visa = $this->Visa->find('first', array(
                        'conditions' => array(
                            'Visa.traitement_id' => $traitementId,
                            'Visa.composition_id' => $composition['Composition']['id'],
                            'Visa.numero_traitement' => $traitement['Traitement']['numero_traitement']),
                        'recursive' => -1));
                    if (empty($visa)) {
                        $dernierATraiter = false;
                        break;
                    }
                }
                return $dernierATraiter;
            }
        }
        return false;
    }

    /**
     * @deprecated
     * @param $circuit_id
     * @param $archive_id
     * @return array
     */
    function getEtapesPrecedents($circuit_id, $archive_id) {
        $liste = array();
        $localisation = $this->getLocalisation($circuit_id, $archive_id);
        $etapes = $this->Circuit->Etape->find('all', array('conditions' => "Etape.circuit_id = $circuit_id",
            'order' => 'Etape.ordre ASC',
            'recursive' => -1));

        foreach ($etapes as $etape) {
            if ($localisation['etape_id'] == $etape['Etape']['id'])
                return $liste;
            $liste[$etape['Etape']['id']] = $etape['Etape']['nom'];
        }

        return $liste;
    }

    /**
     * @param integer $targetId , projet concerné
     * @return integer id de l'etape courante
     */
    function getEtapeCouranteId($targetId) {

        // lecture du traitement pour la cible
        $traitement = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('id', 'numero_traitement', 'circuit_id'),
            'conditions' => array('target_id' => $targetId)));
        if (empty($traitement))
            return null;

        //lecture du nom de l'étape courante pour exclusion
        $visa_actuel = $this->Visa->find('first', array(
            'recursive' => -1,
            'fields' => array('etape_id'),
            'conditions' => array(
                'traitement_id' => $traitement['Traitement']['id'],
                'numero_traitement' => $traitement['Traitement']['numero_traitement']
            )));

        if (empty($visa_actuel))
            return null;

        //lecture du nom de l'étape courante pour exclusion
        $etape_courante = $this->Circuit->Etape->find('first', array(
            'recursive' => -1,
            'fields' => array('id'),
            'conditions' => array(
                'id' => $visa_actuel['Visa']['etape_id'],
                'circuit_id' => $traitement['Traitement']['circuit_id']
            )));
        if (empty($etape_courante))
            return null;

        return $etape_courante['Etape']['id'];

    }

    function retour($circuit_id, $archive_id, $etape_id) {
        $traitement = $this->find('first', array(
            'conditions' => array('Traitement.circuit_id' => $circuit_id,
                'Traitement.archive_id' => $archive_id),
            'order' => 'Etape.ordre ASC',
            'recursive' => -1
        ));
        $numero_traitement = $traitement['Traitement']['numero_traitement'];
        $traitement = array('Traitement' => array(
            'id' => $traitement['Traitement']['id'],
            'circuit_id' => $circuit_id,
            'etape_id' => $etape_id,
            'archive_id' => $archive_id,
            'numero_traitement' => $numero_traitement));

        $this->save($traitement);
    }

    function jump($circuit_id, $archive_id, $user_id) {
        $localisation = $this->getLocalisation($circuit_id, $archive_id);
        if ($this->dernierUserATraiterCircuit($localisation['traitement_id'], $user_id))
            return -1;
        $traitement = $this->find('first', array(
            'conditions' => array('Traitement.id' => $localisation['traitement_id']),
            'recursive' => -1));
        $traitement['Traitement']['etape_id'] = $this->Circuit->Etape->etapeSuivante($localisation['etape_id']);
        $traitement['Traitement']['numero_traitement'] = $traitement['Traitement']['numero_traitement'] + 1;
        return ($this->save($traitement));
    }

    /**
     * indique la position du déclencheur $triggerId dans l'exécution du circuit de traitement de la cible $targetId
     * Attention : le déclencheur doit faire parti du traitement de la cible, et la cible doit être en cours de traitement
     * @param integer $triggerId identifiant du déclencheur
     * @param integer $targetId identifiant de la cible
     * @return integer indique la position du déclencheur comme suit :
     *    -1 : le déclencheur a déjà effectué le traitement de la cible
     *     0 : le déclencheur doit traiter la cible
     *     1 : le déclencheur va effectuer le traitement de la cible dans les étapes suivantes
     */
    function positionTrigger($triggerId, $targetId) {
        // lecture du traitement
        $traitement = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('id', 'numero_traitement', 'treated'),
            'conditions' => array('target_id' => $targetId)));
        if (empty($traitement) || $traitement['Traitement']['treated'])
            return false;
        // priorité au traitement 'a traiter'
        $visa = $this->Visa->find('first', array(
            'recursive' => -1,
            'fields' => array('id'),
            'conditions' => array(
                'traitement_id' => $traitement['Traitement']['id'],
                'numero_traitement' => $traitement['Traitement']['numero_traitement'],
                'trigger_id' => $triggerId,
                'OR' => array(array('action' => 'RI'), array('action' => 'IN')))));
        if (!empty($visa))
            return 0;
        // lecture du dernier visa du déclencheur
        $visa = $this->Visa->find('first', array(
            'recursive' => -1,
            'fields' => array('numero_traitement', 'action'),
            'conditions' => array(
                'traitement_id' => $traitement['Traitement']['id'],
                'trigger_id' => $triggerId),
            'order' => array('numero_traitement DESC')));
        if (empty($visa))
            return false;
        // test de la position du déclencheur
        if ($visa['Visa']['numero_traitement'] < $traitement['Traitement']['numero_traitement'])
            return -1;
        elseif ($visa['Visa']['numero_traitement'] > $traitement['Traitement']['numero_traitement'])
            return 1;
        else {
            if ($visa['Visa']['action'] == 'RI' XOR $visa['Visa']['action'] == 'IN')
                return 0;
            else
                return -1;
        }
    }

    /**
     * retourne la liste des identifiants des cibles (target) liés à l'identifiant du déclencheur $triggerId
     * @param integer $triggerId identifiant du déclencheur lié aux traitements recherchés.
     * @param array $options options de la fonction comme suit :
     *    'etat' => ['TRAITE', 'NONTRAITE'] defaut : 'NONTRAITE'
     *    'traitement' => ['FAIT', 'AFAIRE', 'NONAFAIRE', 'AVENIR'] defaut : 'AFAIRE'. N'a de sens que pour les non traités (paramétre 'treated'=>NONTRAITE)
     *  'targetConditions' => conditions sur le modele des cibles : attention, préfixer le champ avec le nom du model de la cible
     * @return array
     */
    function listeTargetId($triggerId, $options = array()) {
        // initialisations
        $ret = array();
        $initOptions = array(
            'etat' => 'NONTRAITE',
            'traitement' => 'AFAIRE',
            'targetConditions' => array());
        $options = array_merge($initOptions, $options);

        // constitution de la condition
        $conditions = array();
        if ($options['etat'] == 'NONTRAITE')
            $conditions['Traitement.treated'] = 0;
        elseif ($options['etat'] == 'TRAITE')
            $conditions['Traitement.treated'] = 1;

        if (!empty($options['targetConditions']))
            $conditions = array_merge($options['targetConditions'], $conditions);

        // lecture des traitements du triggers //Optimisation de lecture
        $this->bindModel(array('hasOne' => array('Visa' => array('className' => 'Cakeflow.Visa'))));

        $this->Behaviors->load('Containable');
        $conditions['Visa.trigger_id'] = $triggerId;
        $traitements = $this->find('all', array(
            'fields' => array('DISTINCT Traitement.target_id'),
            'conditions' => array($conditions),
            'contain' => array(
                'Visa' => array(
                    'fields' => array('Visa.id'),
                    'conditions' => array('Visa.trigger_id' => $triggerId)
                ),
                CAKEFLOW_TARGET_MODEL => array(
                    'fields' => array(CAKEFLOW_TARGET_MODEL . '.id')
                )
            )
        ));
        // constitution de la réponse
        if ($options['etat'] == 'TRAITE') {
            foreach ($traitements as $traitement)
                $ret[] = $traitement['Traitement']['target_id'];
        } else {
            foreach ($traitements as $traitement) {
                // filtrage en fonction de la position pour les cibles non traitées
                $position = $this->positionTrigger($triggerId, $traitement['Traitement']['target_id']);
                if ($options['traitement'] == 'FAIT' && $position < 0)
                    $ret[] = $traitement['Traitement']['target_id'];
                elseif ($options['traitement'] == 'AFAIRE' && $position === 0)
                    $ret[] = $traitement['Traitement']['target_id'];
                elseif ($options['traitement'] == 'NONAFAIRE' && $position !== 0)
                    $ret[] = $traitement['Traitement']['target_id'];
                elseif ($options['traitement'] == 'AVENIR' && $position > 0)
                    $ret[] = $traitement['Traitement']['target_id'];
            }
        }
        return $ret;
    }

    /**
     * test de la présence d'un trigger dans le traitement d'une cible
     * @param integer $triggerId idenifiant du déclencheur
     * @param integer $targetId identifiant de la cible
     * @return bool
     */
    function triggerDansTraitementCible($triggerId, $targetId) {
        $this->Visa->Behaviors->attach('Containable');
        $visa = $this->Visa->find('first', array(
            'fields' => array('Visa.id'),
            'contain' => array('Traitement.target_id'),
            'conditions' => array(
                'Traitement.target_id' => $targetId,
                'Visa.trigger_id' => $triggerId)));
        return (!empty($visa));
    }

    /**
     * Execution du circuit : enregistre l'action $action du trigger $triggerId pour le traitement de la cible $targetId
     * et retourne true si le traitement est achevé et false dans le cas contraire
     * @param string $action action prise par le déclencheir $triggerId
     *    'OK' : accepter, 'KO' : refuser
     * @param integer $triggerId identifiant du déclencheur
     * @param integer $targetId identifiant de le cible
     * @param array $options options de la fonction :
     *    'commentaire' => commentaire de l'action du déclencheur
     *  'signature' => signature electronique de l'action du déclencheur sous la forme
     *        array(['type'] => string, ['signature'] => string)
     *  'insertion' => insertion de traitements supplémentaires
     *        []['Etape'] => array(['etape_nom']=>string, ['etape_type']=>integer)
     *          ['Visa'][] => array('trigger_id'=>integer, 'type_validation'))
     *  'numero_traitement' => integer, utilisé por les sauts d'étape (JP : précédent et JS : suivant)
     * @param string $date_traitement
     * @return bool
     */
    function execute($action, $triggerId, $targetId, $options = array(), $date_traitement = '') {
        // initialisation
        $signatureId = 0;
        $initOptions = array(
            'commentaire' => '',
            'signature' => array(),
            'insertion' => array(),
            'numero_traitement' => 0,
            'etape_id' => 0,
            'optimisation' => true,
        );

        $options = array_merge($initOptions, $options);
        $etapeAjoutee = 0;

        // lecture du traitement
        $traitement = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('id', 'numero_traitement', 'treated', 'circuit_id'),
            'conditions' => array(
                'target_id' => $targetId,
                'treated' => '0')));
        if (empty($traitement))
            return false;

        // lecture du visa du déclencheur pour ce traitement
        $visa = $this->Visa->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'traitement_id' => $traitement['Traitement']['id'],
                'numero_traitement' => $traitement['Traitement']['numero_traitement'],
                'trigger_id' => $triggerId,
                'OR' => array(
                    array('action' => 'RI'),
                    array('action' => 'IN')))));

        if (empty($visa)) {
            // action hors traitement
            if (strpos(CAKEFLOW_ACTIONS_HORSTRAITEMENT, $action) !== false) {
                $visaInsIds = $this->_insert($traitement['Traitement']['id'], $traitement['Traitement']['numero_traitement'], 'AVANT', $options['insertion']);
                $visa['Visa']['id'] = $visaInsIds[0];
                $etapeAjoutee = count($visaInsIds);
            } else {
                return false;
            }
        }

        // enregistrement de l'action
        if (!empty($options['signature'])) {
            $signature = $this->Visa->Signature->create();
            $signature['Signature']['type_signature'] = $options['signature']['type'];
            $signature['Signature']['signature'] = $options['signature']['signature'];
            $this->Visa->Signature->save($signature);
            $signatureId = $this->Visa->Signature->id;
        }
        $visa['Visa']['signature_id'] = $signatureId;
        $visa['Visa']['action'] = $action;
        $visa['Visa']['commentaire'] = $options['commentaire'];

        if ($date_traitement == '')
            $visa['Visa']['date'] = date('Y-m-d H:i:s');
        else
            $visa['Visa']['date'] = $date_traitement;

        $this->Visa->save($visa);
        $numtraitementComplet = true;
        // traitement en fonction de l'action
        switch ($action) {
            case 'OK' : // accepter
                if ($this->_numTraitementComplet($traitement['Traitement']['id'], $traitement['Traitement']['numero_traitement'])) {
                    if ($this->_numTraitementDernier($traitement['Traitement']['id'], $traitement['Traitement']['numero_traitement']))
                        $traitement['Traitement']['treated'] = true;
                    else
                        $traitement['Traitement']['numero_traitement']++;
                } else {
                    $numtraitementComplet = false;
                }
                break;
            case 'KO' : // refuser
                $traitement['Traitement']['treated'] = true;
                break;
            case 'IL' : // insérer un lacet après le traitement courant
                $this->_insert($traitement['Traitement']['id'], $traitement['Traitement']['numero_traitement'], 'LACET', $options['insertion']);
                $traitement['Traitement']['numero_traitement']++;
                break;
            case 'IP' : // insérer des étapes après le traitement courant
                $this->_insert($traitement['Traitement']['id'], $traitement['Traitement']['numero_traitement'], 'APRES', $options['insertion']);
                $traitement['Traitement']['numero_traitement']++;
                break;
            case 'JP' : // retourner à une étape précédente (jump précédent)
                //Fix l'étape a été créée a la volée (apres envoyer à)
                $etape_fin = $this->Circuit->Etape->getDerniereEtape($traitement['Traitement']['circuit_id']);
                $etapes = $this->_listeEtapesInterval($targetId, $options['etape_id'], $etape_fin);
                $this->_insert($traitement['Traitement']['id'], $traitement['Traitement']['numero_traitement'], 'APRES', $etapes, true);
                $traitement['Traitement']['numero_traitement']++;
                break;
            case 'JS' : // aller à une étape suivante (jump suivant)
                $traitement['Traitement']['numero_traitement'] = $options['numero_traitement'] + $etapeAjoutee;
                break;
            case 'ST' : // stopper un circuit de validation
                $traitement['Traitement']['treated'] = true;
                // suppression des visas suivants
                $this->Visa->deleteAll(array(
                    'Visa.traitement_id' => $traitement['Traitement']['id'],
                    'Visa.numero_traitement >' => $traitement['Traitement']['numero_traitement']), false);
                break;
            case 'IN' : // inserer une cible dans le traitement
                if ($this->_numTraitementComplet($traitement['Traitement']['id'], $traitement['Traitement']['numero_traitement'])) {
                    if ($this->_numTraitementDernier($traitement['Traitement']['id'], $traitement['Traitement']['numero_traitement']))
                        $traitement['Traitement']['treated'] = true;
                    else {
                        $visas = $this->Visa->find('all', array(
                            'fields' => array('id', 'etape_type', 'numero_traitement', 'date', 'action', 'trigger_id', 'traitement_id'),
                            'recursive' => -1,
                            'conditions' => array('Visa.traitement_id' => $traitement['Traitement']['id'],
                                'Visa.trigger_id' => array(0, $triggerId), // 0 : rédacteur
                                'Visa.action' => 'RI'),
                            'order' => array('numero_traitement' => 'ASC'),
                        ));

                        $numero = 1; //Au moins un traitement
                        if ($options['insertion']) $numero = count($options['insertion']) + 1;
                        foreach ($visas as $visa) {
                            if (($visa['Visa']['etape_type'] != CAKEFLOW_COLLABORATIF && $numero == $visa['Visa']['numero_traitement'] && $options['optimisation'])
                                OR ($visa['Visa']['etape_type'] == CAKEFLOW_SIMPLE && $numero == $visa['Visa']['numero_traitement'] && !$options['optimisation'])
                            ) {
                                // Validation du visa
                                $this->Visa->id = $visa['Visa']['id'];
                                $this->Visa->saveField('action', 'OK');
                                $this->Visa->saveField('date', date('Y-m-d H:i:s'));
                                // On regarde si on est pas sur le traitement de la dernière étape
                                if ($this->_numTraitementDernier($traitement['Traitement']['id'], $numero))
                                    $traitement['Traitement']['treated'] = true;
                                else //On passe au traitement suivant (étape suivante)
                                    $numero = $visa['Visa']['numero_traitement'] + 1;
                            } else
                                break;
                        }
                        $traitement['Traitement']['numero_traitement'] = $numero;
                    }
                } else {
                    $numtraitementComplet = false;
                }
                break;
            case 'VF' : // Supprimer toutes les étapes après celle qui a été ajoutée
                $this->_insert($traitement['Traitement']['id'], $traitement['Traitement']['numero_traitement'], 'APRES', $options['insertion']);
                $traitement['Traitement']['numero_traitement']++;
                $this->Visa->deleteAll(array(
                    'Visa.traitement_id' => $traitement['Traitement']['id'],
                    'Visa.numero_traitement >' => $traitement['Traitement']['numero_traitement']), false);
                break;
        }
        // enregistre les changements du traitement
        $this->save($traitement);

        if ((!$traitement['Traitement']['treated'] && $numtraitementComplet && ($action == "OK" || $action == "IN")) || $action == 'JS' || $action == 'JP') {
            $this->delegSiBesoin($targetId, $traitement);
        }
        return $traitement['Traitement']['treated'];
    }

    public function delegSiBesoin($targetId, $traitement) {
        $visa_deleg = $this->Visa->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'traitement_id' => $traitement['Traitement']['id'],
                'numero_traitement' => $traitement['Traitement']['numero_traitement'],
                'type_validation' => 'D',
                'trigger_id' => -1,
                'action' => 'RI'
            ),
            'fields' => array('etape_id')
        ));
        if ($visa_deleg != null) {
            $etapeSuiv = $this->Circuit->Etape->find('first', array(
                'recursive' => -1,
                'fields' => array('id', 'soustype', 'type'),
                'conditions' => array(
                    'circuit_id' => $traitement['Traitement']['circuit_id'],
                    'id' => $visa_deleg['Visa']['etape_id']
                )));
            if ($etapeSuiv != null) {
                $libelleSousType = $this->Circuit->Etape->libelleSousType($etapeSuiv['Etape']['soustype']);
                return $this->_delegToParapheur($targetId, $libelleSousType); //ENVOI PARAPHEUR
            }
        }
        return false;
    }

    /**
     * envoi le dossier au parapheur pour finir son circuit si celui-ci comporte une étape de délégation
     * @param integer $targetId numéro de la délibération concernée
     * @param String $libelleSousType libelle du sous-type
     * @return boolean code de retour, true: éxecution déroulée avec succès, false: si erreur
     */
    function _delegToParapheur($targetId, $libelleSousType) {
        $target = $this->{CAKEFLOW_TARGET_MODEL}->find('first', array(
            'contain' => array('Typeacte.nature_id'),
            'conditions' => array(CAKEFLOW_TARGET_MODEL . '.id' => $targetId)
        ));
        $this->{CAKEFLOW_TARGET_MODEL}->id = $targetId;

        $aDelegToParapheurDocuments = $this->{CAKEFLOW_TARGET_MODEL}->getDocumentsForDelegation($targetId);

        App::uses('Signature', 'Lib');
        $Signature = new Signature();
        $ret = $Signature->send($target, $libelleSousType, $aDelegToParapheurDocuments['docPrincipale'], $aDelegToParapheurDocuments['annexes']);
        unset($aDelegToParapheurDocuments);
        if ($ret !== false) {
            $this->{CAKEFLOW_TARGET_MODEL}->saveField('parapheur_etat', 1);
            $this->{CAKEFLOW_TARGET_MODEL}->saveField('parapheur_id', $ret);
            $this->{CAKEFLOW_TARGET_MODEL}->saveField('parapheur_cible', Configure::read('PARAPHEUR'));
            if (Configure::read('PARAPHEUR') == 'PASTELL')
                $this->{CAKEFLOW_TARGET_MODEL}->saveField('pastell_id', $ret);
            return true;
        } else {
            $this->log("Echec de l'envoi en délégation du dossier " . $targetId, 'parapheur');
            return false;
        }
    }

    function majTraitementsParapheur($id = null) {
        return $this->{'majTraitements' . ucfirst(strtolower(Configure::read('PARAPHEUR')))}($id);
    }

    /**
     * Connexion au parapheur pour vérifier le statut des dossiers délégués et les faire avancer d'une étape si le circuit est terminé coté parapheur
     * @param integer $id
     * @return string ret et rapport
     */
    function majTraitementsIparapheur($id = null) {
        $rapport = "";
        $conditions = array();
        //Récupère les dossiers en étape de délégation
        $conditions['parapheur_etat'] = 1;
        $conditions['parapheur_id !='] = null;
        $conditions['etat'] = 1;
        if (!empty($id)) {
            $conditions['id'] = $id;
        }

        $toCheck = $this->{CAKEFLOW_TARGET_MODEL}->find('all', array(
            'recursive' => -1,
            'fields' => array('id', 'objet', 'parapheur_id', 'redacteur_id'),
            'conditions' => $conditions,
            'order' => array('id' => 'desc')
        ));

        //Utilisation du component Iparapheur pour la connexion aux webservices
        App::uses('IparapheurComponent', 'Controller/Component');
        $this->Parapheur = new IparapheurComponent;

        $ret = 'TRAITEMENT_TERMINE_OK';
        //Pour chacun des objets en étape de délégation
        foreach ($toCheck as $objet) {
            $this->{CAKEFLOW_TARGET_MODEL}->id = $objet[CAKEFLOW_TARGET_MODEL]['id'];

            $traitement = $this->find('first', array(
                'recursive' => -1,
                'fields' => array('id', 'numero_traitement', 'circuit_id', 'treated'),
                'conditions' => array(
                    'target_id' => $objet[CAKEFLOW_TARGET_MODEL]['id']
                )
            ));

            if (empty($traitement)) continue;

            $this->id = $traitement["Traitement"]['id'];
            $this->traiterDelegationsPassees($traitement['Traitement']['id']);
            if ($objet[CAKEFLOW_TARGET_MODEL]['parapheur_id'] != "")
                $nom_dossier = $objet[CAKEFLOW_TARGET_MODEL]['parapheur_id'];
            else
                continue;

            //Récupère l'historique du dossier coté parapheur
            $logdossier = $this->Parapheur->getHistoDossierWebservice($nom_dossier);
            //Si le dossier est trouvé (actuellement en circuit)
            if ($logdossier['messageretour']['coderetour'] == 'OK') {
                //Statut de l'étape actuelle
                $lastStatus = $logdossier["logdossier"][count($logdossier["logdossier"]) - 1]['status'];
                //En fin de circuit
                if ($lastStatus == "Archive" || $lastStatus == "RejetSignataire" || $lastStatus == "RejetVisa") {
                    //traitement correspondant à l'objet
                    //visa de délégation correspondant au traitement en cours
                    $visa = $this->Visa->find('first', array(
                        'recursive' => -1,
                        'conditions' => array(
                            'traitement_id' => $traitement['Traitement']['id'],
                            'numero_traitement' => $traitement['Traitement']['numero_traitement'],
                            'trigger_id' => CAKEFLOW_TRIGGER_PARAPHEUR
                        )
                    ));
                    if (empty($visa)) continue;
                    $this->Visa->id = $visa['Visa']['id'];
                    $type_etape = $visa['Visa']['etape_type'];

                    //Historique parapheur
                    $parafhisto = "Délégation de validation terminée :";
                    for ($i = 0; $i < count($logdossier['logdossier']); $i++) { //Pour chaque étape parapheur
                        $action_importante = false;
                        if (preg_match("/Dossier déposé*/", $logdossier["logdossier"][$i]['annotation']) == 1) { //Changement de bureau
                            $parafhisto .= "\n>>> " . date("d/m/Y \à H\hi", strtotime($logdossier["logdossier"][$i]['timestamp'])) . " [Parapheur] " . $logdossier["logdossier"][$i]['annotation'];
                        } elseif ($logdossier["logdossier"][$i]['status'] == "Vise") { //Visa
                            $action_importante = true;
                            $parafhisto .= "\n>>> " . date("d/m/Y \à H\hi", strtotime($logdossier["logdossier"][$i]['timestamp'])) . " [Parapheur] Dossier visé par " . $logdossier["logdossier"][$i]['nom'];
                        } elseif ($logdossier["logdossier"][$i]['status'] == "Signe") { //Signature
                            $action_importante = true;
                            $parafhisto .= "\n>>> " . date("d/m/Y \à H\hi", strtotime($logdossier["logdossier"][$i]['timestamp'])) . " [Parapheur] Dossier signé par " . $logdossier["logdossier"][$i]['nom'];
                        } elseif (preg_match("/^Rejet*/", $logdossier["logdossier"][$i]['status']) == 1) { //Rejet
                            $action_importante = true;
                            $parafhisto .= "\n>>> " . date("d/m/Y \à H\hi", strtotime($logdossier["logdossier"][$i]['timestamp'])) . " [Parapheur] Dossier rejetté par " . $logdossier["logdossier"][$i]['nom'] . " pour le motif suivant : " . $logdossier["logdossier"][$i]['annotation'];
                        }

                        if (CAKEFLOW_TARGET_MODEL == 'Deliberation') {
                            //Nouveau commentaire pour annotation perso (visa, signature, rejet) 
                            if (preg_match("/^(Visa|Signature) sur dossier$/", $logdossier["logdossier"][$i]['annotation']) == 0 && $action_importante) {
                                $this->{CAKEFLOW_TARGET_MODEL}->setCommentaire($objet['Deliberation']['id'], $logdossier["logdossier"][$i]);
                            }
                        }
                    }

                    if (CAKEFLOW_TARGET_MODEL == 'Deliberation')
                        $this->{CAKEFLOW_TARGET_MODEL}->setHistorique($parafhisto, $objet['Deliberation']['id'], $traitement['Traitement']['circuit_id']);

                    $this->Visa->saveField('commentaire', $parafhisto);
                    $this->Visa->saveField('date', $logdossier["logdossier"][count($logdossier['logdossier']) - 1]['timestamp']);

                    $etape_termine = $this->Visa->visasParallelesValides($visa);

                    //Si en attente d'archivage (circuit validé)
                    if ($lastStatus == "Archive") {
                        $this->{CAKEFLOW_TARGET_MODEL}->saveField('parapheur_etat', 0); // MàJ Objet : Circuit parapheur validé
                        $this->Visa->saveField('action', 'OK');

                        $this->Parapheur->archiverDossierWebservice($nom_dossier, "EFFACER");
                        if ($etape_termine) {
                            if (!$this->Visa->isLastEtape($visa)) { //Si n'est pas dernier traitement numero_traitement++
                                $this->saveField('numero_traitement', $traitement['Traitement']['numero_traitement'] + 1);
                                $this->delegSiBesoin($objet[CAKEFLOW_TARGET_MODEL]['id'], $traitement);

                                $next_users = $this->whoIs($objet[CAKEFLOW_TARGET_MODEL]['id']);
                                //Notifier le(s) utilisateur(s) de l'étape suivante pour traitement
                                foreach ($next_users as $user_id) {
                                    if ($user_id != CAKEFLOW_TRIGGER_PARAPHEUR)
                                        $this->Visa->{CAKEFLOW_TRIGGER_MODEL}->notifier($objet[CAKEFLOW_TARGET_MODEL]['id'], $user_id, 'traitement');
                                }
                            } else { //Si dernier traitement, treated = true
                                $this->saveField('treated', true);
                                $this->{CAKEFLOW_TARGET_MODEL}->saveField('etat', 2);
                            }
                        }
                    } elseif (preg_match("/^Rejet*/", $lastStatus) == 1) { //Cas de rejet
                        $this->{CAKEFLOW_TARGET_MODEL}->saveField('parapheur_etat', 0);
                        $this->{CAKEFLOW_TARGET_MODEL}->saveField('parapheur_commentaire', $logdossier["logdossier"][count($logdossier['logdossier']) - 1]['annotation']);
                        $this->Visa->saveField('action', 'KO');
                        if ($type_etape !== CAKEFLOW_COLLABORATIF XOR $etape_termine) {
                            $this->saveField('treated', true);
                            $this->{CAKEFLOW_TARGET_MODEL}->refusDossier($objet[CAKEFLOW_TARGET_MODEL]['id']);
                            //Notifier le rédacteur du refus
                            $this->Visa->{CAKEFLOW_TRIGGER_MODEL}->notifier($objet[CAKEFLOW_TARGET_MODEL]['id'], $objet[CAKEFLOW_TARGET_MODEL]['redacteur_id'], 'refus');
                        }
                        // Supprimer le dossier du parapheur
                        $this->Parapheur->effacerDossierRejeteWebservice($nom_dossier);
                    }
                }
            } else {
                $rapport .= $logdossier['messageretour']['message'] . "\n";
                $ret = "TRAITEMENT_TERMINE_ALERTE";
            }
        }
        return ($ret . $rapport);
    }


    /**
     * @param integer $id identifiant du projet à mettre à jour
     * @return bool true si le dossier à terminé son circuit
     */
    function majTraitementsPastell($id = null) {
        $ret = 'TRAITEMENT_TERMINE_OK';
        $rapport = "";
        $conditions = array();
        //Récupère les dossiers en étape de délégation
        $conditions['parapheur_etat'] = 1;
        $conditions['etat'] = 1;
        if (!empty($id)) {
            $conditions['id'] = $id;
        }

        $toCheck = $this->{CAKEFLOW_TARGET_MODEL}->find('all', array(
            'recursive' => -1,
            'fields' => array('id', 'objet', 'pastell_id', 'redacteur_id'),
            'conditions' => $conditions,
            'order' => array('id' => 'desc')
        ));

        if (empty($toCheck))
            return $ret;

        App::uses('Signature', 'Lib');
        $this->Signature = new Signature;

        try {
            //Pour chacun des objets en étape de délégation
            foreach ($toCheck as $objet) {
                //Délibération en cours
                $delib_id = $objet[CAKEFLOW_TARGET_MODEL]['id'];
                $this->{CAKEFLOW_TARGET_MODEL}->id = $delib_id;
                //Traitement en cours
                $traitement = $this->find('first', array(
                    'recursive' => -1,
                    'fields' => array('id', 'numero_traitement', 'circuit_id', 'treated'),
                    'conditions' => array(
                        'target_id' => $delib_id
                    )
                ));
                if (empty($traitement)) continue;
                $this->id = $traitement['Traitement']['id'];

//                $this->traiterDelegationsPassees($traitement['Traitement']['id']);

                //visa de délégation correspondant au traitement en cours
                $visa = $this->Visa->find('first', array(
                    'recursive' => -1,
                    'conditions' => array(
                        'traitement_id' => $traitement['Traitement']['id'],
                        'numero_traitement' => $traitement['Traitement']['numero_traitement'],
                        'trigger_id' => CAKEFLOW_TRIGGER_PARAPHEUR
                    )
                ));
                if (empty($visa)) continue;
                $this->Visa->id = $visa['Visa']['id'];
                $type_etape = $visa['Visa']['etape_type'];

                $etape_termine = $this->Visa->visasParallelesValides($visa);

                $infos = $this->Signature->getDetails($objet[CAKEFLOW_TARGET_MODEL]['pastell_id'], true);
                if ($infos['last_action']['action'] == 'rejet-iparapheur') {
                    $this->{CAKEFLOW_TARGET_MODEL}->saveField('parapheur_etat', '0');
                    $this->{CAKEFLOW_TARGET_MODEL}->saveField('parapheur_commentaire', $infos['last_action']['message']);
                    $this->Visa->saveField('action', 'KO');
                    $this->Visa->saveField('date', date('Y-m-d H:i:s'));
                    //Ajout de l'action à l'historique
                    $this->setHistorique($infos['last_action']['message'], $delib_id, 0);
                    //Commentaire refus
                    $this->Commentaire->create();
                    $com = array();
                    $com['Commentaire']['delib_id'] = $delib_id;
                    $com['Commentaire']['agent_id'] = CAKEFLOW_TRIGGER_PARAPHEUR;
                    $com['Commentaire']['texte'] = $infos['last_action']['message'];
                    $com['Commentaire']['commentaire_auto'] = 0;
                    $this->Commentaire->save($com);
                    if ($type_etape !== CAKEFLOW_COLLABORATIF XOR $etape_termine) {
                        $this->saveField('treated', true);
                        $this->{CAKEFLOW_TARGET_MODEL}->refusDossier($objet[CAKEFLOW_TARGET_MODEL]['id']);
                        $this->{CAKEFLOW_TARGET_MODEL}->saveField('etat', '-1');
                        //Notifier le rédacteur du refus
                        $this->Visa->{CAKEFLOW_TRIGGER_MODEL}->notifier($objet[CAKEFLOW_TARGET_MODEL]['id'], $objet[CAKEFLOW_TARGET_MODEL]['redacteur_id'], 'refus');
                    }
                    // Supprimer le dossier de pastell
                    $this->Signature->delete($objet[CAKEFLOW_TARGET_MODEL]['pastell_id']);
                } elseif ($infos['last_action']['action'] == 'recu-iparapheur' || !empty($infos['data']['has_signature'])) {
                    $this->{CAKEFLOW_TARGET_MODEL}->saveField('parapheur_etat', '0');
                    $this->{CAKEFLOW_TARGET_MODEL}->saveField('parapheur_commentaire', $infos['last_action']['message']);
                    $this->saveField('treated', true);

                    $this->setHistorique($infos['last_action']['message'], $delib_id, 0);
                    $this->Visa->saveField('action', 'OK');
                    $this->Visa->saveField('date', date('Y-m-d H:i:s'));

                    if ($etape_termine) {
                        if (!$this->Visa->isLastEtape($visa)) {
                            $this->saveField('numero_traitement', $traitement['Traitement']['numero_traitement'] + 1);
                            $this->delegSiBesoin($objet[CAKEFLOW_TARGET_MODEL]['id'], $traitement);
                            $next_users = $this->whoIs($objet[CAKEFLOW_TARGET_MODEL]['id']);
                            //Notifier le(s) utilisateur(s) de l'étape suivante pour traitement
                            foreach ($next_users as $user_id) {
                                if ($user_id != CAKEFLOW_TRIGGER_PARAPHEUR)
                                    $this->Visa->{CAKEFLOW_TRIGGER_MODEL}->notifier($objet[CAKEFLOW_TARGET_MODEL]['id'], $user_id, 'traitement');
                            }
                        } else { //Si derniere etape, treated = true
                            $this->saveField('treated', true);
                            $this->{CAKEFLOW_TARGET_MODEL}->saveField('etat', 2);
                        }
                    }
                    // Supprimer le dossier de pastell
                    $this->Signature->delete($objet[CAKEFLOW_TARGET_MODEL]['pastell_id']);
                }
            }
        } catch (Exception $e) {
            $rapport .= "\n" . $e->getMessage();
            $ret = "TRAITEMENT_TERMINE_ALERTE";
        }
        return $ret . $rapport;
    }

    /**
     * @param integer $traitement_id
     * @param integer $etape
     * @param bool $inf_ou_egal
     * @return bool
     */
    function traiterDelegationsPassees($traitement_id, $etape = null, $inf_ou_egal = false) {
        $maj = false;
        $this->id = $traitement_id;
        $traitement = $this->findById($traitement_id);

        if ($etape == null) {
            $num = $traitement['Traitement']['numero_traitement'];
            $signe = ' <';
        } elseif ($inf_ou_egal) {
            $num = $etape;
            $signe = ' <=';
        } else {
            $num = $etape;
            $signe = '';
        }

        $delegation_restante = array(
            "Visa.traitement_id" => $traitement['Traitement']['id'],
            'Visa.trigger_id' => CAKEFLOW_TRIGGER_PARAPHEUR,
            "Visa.action" => "RI",
            "Visa.numero_traitement" . $signe => $num);

        //Délégations passées en attentes
        $visas = $this->Visa->find("all", array("conditions" => $delegation_restante, "recursive" => -1));
        if (!empty($visas)) {

            App::uses('IparapheurComponent', 'Controller/Component');
            $this->Parapheur = new IparapheurComponent;

            $objet = $this->{CAKEFLOW_TARGET_MODEL}->find("first", array(
                'conditions' => array(
                    'id' => $traitement['Traitement']['target_id'],
                    'etat' => 1
                ),
                'recursive' => -1));

            foreach ($visas as $visa) {
                $nom_dossier = $objet[CAKEFLOW_TARGET_MODEL]['parapheur_id'];

                //Récupère l'historique du dossier coté parapheur
                $logdossier = $this->Parapheur->getHistoDossierWebservice($nom_dossier);
                //Si le dossier est trouvé (actuellement en circuit)
                if ($logdossier['messageretour']['coderetour'] == 'OK') {
                    //Statut de l'étape actuelle
                    $lastStatus = $logdossier["logdossier"][count($logdossier["logdossier"]) - 1]['status'];
                    //En fin de circuit
                    if ($lastStatus == "Archive" || $lastStatus == "RejetSignataire" || $lastStatus == "RejetVisa") {
                        //Historique parapheur
                        $parafhisto = "Délégation de validation terminée :";
                        for ($i = 0; $i < count($logdossier['logdossier']); $i++) { //Pour chaque étape parapheur
                            $action_importante = false;
                            if (preg_match("/Dossier déposé*/", $logdossier["logdossier"][$i]['annotation']) == 1) { //Changement de bureau
                                $parafhisto .= "\n>>> " . date("d/m/Y \à H\hi", strtotime($logdossier["logdossier"][$i]['timestamp'])) . " [Parapheur] " . $logdossier["logdossier"][$i]['annotation'];
                            } elseif ($logdossier["logdossier"][$i]['status'] == "Vise") { //Visa
                                $action_importante = true;
                                $parafhisto .= "\n>>> " . date("d/m/Y \à H\hi", strtotime($logdossier["logdossier"][$i]['timestamp'])) . " [Parapheur] Dossier visé par " . $logdossier["logdossier"][$i]['nom'];
                            } elseif ($logdossier["logdossier"][$i]['status'] == "Signe") { //Signature
                                $action_importante = true;
                                $parafhisto .= "\n>>> " . date("d/m/Y \à H\hi", strtotime($logdossier["logdossier"][$i]['timestamp'])) . " [Parapheur] Dossier signé par " . $logdossier["logdossier"][$i]['nom'];
                            } elseif (preg_match("/^Rejet*/", $logdossier["logdossier"][$i]['status']) == 1) { //Rejet
                                $action_importante = true;
                                $parafhisto .= "\n>>> " . date("d/m/Y \à H\hi", strtotime($logdossier["logdossier"][$i]['timestamp'])) . " [Parapheur] Dossier rejetté par " . $logdossier["logdossier"][$i]['nom'] . " pour le motif suivant : " . $logdossier["logdossier"][$i]['annotation'];
                            }

                            if (CAKEFLOW_TARGET_MODEL == 'Deliberation') {
                                //Nouveau commentaire pour annotation perso (visa, signature, rejet) 
                                if (preg_match("/^(Visa|Signature) sur dossier$/", $logdossier["logdossier"][$i]['annotation']) == 0 && $action_importante) {
                                    $this->{CAKEFLOW_TARGET_MODEL}->setCommentaire($objet[CAKEFLOW_TARGET_MODEL]['id'], $logdossier["logdossier"][$i]);
                                }
                            }
                        }

                        if (CAKEFLOW_TARGET_MODEL == 'Deliberation') //Insert Historique
                            $this->{CAKEFLOW_TARGET_MODEL}->setHistorique($parafhisto, $objet[CAKEFLOW_TARGET_MODEL]['id'], $traitement['Traitement']['circuit_id']);

                        $visa['Visa']['commentaire'] = $parafhisto;
                        $visa['Visa']['date'] = $logdossier["logdossier"][count($logdossier['logdossier']) - 1]['timestamp'];

                        //Si en attente d'archivage (circuit validé)
                        if ($lastStatus == "Archive") {
                            $maj = true;
                            $visa['Visa']['action'] = 'OK';
                            $this->Parapheur->archiverDossierWebservice($nom_dossier, "EFFACER");
                        } elseif (preg_match("/^Rejet*/", $lastStatus) == 1) { //Cas de rejet
                            $maj = true;
                            $visa['Visa']['action'] = 'KO';
                            // Supprimer le dossier du parapheur
                            $this->Parapheur->effacerDossierRejeteWebservice($nom_dossier);
                        }
                        $this->Visa->save($visa); //MàJ Visa
                    }
                }
            }
        }
        return $maj;
    }

    /**
     * Insert des visas au traitement $traitementId
     * @param integer $traitementId description
     * @param integer $numeroTraitement numéro de traitement concerné par l'insertion
     * @param string $typeInsertion type d'insertion 'AVANT', 'APRES', 'LACET'
     *    - 'AVANT' : insert les visas avant le numéro de traitement concerné
     *    - 'APRES' : insert les visas apres le numéro de traitement concerné
     *  - 'LACET' : insert les visas apres le numéro de traitement concerné et duplique celui à la fin de l'insertion
     * @param array $etapes liste des visas à insérer sous la forme :
     *        array([]=> array(
     *            'Etape'=>array('etape_nom'=>string, 'etape_type'),
     *            'Visa'=>array([]=> array(
     *                'trigger_id'=>integer, 'type_validation'=>string))))
     * @param bool $viderSuivants supprimer ou pas les visas suivants le visa courant (utile pour retour afin d'éviter les doublons)
     * @return array tableau des id des visas insérés
     */
    function _insert($traitementId, $numeroTraitement, $typeInsertion, $etapes, $viderSuivants = false) {
        // initialisation
        $ret = array();
        // lecture du ou des visas du numéro de traitement
        $ntVisas = $this->Visa->find('all', array(
            'recursive' => -1,
            'fields' => array('trigger_id', 'etape_id', 'etape_nom', 'etape_type', 'type_validation', 'date_retard'),
            'conditions' => array(
                'traitement_id' => $traitementId,
                'numero_traitement' => $numeroTraitement
            )));
        if (empty($ntVisas))
            return false;

        $traitement = $this->find('first', array(
            'recursive' => -1,
            'conditions' => array('Traitement.id' => $traitementId),
            'fields' => array('Traitement.target_id')
        ));
        if (empty($traitement))
            return false;

        // initialisation
        $numTraitIns = $numeroTraitement + ($typeInsertion == 'AVANT' ? 0 : 1);
        $nbEtapeIns = count($etapes) + ($typeInsertion == 'LACET' ? 1 : 0);

        // décalage du numero_traitement des suivants
        $this->Visa->recursive = -1;
        $this->Visa->updateAll(array(
                'Visa.numero_traitement' => 'Visa.numero_traitement+' . $nbEtapeIns),
            array(
                'Visa.traitement_id' => $traitementId,
                'Visa.numero_traitement >=' => $numTraitIns
            ));
        if ($viderSuivants) {
            $this->Visa->deleteAll(array(
                'traitement_id' => $traitementId,
                'numero_traitement >' => $numeroTraitement
            ), false);
        }
        // insertion des nouveaux visas
        foreach ($etapes as $etape) {
            if (array_key_exists('Visa', $etape))
                foreach ($etape['Visa'] as $visa) {
                    if (empty($etape['Etape']['etape_id']) && isset($ntVisas[0]['Visa']['etape_id']))
                        $etape['Etape']['etape_id'] = $ntVisas[0]['Visa']['etape_id'];
                    //Eviter les doublons
                    $exist = $this->Visa->find('count', array(
                        'conditions' => array(
                            'etape_id' => $etape['Etape']['etape_id'],
                            'etape_nom' => $etape['Etape']['etape_nom'],
                            'traitement_id' => $traitementId,
                            'trigger_id' => $visa['trigger_id'],
                            'etape_type' => $etape['Etape']['etape_type'],
                            'numero_traitement' => $numTraitIns,
                            'type_validation' => $visa['type_validation']
                        )
                    ));
                    if ($exist == 0) {
                        $vis = $this->Visa->create();
                        $vis['Visa']['traitement_id'] = $traitementId;
                        $vis['Visa']['trigger_id'] = $visa['trigger_id'];
                        $vis['Visa']['signature_id'] = 0;
                        $vis['Visa']['etape_nom'] = $etape['Etape']['etape_nom'];
                        $vis['Visa']['etape_id'] = $etape['Etape']['etape_id'];
                        $vis['Visa']['etape_type'] = $etape['Etape']['etape_type'];
                        if (!empty($etape['Etape']['cpt_retard']))
                            $vis['Visa']['date_retard'] = $this->Circuit->Etape->computeDateRetard($etape['Etape']['cpt_retard'], $traitement['Traitement']['target_id']);
                        else
                            $vis['Visa']['date_retard'] = null;
                        $vis['Visa']['action'] = 'RI';
                        $vis['Visa']['commentaire'] = '';
                        $vis['Visa']['date'] = null;
                        $vis['Visa']['numero_traitement'] = $numTraitIns;
                        $vis['Visa']['type_validation'] = $visa['type_validation'];
                        $this->Visa->save($vis);
                        $ret[] = $this->Visa->id;
                    }
                }
            $numTraitIns++;
        }

        // traitement du lacet : ajout des (anciens) visas courant
        if ($typeInsertion == 'LACET')
            foreach ($ntVisas as $visa) {
                $vis = $this->Visa->create();
                $vis['Visa']['traitement_id'] = $traitementId;
                $vis['Visa']['trigger_id'] = $visa['Visa']['trigger_id'];
                $vis['Visa']['signature_id'] = 0;
                $vis['Visa']['etape_nom'] = $visa['Visa']['etape_nom'];
                $vis['Visa']['etape_id'] = $visa['Visa']['etape_id'];
                $vis['Visa']['etape_type'] = $visa['Visa']['etape_type'];
                $vis['Visa']['date_retard'] = $visa['Visa']['date_retard'];
                $vis['Visa']['action'] = 'RI';
                $vis['Visa']['commentaire'] = '';
                $vis['Visa']['date'] = null;
                $vis['Visa']['numero_traitement'] = $numTraitIns;
                $vis['Visa']['type_validation'] = $visa['Visa']['type_validation'];
                $this->Visa->save($vis);
                $ret[] = $this->Visa->id;
            }
        return $ret;
    }

    /**
     * détermine si le  numero de traitement $numeroTraitement du traitement $traitementId est complet (ie : doit-on passer à l'étape suivante?)
     * @param integer $traitementId identifiant du traitement
     * @param integer $numeroTraitement numero du traitement
     * @return bool true si le traitement courant est complet, false dans le cas contraire
     */
    function _numTraitementComplet($traitementId, $numeroTraitement) {
        // lecture des visas pour le traitement et le numéro de traitement
        $visas = $this->Visa->find('all', array(
            'recursive' => -1,
            'fields' => array('id', 'action', 'etape_type'),
            'conditions' => array(
                'traitement_id' => $traitementId,
                'numero_traitement' => $numeroTraitement)));
        if (empty($visas))
            return false;

        // analyse des visas en fonction du type d'étape
        if ($visas[0]['Visa']['etape_type'] == CAKEFLOW_COLLABORATIF) {
            $ret = true;
            foreach ($visas as $visa)
                if ($visa['Visa']['action'] == 'RI') {
                    $ret = false;
                    break;
                }
        } else {
            $ret = false;
            foreach ($visas as $visa)
                if ($visa['Visa']['action'] != 'RI') {
                    $ret = true;
                    break;
                }
        }

        return $ret;
    }

    /**
     * détermine si le numéro de traitement $numeroTraitement du traitement $traitementId est le dernier
     * @param integer $traitementId identifiant du traitement
     * @param integer $numeroTraitement numero du traitement
     * @return bool true si le traitement est terminé, false dans le cas contraire
     */
    function _numTraitementDernier($traitementId, $numeroTraitement) {
        // lecture des visas pour le traitement et le numéro de traitement suivant
        $visaNumTraitSuiv = $this->Visa->find('first', array(
            'recursive' => -1,
            'fields' => array('id'),
            'conditions' => array(
                'traitement_id' => $traitementId,
                'numero_traitement' => $numeroTraitement + 1)));

        return empty($visaNumTraitSuiv);
    }

    /**
     * détermine le nombre d'"étapes" de traitement du traitement $traitementId
     * @param integer $traitementId identifiant du traitement
     * @return integer nombre d'étapes du traitement
     */
    function _nbEtapeTraitement($traitementId) {
        // lecture des visas pour le traitement et le numéro de traitement suivant
        $visa = $this->Visa->find('first', array(
            'recursive' => -1,
            'fields' => array('numero_traitement'),
            'conditions' => array('traitement_id' => $traitementId),
            'order' => array('numero_traitement DESC')));

        return $visa['Visa']['numero_traitement'];
    }

    /**
     * détermine si la cible $targetId est présente dans un traitement
     * @param integer $targetId identifiant de la cible
     * @return bool true si la cible fait l'objet d'un traitement, false dans le cas contraire
     */
    function targetExists($targetId) {
        // lecture du traitement pour la cible
        $traitement = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('id'),
            'conditions' => array('target_id' => $targetId)));

        return !empty($traitement);
    }

    /**
     * retourne la liste des 'étapes' (numero-traitement) du traitement de la cible $targetId en vue de son utilisation dans un select
     * @param integer $targetId identifiant de la cible du traitement
     * @param array $options options de la fonction sous la forme :
     *    - ['selection'] : string, 'TOUTES', 'AVANT' (defaut), 'APRES', 'BORNES'
     *        'TOUTES' : retourne toutes les 'étapes' du traitement
     *        'AVANT' : retourne les 'étapes' du traitement entre l'étape 'debut' (defaut=première étape) et l'étape précédente de l'étape courante
     *        'APRES' : retourne les 'étapes' du traitement entre l'étape suivante de l'étape courante et l'étape 'fin' (défaut=dernière étape)
     *        'BORNES' : retourne les 'étapes' du traitement entre l'étape 'debut' et 'fin' (compris)
     *    - ['debut'] : integer, numéro de traitement de début
     *    - ['fin'] : integer, numéro de traitement de fin
     * @return array
     */
    function listeEtapes($targetId, $options = array()) {
        // initialisation
        $nbMaxVisa = 5;
        $ret = array();
        $initOptions = array(
            'selection' => 'AVANT',
            'debut' => 0,
            'fin' => 0);
        $options = array_merge($initOptions, $options);

        // lecture du traitement pour la cible
        $traitement = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('id', 'numero_traitement'),
            'conditions' => array('target_id' => $targetId)));
        if (empty($traitement))
            return $ret;

        // initialisation des bornes
        if ($options['selection'] == 'AVANT') {
            $options['fin'] = $traitement['Traitement']['numero_traitement'] - 1;
        } elseif ($options['selection'] == 'APRES') {
            $options['debut'] = $traitement['Traitement']['numero_traitement'] + 1;
        } elseif ($options['selection'] == 'TOUTES') {
            $options['debut'] = 0;
            $options['fin'] = 0;
        }

        // initialisation de la condition
        $conditions['traitement_id'] = $traitement['Traitement']['id'];
        if ($options['debut'])
            $conditions['numero_traitement >='] = $options['debut'];
        if ($options['fin'])
            $conditions['numero_traitement <='] = $options['fin'];

        // lecture des visas pour le traitement
        $visas = $this->Visa->find('all', array(
            'recursive' => -1,
            'fields' => array('id', 'etape_id', 'etape_nom', 'numero_traitement', 'trigger_id'),
            'conditions' => $conditions,
            'order' => 'numero_traitement'));

        // constitution de la reponse
        App::uses('CakeflowAppController', 'Cakeflow.Controller');
        $CakeflowAppController = new CakeflowAppController;
        $ntCourant = 0;
        $nbVisa = 0;
        foreach ($visas as $visa) {
            if ($ntCourant != $visa['Visa']['numero_traitement']) {
                $ret[$visa['Visa']['numero_traitement']] = '[' . $visa['Visa']['numero_traitement'] . '] ' . $visa['Visa']['etape_nom'];
                $ntCourant = $visa['Visa']['numero_traitement'];
                $nbVisa = 0;
            }
            $nbVisa++;
            if ($nbVisa <= $nbMaxVisa) {
                $ret[$visa['Visa']['numero_traitement']] .= ($nbVisa == 1 ? ' : ' : ', ');
                $ret[$visa['Visa']['numero_traitement']] .= $CakeflowAppController->formatLinkedModel('Trigger', $visa['Visa']['trigger_id']);
            } elseif ($nbVisa == $nbMaxVisa + 1) {
                $ret[$visa['Visa']['numero_traitement']] .= ', ...';
            }
        }

        return $ret;
    }

    /**
     * retourne la liste des 'étapes' (model Etape) du traitement de la cible $targetId en vue de son utilisation dans un select (renvoyer à une étape précédente)
     * @param integer $targetId identifiant de la cible du traitement
     * @return array
     */
    function listeEtapesPrecedentes($targetId) {
        // initialisation
        $ret = array();

        // lecture du traitement pour la cible
        $traitement = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('id', 'numero_traitement', 'circuit_id'),
            'conditions' => array('target_id' => $targetId)));
        if (empty($traitement))
            return $ret;

        //Fix etape courante issue de "envoyer à"
        $etape_courante = array();
        $decalage = 0;
        while (empty($etape_courante)) {
            $visa = $this->Visa->find('first', array(
                'recursive' => -1,
                'fields' => array('etape_id', 'etape_nom', 'etape_type'),
                'conditions' => array(
                    'traitement_id' => $traitement['Traitement']['id'],
                    'numero_traitement' => $traitement['Traitement']['numero_traitement'] - $decalage
                )));
            $etape_courante = $this->Circuit->Etape->find('first', array(
                'recursive' => -1,
                'fields' => array('ordre', 'nom', 'circuit_id', 'id'),
                'conditions' => array(
                    'id' => $visa['Visa']['etape_id']
                )));
            if ($visa['Visa']['etape_nom'] != $etape_courante['Etape']['nom'])
                $etape_courante = $this->Circuit->Etape->find('first', array(
                    'recursive' => -1,
                    'fields' => array('ordre', 'nom', 'id'),
                    'conditions' => array(
                        'circuit_id' => $etape_courante['Etape']['circuit_id'],
                        'ordre' => $etape_courante['Etape']['ordre'] + 1
                    )));
            if (empty($etape_courante))
                $decalage++;
        }
        // initialisation de la condition
        $conditions['traitement_id'] = $traitement['Traitement']['id'];
        $conditions['etape_id <>'] = $etape_courante['Etape']['id'];
        $conditions['numero_traitement <'] = $traitement['Traitement']['numero_traitement'];

        // lecture des visas pour le traitement
        $visas = $this->Visa->find('all', array(
            'recursive' => -1,
            'fields' => array('DISTINCT Visa.etape_nom'),
            'conditions' => $conditions));
        //Aplatissement du tableau
        $visas_etapes_nom = Hash::extract($visas, '{n}.Visa.etape_nom');
        // lecture des etapes pour le traitement
        $ordre = $decalage == 0 ? $etape_courante['Etape']['ordre'] : $etape_courante['Etape']['ordre'] + 1;
        $etapes = $this->Circuit->Etape->find('all', array(
            'recursive' => -1,
            'fields' => array('id', 'nom', 'ordre'),
            'conditions' => array(
                'nom' => array_values($visas_etapes_nom),
                'circuit_id' => $traitement['Traitement']['circuit_id'],
                'ordre <' => $ordre
            ),
            'order' => array('ordre')));

        // constitution de la reponse
        App::uses('CakeflowAppController', 'Cakeflow.Controller');
        $CakeflowAppController = new CakeflowAppController;
        foreach ($etapes as $etape) {
            // liste des compositions de l'étape sauf celle de $userId
            $compositions = $this->Circuit->Etape->Composition->find('all', array(
                'fields' => array('trigger_id'),
                'conditions' => array('Composition.etape_id' => $etape['Etape']['id']),
                'recursive' => -1));

            $compo_array = $CakeflowAppController->formatLinkedModels('Trigger', Hash::extract($compositions, '{n}.Composition.trigger_id'));
            //Ajout d'un élément au select
            $ret[$etape['Etape']['id']] = '[' . $etape['Etape']['ordre'] . '] ' . $etape['Etape']['nom'] . ' : ' . implode(', ', $compo_array);
        }

        return $ret;
    }

    /**
     * Récupère les étapes et visas compris entres deux étapes en éliminant les étapes générées (envoyer à, rédacteur..)
     * @param integer $targetId identifiant de la cible du traitement
     * @param integer $debut identifiant de l'étape de début
     * @param integer $fin identifiant de l'étape de fin
     * @return array liste d'étapes
     */
    function _listeEtapesInterval($targetId, $debut, $fin) {
        // initialisation
        $ret = array();

        // lecture du traitement pour la cible
        $traitement = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('id', 'numero_traitement', 'circuit_id'),
            'conditions' => array('target_id' => $targetId)));
        if (empty($traitement)) return $ret;

        // lecture de l'étape de début
        $etape_debut = $this->Circuit->Etape->find('first', array(
            'recursive' => -1,
            'conditions' => array('id' => $debut)));
        if (empty($etape_debut)) return $ret;

        // lecture de l'étape de fin
        $etape_fin = $this->Circuit->Etape->find('first', array(
            'recursive' => -1,
            'conditions' => array('id' => $fin)));
        if (empty($etape_fin)) return $ret;

        // conditions de la liste d'étapes
        $conditions['ordre >='] = $etape_debut['Etape']['ordre'];
        $conditions['ordre <='] = $etape_fin['Etape']['ordre'];
        $conditions['circuit_id'] = $traitement['Traitement']['circuit_id'];

        $etapes = $this->Circuit->Etape->find('all', array(
            'recursive' => -1,
            'conditions' => $conditions,
            'order' => array('ordre ASC'),
            'fields' => array('id', 'nom', 'type')
        ));
        if (empty($etapes)) return $ret;
        foreach ($etapes as $etape) {
            $ret_etape = array();
            $visas = $this->Visa->find('all', array(
                'recursive' => -1,
                'fields' => array(
                    'DISTINCT trigger_id', 'etape_nom', 'etape_type', 'type_validation', 'action', 'commentaire', 'date'
                ),
                'conditions' => array(
                    'etape_id' => $etape['Etape']['id'],
                    'traitement_id' => $traitement['Traitement']['id']
                )));
            foreach ($visas as $visa) {
                //Vérifie si le visa correspond à l'étape (pour ne pas ajouter les visas générés depuis l'étape, ex: envoyer à)
                if ($visa['Visa']['etape_nom'] == $etape['Etape']['nom'])
                    $ret_etape['Visa'][] = $visa['Visa'];
            }
            //Pour respecter le format de l'argument $etapes de la méthode insert
            $ret_etape['Etape'] = array(
                'traitement_id' => $traitement['Traitement']['id'],
                'etape_id' => $etape['Etape']['id'],
                'etape_nom' => $etape['Etape']['nom'],
                'etape_type' => $etape['Etape']['type']);

            $ret[] = $ret_etape;

        }
        return $ret;
    }

    /**
     * retourne la liste des 'étapes' (numero-traitement) du traitement de la cible $targetId sous la forme
     *    []['Etape']['etape_nom=>string', 'etape_type'=>integer]
     *    ['Visa'][]['id', ...]
     * @param integer $targetId identifiant de la cible du traitement
     * @param array $options options de la fonction sous la forme :
     *    - ['selection'] : string, 'TOUTES', 'AVANT' (defaut), 'APRES', 'BORNES'
     *        'TOUTES' : retourne toutes les 'étapes' du traitement
     *        'AVANT' : retourne les 'étapes' du traitement avant le paramétre 'debut' (non comprise)
     *        'APRES' : retourne les 'étapes' du traitement aprés le paramétre 'debut' (non comprise)
     *        'BORNES' : retourne les 'etapes' du traitement entre le paramétre 'debut' et 'fin' (compris)
     *    - ['debut'] : integer, numero de traitement de debut (optionnel, defaut = numero de traitement courant)
     *    - ['fin'] : integer, numero de traitement de fin (optionnel)
     * @return array
     */
    function _listeEtapesVisas($targetId, $options = array()) {
        // initialisation
        $ret = array();
        $initOptions = array(
            'selection' => 'AVANT',
            'debut' => 0,
            'fin' => 0);
        $options = array_merge($initOptions, $options);

        // lecture du traitement pour la cible
        $traitement = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('id', 'numero_traitement'),
            'conditions' => array('target_id' => $targetId)));
        if (empty($traitement))
            return $ret;

        // inialisation de la borne de debut
        $options['debut'] = $options['debut'] ? $options['debut'] : $traitement['Traitement']['numero_traitement'];

        // initialisation de la condition
        $conditions['traitement_id'] = $traitement['Traitement']['id'];
        if ($options['selection'] == 'AVANT') {
            $conditions['numero_traitement <'] = $options['debut'];
        } elseif ($options['selection'] == 'APRES') {
            $conditions['numero_traitement >'] = $options['debut'];
        } elseif ($options['selection'] == 'BORNES') {
            $conditions['numero_traitement >='] = $options['debut'];
            if ($options['fin'])
                $conditions['numero_traitement <='] = $options['fin'];
        }

        // lecture des visas pour le traitement
        $visas = $this->Visa->find('all', array(
            'recursive' => -1,
            'conditions' => $conditions,
            'order' => 'numero_traitement'));

        // constitution de la reponse
        $ntCourant = 0;
        $iEtape = 0;
        $iVisa = 0;
        foreach ($visas as $visa) {
            if ($ntCourant != $visa['Visa']['numero_traitement']) {
                // nouvelle etape
                $iEtape++;
                $iVisa = 0;
                $ntCourant = $visa['Visa']['numero_traitement'];
                $ret[$iEtape]['Etape'] = array(
                    'traitement_id' => $visa['Visa']['traitement_id'],
                    'etape_id' => $visa['Visa']['etape_id'],
                    'etape_nom' => $visa['Visa']['etape_nom'],
                    'etape_type' => $visa['Visa']['etape_type']);
            }
            // ajout des visas
            $iVisa++;
            $ret[$iEtape]['Visa'][$iVisa] = array(
                'trigger_id' => $visa['Visa']['trigger_id'],
                'action' => $visa['Visa']['action'],
                'commentaire' => $visa['Visa']['commentaire'],
                'date' => $visa['Visa']['date'],
                'type_validation' => $visa['Visa']['type_validation']
            );
        }

        return $ret;
    }

    /**
     * retourne le type d'etape pour le traitement de la cible $targetId
     * @param integer $targetId identifiant de la cible
     * @param integer $numeroTraitement numero de traitement de l'etape (optionnel, defaut = numero de traitement courant)
     * @return integer type de l'etape du traitement
     */
    function typeEtape($targetId, $numeroTraitement = 0) {
        // lecture du traitement pour la cible
        $traitement = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('id', 'numero_traitement'),
            'conditions' => array('target_id' => $targetId)));
        if (empty($traitement))
            return false;

        // initialisation du numero de traitement
        if (!$numeroTraitement)
            $numeroTraitement = $traitement['Traitement']['numero_traitement'];

        // lecture du premier visa du traitement
        $visa = $this->Visa->find('first', array(
            'recursive' => -1,
            'fields' => array('etape_type'),
            'conditions' => array(
                'traitement_id' => $traitement['Traitement']['id'],
                'numero_traitement' => $numeroTraitement)));
        if (empty($visa))
            return false;

        return $visa['Visa']['etape_type'];
    }

    /**
     * retourne le dernier trigger qui a realise une action dans le traitement de la cible $targetId
     * @param integer $targetId identifiant de la cible
     * @return string declencheur formate selon le fichier de conf.
     */
    function getLastVisaTrigger($targetId) {
        // initialisation
        $ret = '';

        // lecture du traitement pour la cible
        $traitement = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('id', 'numero_traitement'),
            'conditions' => array('target_id' => $targetId)));
        if (empty($traitement))
            return false;

        if (!$this->_numTraitementComplet($traitement['Traitement']['id'], $traitement['Traitement']['numero_traitement'])) {
            $traitement['Traitement']['numero_traitement'] = $traitement['Traitement']['numero_traitement']--;
        }


        // lecture du dernier visa en date
        $visa = $this->Visa->find('first', array(
            'recursive' => -1,
            'fields' => array('trigger_id'),
            'conditions' => array(
                'traitement_id' => $traitement['Traitement']['id'],
                'numero_traitement' => $traitement['Traitement']['numero_traitement'],
                array('NOT' => array('action' => 'RI'))
            ),
            'order' => 'date DESC'));

        // mise en forme de la reponse
        if (!empty($visa)) {
            App::uses('CakeflowAppController', 'Cakeflow.Controller');
            $CakeflowAppController = new CakeflowAppController;
            $ret = $CakeflowAppController->formatLinkedModel('trigger', $visa['Visa']['trigger_id']);
        }

        return $ret;
    }

    /**
     * Liste les utilisateur du circuit avant/après/courant/suivant/précédent
     * @param int|string $targetId identifiant de projet
     * @param string $position before|after|previous|next
     * @param null|array|string(2) $action filtre sur les actions (visa) [RI,OK,KO,IN,JS,JP,IL,IP,VF]
     * @param null|bool $treated filtre sur l'etat du traitement (terminé ou non)
     * @return array
     */
    function whoIs($targetId, $position = 'current', $action = null, $treated = null) {
        $triggerList = array();
        $conditions = array('Traitement.target_id' => $targetId);
        if (!is_null($treated))
            $conditions['Traitement.treated'] = $treated;

        $traitement = $this->find('first', array(
            'recursive' => -1,
            'conditions' => $conditions,
            'fields' => 'Traitement.id, Traitement.numero_traitement'
        ));

        if (!empty($traitement)) {
            // Définition des conditions
            $conditions = array();
            $conditions['Visa.traitement_id'] = $traitement['Traitement']['id'];
            if (!empty($action))
                $conditions['Visa.action'] = $action;
            $tmpCond = 'Visa.numero_traitement';
            $tmpVal = $traitement['Traitement']['numero_traitement'];
            switch ($position) {
                case 'before':
                    $tmpCond .= ' <';
                    break;
                case 'after':
                    $tmpCond .= ' >';
                    break;
                case 'previous':
                    $tmpVal -= 1;
                    break;
                case 'next':
                    $tmpVal += 1;
                    break;
                case 'in':
                    $tmpCond = $tmpVal = null;
            }
            if (!empty($tmpCond) && !empty($tmpVal))
                $conditions[$tmpCond] = $tmpVal;

            $visas = $this->Visa->find('all', array(
                'recursive' => -1,
                'fields' => array('DISTINCT Visa.trigger_id'),
                'conditions' => $conditions
            ));
            if (!empty($visas))
                $triggerList = Hash::extract($visas, '{n}.Visa.trigger_id');
        }

        return $triggerList;
    }

    /**
     * @deprecated au profit de this::whoIs
     * Liste les utilisateur du circuit a l'étape courante
     * @param integer $targetId identifiant de projet
     * @return array
     */
    function whoIsNext($targetId) {
        $members = array();
        $traitement = $this->find('first', array('conditions' => array('Traitement.target_id' => $targetId),
            'recursive' => -1,
            'fields' => 'Traitement.id, Traitement.numero_traitement'));

        $visas = $this->Visa->find('all', array('conditions' => array(
            'Visa.traitement_id' => $traitement['Traitement']['id'],
            'Visa.action' => 'RI',
            'Visa.numero_traitement' => $traitement['Traitement']['numero_traitement']),
            'recursive' => -1));

        foreach ($visas as $visa)
            $members[] = $visa['Visa']['trigger_id'];

        return $members;
    }

    /**
     * Liste les utilisateur du circuit avant l'étape courante
     * @param integer $targetId identifiant de projet
     * @return array
     */
    function whoIsPrevious($targetId) {
        $members = array();
        $traitement = $this->find('first', array('conditions' => array('Traitement.target_id' => $targetId),
            'recursive' => -1,
            'fields' => 'Traitement.id, Traitement.numero_traitement'));
        if (empty($traitement)) return array();
        $visas = $this->Visa->find('all', array('conditions' => array(
            'Visa.traitement_id' => $traitement['Traitement']['id'],
            'Visa.numero_traitement <' => $traitement['Traitement']['numero_traitement']),
            'recursive' => -1));

        foreach ($visas as $visa)
            $members[] = $visa['Visa']['trigger_id'];

        return $members;
    }

    /**
     * récupère la liste des targetId sur lesquelles trigger_id à apposé un visa
     * @param integer $trigger_id
     * @return String targets, liste des targetId que trigger_id a visé
     */
    function getListTargetByTrigger($trigger_id) {
        $targets = array();
        $traitements = array();
        $visas = $this->Visa->find('all', array(
            'conditions' => array('Visa.trigger_id' => $trigger_id),
            'recursive' => -1));
        foreach ($visas as $visa) {
            if (!in_array($visa['Visa']['traitement_id'], $traitements)) {
                $traitement = $this->find('first', array(
                    'conditions' => array('Traitement.id' => $visa['Visa']['traitement_id']),
                    'recursive' => -1));
                if (!in_array($traitement['Traitement']['target_id'], $targets))
                    array_push($targets, $traitement['Traitement']['target_id']);
            }
        }
        return implode($targets, ',');
    }

}
