<?php
	/**
	 * BasicsTest file
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Test.Case
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	require_once CakePlugin::path( 'Database' ).'Config'.DS.'bootstrap.php';

	/**
	 * La classe BasicsTest effectue les tests unitaires des fonctions utilitaires
	 * du plugin Database.
	 *
	 * @package Database
	 * @subpackage Test.Case
	 */
	class BasicsTest extends CakeTestCase
	{

		/**
		 * Test de la fonction cacheKey()
		 */
		public function testCacheKey() {
			$result = cacheKey( array( 'ClassName', 'methodName' ) );
			$expected = 'ClassName_methodName';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = cacheKey( array( 'ClassName', 'methodName' ), true );
			$expected = 'class_name_method_name';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction preg_replace_array()
		 */
		public function testPregReplaceArray() {
			$array = array( 0 => 1, 1 => 'woot', '2' => array( 'Foo' => 5, '1' => 'Bar' ) );
			$replacements = array( '/([0-9]+)/' => '\1\1' );
			$result = preg_replace_array( $array, $replacements );
			$expected = array(
				'00' => '11',
				11 => 'woot',
				22 =>
				array(
					'Foo' => '55',
					11 => 'Bar',
				),
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction alias_querydata()
		 */
		public function testAliasQuerydata() {
			$querydata = array(
				'table' => '"authors"',
				'alias' => 'Author',
				'type' => 'LEFT',
				'conditions' => '"Post"."author_id" = "Author"."id"'
			);
			$result = alias_querydata( $querydata, array( 'Author' => 'authors', 'Post' => 'posts' ) );
			$expected = array(
				'table' => '"authors"',
				'alias' => 'authors',
				'type' => 'LEFT',
				'conditions' => '"posts"."author_id" = "authors"."id"',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$querydata = array(
				'fields' => array(
					'Post.id'
				),
				'joins' => array(
					array(
						'table' => '"authors"',
						'alias' => 'Author',
						'type' => 'LEFT',
						'conditions' => '"Post"."author_id" = "Author"."id"'
					)
				),
				'order' => array( 'Post.created DESC' ),
				'limit' => 1
			);
			$result = alias_querydata( $querydata, array( 'Author' => 'authors', 'Post' => 'posts' ) );
			$expected = array(
				'fields' => array(
					'posts.id',
				),
				'joins' => array(
					array(
						'table' => '"authors"',
						'alias' => 'authors',
						'type' => 'LEFT',
						'conditions' => '"posts"."author_id" = "authors"."id"',
					),
				),
				'order' => array(
					'posts.created DESC',
				),
				'limit' => '1',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction recursive_key_value_preg_replace().
		 */
		public function testRecursiveKeyValuePregReplace() {
			$array = array( 'foo' => 1, 'bar' => 'foo', 'baz' => array( 'foo' => 'foo' ) );

			$result = recursive_key_value_preg_replace( $array, array( '/foo/' => 'Foo' ) );
			$expected = array( 'Foo' => 1, 'bar' => 'Foo', 'baz' => array( 'Foo' => 'Foo' ) );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}


		/**
		 * Test de la fonction words_replace()
		 */
		public function testWordsReplace() {
			// 1. Avec une chaine de caractères simple
			$result = words_replace( 'Post', array( 'Author' => 'authors', 'Post' => 'posts' ) );
			$expected = 'posts';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 2. Pour des conditions de query CakePHP
			$result = words_replace(
				'"Fichiermodule"."modele" = \'Personne\' AND "Fichiermodule"."fk_value" = {$__cakeID__$}',
				array( '{$__cakeID__$}' => 594593 )
			);
			$expected = '"Fichiermodule"."modele" = \'Personne\' AND "Fichiermodule"."fk_value" = 594593';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 3. Avec une sous-requete créée par CakePHP
			$result = words_replace( 'SELECT "Foo"."id" AS "Foo__id" FROM "public"."foos" AS "Foo" WHERE "Foo"."name" = \'FooBar\';', array( 'Foo' => 'Bar' ) );
			$expected = 'SELECT "Bar"."id" AS "Foo__id" FROM "public"."foos" AS "Bar" WHERE "Bar"."name" = \'FooBar\';';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 4. Pour une partie de querydata
			$querydata = array(
				'table' => '"authors"',
				'alias' => 'Author',
				'type' => 'LEFT',
				'conditions' => '"Post"."author_id" = "Author"."id"'
			);
			$result = words_replace( $querydata, array( 'Author' => 'authors', 'Post' => 'posts' ) );
			$expected = array(
				'table' => '"authors"',
				'alias' => 'authors',
				'type' => 'LEFT',
				'conditions' => '"posts"."author_id" = "authors"."id"',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 5. Pour un querydata complet
			$querydata = array(
				'fields' => array(
					'Post.id'
				),
				'joins' => array(
					array(
						'table' => '"authors"',
						'alias' => 'Author',
						'type' => 'LEFT',
						'conditions' => '"Post"."author_id" = "Author"."id"'
					)
				),
				'order' => array( 'Post.created DESC' ),
				'limit' => 1
			);
			$result = words_replace( $querydata, array( 'Author' => 'authors', 'Post' => 'posts' ) );
			$expected = array(
				'fields' => array(
					'posts.id',
				),
				'joins' => array(
					array(
						'table' => '"authors"',
						'alias' => 'authors',
						'type' => 'LEFT',
						'conditions' => '"posts"."author_id" = "authors"."id"',
					),
				),
				'order' => array(
					'posts.created DESC',
				),
				'limit' => '1',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction groupKeysByValues().
		 */
		public function testGroupKeysByValues() {
			$result = groupKeysByValues( array( '0' => 'integer', '2' => 'float', '3' => 'integer' ) );
			$expected = array(
				'integer' => array( 0, 3 ),
				'float' => array( 2 ),
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction array_normalize().
		 */
		public function testArrayNormalize() {
			// 1. Avec un array vide
			$result = array_normalize( array() );
			$expected = array();
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 2. Cas nominal
			$array = array(
				'Foo',
				'FooBar' => array( 'Bar', 'Baz' => 'Boz' ),
				array( 'FooBaz' => 'FooBoz' )
			);
			$result = array_normalize( $array );

			$expected = array(
				'Foo' => NULL,
				'FooBar' => array(
					'Bar',
					'Baz' => 'Boz',
				),
				1 => array( 'FooBaz' => 'FooBoz' )
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction core_version().
		 */
		public function testCoreVersion() {
			$this->assertRegexp( '/^2\./', core_version() );
		}

		/**
		 * Test de la fonction merge_conditions().
		 */
		public function testMergeConditions() {
			// 1. Avec des chaines de caractères
			$result = merge_conditions( '1 = 1', '2 = 2' );
			$expected = array( '1 = 1', '2 = 2' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 2. Avec un array et une chaîne de caractères
			$result = merge_conditions( array( 'Foo.bar' => 'baz' ), '2 = 2' );
			$expected = array(
				'2 = 2',
				'Foo.bar' => 'baz',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 3. Avec deux array
			$result = merge_conditions( array( 'Foo.bar' => 'baz' ), array( 'Foo.bar' => 'boz', 'Foo.boz' => 'bar' ) );
			$expected = array (
				'Foo.bar' => 'baz',
				'Foo.boz' => 'bar',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 4. Avec un second paramètre vide
			$result = merge_conditions( array( 'Foo.bar' => 'baz' ), null );
			$expected = array ( 'Foo.bar' => 'baz' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 5. Avec une chaîne de caractères et un array
			$result = merge_conditions( '1 = 1', array( 'Foo.bar' => 'baz' ) );
			$expected = array(
				'1 = 1',
				'Foo.bar' => 'baz',
			);

			// 5. Avec un premier paramètre vide
			$result = merge_conditions( null, array( 'Foo.bar' => 'baz' ) );
			$expected = array ( 'Foo.bar' => 'baz' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}
	}
?>