<?php
/**
*
* PHP 5.3
*
* @package app.Test.Case.Model
* @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
*/
App::uses('Tdt', 'Lib');
class IterationTest extends CakeTestCase {

    private $Tdt;

    public function setUp() {
        parent::setUp();
        $this->Tdt = new Tdt;
    }

    /**
     * Méthode exécutée avant chaque test.
     *
     * @return void
     */
    public function tearDown() {
        parent::tearDown();
    }

    /**
     * Test updateAll()
     * @return void
     */
    public function testGetClassification(){
        $retour = $this->Tdt->listClassification();
        debug ($retour);
//        debug('toto');
    }


}
