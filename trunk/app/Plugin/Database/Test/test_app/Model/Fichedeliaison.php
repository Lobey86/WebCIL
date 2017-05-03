<?php
	/**
	 * Code source de la classe Fichedeliaison.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Fichedeliaison ...
	 *
	 * @package Database
	 * @subpackage Model
	 */
	class Fichedeliaison extends AppModel
	{

		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Fichedeliaison';

		/**
		 * Nom de la table utilisée.
		 *
		 * @var string
		 */
		public $useTable = 'fichesdeliaison';

		/**
		 * Possède des clefs étrangères vers d'autres modèles
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Expediteur' => array(
				'className' => 'Service66',
				'foreignKey' => 'destinataire_id'
			)
		);
	}
?>