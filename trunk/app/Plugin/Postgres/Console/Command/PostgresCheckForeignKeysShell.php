<?php
	/**
	 * Code source de la classe PostgresCheckForeignKeysShell.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	// @codeCoverageIgnoreStart
	App::uses( 'PostgresAbstractShell', 'Postgres.Console/Command' );
	App::uses( 'PostgresForeignKeys', 'Postgres.Utility' );
	// @codeCoverageIgnoreEnd

	/**
	 * La classe PostgresCheckForeignKeysShell fait la vérification de la
	 * correspondance entre les clés étrangères définies en base de données et
	 * les clés étrangères définies dans les relations entre modèles.
	 *
	 * @package Postgres
	 * @subpackage Console.Command
	 */
	class PostgresCheckForeignKeysShell extends PostgresAbstractShell
	{
		/**
		 * Description courte du shell
		 *
		 * @var string
		 */
		public $description = 'Shell de vérification de clés étrangères PostgreSQL / CakePHP';

		/**
		 * Liste des sous-commandes et de leur description.
		 *
		 * @var array
		 */
		public $commands = array(
			'missing' => array(
				'help' => 'Recherche des clés étrangères présentes au niveau des modèles mais absentes de la base de données.'
			)
		);

		/**
		 * Liste des options et de leur description.
		 *
		 * @var array
		 */
		public $options = array(
			'connection' => array(
				'short' => 'c',
				'help' => 'Le nom de la connection à la base de données',
				'default' => 'default',
			)
		);

		/**
		 * Recherche des clés étrangères présentes au niveau des modèles mais
		 * absentes de la base de données.
		 */
		public function missing() {
			$missing = PostgresForeignKeys::missing( $this->params['connection'] );

			if( false === empty( $missing ) ) {
				$message = sprintf( 'Clés étrangères manquantes en base de données (connexion "%s") mais présentes dans les modèles', $this->params['connection'] );
				$this->err( $message );

				ksort( $missing );
				foreach( $missing as $fromTable => $details ) {
					ksort( $details );
					foreach( $details as $foreignKey => $toTable ) {
						$message = sprintf(
							"\tdepuis <info>%s</info> vers <info>%s</info>, champ <info>%s</info>",
							$fromTable,
							$toTable,
							$foreignKey
						);
						$this->err( $message );
					}
				}
			}
			else {
				$message = sprintf( 'Aucune clé étrangère manquante en base de données (connexion "%s")', $this->params['connection'] );
				$this->out( $message );
			}

			$this->_stop( true === empty( $missing ) ? self::SUCCESS : self::ERROR );
		}
	}
?>