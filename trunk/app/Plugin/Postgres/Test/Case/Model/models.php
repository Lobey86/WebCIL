<?php
	/**
	 * Mock models file
	 *
	 * Mock classes for use in Model and related test cases
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Model
	 */
	App::uses( 'Model', 'Model' );

	/**
	 * AppModel class
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Model
	 */
	class AppModel extends Model
	{
	}

	class PostgresGroup extends AppModel
	{
		public $hasMany = array(
			'User' => array(
				'className' => 'PostgresUser',
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

	class PostgresUser extends AppModel
	{
		public $belongsTo = array(
			'Group' => array(
				'className' => 'PostgresGroup',
				'foreignKey' => 'group_id',
				'conditions' => null,
				'fields' => null,
				'order' => null
			),
		);
	}
?>