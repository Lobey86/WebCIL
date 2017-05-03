<?php
	/**
	 * Code source des classes de blog utilisées dans les tests unitaires du plugin
	 * Database.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Test.Case
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * TestPost class
	 *
	 * @package Database
	 * @subpackage Test.Case
	 */
	class TestPost extends CakeTestModel
	{
		/**
		 * name property
		 *
		 * @var string 'PaginatorControllerPost'
		 */
		public $name = 'TestPost';

		/**
		 * useTable property
		 *
		 * @var string 'posts'
		 */
		public $useTable = 'posts';

		/**
		 * belongsTo property
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Author' => array(
				'className' => 'TestAuthor',
				'foreignKey' => 'author_id'
			)
		);

		/**
		 * hasMany property
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Comment' => array(
				'className' => 'TestComment',
				'foreignKey' => 'post_id'
			)
		);

		/**
		 * hasAndBelongsToMany property
		 *
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
			'Tag' => array(
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
				'with' => 'TestPostTag'
			)
		);
	}

	/**
	 * TestPost class
	 *
	 * @package Database
	 * @subpackage Test.Case
	 */
	class TestTag extends CakeTestModel
	{
		/**
		 * name property
		 *
		 * @var string
		 */
		public $name = 'TestTag';

		/**
		 * useTable property
		 *
		 * @var string 'posts'
		 */
		public $useTable = 'tags';

		/**
		 * hasAndBelongsToMany property
		 *
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
			'Post' => array(
				'className' => 'TestPost',
				'joinTable' => 'posts_tags',
				'foreignKey' => 'tag_id',
				'associationForeignKey' => 'post_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'TestPostTag'
			)
		);
	}

	/**
	 * TestPost class
	 *
	 * @package Database
	 * @subpackage Test.Case
	 */
	class TestPostTag extends CakeTestModel
	{
		/**
		 * name property
		 *
		 * @var string
		 */
		public $name = 'TestPostTag';

		/**
		 * useTable property
		 *
		 * @var string 'posts'
		 */
		public $useTable = 'posts_tags';

		/**
		 * belongsTo property
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Post' => array(
				'className' => 'TestPost',
				'foreignKey' => 'post_id'
			),
			'Tag' => array(
				'className' => 'TestTag',
				'foreignKey' => 'tag_id'
			),
		);
	}

	/**
	 * TestComment class
	 *
	 * @package Database
	 * @subpackage Test.Case
	 */
	class TestComment extends CakeTestModel
	{

		/**
		 * name property
		 *
		 * @var string 'PaginatorControllerPost'
		 */
		public $name = 'TestComment';

		/**
		 * useTable property
		 *
		 * @var string 'posts'
		 */
		public $useTable = 'comments';

		/**
		 * belongsTo property
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Post' => array(
				'className' => 'TestPost',
				'foreignKey' => 'post_id'
			)
		);
	}

	/**
	 * TestAuthor class
	 *
	 * @package Database
	 * @subpackage Test.Case
	 */
	class TestAuthor extends CakeTestModel
	{

		/**
		 * name property
		 *
		 * @var string
		 */
		public $name = 'TestAuthor';

		/**
		 * useTable property
		 *
		 * @var string 'posts'
		 */
		public $useTable = 'authors';

		/**
		 * hasMany property
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Post' => array(
				'className' => 'TestPost',
				'foreignKey' => 'author_id'
			)
		);
	}

	// -------------------------------------------------------------------------

//	/**
//	 * TestPost class
//	 *
//	 * @package Database
//	 * @subpackage Test.Case.Utility
//	 */
//	class TestPost extends CakeTestModel
//	{
//		/**
//		 * name property
//		 *
//		 * @var string 'PaginatorControllerPost'
//		 */
//		public $name = 'TestPost';
//
//		/**
//		 * useTable property
//		 *
//		 * @var string 'posts'
//		 */
//		public $useTable = 'posts';
//
//		/**
//		 * belongsTo property
//		 *
//		 * @var array
//		 */
//		public $belongsTo = array(
//			'Author' => array(
//				'className' => 'TestAuthor',
//				'foreignKey' => 'author_id'
//			)
//		);
//
//		/**
//		 * hasMany property
//		 *
//		 * @var array
//		 */
//		public $hasMany = array(
//			'Comment' => array(
//				'className' => 'TestComment',
//				'foreignKey' => 'post_id'
//			)
//		);
//
//		/**
//		 * hasAndBelongsToMany property
//		 *
//		 * @var array
//		 */
//		public $hasAndBelongsToMany = array(
//			'Tag' => array(
//				'className' => 'TestTag',
//				'joinTable' => 'posts_tags',
//				'foreignKey' => 'post_id',
//				'associationForeignKey' => 'tag_id',
//				'unique' => true,
//				'conditions' => '',
//				'fields' => '',
//				'order' => '',
//				'limit' => '',
//				'offset' => '',
//				'finderQuery' => '',
//				'deleteQuery' => '',
//				'insertQuery' => '',
//				'with' => 'TestPostTag'
//			)
//		);
//	}
//
//	/**
//	 * TestPost class
//	 *
//	 * @package Database
//	 * @subpackage Test.Case.Utility
//	 */
//	class TestTag extends CakeTestModel
//	{
//		/**
//		 * name property
//		 *
//		 * @var string
//		 */
//		public $name = 'TestTag';
//
//		/**
//		 * useTable property
//		 *
//		 * @var string 'posts'
//		 */
//		public $useTable = 'tags';
//
//		/**
//		 * hasAndBelongsToMany property
//		 *
//		 * @var array
//		 */
//		public $hasAndBelongsToMany = array(
//			'Post' => array(
//				'className' => 'TestPost',
//				'joinTable' => 'posts_tags',
//				'foreignKey' => 'tag_id',
//				'associationForeignKey' => 'post_id',
//				'unique' => true,
//				'conditions' => '',
//				'fields' => '',
//				'order' => '',
//				'limit' => '',
//				'offset' => '',
//				'finderQuery' => '',
//				'deleteQuery' => '',
//				'insertQuery' => '',
//				'with' => 'TestPostTag'
//			)
//		);
//	}
//
//	/**
//	 * TestPost class
//	 *
//	 * @package Database
//	 * @subpackage Test.Case.Utility
//	 */
//	class TestPostTag extends CakeTestModel
//	{
//		/**
//		 * name property
//		 *
//		 * @var string
//		 */
//		public $name = 'TestPostTag';
//
//		/**
//		 * useTable property
//		 *
//		 * @var string 'posts'
//		 */
//		public $useTable = 'posts_tags';
//
//		/**
//		 * belongsTo property
//		 *
//		 * @var array
//		 */
//		public $belongsTo = array(
//			'Post' => array(
//				'className' => 'TestPost',
//				'foreignKey' => 'post_id'
//			),
//			'Tag' => array(
//				'className' => 'TestTag',
//				'foreignKey' => 'tag_id'
//			),
//		);
//	}
//
//	/**
//	 * TestComment class
//	 *
//	 * @package Database
//	 * @subpackage Test.Case.Utility
//	 */
//	class TestComment extends CakeTestModel
//	{
//
//		/**
//		 * name property
//		 *
//		 * @var string 'PaginatorControllerPost'
//		 */
//		public $name = 'TestComment';
//
//		/**
//		 * useTable property
//		 *
//		 * @var string 'posts'
//		 */
//		public $useTable = 'comments';
//
//		/**
//		 * belongsTo property
//		 *
//		 * @var array
//		 */
//		public $belongsTo = array(
//			'Post' => array(
//				'className' => 'TestPost',
//				'foreignKey' => 'post_id'
//			)
//		);
//	}
//
//	/**
//	 * TestAuthor class
//	 *
//	 * @package Database
//	 * @subpackage Test.Case.Utility
//	 */
//	class TestAuthor extends CakeTestModel
//	{
//
//		/**
//		 * name property
//		 *
//		 * @var string
//		 */
//		public $name = 'TestAuthor';
//
//		/**
//		 * useTable property
//		 *
//		 * @var string 'posts'
//		 */
//		public $useTable = 'authors';
//
//		/**
//		 * hasMany property
//		 *
//		 * @var array
//		 */
//		public $hasMany = array(
//			'Post' => array(
//				'className' => 'TestPost',
//				'foreignKey' => 'author_id'
//			)
//		);
//	}

	// -------------------------------------------------------------------------
?>
