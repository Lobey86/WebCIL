<?php
	/**
	 * BasicsTest file
	 *
	 * PHP 5.3
	 *
	 * @package Translator
	 * @subpackage Test.Case
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	require_once CakePlugin::path( 'Translator' ).'Lib'.DS.'basics.php';

	/**
	 * La classe BasicsTest effectue les tests unitaires des fonctions utilitaires
	 * du plugin Translator.
	 *
	 * @package Translator
	 * @subpackage Test.Case
	 */
	class BasicsTest extends CakeTestCase
	{
		public function setUp() {
			parent::setUp();

			ClassRegistry::flush();
			Cache::clear();

			App::build(
				array(
					'Locale' => array(
						CakePlugin::path( 'Translator' ) . 'Test' . DS . 'test_app' . DS . 'Locale' . DS
					)
				),
				App::RESET
			);

			if(	true === version_compare( core_version(), '2.4.0', '>=' ) ) {
				Configure::write( 'Config.language', 'fra' );
			}
			else {
				Configure::write( 'Config.language', 'fre' );
			}

			$Translator = Translator::getInstance();
			$Translator->reset();

			$domains = array( 'domain1', 'domain2', 'default' );
			$Translator->domains( $domains );
		}

		/**
		 * Test de la fonction __m
		 */
		public function test__m() {
			$this->assertEquals('traduction domain1/test1', __m('test1'), "Traduction avec __m");
			$this->assertEquals(
				'Some string with multiple arguments',
				__m('Some string with %s %s', array('multiple', 'arguments')), "Remplacement vsprintf"
			);
			$this->assertEquals(
				'traduction avec 2 arguments',
				__m('test6', 2, 'arguments'), "Remplacement vsprintf avec traduction"
			);
		}

		/**
		 * Test de la fonction __mn
		 */
		public function test__mn() {
			$this->assertEquals('traduction domain1/test5', __mn('test5', 'tests5', 1), "Traduction avec __mn singulier");
			$this->assertEquals('traduction domain1/test5 pluriel', __mn('test5', 'tests5', 2), "Traduction avec __mn pluriel");
			$this->assertEquals(
				'traduction avec 2 arguments',
				__mn('test7', 'tests7', 1, 2, 'arguments'), "Remplacement vsprintf avec traduction singulier"
			);
			$this->assertEquals(
				'traduction avec 2 arguments au pluriel',
				__mn('test7', 'tests7', 2, array(2, 'arguments')), "Remplacement vsprintf avec traduction pluriel"
			);
		}

		/**
		 * Test de la fonction __domain
		 */
		public function test__domain() {
			$this->assertEquals('domain1', __domain('test1'), "__domain() utilisé pour 'test1'");
			$this->assertEquals('domain1', __domain('pas de traduction'), "__domain() dernier domain connu");
			$this->assertEquals('domain2', __domain('test2'), "__domain() utilisé pour 'test2'");
			$this->assertEquals('domain2', __domain('test5', 'tests5', 2), "__domain() utilisé pour 'test5' avec pluriel");
		}
	}
?>