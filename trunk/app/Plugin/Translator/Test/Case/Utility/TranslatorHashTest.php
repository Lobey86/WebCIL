<?php
	/**
	 * TranslatorHashTest file
	 *
	 * PHP 5.3
	 *
	 * @package Translator
	 * @subpackage Test.Case.Utility.Translator
	 */

	App::uses('TranslatorHash', 'Translator.Utility');

	/**
	 * TranslatorHashTest class
	 *
	 * @package Translator
	 * @subpackage Test.Case.Utility.Translator
	 */
	class TranslatorHashTest extends CakeTestCase
	{
		/**
		* Test of the TranslatorHash::exists() exists.
		*
		* @covers TranslatorHash::exists
		*/
		public function testExists()
		{
			$data = array(
				'fr_FR' => array(
					'a:3:{i:0;s:12:"groups_index";i:1;s:6:"groups";i:2;s:7:"default";}' => array(
						'__' => array(
							'id' => 'Id'
						)
					)
				)
			);
			// 1. Empty path
			$this->assertFalse(TranslatorHash::exists($data, array()));
			// Normal tests
			$keys1 = array('fr_FR', 'a:3:{i:0;s:12:"groups_index";i:1;s:6:"groups";i:2;s:7:"default";}', '__', 'id');
			$this->assertTrue(TranslatorHash::exists($data, $keys1));
			$keys2 = array('fr_FR', 'a:3:{i:0;s:12:"groups_index";i:1;s:6:"groups";i:2;s:7:"default";}');
			$this->assertTrue(TranslatorHash::exists($data, $keys2));
			$keys3 = array('fr_FR', 'a:3:{i:0;s:12:"groups_index";i:1;s:6:"groups";i:2;s:7:"default";}', '__', 'name');
			$this->assertFalse(TranslatorHash::exists($data, $keys3));
			$keys4 = array();
			$this->assertFalse(TranslatorHash::exists($data, $keys4));
		}
		/**
		* Test of the TranslatorHash::insert() method.
		*
		* @covers TranslatorHash::insert
		*/
		public function testInsert()
		{
			$data = array(
				'fr_FR' => array(
					'a:3:{i:0;s:12:"groups_index";i:1;s:6:"groups";i:2;s:7:"default";}' => array(
						'__' => array(
							'id' => 'Id'
						)
					)
				)
			);
			// 1. Empty path
			$this->assertFalse(TranslatorHash::insert($data, array(), 'Nom'));
			// 2. Normal test
			$keys = array('fr_FR', 'a:3:{i:0;s:12:"groups_index";i:1;s:6:"groups";i:2;s:7:"default";}', '__', 'name');
			$expected = array(
				'fr_FR' => array(
					'a:3:{i:0;s:12:"groups_index";i:1;s:6:"groups";i:2;s:7:"default";}' => array(
						'__' => array(
							'id' => 'Id',
							'name' => 'Nom'
						)
					)
				)
			);
			$this->assertEquals($expected, TranslatorHash::insert($data, $keys, 'Nom'));
		}
		/**
		* Test of the TranslatorHash::get() method.
		*
		* @covers TranslatorHash::get
		*/
		public function testGet()
		{
			$data = array(
				'fr_FR' => array(
					'a:3:{i:0;s:12:"groups_index";i:1;s:6:"groups";i:2;s:7:"default";}' => array(
						'__' => array(
							'id' => 'Id'
						)
					)
				)
			);
			// 1. Empty path
			$this->assertNull(TranslatorHash::get($data, array()));
			// 2. Normal tests
			$keys1 = array('fr_FR', 'a:3:{i:0;s:12:"groups_index";i:1;s:6:"groups";i:2;s:7:"default";}', '__', 'id');
			$this->assertEquals('Id', TranslatorHash::get($data, $keys1));
			$keys2 = array('fr_FR', 'a:3:{i:0;s:12:"groups_index";i:1;s:6:"groups";i:2;s:7:"default";}', '__', 'name');
			$this->assertNull(TranslatorHash::get($data, $keys2));
			$keys3 = array('fr_FR', 'a:3:{i:0;s:12:"groups_index";i:1;s:6:"groups";i:2;s:7:"default";}', '__');
			$this->assertEquals(array('id' => 'Id'), TranslatorHash::get($data, $keys3));
		}
	}