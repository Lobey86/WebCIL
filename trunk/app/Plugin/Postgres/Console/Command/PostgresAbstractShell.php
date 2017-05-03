<?php
	/**
	 * Code source de la classe PostgresAbstractShell.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	// @codeCoverageIgnoreStart
	App::uses( 'AppShell', 'Console/Command' );
	App::uses( 'ConnectionManager', 'Model' );
	// @codeCoverageIgnoreEnd

	/**
	 * La classe PostgresAbstractShell est la classe de base des shells du plugin
	 * Postgres.
	 *
	 * Elle permet de s'assurer que l'utilisateur lançant le shell soit bien
	 * l'utilisateur du serveur web (voir $acceptedUsers) et que la connection à
	 * la base de données soit bien Postgres (ou une de ses sous-classes).
	 *
	 * @package Postgres
	 * @subpackage Console.Command
	 */
	class PostgresAbstractShell extends AppShell
	{
		/**
		 * La constante à utiliser dans la méthode _stop() en cas de succès.
		 */
		const SUCCESS = 0;

		/**
		 * La constante à utiliser dans la méthode _stop() en cas d'erreur.
		 */
		const ERROR = 1;

		/**
		 * La connexion vers la base de données.
		 *
		 * @var DboSource
		 */
		public $Dbo = null;

		/**
		 * Description courte du shell
		 *
		 * @var string
		 */
		public $description = null;

		/**
		 * Liste des sous-commandes et de leur description.
		 *
		 * @var array
		 */
		public $commands = array();

		/**
		 * Liste des options et de leur description.
		 *
		 * @var array
		 */
		public $options = array();

		/**
		 * Liste de noms d'utilisateurs ayant la possibilité de lancer des shells.
		 *
		 * @var array
		 */
		public $acceptedUsers = array( 'www-data', 'apache', 'httpd', 'jenkins' );

		/**
		 * Vérifie que l'utilisateur qui lance le shell soit bien le même que
		 * l'utilisateur du serveur web, afin d'éviter les problèmes de droits
		 * sur les fichiers du cache ou les fichiers temporaires.
		 */
		public function checkCliUser() {
			$whoami = exec( 'whoami' );

			if( false === in_array( $whoami, $this->acceptedUsers ) ) {
				$msgstr = 'Mauvais utilisateur (%s), veuillez exécuter ce shell en tant que: %s';

				$Parser = $this->getOptionParser();
				$command = $Parser->command();

				$this->error(
					sprintf( $msgstr, $whoami, implode( ', ', $this->acceptedUsers ) ),
					"<info>Exemple:</info> sudo -u {$this->acceptedUsers[0]} lib/Cake/Console/cake {$command} [...]"
				);
			}
		}

		/**
		 * Surcharge de la méthode pour vérifier que l'utilisateur qui lance la
		 * commande soit le même que l'utilisateur du serveur web.
		 *
		 * @see AppShell::checkCliUser()
		 */
		public function startup() {
			parent::startup();

			$this->checkCliUser();

			$this->params['connection'] = ( isset( $this->params['connection'] ) ? $this->params['connection'] : 'default' );

			try {
				$this->Dbo = ConnectionManager::getDataSource( $this->params['connection'] );
			} catch( Exception $Exception ) {
				 $this->log( $Exception->getMessage(), LOG_ERR );
			}

			if( !is_a( $this->Dbo, 'DataSource' ) || !$this->Dbo->connected ) {
				$this->err( "Impossible de se connecter avec la connexion {$this->params['connection']}" );
				$this->_stop( self::ERROR );
				return;
			}

			if( !( $this->Dbo instanceof Postgres ) ) {
				$this->err( "La connexion {$this->params['connection']} n'utilise pas le driver postgres" );
				$this->_stop( self::ERROR );
				return;
			}

			$result = $this->Dbo->query( 'SELECT version();' );
			$this->out( Hash::get( $result, '0.0.version' ) );
			$this->hr();
			$this->out();
		}

		/**
		 * Paramétrages et aides du shell.
		 */
		public function getOptionParser() {
			$Parser = parent::getOptionParser();

			$Parser->description( $this->description );
			$Parser->addSubcommands( $this->commands );
			$Parser->addOptions( $this->options );

			return $Parser;
		}
	}
?>