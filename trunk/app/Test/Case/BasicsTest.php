<?php

/**
 * BasicsTest file
 *
 * PHP 5.3
 *
 * @package app.Test.Case
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * BasicsTest class
 *
 * @see http://book.cakephp.org/2.0/en/development/testing.html
 * @package app.Test.Case
 */
class BasicsTest extends CakeTestCase {

    /**
     * Test de la fonction replace_accents().
     */
    public function testReplaceAccents() {
        $result = replace_accents('Âéï');
        $expected = 'Aei';
        $this->assertEquals($expected, $result, var_export($result, true));
    }

    /**
     * Test de la fonction noaccents_upper().
     */
    public function testNoaccentsUpper() {
        $result = noaccents_upper('Âéï');
        $expected = 'AEI';
        $this->assertEquals($expected, $result, var_export($result, true));
    }

}
