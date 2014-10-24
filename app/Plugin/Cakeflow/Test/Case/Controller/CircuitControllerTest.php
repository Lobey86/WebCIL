<?php
/**
* Code source de la classe CircuitTest.
*
* PHP 5.3
*
* @package app.Test.Case.Controller
* @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
*/
App::uses('Circuit', 'Cakeflow.Controller');
App::uses('Traitement', 'Cakeflow.Model');
//App::uses(CAKEFLOW_TARGET_MODEL, 'Model');

/**
* Classe CircuitTest.
*
* @package Cakeflow.Test.Case.Controller
* 
*/
class CircuitTest extends CakeTestCase {
       
    // Les fixtures de plugin localisé dans /app/Plugin/Blog/Test/Fixture/
    public $fixtures = array(   'plugin.cakeflow.circuit',
                                'plugin.cakeflow.traitement',
                                'plugin.cakeflow.visa',
                                'plugin.cakeflow.etape',
                                'plugin.cakeflow.composition',
                                 CAKEFLOW_TARGET_MODEL
                            );
    public $Circuit;
    public $Visa;
    public $Traitement;

    public function setUp() {
        parent::setUp();
        $this->Circuit = ClassRegistry::init('Cakeflow.Circuit');
        $this->Visa = ClassRegistry::init('Cakeflow.Visa');
        $this->Traitement = ClassRegistry::init('Cakeflow.Traitement');
        
    }

    /**
    * Méthode exécutée avant chaque test.
    *
    * @return void
    */
    public function tearDown() {
        unset( $this->Circuit );
        unset( $this->Visa );
        unset( $this->Traitement );
    }
    
    /** Test l'insertion dans un nouveau circuit de traitment avec le valideur en étape 1 avec une seule étape avec l'optimisation
     * 
     */
    public function testInsertionCircuitRedacteurValideurOptimisation() {
        $return = $this->Circuit->insertDansCircuit($circuit_id=2, $deliberation_id=99100, $user_connecte=7);
                    $options = array(
                        'insertion' => array(
                            '0' => array(
                                'Etape' => array(
                                    'etape_nom' => 'Rédacteur',
                                    'etape_type' => 1
                                ),
                                'Visa' => array(
                                    '0' => array(
                                        'trigger_id' => $user_connecte,
                                        'type_validation' => 'V'
                                    )
                                )
                            )
                        )
                        ,
                        'optimisation'=> true,
                    );
                    
        if($return)
        $return = $this->Traitement->execute('IN', $user_connecte, $deliberation_id, $options);
        
        $this->Visa->Behaviors->attach('Containable');
        $visa = $this->Visa->find('all', array(
            'fields'=> array('Visa.id','Visa.action','Visa.numero_traitement'),
            'conditions' => array('trigger_id' => array(0,7)),
            'contain'=>array('Traitement'=>array('fields'=> array('Traitement.id','Traitement.numero_traitement','Traitement.target_id'),'conditions' => array('target_id' => 99100))),
            'order'=>array('Visa.numero_traitement ASC')
                ));

        if(count($visa)==2 && $visa[0]['Traitement']['numero_traitement']==3)
            $result=true;
        else $result=false;
        
        $this->assertEqual($result,true,'Result->'.var_export( $visa, true));
        
    }
    /** Test l'insertion dans un nouveau circuit de traitment avec le valideur en étape 1 avec une seule étape avec l'optimisation
     * 
     */
    public function testInsertionCircuitRedacteurValideur() {
        $return = $this->Circuit->insertDansCircuit($circuit_id=2, $deliberation_id=99100, $user_connecte=7);
                    $options = array(
                        'insertion' => array(
                            '0' => array(
                                'Etape' => array(
                                    'etape_nom' => 'Rédacteur',
                                    'etape_type' => 1
                                ),
                                'Visa' => array(
                                    '0' => array(
                                        'trigger_id' => $user_connecte,
                                        'type_validation' => 'V'
                                    )
                                )
                            )
                        )
                        ,
                        'optimisation'=> false,
                    );
                    
        if($return)
        $return = $this->Traitement->execute('IN', $user_connecte, $deliberation_id, $options);
        
        $this->Visa->Behaviors->attach('Containable');
        $visa = $this->Visa->find('all', array(
            'fields'=> array('Visa.id','Visa.action','Visa.numero_traitement'),
            'conditions' => array('trigger_id' => array(0,7)),
            'contain'=>array('Traitement'=>array('fields'=> array('Traitement.id','Traitement.numero_traitement','Traitement.target_id'),'conditions' => array('target_id' => 99100))),
            'order'=>array('Visa.numero_traitement ASC')
                ));

        if(count($visa)==2 && $visa[0]['Traitement']['numero_traitement']==3)
            $result=true;
        else $result=false;
        
        $this->assertEqual($result,true,'Result->'.var_export( $visa, true));
        
    }
    
    /** Test l'insertion dans un nouveau circuit de traitment avec le valideur en étape 1 avec une seule étape sans optimisation
     * 
     */
    public function testInsertionCircuitRedacteurOptimisation() {

        $return = $this->Circuit->insertDansCircuit($circuit_id=1, $deliberation_id=99101, $user_connecte=7);
                    $options = array(
                        'insertion' => array(
                            '0' => array(
                                'Etape' => array(
                                    'etape_nom' => 'Rédacteur',
                                    'etape_type' => 1
                                ),
                                'Visa' => array(
                                    '0' => array(
                                        'trigger_id' => $user_connecte,
                                        'type_validation' => 'V'
                                    )
                                )
                            )
                        )
                        ,
                        'optimisation'=> true,
                    );
                    
        if($return)
        $return = $this->Traitement->execute('IN', $user_connecte, $deliberation_id, $options);

        $this->Visa->Behaviors->attach('Containable');
        $visa = $this->Visa->find('all', array(
            'fields'=> array('Visa.id','Visa.action','Visa.numero_traitement'),
            'conditions' => array('trigger_id' => array(0,7)),
            'contain'=>array('Traitement'=>array('fields'=> array('Traitement.id','Traitement.numero_traitement','Traitement.target_id'),'conditions' => array('target_id' => 99101))),
            'order'=>array('Visa.numero_traitement ASC')
                ));
        
        if(count($visa)==1 && $visa[0]['Traitement']['numero_traitement']==2)
            $result=true;
        else $result=false;
        
        $this->assertEqual($result,true,'Result->'.var_export( $visa, true));
        
    }
    
    /** Test l'insertion dans un nouveau circuit de traitment avec le valideur en étape 1 avec une seule étape sans optimisation
     * 
     */
    public function testInsertionCircuitRedacteur() {

        $return = $this->Circuit->insertDansCircuit($circuit_id=1, $deliberation_id=99101, $user_connecte=7);
                    $options = array(
                        'insertion' => array(
                            '0' => array(
                                'Etape' => array(
                                    'etape_nom' => 'Rédacteur',
                                    'etape_type' => 1
                                ),
                                'Visa' => array(
                                    '0' => array(
                                        'trigger_id' => $user_connecte,
                                        'type_validation' => 'V'
                                    )
                                )
                            )
                        )
                        ,
                        'optimisation'=> false,
                    );
                    
        if($return)
        $return = $this->Traitement->execute('IN', $user_connecte, $deliberation_id, $options);

        $this->Visa->Behaviors->attach('Containable');
        $visa = $this->Visa->find('all', array(
            'fields'=> array('Visa.id','Visa.action','Visa.numero_traitement'),
            'conditions' => array('trigger_id' => array(0,7)),
            'contain'=>array('Traitement'=>array('fields'=> array('Traitement.id','Traitement.numero_traitement','Traitement.target_id'),'conditions' => array('target_id' => 99101))),
            'order'=>array('Visa.numero_traitement ASC')
                ));
        
        if(count($visa)==1 && $visa[0]['Traitement']['numero_traitement']==2)
            $result=true;
        else $result=false;
        
        $this->assertEqual($result,true,'Result->'.var_export( $visa, true));
        
    }

}