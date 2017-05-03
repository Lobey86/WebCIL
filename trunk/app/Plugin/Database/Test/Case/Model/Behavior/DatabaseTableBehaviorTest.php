<?php
	/**
	 * Code source de la classe DatabaseTableBehaviorTest.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Test.Case.Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'DatabaseTableBehavior', 'Database.Model/Behavior' );
	require_once CakePlugin::path( 'Database' ).DS.'Test'.DS.'Case'.DS.'blog_models.php';

	/**
	 * La classe DatabaseTableBehaviorTest effectue les tests unitaires de
	 * la classe DatabaseTableBehavior.
	 *
	 * @package Database
	 * @subpackage Test.Case.Model.Behavior
	 */
	class DatabaseTableBehaviorTest extends CakeTestCase
	{
		/**
		 *
		 * @var AppModel
		 */
		public $Site = null;

		/**
		 *
		 * @var AppModel
		 */
		public $Post = null;

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
		);

		protected function _attach( Model $Model ) {
			$attachedAliases = array_unique( Hash::extract( $Model->Behaviors->methods(), '{s}.0' ) );
			foreach( $attachedAliases as $attachedAlias ) {
				$Model->Behaviors->detach( $attachedAlias );
			}

//			$Model->Behaviors->attach( 'FooBar', array( 'className' => 'Database.DatabaseTable' ) );
			$Model->Behaviors->attach( 'Database.DatabaseTable' );
		}

		/**
		 * Préparation du test.
		 *
		 * INFO: ne pas utiliser parent::setUp();
		 */
		public function setUp() {
			$this->Post = ClassRegistry::init(
				array(
					'class' => 'TestPost',
					'alias' => 'Post'
				)
			);

			// On attache le bon behavior -> FIXME....
			$Models = array(
				$this->Post,
				$this->Post->Author,
				$this->Post->Comment,
				$this->Post->TestPostTag,
				$this->Post->Tag,
			);
			foreach( $Models as $Model ) {
				$this->_attach( $Model );
			}
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Post );
			ClassRegistry::flush();
			parent::tearDown();
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::hasUniqueIndex()
		 *
		 * @covers DatabaseTableBehavior::hasUniqueIndex
		 */
		public function testHasUniqueIndex() {
			// Ajout d'un index unique sur la colonne tag du modèle Tag
			$Dbo = $this->Post->Tag->getDatasource();
			$tableName = $Dbo->fullTableName( $this->Post->Tag, false, true );
			$indexName = $Dbo->fullTableName( $this->Post->Tag, false, false ).'_tag_idx';

			$indexes = $Dbo->index( $this->Post->Tag );
			if( !isset( $indexes[$indexName] ) ) {
				$dontCache = '/* '.microtime( true ).' */';
				$sql = "CREATE UNIQUE INDEX {$indexName} ON {$tableName} ( tag ); {$dontCache}";
				$Dbo->query( $sql );
			}

//			$indexes = $this->Post->Tag->getDatasource()->index( $this->Post->Tag );
//			debug( $indexes );

			$result = $this->Post->Tag->hasUniqueIndex( 'tag' );
			$expected = true;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = $this->Post->Tag->hasUniqueIndex( 'tag', 'tags_tag_idx' );
			$expected = true;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = $this->Post->Tag->hasUniqueIndex( 'created' );
			$expected = false;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::hasUniqueIndex() lorsque le
		 * modèle n'est pas lié à une table et qu'une exception est renvoyée.
		 *
		 * @expectedException RuntimeException
		 *
		 * @covers DatabaseTableBehavior::hasUniqueIndex
		 */
		public function testHasUniqueIndexException() {
			$this->Post->Tag->useTable = false;
			$this->Post->Tag->hasUniqueIndex( 'tag' );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::sql()
		 *
		 * @covers DatabaseTableBehavior::sql
		 */
		public function testSql() {
			$result = trim( $this->Post->sql( array() ) );
			$expected = 'SELECT "Post"."id" AS "Post__id" FROM "public"."posts" AS "Post"   WHERE 1 = 1';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = trim( $this->Post->sql( array( 'conditions' => array( 'Post.id' => '4' ) ) ) );
			$expected = 'SELECT "Post"."id" AS "Post__id" FROM "public"."posts" AS "Post"   WHERE "Post"."id" = 4';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = trim(
				$this->Post->sql(
					array(
						'fields' => array(
							'Post.title'
						),
						'conditions' => array(
							'Post.published' => '1'
						),
						'limit' => 1,
						'order' => array( 'Post.id ASC' )
					)
				)
			);
			$expected = 'SELECT "Post"."title" AS "Post__title" FROM "public"."posts" AS "Post"   WHERE "Post"."published" = \'1\'   ORDER BY "Post"."id" ASC  LIMIT 1';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::sql() lorsque le
		 * modèle n'est pas lié à une table et qu'une exception est renvoyée.
		 *
		 * @expectedException RuntimeException
		 *
		 * @covers DatabaseTableBehavior::sql
		 */
		public function testSqException() {
			$this->Post->useTable = false;
			$this->Post->sql( array( ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::fields()
		 *
		 * @covers DatabaseTableBehavior::fields
		 */
		public function testFields() {
			// 1. Sans les champs virtuels
			$result = $this->Post->fields();
			$expected = array(
				'Post.id',
				'Post.author_id',
				'Post.title',
				'Post.body',
				'Post.published',
				'Post.created',
				'Post.updated',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 2. Avec les champs virtuels
			$this->Post->virtualFields['virtual1'] = 'Post.id < 100';
			$result = $this->Post->fields( true );
			$expected = array(
				'Post.id',
				'Post.author_id',
				'Post.title',
				'Post.body',
				'Post.published',
				'Post.created',
				'Post.updated',
				'Post.virtual1'
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::fields() lorsque le
		 * modèle n'est pas lié à une table et qu'une exception est renvoyée.
		 *
		 * @expectedException RuntimeException
		 *
		 * @covers DatabaseTableBehavior::fields
		 */
		public function testFieldsException() {
			$this->Post->useTable = false;
			$this->Post->fields();
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::uniqueIndexes()
		 *
		 * @covers DatabaseTableBehavior::uniqueIndexes
		 */
		public function testUniqueIndexes() {
			$result = $this->Post->Tag->uniqueIndexes();
			$expected = array(
				'PRIMARY' => 'id',
				'tags_tag_idx' => 'tag',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::uniqueIndexes() lorsque le
		 * modèle n'est pas lié à une table et qu'une exception est renvoyée.
		 *
		 * @expectedException RuntimeException
		 *
		 * @covers DatabaseTableBehavior::uniqueIndexes
		 */
		public function testUniqueIndexesException() {
			$this->Post->Tag->useTable = false;
			$this->Post->Tag->uniqueIndexes();
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::uniqueIndexes()
		 * avec activation du cache.
		 *
		 * Première partie, écriture dans le cache.
		 *
		 * @covers DatabaseTableBehavior::uniqueIndexes
		 */
		public function testCachedUniqueIndexesWrite() {
			Configure::write( 'Cache.disable', false );

			$result = $this->Post->Tag->uniqueIndexes();
			$expected = array (
				'PRIMARY' => 'id',
				'tags_tag_idx' => 'tag',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::uniqueIndexes()
		 * avec activation du cache.
		 *
		 * Seconde partie, lecture du cache.
		 *
		 * @covers DatabaseTableBehavior::uniqueIndexes
		 */
		public function testCachedUniqueIndexesRead() {
			Configure::write( 'Cache.disable', false );
			$cacheKey = cacheKey( array( $this->Post->Tag->useDbConfig, 'DatabaseTableBehavior', $this->Post->Tag->alias ) );

			$result = Cache::read( $cacheKey );
			$expected = array(
				'Tag' => array(
					'PRIMARY' => 'id',
					'tags_tag_idx' => 'tag',
				)
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = $this->Post->Tag->uniqueIndexes();
			$expected = array (
				'PRIMARY' => 'id',
				'tags_tag_idx' => 'tag',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			Configure::write( 'debug', 2 );
			Configure::write( 'Cache.disable', true );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::joinAssociationData()
		 *
		 * @covers DatabaseTableBehavior::joinAssociationData
		 * @covers DatabaseTableBehavior::_whichHabtmModel
		 */
		public function testJoinAssociationData() {
			// belongsTo
			$result = $this->Post->joinAssociationData( 'Author' );
			$expected = array(
				'className' => 'TestAuthor',
				'foreignKey' => 'author_id',
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'counterCache' => '',
				'association' => 'belongsTo',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// hasMany
			$result = $this->Post->joinAssociationData( 'Comment' );
			$expected = array(
				'className' => 'TestComment',
				'foreignKey' => 'post_id',
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'dependent' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => '',
				'association' => 'hasMany',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// hasAndBelongsToMany
			$result = $this->Post->joinAssociationData( 'TestPostTag' );
			$expected = array(
				'className' => 'TestTag',
				'joinTable' => 'posts_tags',
				'foreignKey' => 'post_id',
				'associationForeignKey' => 'tag_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'TestPostTag',
				'association' => 'hasAndBelongsToMany',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::joinAssociationData() lorsque le
		 * modèle n'est pas lié à une table et qu'une exception est renvoyée.
		 *
		 * @expectedException RuntimeException
		 *
		 * @covers DatabaseTableBehavior::joinAssociationData
		 * @covers DatabaseTableBehavior::_whichHabtmModel
		 */
		public function testJoinAssociationDataExceptionNoModelTable() {
			$this->Post->useTable = false;
			$this->Post->joinAssociationData( 'Author' );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::joinAssociationData() lorsque le
		 * modèle n'est pas lié à l'autre modèle et qu'une exception est renvoyée.
		 *
		 * @expectedException RuntimeException
		 *
		 * @covers DatabaseTableBehavior::joinAssociationData
		 * @covers DatabaseTableBehavior::_whichHabtmModel
		 */
		public function testJoinAssociationDataExceptionNotBoundAssociatedModel() {
			$this->Post->joinAssociationData( 'Site' );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::joinAssociationData() lorsque le
		 * modèle n'est pas lié à l'autre modèle et qu'une exception est renvoyée.
		 *
		 * @expectedException RuntimeException
		 *
		 * @covers DatabaseTableBehavior::joinAssociationData
		 * @covers DatabaseTableBehavior::_whichHabtmModel
		 */
		public function testJoinAssociationDataExceptionNoAssociatedModelTable() {
			$this->Post->Author->useTable = false;
			$this->Post->joinAssociationData( 'Author' );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::joinAssociationData() lorsque le
		 * modèle et l'autre modèle n'utilisent pas le même et qu'une exception
		 * est renvoyée.
		 *
		 * @expectedException RuntimeException
		 *
		 * @covers DatabaseTableBehavior::joinAssociationData
		 * @covers DatabaseTableBehavior::_whichHabtmModel
		 */
		public function testJoinAssociationDataExceptionDifferentDbConfigs() {
			$this->Post->useDbConfig = 'foo';
			$this->Post->joinAssociationData( 'Author' );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::join()
		 *
		 * @covers DatabaseTableBehavior::join
		 */
		public function testJoin() {
			// belongsTo
			$result = $this->Post->join( 'Author' );
			$expected = array(
				'table' => '"authors"',
				'alias' => 'Author',
				'type' => 'LEFT',
				'conditions' => '"Post"."author_id" = "Author"."id"'
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// belongsTo avec clés supplémentaires
			$result = $this->Post->join(
				'Author',
				array(
					'type' => 'INNER',
					'conditions' => array( 'Author.id >' => 4 )
				)
			);
			$expected = array(
				'table' => '"authors"',
				'alias' => 'Author',
				'type' => 'INNER',
				'conditions' => '"Post"."author_id" = "Author"."id" AND "Author"."id" > 4',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// hasMany
			$result = $this->Post->join( 'Comment' );
			$expected = array(
				'table' => '"comments"',
				'alias' => 'Comment',
				'type' => 'LEFT',
				'conditions' => '"Comment"."post_id" = "Post"."id"'
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// hasAndBelongsToMany
			$result = $this->Post->join( 'TestPostTag' );
			$expected = array(
				'table' => '"posts_tags"',
				'alias' => 'TestPostTag',
				'type' => 'LEFT',
				'conditions' => '"TestPostTag"."post_id" = "Post"."id"',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::types()
		 *
		 * @covers DatabaseTableBehavior::types
		 */
		public function testTypes() {
			$result = $this->Post->types();
			$expected = array(
				'Post.id' => 'integer',
				'Post.author_id' => 'integer',
				'Post.title' => 'string',
				'Post.body' => 'text',
				'Post.published' => 'string',
				'Post.created' => 'datetime',
				'Post.updated' => 'datetime',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::types() lorsque le
		 * modèle n'est pas lié à une table et qu'une exception est renvoyée.
		 *
		 * @expectedException RuntimeException
		 *
		 * @covers DatabaseTableBehavior::types
		 */
		public function testTypesException() {
			$this->Post->useTable = false;
			$this->Post->types();
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::joins() sur un niveau.
		 *
		 * @covers DatabaseTableBehavior::joins
		 */
		public function testJoinsEmpty() {
			$result = $this->Post->joins( array() );
			$expected = array();
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::joins() sur un niveau.
		 *
		 * @covers DatabaseTableBehavior::joins
		 */
		public function testJoinsOneLevel() {
			$result = $this->Post->joins(
				array(
					'Author',
					'Comment' => array( 'type' => 'INNER' )
				)
			);
			$expected = array(
				array(
					'table' => '"authors"',
					'alias' => 'Author',
					'type' => 'LEFT',
					'conditions' => '"Post"."author_id" = "Author"."id"'
				),
				array(
					'table' => '"comments"',
					'alias' => 'Comment',
					'type' => 'INNER',
					'conditions' => '"Comment"."post_id" = "Post"."id"'
				),
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::joins() sur plusieurs niveaux,
		 * avec des conditions supplémentaires et une jointure "ad-hoc" complètement
		 * spécifiée.
		 *
		 * @covers DatabaseTableBehavior::joins
		 */
		public function testJoinsTwoLevels() {
			$result = $this->Post->joins(
				array(
					'Author',
					'Comment' => array( 'type' => 'INNER' ),
					array(
						'table' => '"users"',
						'alias' => 'User',
						'type' => 'LEFT OUTER',
						'conditions' => '"Comment"."user_id" = "User"."id"',
					),
					'TestPostTag' => array(
						'type' => 'LEFT OUTER',
						'joins' => array(
							'Tag' => array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'1 = 2'
								)
							)
						)
					)
				)
			);
			$expected = array(
				array(
					'table' => '"authors"',
					'alias' => 'Author',
					'type' => 'LEFT',
					'conditions' => '"Post"."author_id" = "Author"."id"'
				),
				array(
					'table' => '"comments"',
					'alias' => 'Comment',
					'type' => 'INNER',
					'conditions' => '"Comment"."post_id" = "Post"."id"'
				),
				array(
					'table' => '"users"',
					'alias' => 'User',
					'type' => 'LEFT OUTER',
					'conditions' => '"Comment"."user_id" = "User"."id"',
				),
				array(
					'table' => '"posts_tags"',
					'alias' => 'TestPostTag',
					'type' => 'LEFT OUTER',
					'conditions' => '"TestPostTag"."post_id" = "Post"."id"'
				),
				array(
					'table' => '"tags"',
					'alias' => 'Tag',
					'type' => 'LEFT OUTER',
					'conditions' => '"TestPostTag"."tag_id" = "Tag"."id" AND 1 = 2'
				)
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}
	}
?>