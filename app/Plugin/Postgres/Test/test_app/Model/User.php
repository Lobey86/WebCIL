<?php
	/**
	 * Code source de la classe User.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe User ...
	 *
	 * @package Postgres
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

		public $useTable = 'postgres_users';

		/**
		 * Modèles possèdent une clef étrangère vers ce modèle
		 * @var array
		 */
		public $belongsTo = array(
			'Group' => array(
				'className' => 'Group',
				'foreignKey' => 'group_id',
				'conditions' => null,
				'fields' => null,
				'order' => null
			),
		);
	}
?>