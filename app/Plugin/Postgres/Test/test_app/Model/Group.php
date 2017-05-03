<?php
	/**
	 * Code source de la classe Group.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Group ...
	 *
	 * @package Postgres
	 * @subpackage Model
	 */
	class Group extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Group';

		public $useTable = 'postgres_groups';

		/**
		 * Modèles possèdent une clef étrangère vers ce modèle
		 * @var array
		 */
		public $hasMany = array(
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'group_id',
				'dependent' => true,
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'exclusive' => null,
				'finderQuery' => null,
				'counterQuery' => null
			),
		);
	}
?>