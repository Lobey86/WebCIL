<?php
	/**
	 * Code source de la classe Service66.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Service66 ...
	 *
	 * @package Database
	 * @subpackage Model
	 */
	class Service66 extends AppModel
	{

		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Service66';

		/**
		 * Nom de la table utilisée.
		 *
		 * @var string
		 */
		public $useTable = 'services66';

		/**
		 * Modèles possèdent une clef étrangère vers ce modèle
		 *
		 * @var array
		 */
		public $hasMany = array(
			'FichedeliaisonDestinataire' => array(
				'className' => 'Fichedeliaison',
				'foreignKey' => 'destinataire_id'
			)
		);
	}
?>