<?php
	/**
	 * Code source de la classe FusionConvDebuggerComponent.
	 *
	 * PHP 5.3
	 *
	 * @package FusionConv
	 * @subpackage Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe FusionConvDebuggerComponent ...
	 *
	 * @package FusionConv
	 * @subpackage Controller.Component
	 */
	class FusionConvDebuggerComponent extends Component
	{
		/**
		 * Contrôleur utilisant ce component.
		 *
		 * @var Controller
		 */
		public $Controller = null;

		/**
		 * Paramètres de ce component
		 *
		 * @var array
		 */
		public $settings = array( );

		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array( );

		/**
		 * Appelée avant Controller::beforeFilter().
		 *
		 * @param Controller $controller Controller with components to initialize
		 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
		 */
		public function initialize( Controller $controller ) {
			parent::initialize( $controller );
			$this->Controller = $controller;
		}

		/**
		 *
		 * @param GDO_PartType $oMainPart
		 */
		public function toCsv( GDO_PartType $oMainPart ) {
			if( Configure::read( 'debug' ) > 0 ) { // TODO: avec une configuration
				CakePlugin::load( 'FusionConv', array( 'bootstrap' => false ) );
				App::uses( 'FusionConvDebugger', 'FusionConv.Utility' );
				App::uses( 'File', 'Utility' );

				$hashPathsToCsv = FusionConvDebugger::hashPathsToCsv( $oMainPart );
				$allPathsToCsv = FusionConvDebugger::allPathsToCsv( $oMainPart, true );

				// TODO: une méthode
				$params = $this->Controller->request->params;
				$controllerName = Inflector::camelize( $params['controller'] ); // TODO: plugin
				$actionName = $params['action'];
				$pathStart = "{$controllerName}_{$actionName}";

				$params = implode( '_', $params['pass'] );
				// unset( $params['named'] ); // TODO: named


				$File = new File( TMP.'logs'.DS."{$pathStart}_hashPaths_{$params}.csv", true );
				$File->write( $hashPathsToCsv );

				$File = new File( TMP.'logs'.DS."{$pathStart}_allPaths_{$params}.csv", true );
				$File->write( $allPathsToCsv );

				// TODO: pas de debug/die ou alors par config ?
//				debug( $hashPathsToCsv );
//				debug( $allPathsToCsv );
//				die();
			}
		}
	}
?>