<?php
/**
 *
 * PHP 5.3
 *
 * @package app.Test.Case.Model
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */
App::uses('Circuit', 'Cakeflow.Model');

class TraitementTest extends CakeTestCase {


    /**
     * setUp method
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->Circuit = new Circuit();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown() {
        unset($this->Circuit);
        parent::tearDown();
    }

    /**
     * hasEtapeDelegation test method
     *
     * @return void
     */
    public function test_delegToParapheur() {
        
       
        
    }

}
