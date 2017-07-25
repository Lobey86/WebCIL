<?php
	/**
	 * Code source de la classe RoleDroitFixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	/**
	 * La classe RoleDroitFixture ...
	 *
	 * @package app.Test.Fixture
	 */
	class RoleDroitFixture extends CakeTestFixture
	{

		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = [
			'model' => 'RoleDroit',
			'records' => false
		];

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = [
			1 => [
				'role_id' => 1,
				'liste_droit_id' => 1
			],
			2 => [
				'role_id' => 1,
				'liste_droit_id' => 4
			],
			3 => [
				'role_id' => 1,
				'liste_droit_id' => 7
			],
			4 => [
				'role_id' => 2,
				'liste_droit_id' => 2
			],
			5 => [
				'role_id' => 2,
				'liste_droit_id' => 4
			],
			6 => [
				'role_id' => 2,
				'liste_droit_id' => 7
			],
			7 => [
				'role_id' => 3,
				'liste_droit_id' => 3
			],
			8 => [
				'role_id' => 3,
				'liste_droit_id' => 4
			],
			9 => [
				'role_id' => 4,
				'liste_droit_id' => 1
			],
			10 => [
				'role_id' => 4,
				'liste_droit_id' => 2
			],
			11 => [
				'role_id' => 4,
				'liste_droit_id' => 3
			],
			12 => [
				'role_id' => 4,
				'liste_droit_id' => 4
			],
			13 => [
				'role_id' => 4,
				'liste_droit_id' => 7
			],
			14 => [
				'role_id' => 4,
				'liste_droit_id' => 8
			],
			15 => [
				'role_id' => 4,
				'liste_droit_id' => 9
			],
			16 => [
				'role_id' => 4,
				'liste_droit_id' => 10
			],
			17 => [
				'role_id' => 4,
				'liste_droit_id' => 11
			],
			18 => [
				'role_id' => 4,
				'liste_droit_id' => 12
			],
			19 => [
				'role_id' => 4,
				'liste_droit_id' => 13
			],
			20 => [
				'role_id' => 4,
				'liste_droit_id' => 14
			],
			21 => [
				'role_id' => 4,
				'liste_droit_id' => 15
			],
			22 => [
				'role_id' => 5,
				'liste_droit_id' => 1
			],
			23 => [
				'role_id' => 5,
				'liste_droit_id' => 4
			],
			24 => [
				'role_id' => 5,
				'liste_droit_id' => 7
			],
			25 => [
				'role_id' => 6,
				'liste_droit_id' => 2
			],
			26 => [
				'role_id' => 6,
				'liste_droit_id' => 4
			],
			27 => [
				'role_id' => 6,
				'liste_droit_id' => 7
			],
			28 => [
				'role_id' => 7,
				'liste_droit_id' => 3
			],
			29 => [
				'role_id' => 7,
				'liste_droit_id' => 4
			],
			30 => [
				'role_id' => 8,
				'liste_droit_id' => 1
			],
			31 => [
				'role_id' => 8,
				'liste_droit_id' => 2
			],
			32 => [
				'role_id' => 8,
				'liste_droit_id' => 3
			],
			33 => [
				'role_id' => 8,
				'liste_droit_id' => 4
			],
			34 => [
				'role_id' => 8,
				'liste_droit_id' => 7
			],
			35 => [
				'role_id' => 8,
				'liste_droit_id' => 8
			],
			36 => [
				'role_id' => 8,
				'liste_droit_id' => 9
			],
			37 => [
				'role_id' => 8,
				'liste_droit_id' => 10
			],
			38 => [
				'role_id' => 8,
				'liste_droit_id' => 11
			],
			39 => [
				'role_id' => 8,
				'liste_droit_id' => 12
			],
			40 => [
				'role_id' => 8,
				'liste_droit_id' => 13
			],
			41 => [
				'role_id' => 8,
				'liste_droit_id' => 14
			],
			42 => [
				'role_id' => 8,
				'liste_droit_id' => 15
			],
			43 => [
				'role_id' => 9,
				'liste_droit_id' => 1
			],
			44 => [
				'role_id' => 9,
				'liste_droit_id' => 4
			],
			45 => [
				'role_id' => 9,
				'liste_droit_id' => 7
			],
			46 => [
				'role_id' => 10,
				'liste_droit_id' => 2
			],
			47 => [
				'role_id' => 10,
				'liste_droit_id' => 4
			],
			48 => [
				'role_id' => 10,
				'liste_droit_id' => 7
			],
			49 => [
				'role_id' => 11,
				'liste_droit_id' => 3
			],
			50 => [
				'role_id' => 11,
				'liste_droit_id' => 4
			],
			51 => [
				'role_id' => 12,
				'liste_droit_id' => 1
			],
			52 => [
				'role_id' => 12,
				'liste_droit_id' => 2
			],
			53 => [
				'role_id' => 12,
				'liste_droit_id' => 3
			],
			54 => [
				'role_id' => 12,
				'liste_droit_id' => 4
			],
			55 => [
				'role_id' => 12,
				'liste_droit_id' => 7
			],
			56 => [
				'role_id' => 12,
				'liste_droit_id' => 8
			],
			57 => [
				'role_id' => 12,
				'liste_droit_id' => 9
			],
			58 => [
				'role_id' => 12,
				'liste_droit_id' => 10
			],
			59 => [
				'role_id' => 12,
				'liste_droit_id' => 11
			],
			60 => [
				'role_id' => 12,
				'liste_droit_id' => 12
			],
			61 => [
				'role_id' => 12,
				'liste_droit_id' => 13
			],
			62 => [
				'role_id' => 12,
				'liste_droit_id' => 14
			],
			63 => [
				'role_id' => 12,
				'liste_droit_id' => 15
			]
		];

	}
?>