<?php
	/**
	 * TranslatorTest file
	 *
	 * PHP 5.3
	 *
	 * @package Translator
	 * @subpackage Test.Case.Utility.Translator
	 */
	App::uses('CakeSession', 'Model/Datasource');
	App::uses('Translator', 'Translator.Utility');
	require_once CakePlugin::path( 'Translator' ).'Lib'.DS.'basics.php';

	/**
	 * TranslatorTest class
	 *
	 * @package Translator
	 * @subpackage Test.Case.Utility.Translator
	 */
	class TranslatorTest extends CakeTestCase
	{
		/**
		 * setUp method
		 *
		 * @return void
		 */
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
			$Translator::domains( array( 'domain1', 'domain2', 'domain3' ) );
		}

		/**
		 * Test de la méthode Translator::translate();
		 */
		public function testTranslateSingle() {
			$result = Translator::translate('pas de traduction');
			$expected = 'pas de traduction';
			$this->assertEquals( $expected, $result, $expected);

			$result = Translator::translate('test1');
			$expected = 'traduction domain1/test1';
			$this->assertEquals( $expected, $result, $expected);

			$result = Translator::translate('test2');
			$expected = 'traduction domain2/test2';
			$this->assertEquals( $expected, $result, $expected);

			$result = Translator::translate('test3');
			$expected = 'traduction domain2/test3';
			$this->assertEquals( $expected, $result, $expected.' avec présence dans domain3');
		}

		/**
		 * Test de la méthode Translator::translate();
		 */
		public function testTranslateEnglish() {
			Configure::write('Config.language', 'eng');

			$result = Translator::translate('test4');
			$expected = 'English traduction\'s file';
			$this->assertEquals( $expected, $result, $expected);
		}

		/**
		 * Test de la méthode Translator::translate();
		 */
		public function testTranslatePlural() {
			$result = Translator::translate('test5', 'tests5', 6, $nb = 1);
			$expected = 'traduction domain1/test5';
			$this->assertEquals( $expected, $result, $expected.' singulier');

			$result = Translator::translate('test5', 'tests5', 6, $nb = 2);
			$expected = 'traduction domain1/test5 pluriel';
			$this->assertEquals( $expected, $result, $expected);
		}

		/**
		 * Test de la méthode Translator::translate();
		 * @expectedException Exception
		 */
		public function testTranslateException() {
			Translator::domains(array());
			Translator::translate('test6');
		}

		/**
		 * @covers Translator::lang
		 */
		public function testLang() {
			CakeSession::write( 'Config.language', 'eng' );

			$result = Translator::lang();
			$expected = 'eng';

			$this->assertEquals($expected, $result, "Language changé par variable \$_SESSION");

			CakeSession::clear();
			$result = Translator::lang();
			$expected = ( true === version_compare( core_version(), '2.4.0', '>=' ) ? 'fra' : 'fre' );

			$this->assertEquals($expected, $result, "Language changé par Config");
		}

		/**
		 * @covers Translator::domainsKey
		 */
		public function testDomainsKey() {
			$result = Translator::domainsKey();
			$expected = '["domain1","domain2","domain3"]';

			$this->assertEquals($expected, $result, "Domains key");
		}

		/**
		 * @covers Translator::import
		 */
		public function testImport() {
			$language = ( true === version_compare( core_version(), '2.4.0', '>=' ) ? 'fra' : 'fre' );
			$cache = array(
				$language => array(
					'["groups_index","groups"]' => array(
						'{"plural":null,"category":6,"count":null,"language":null}' => array(
							'name' => 'Nom',
						)
					)
				)
			);
			Translator::import($cache);
			Translator::domains(array('groups_index', 'groups'));
			$result = Translator::translate('name');
			$expected = 'Nom';
			$this->assertEquals($expected, $result, "Import data");
		}

		/**
		 * @covers Translator::import
		 */
		public function testMultipleImport() {
			$cache1 = array(
				'fr_FR' => array(
					'a:1:{i:0;s:13:"groups_index2";}' => array(
						'a:0:{}' => array(
							'Group.name' => 'Nom'
						)
					)
				)
			);
			$cache2 = array(
				'fr_FR' => array(
					'a:1:{i:0;s:13:"groups_index2";}' => array(
						'a:0:{}' => array(
							'Group.id' => 'Id'
						)
					)
				)
			);
			Translator::import($cache1);
			Translator::import($cache2);
			Translator::domains('groups_index2');
			$result = Translator::export();
			$expected = array(
				'fr_FR' => array(
					'a:1:{i:0;s:13:"groups_index2";}' => array(
						'a:0:{}' => array(
							'Group.name' => 'Nom',
							'Group.id' => 'Id'
						)
					)
				)
			);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @covers Translator::export
		 */
		public function testExport() {
			Translator::translate('test1');
			$result = Translator::export();
			$language = ( true === version_compare( core_version(), '2.4.0', '>=' ) ? 'fra' : 'fre' );
			$expected = array(
				$language => array(
					'["domain1","domain2","domain3"]' => array(
						'{"plural":null,"category":6,"count":null,"language":null}' => array(
							'test1' => 'traduction domain1/test1'
						)
					)
				)
			);
			$this->assertEquals($expected, $result);
		}

		/**
		 * @covers Translator::reset
		 */
		public function testReset() {
			Translator::reset();
			$result = Translator::domains();
			$expected = array(0 => 'default');

			$this->assertEquals($expected, $result, "Reset");
		}

		/**
		 * Test of the Translator::tainted() method.
		 *
		 * @covers Translator::tainted
		 */
		public function testTainted()
		{
			Translator::domains( array('groups_index', 'groups') );
			$this->assertFalse(Translator::tainted());
			Translator::translate('name');
			$this->assertTrue(Translator::tainted());
		}
	}
?>