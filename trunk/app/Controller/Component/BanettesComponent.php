<?php

/**
 * Code source de la classe BanettesComponent.
 *
 * PHP 5.3
 *
 * @package app.Controller.Component
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */
App::uses('Component', 'Controller');
App::uses('Model', 'EtatFiche');

/**
 * La classe BanettesComponent ...
 *
 * @package app.Controller.Component
 */
class BanettesComponent extends Component {

    /**
     * Components utilisés par ce component.
     *
     * @var array
     */
    public $components = array('Session');

    public $Fiche = null;
    
    /**
     *
     * @param ComponentCollection $collection
     * @param array $settings
     */
    public function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings);

        $this->Fiche = ClassRegistry::init('Fiche');
    }
    
    /**
     * 
     * @return array()
     * 
     * @access protected
     * @created 18/07/2017
     * @version V1.0.0
     */
    protected function _query($etatFicheActif = true) {
        $query = [
            'fields' => array_merge(
                $this->Fiche->fields(),
                $this->Fiche->EtatFiche->fields()
            ),
            'joins' => [
                $this->Fiche->join(
                    'EtatFiche',
                    [
                        'type' => 'INNER',
                        'conditions'  => true === $etatFicheActif
                            ? ['EtatFiche.actif' => true]
                            : []
                    ]
                )
            ],
            'contain' => [
                'User' => [
                    'fields' => [
                        'id',
                        'nom',
                        'prenom'
                    ]
                ],
                'Valeur' => [
                    'conditions' => [
                        'champ_name' => ['outilnom', 'typedeclaration']
                    ],
                    'fields' => [
                        'champ_name',
                        'valeur'
                    ]
                ]
            ],
            'conditions' => [
                'Fiche.organisation_id' => $this->Session->read('Organisation.id')
            ]
        ];
                
        return $query;
    }

    /**
     * Traitement en cours de rédaction
     * 
     * @return array()
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function queryEncoursRedaction() {
        $query = $this->_query();

        $query['conditions'] += [
            'EtatFiche.user_id' => $this->Session->read('Auth.User.id'),
            'EtatFiche.etat_id' => [
                EtatFiche::ENCOURS_REDACTION,
                EtatFiche::REPLACER_REDACTION
            ]
        ];

        return $query;          
    }

    /**
     * Traitement en attente de validation
     * 
     * @return array()
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function queryAttente() {
        $query = $this->_query();

        $query['conditions'] += [
            'Fiche.user_id' => $this->Session->read('Auth.User.id'),
            'EtatFiche.etat_id' => EtatFiche::ENCOURS_VALIDATION
        ];

        return $query;          
    }

    /**
     * Traitements reçus pour validation
     * 
     * @return array()
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function queryRecuValidation() {
        $query = $this->_query();

        $query['conditions'] += [
            'EtatFiche.user_id' => $this->Session->read('Auth.User.id'),
            'EtatFiche.etat_id' => EtatFiche::ENCOURS_VALIDATION
        ];
        
        return $query;          
    }
    
    /**
     * Traitement refusées
     * 
     * @return array()
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function queryRefuser() {
        $query = $this->_query();

        $query['conditions'] += [
            'Fiche.user_id' => $this->Session->read('Auth.User.id'),
            'EtatFiche.etat_id' => EtatFiche::REFUSER
        ];

        return $query;          
    }
    
    /**
     * Traitements reçus pour consultation
     * 
     * @return array()
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function queryRecuConsultation() {
        $query = $this->_query(false);

        $query['conditions'] += [
            'EtatFiche.user_id' => $this->Session->read('Auth.User.id'),
            'EtatFiche.etat_id' => EtatFiche::DEMANDE_AVIS
        ];

        return $query;
    }
    
    /**
     * Traitement validés et insérés au registre ou archivé au registre
     * 
     * @return array()
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function queryArchives() {
        $query = $this->_query();

        $query['conditions'] += [
            'EtatFiche.etat_id' => [
                EtatFiche::VALIDER_CIL,
                EtatFiche::ARCHIVER
            ],
            'EtatFiche.actif' => true,
            'Fiche.user_id' => $this->Session->read('Auth.User.id')
        ];

        return $query;
    }
    
    /**
     * Etat des traitements passés en ma possession
     * 
     * @return array()
     * 
     * @access public
     * @created 18/07/2017
     * @version V1.0.0
     */
    public function queryConsulte() {
        $query = $this->_query();

        $subQuery = [
            'alias' => 'etats_fiches',
            'fields' => ['etats_fiches.id'],
            'conditions' => [
                'etats_fiches.fiche_id = Fiche.id',
                'etats_fiches.actif' => false,
                'etats_fiches.user_id' => $this->Session->read('Auth.User.id'),
            ]
        ];
        $sql = $this->Fiche->EtatFiche->sql($subQuery);

        $query['conditions'] += [
            'EtatFiche.user_id <>' => $this->Session->read('Auth.User.id'),
            "EXISTS( {$sql} )"
        ];
            
        return $query;
    }
}
