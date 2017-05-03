<?php
	/**
	 * Code source de la classe User.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe User ...
	 *
	 * @package Database
	 * @subpackage Model
	 */
	class User extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'User';

		/**
		 * Modèles possèdent une clef étrangère vers ce modèle
		 * @var array
		 */
		public $hasMany = array(
			'Post' => array(
				'className' => 'Post',
				'foreignKey' => 'post_id',
				'dependent' => true,
				'conditions' => array(),
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
		);
	}
?>