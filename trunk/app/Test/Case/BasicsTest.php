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

    /**
     * Test de la fonction evaluate().
     */
    public function testEvaluate() {
        // Cas nominal
        $result = evaluate(['Fiche' => ['id' => 5]], '/fiches/show/#Fiche.id#');
        $expected = '/fiches/show/5';
        $this->assertEquals($expected, $result, var_export($result, true));

        // Sans données
        $result = evaluate([], '/fiches/show/#Fiche.id#');
        $expected = '/fiches/show/';
        $this->assertEquals($expected, $result, var_export($result, true));

        // Avec un array
        $result = evaluate(
            ['Fiche' => ['id' => 5]],
            [
                'url' => '/fiches/show/#Fiche.id#',
                'id' => 'btnShow#Fiche.id#',
                'test_#Fiche.id#' => 'foo #Fiche.id# bar'
            ]
        );
        $expected = [
            'url' => '/fiches/show/5',
            'id' => 'btnShow5',
            'test_5' => 'foo 5 bar'
        ];
        $this->assertEquals($expected, $result, var_export($result, true));
    }

    /**
     * Test de la fonction evaluate_string().
     */
    public function testEvaluateString() {
        // Cas nominal
        $result = evaluate_string(['Fiche' => ['id' => 5]], '/fiches/show/#Fiche.id#');
        $expected = '/fiches/show/5';
        $this->assertEquals($expected, $result, var_export($result, true));

        // Sans données
        $result = evaluate_string([], '/fiches/show/#Fiche.id#');
        $expected = '/fiches/show/';
        $this->assertEquals($expected, $result, var_export($result, true));
    }

    /**
     * Test de la fonction url_to_array().
     */
    public function testUrlToArray() {
        // Cas nominal
        $result = url_to_array('/Fiches/show/1');
        $expected = [
            'plugin' => null,
            'controller' => 'fiches',
            'action' => 'show',
            '1'
        ];
        $this->assertEquals($expected, $result, var_export($result, true));

        // Avec des paramètres nommés
        $result = url_to_array('/Fiches/show/1/foo:bar/baz:boz/page:1');
        $expected = [
            'plugin' => null,
            'controller' => 'fiches',
            'action' => 'show',
            '1',
            'foo' => 'bar',
            'baz' => 'boz',
            'page' => 1
        ];
        $this->assertEquals($expected, $result, var_export($result, true));

        // Avec un objet CakeRequest
		$request = new CakeRequest();
		$request->addParams([
            'plugin' => null,
            'controller' => 'Fiches',
            'action' => 'show',
            1
        ]);
        $request->query = [];
        $result = url_to_array($request);
        $expected = [
            'controller' => 'fiches',
            'action' => 'show',
            'plugin' => NULL,
            0 => '1'
        ];
        $this->assertEquals($expected, $result, var_export($result, true));

    }

    /**
     * Test de la fonction url_to_string().
     */
    public function testUrlToString() {
        // Cas nominal
        $result = url_to_string([
            'plugin' => null,
            'controller' => 'fiches',
            'action' => 'show',
            '1'
        ]);
        $expected = '/fiches/show/1';
        $this->assertEquals($expected, $result, var_export($result, true));

        // Avec des paramètres nommés
        $result = url_to_string([
            'plugin' => null,
            'controller' => 'Fiches',
            'action' => 'show',
            '1',
            'foo' => 'bar',
            'baz' => 'boz',
            'page' => 1
        ]);
        $expected = '/fiches/show/1/foo:bar/baz:boz/page:1';
        $this->assertEquals($expected, $result, var_export($result, true));

        // Avec un objet CakeRequest
		$request = new CakeRequest();
		$request->addParams([
            'plugin' => null,
            'controller' => 'Fiches',
            'action' => 'show',
            1
        ]);
        $request->query = [];
        $result = url_to_string($request);
        $expected = '/fiches/show/1';
        $this->assertEquals($expected, $result, var_export($result, true));
    }
}
