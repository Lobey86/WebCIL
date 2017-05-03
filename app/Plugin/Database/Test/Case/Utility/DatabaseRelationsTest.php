<?php
	/**
	 * Code source de la classe DatabaseRelationsTest.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Test.Case.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	CakePlugin::load( 'Database', array( 'bootstrap' => true ) );
	App::uses( 'DatabaseRelations', 'Database.Utility' );
	require_once CakePlugin::path( 'Database' ).DS.'Test'.DS.'Case'.DS.'blog_models.php';

	/**
	 * La classe DatabaseRelationsTest effectue les tests unitaires de
	 * la classe DatabaseRelations.
	 *
	 * @package Database
	 * @subpackage Test.Case.Utility
	 */
	class DatabaseRelationsTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.User',
			'core.Post',
			'core.Comment',
			'core.Author',
			'core.Tag',
			'core.PostsTag',
			'plugin.Database.DatabaseFichedeliaison',
			'plugin.Database.DatabaseService66'
		);

		/**
		 * Préparation du test.
		 *
		 * INFO: ne pas utiliser parent::setUp();
		 */
		public function setUp() {
			ClassRegistry::flush();
			App::build(
				array(
					'Model' => array( CakePlugin::path( 'Database' ) . 'Test' . DS . 'test_app' . DS . 'Model' . DS )
				),
				App::PREPEND
			);

			$this->Post = ClassRegistry::init(
				array(
					'class' => 'TestPost',
					'alias' => 'Post'
				)
			);

			$this->Fichedeliaison = ClassRegistry::init(
				array(
					'class' => 'Fichedeliaison',
					'alias' => 'Fichedeliaison'
				)
			);

			$this->Service66 = ClassRegistry::init(
				array(
					'class' => 'Service66',
					'alias' => 'Service66'
				)
			);
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Fichedeliaison, $this->Post );
			parent::tearDown();
		}

		/**
		 * Test de la méthode DatabaseRelations::from()
		 *
		 * @covers DatabaseRelations::from
		 */
		public function testFrom() {
			$result = DatabaseRelations::from( $this->Post->Author );
			$expected = array();
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::from( $this->Post->Comment );
			$expected = array( 'Comment.post_id' => 'Post.id' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::from( $this->Post );
			$expected = array( 'Post.author_id' => 'Author.id' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::from( $this->Post->Tag );
			$expected = array();
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::from( $this->Post->TestPostTag );
			$expected = array(
				'TestPostTag.post_id' => 'Post.id',
				'TestPostTag.tag_id' => 'Tag.id',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::from( $this->Fichedeliaison );
			$expected = array(
				'Fichedeliaison.destinataire_id' => 'Expediteur.id'
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::from( $this->Fichedeliaison->Expediteur );
			$expected = array();
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseRelations::to()
		 *
		 * @covers DatabaseRelations::to
		 */
		public function testTo() {
			$result = DatabaseRelations::to( $this->Post->Author );
			$expected = array( 'Post.author_id' => 'Author.id' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::to( $this->Post->Comment );
			$expected = array();
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::to( $this->Post );
			$expected = array(
				'Comment.post_id' => 'Post.id',
				'TestPostTag.post_id' => 'Post.id',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::to( $this->Post->Tag );
			$expected = array( 'TestPostTag.tag_id' => 'Tag.id' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::to( $this->Post->TestPostTag );
			$expected = array();
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::to( $this->Fichedeliaison );
			$expected = array();
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::to( $this->Fichedeliaison->Expediteur );
			$expected = array(
				'FichedeliaisonDestinataire.destinataire_id' => 'Expediteur.id'
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseRelations::links()
		 *
		 * @covers DatabaseRelations::links
		 */
		public function testLinks() {
			$result = DatabaseRelations::links( $this->Post->Author );
			$expected = array( 'Post' => 'TestPost' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::links( $this->Post->Comment );
			$expected = array( 'Post' => 'TestPost' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::links( $this->Post );
			$expected = array(
				'Author' => 'TestAuthor',
				'Comment' => 'TestComment',
				'TestPostTag' => 'TestPostTag',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::links( $this->Post->Tag );
			$expected = array( 'TestPostTag' => 'TestPostTag' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::links( $this->Post->TestPostTag );
			$expected = array(
				'Post' => 'TestPost',
				'Tag' => 'TestTag',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::links( $this->Fichedeliaison );
			$expected = array(
				'Expediteur' => 'Service66'
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::links( $this->Fichedeliaison->Expediteur );
			$expected = array(
				'FichedeliaisonDestinataire' => 'Fichedeliaison'
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::links( $this->Service66 );
			$expected = array(
				'FichedeliaisonDestinataire' => 'Fichedeliaison'
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseRelations::relations()
		 *
		 * @covers DatabaseRelations::relations
		 */
		public function testRelations() {
			$result = DatabaseRelations::relations( $this->Post->Author );
			$expected = array(
				'from' => array(),
				'to' => array(
					'Post.author_id' => 'Author.id'
				),
				'links' => array(
					'Post' => 'TestPost'
				)
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = DatabaseRelations::relations( $this->Fichedeliaison );
			$expected = array(
				'from' => array(
					'Fichedeliaison.destinataire_id' => 'Expediteur.id',
				),
				'to' => array(),
				'links' => array(
					'Expediteur' => 'Service66',
				)
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseRelations::aliases()
		 *
		 * @covers DatabaseRelations::aliases
		 */
		public function testAliases() {
			$relations = array(
				'Author' => DatabaseRelations::relations( $this->Post->Author ),
				'Comment' => DatabaseRelations::relations( $this->Post->Comment ),
				'Expediteur' => DatabaseRelations::relations( $this->Fichedeliaison->Expediteur ),
				'Fichedeliaison' => DatabaseRelations::relations( $this->Fichedeliaison ),
				'Post' => DatabaseRelations::relations( $this->Post ),
				'Service66' => DatabaseRelations::relations( $this->Service66 ),
				'Tag' => DatabaseRelations::relations( $this->Post->Tag ),
				'TestPostTag' => DatabaseRelations::relations( $this->Post->TestPostTag ),
			);

			$result = DatabaseRelations::aliases( $relations );
			$expected = array(
				'Author' => array( 'Author', 'TestAuthor', ),
				'Comment' => array( 'Comment', 'TestComment', ),
				'Expediteur' => array( 'Expediteur', 'Service66' ),
				'Fichedeliaison' => array( 'Fichedeliaison', 'FichedeliaisonDestinataire' ),
				'FichedeliaisonDestinataire' => array( 'Fichedeliaison', 'FichedeliaisonDestinataire' ),
				'Post' => array( 'Post', 'TestPost', ),
				'Service66' => array( 'Service66', 'Expediteur' ),
				'Tag' => array( 'Tag', 'TestTag', ),
				'TestAuthor' => array( 'TestAuthor', 'Author', ),
				'TestComment' => array( 'TestComment', 'Comment', ),
				'TestPost' => array( 'TestPost', 'Post', ),
				'TestPostTag' => array( 'TestPostTag', ),
				'TestTag' => array( 'TestTag', 'Tag', ),
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseRelations::missing()
		 *
		 * @covers DatabaseRelations::missing
		 * @covers DatabaseRelations::_find
		 */
		public function testMissing() {
			// Aucune relation manquante
			$relations = array(
				'Author' => DatabaseRelations::relations( $this->Post->Author ),
				'Comment' => DatabaseRelations::relations( $this->Post->Comment ),
				'Fichedeliaison' => DatabaseRelations::relations( $this->Fichedeliaison ),
				'Post' => DatabaseRelations::relations( $this->Post ),
				'Service66' => DatabaseRelations::relations( $this->Service66 ),
				'Tag' => DatabaseRelations::relations( $this->Post->Tag ),
				'TestPostTag' => DatabaseRelations::relations( $this->Post->TestPostTag ),
			);

			$result = DatabaseRelations::missing( $relations );
			$expected = array();
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// Suppression de certaines relations
			$empty = array( 'from' => array(), 'to' => array(), 'links' => array() );
			$relations['TestAuthor'] = $relations['Author'] = $empty;
			$relations['TestComment'] = $relations['Comment'] = $empty;
			$relations['TestPost'] = $relations['Post'] = array(
				'from' => array( 'Post.author_id' => 'Author.id' ),
				'to' => array( 'Comment.post_id' => 'Post.id' ),
				'links' => array(
					'Author' => 'TestAuthor',
					'Comment' => 'TestComment',
				)
			);
			$relations['TestTag'] = $relations['Tag'] = $empty;

			$result = DatabaseRelations::missing( $relations );
			$expected = array(
				'from' => array(
					'Comment.post_id' => 'Post.id',
				),
				'to' => array(
					'Post.author_id' => 'Author.id',
					'TestPostTag.post_id' => 'Post.id',
					'TestPostTag.tag_id' => 'Tag.id',
				),
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}
	}
?>
