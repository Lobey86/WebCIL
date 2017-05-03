<?php
	/**
	 * Code source de la classe DatabaseCheckRelationsShell.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	// @codeCoverageIgnoreStart
	App::uses( 'DatabaseRelations', 'Database.Utility' );
	// @codeCoverageIgnoreEnd

	/**
	 * La classe DatabaseCheckRelationsShell parcourt les classes de modèles et
	 * vérifie que les relations entre eux soient bien définies dans les deux sens.
	 *
	 * @package Database
	 * @subpackage Console.Command
	 */
	class DatabaseCheckRelationsShell extends AppShell
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
		 * Obtient les relations manquantes entre les modèles.
		 *
		 * @return array
		 */
		protected function _getMissingRelations() {
			$relations = array();

			$modelNames = App::objects( 'Model' );
			foreach( $modelNames as $modelName ) {
				if( 'AppModel' !== $modelName ) {
					App::uses( $modelName, 'Model' );
					$Reflection = new ReflectionClass( $modelName );
					if( false === $Reflection->isAbstract() ) {
						$Model = ClassRegistry::init( $modelName );
						$relations[$modelName] = DatabaseRelations::relations( $Model );
					}
				}
			}

			return DatabaseRelations::missing( $relations );
		}

		/**
		 * Transforme les relations manquantes entre modèles en messages d'erreur.
		 *
		 * @param array $missing
		 * @return array
		 */
		protected function _getErrorMessages( array $missing ) {
			$errors = array();
			$msgstr = "Relation non définie: <error>%s</error> -> <error>%s</error>";

			if( isset( $missing['from'] ) && !empty( $missing['from'] ) ) {
				foreach( $missing['from'] as $from => $to ) {
					$errors[] = sprintf( $msgstr, $from, $to );
				}
			}

			if( isset( $missing['to'] ) && !empty( $missing['to'] ) ) {
				foreach( $missing['to'] as $from => $to ) {
					$errors[] = sprintf( $msgstr, $to, $from );
				}
			}

			return $errors;
		}

		/**
		 * Méthode principale.
		 */
		public function main() {
			$missing = $this->_getMissingRelations();
			$errors = $this->_getErrorMessages( $missing );

			// Affichage des relations manquantes
			if( !empty( $errors ) ) {
				sort( $errors );

				$this->err( sprintf( "<error>%d relation(s) non définie(s)</error>", count( $errors ) ) );

				foreach( $errors as $error ) {
					$this->err( "\t{$error}" );
				}

				$this->_stop( self::ERROR );
			}
			else {
				$this->out( "<success>Aucune relation manquante<success>" );
				$this->_stop( self::SUCCESS );
			}
		}

		/**
		 * Paramétrages et aides du shell.
		 */
		public function getOptionParser() {
			$Parser = parent::getOptionParser();
			return $Parser;
		}
	}
?>