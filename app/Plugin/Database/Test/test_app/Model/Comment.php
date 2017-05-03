<?php
	/**
	 * Code source de la classe Comment.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Comment ...
	 *
	 * @package Database
	 * @subpackage Model
	 */
	class Comment extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Comment';

		/**
		 * Possède des clefs étrangères vers d'autres modèles
		 * @var array
		 */
        public $belongsTo = array(
			'Post' => array(
				'className' => 'Post',
				'foreignKey' => 'post_id',
				'dependent' => true,
			),
        );
	}
?>