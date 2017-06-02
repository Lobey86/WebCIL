<?php
	/**
	 * TranslatorAutoloadComponentTest file
	 *
	 * PHP 5.3
	 *
	 * @package Translator
	 * @subpackage Test.Case.Utility.Translator
	 */
	App::uses('TranslatorAutoloadComponent', 'Translator.Controller/Component');
	App::uses('Controller', 'Controller');
	App::uses('Translator', 'Translator.Utility');
	require_once CakePlugin::path( 'Translator' ).'Lib'.DS.'basics.php';

	/**
	 * InsertionsAllocatairesTestController class
	 *
	 * @package app.Test.Case.Controller.Component
	 */
	class Domain1Controller extends Controller
	{
		public $components = array(
			'Session'
		);

		public function index() {}
	}

	/**
	 * TranslatorAutoloadComponentTest class
	 *
	 * @package Translator
	 * @subpackage Test.Case.Utility.Translator
	 */
	class TranslatorAutoloadComponentTest extends ControllerTestCase
	{
		public function setUpTranslator(array $requestParams = array(), array $mockMethods = array(), array $componentSettings = array())
		{
			$requestParams += array(
				'plugin' => null,
				'controller' => 'domain1',
				'action' => 'index',
				'named' => array(),
				'pass' => array()
			);
			$url = ltrim("{$requestParams['plugin']}.{$requestParams['controller']}/{$requestParams['action']}", '.');

			$request = new CakeRequest( $url, false );
			$request->addParams( $requestParams );
			$this->Controller = new Domain1Controller($request);//FIXME: controller name
			$this->Controller->request = $request;
			$this->Controller->Components->init($this->Controller);
			if( empty( $mockMethods ) ) {
				$this->Controller->TranslatorAutoload = new TranslatorAutoloadComponent(
					$this->Controller->Components,
					$componentSettings
				);
			}
			else {
				$this->Controller->TranslatorAutoload = $this->getMock(
					'TranslatorAutoloadComponent',
					$mockMethods,
					array( $this->Controller->Components, $componentSettings )
				);
			}
			Cache::delete($this->Controller->TranslatorAutoload->cacheKey());
			$this->Controller->TranslatorAutoload->initialize($this->Controller);
			$this->testAction('/'.$url, array('method' => 'GET'));
		}

		/**
		 * setUp method
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();

			ClassRegistry::flush();
			Cache::clear();

			Configure::write( 'Translator.suffix', null );

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
		}

		/**
		 * Test de la méthode TranslatorAutoloadComponent::domains();
		 */
		public function testDomains() {
			$this->setUpTranslator();

			$results = $this->Controller->TranslatorAutoload->domains();
			$expected = array(
				'domain1_index',
				'domain1',
				'default'
			);
			$this->assertEquals( $expected, $results, "Retourne la liste de domaines");

			$results = Translator::domains();
			$expected = $this->Controller->TranslatorAutoload->domains();
			$this->assertEquals( $expected, $results, "Domaines de l'utilitaire identique à ceux du component");
		}

		/**
		 * Test de la méthode TranslatorAutoloadComponent::domains();
		 */
		public function testDomainsWithSuffix() {
			Configure::write( 'Translator.suffix', 'monclient' );
			$this->setUpTranslator();

			$results = $this->Controller->TranslatorAutoload->domains();
			$expected = array(
				'domain1_index_monclient',
				'domain1_index',
				'domain1_monclient',
				'domain1',
				'default_monclient',
				'default'
			);
			$this->assertEquals( $expected, $results, "Retourne la liste de domaines");

			$results = Translator::domains();
			$expected = $this->Controller->TranslatorAutoload->domains();
			$this->assertEquals( $expected, $results, "Domaines de l'utilitaire identique à ceux du component");
		}

		/**
		 * Test de la méthode TranslatorAutoloadComponent::save();
		 */
		public function testSave() {
			$this->setUpTranslator();

			Configure::write('Cache.disable', false);
			Translator::translate('test1'); // effectue une traduction pour contrôle de la sauvegarde

			$this->Controller->TranslatorAutoload->save(); // force la sauvegarde

			$beforeReset = Translator::export();
			Translator::reset();
			$afterReset = Translator::export();

			$this->Controller->TranslatorAutoload->load();
			$afterLoad = Translator::export();

			$language = ( true === version_compare( core_version(), '2.4.0', '>=' ) ? 'fra' : 'fre' );
			$expected = array(
				$language => array(
					'["domain1_index","domain1","default"]' => array(
						'{"plural":null,"category":6,"count":null,"language":null}' => array(
							'test1' => 'traduction domain1/test1'
						)
					)
				)
			);
			$this->assertEquals( $expected, $beforeReset, "Avant reset");
			$this->assertEquals( array(), $afterReset, "Après reset");
			$this->assertEquals( $expected, $afterLoad, "Après load");
		}

		/**
		 * Test of the TranslatorAutoloadComponent::load().
		 *
		 * @covers TranslatorAutoloadComponent::load
		 * @covers TranslatorAutoloadComponent::_translator
		 */
		public function testLoad() {
			$this->setUpTranslator();

			$translatorClass = $this->Controller->TranslatorAutoload->settings['translatorClass'];
			$Instance = $translatorClass::getInstance();
			$this->Controller->TranslatorAutoload->load();
			$this->assertEquals(array(), $Instance->export());
		}

		/**
		 * @expectedException        RuntimeException
		 * @expectedExceptionMessage Missing utility class falseClassName
		 * @covers TranslatorAutoloadComponent::_translator
		 */
		public function testLoadMissingUtilityClassException() {
			$this->setUpTranslator();

			$Collection = new ComponentCollection($this->Controller);
			$this->Controller->TranslatorAutoload = new TranslatorAutoloadComponent($Collection);
			$this->Controller->TranslatorAutoload->settings['translatorClass'] = 'falseClassName';
			$this->Controller->TranslatorAutoload->load();
		}

		/**
		 * @expectedException        RuntimeException
		 * @expectedExceptionMessage Utility class TranslatorHash does not implement TranslatorInterface
		 * @covers TranslatorAutoloadComponent::_translator
		 */
		public function testLoadNotImplementsUtilityClassException() {
			$this->setUpTranslator();

			$Collection = new ComponentCollection($this->Controller);
			$this->Controller->TranslatorAutoload = new TranslatorAutoloadComponent($Collection);
			$this->Controller->TranslatorAutoload->settings['translatorClass'] = 'TranslatorHash';
			$this->Controller->TranslatorAutoload->load();
		}

		/**
		 * @expectedException        RuntimeException
		 * @expectedExceptionMessage Method "fakeMethod" cannot be called. Use one of "load", "save"
		 * @covers TranslatorAutoloadComponent::dispatchEvent
		 */
		public function testDispatchEventException() {
			$this->setUpTranslator();

			$this->Controller->TranslatorAutoload->settings['events']['fakeEvent'] = 'fakeMethod';
			$this->Controller->TranslatorAutoload->dispatchEvent('fakeEvent');
		}

		/**
		 * Test de la méthode TranslatorAutoloadComponent::beforeRedirect()
		 *
		 * @covers TranslatorAutoloadComponent::beforeRedirect
		 */
		public function testControllerBeforeRedirect() {
			$this->setUpTranslator( array(), array( 'load', 'save' ), array('events' => array( 'beforeRedirect' => 'save' ) ) );

			$translatorClass = $this->Controller->TranslatorAutoload->settings['translatorClass'];
			$Instance = $translatorClass::getInstance();
			$Instance->translate('name');
			$this->Controller->TranslatorAutoload->expects($this->once())->method( 'save' );
			$this->Controller->TranslatorAutoload->beforeRedirect( $this->Controller, '/users/index' );
		}

		/**
		 * Test de la méthode TranslatorAutoloadComponent::startup()
		 *
		 * @covers TranslatorAutoloadComponent::startup
		 */
		public function testControllerStartup() {
			$this->setUpTranslator( array(), array( 'load', 'save' ), array('events' => array( 'startup' => 'load' ) ) );

			$this->Controller->TranslatorAutoload->expects($this->once())->method( 'load' );
			$this->Controller->TranslatorAutoload->startup( $this->Controller );
		}

		/**
		 * Test de la méthode TranslatorAutoloadComponent::beforeRender()
		 *
		 * @covers TranslatorAutoloadComponent::beforeRender
		 */
		public function testControllerBeforeRender() {
			$this->setUpTranslator( array(), array( 'load', 'save' ), array('events' => array( 'beforeRender' => 'save' ) ) );

			$translatorClass = $this->Controller->TranslatorAutoload->settings['translatorClass'];
			$Instance = $translatorClass::getInstance();
			$Instance->translate('name');
			$this->Controller->TranslatorAutoload->expects($this->once())->method( 'save' );
			$this->Controller->TranslatorAutoload->beforeRender( $this->Controller );
		}

		/**
		 * Test de la méthode TranslatorAutoloadComponent::shutdown()
		 *
		 * @covers TranslatorAutoloadComponent::shutdown
		 */
		public function testControllerShutdown() {
			$this->setUpTranslator( array(), array( 'load', 'save' ), array('events' => array( 'shutdown' => 'save' ) ) );

			$translatorClass = $this->Controller->TranslatorAutoload->settings['translatorClass'];
			$Instance = $translatorClass::getInstance();
			$Instance->translate('name');
			$this->Controller->TranslatorAutoload->expects($this->once())->method( 'save' );
			$this->Controller->TranslatorAutoload->shutdown( $this->Controller );
		}
	}