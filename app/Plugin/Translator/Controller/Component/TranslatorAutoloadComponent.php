<?php
	/**
	 * Code source de la classe TranslatorAutoloadComponent.
	 *
	 * @package Translator
	 * @subpackage Component
	 */
	App::uses('Component', 'Controller');
	App::uses('Translator', 'Translator.Utility');

	/**
	 * La classe TranslatorAutoloadComponent ...
	 *
	 * @package Translator
	 * @subpackage Component
	 */
	class TranslatorAutoloadComponent extends Component
	{
		/**
		 * Name of the component.
		 *
		 * @var string
		 */
		public $name = 'TranslatorAutoload';

		/**
		 * Default configuration.
		 *
		 * @var array
		 */
		public $_defaultConfig = array(
			'translatorClass' => 'Translator',
			'events' => array(
				'initialize' => 'load',
				'startup' => null,
				'beforeRender' => null,
				'beforeRedirect' => 'save',
				'shutdown' => 'save'
			)
		);

		/**
		 * The list of domains to send to the translator class.
		 *
		 * @var array
		 */
		protected $_domains = null;

		/**
		 * The cache key to load and save the cache.
		 *
		 * @var string
		 */
		protected $_cacheKey = null;

		/**
		 * The instance of the translator class.
		 *
		 * @var TranslatorInterface
		 */
		protected $_translator = null;

		/**
		 * Called before the Controller::beforeFilter().
		 *
		 * @param Controller $controller Controller with components to initialize
		 * @return void
		 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
		 */
		public function initialize(Controller $controller) {
			parent::initialize($controller);
			$this->settings += $this->_defaultConfig;
			call_user_func(array($this->_translator(), 'domains'), $this->domains());

			$this->dispatchEvent(__FUNCTION__);
		}

		/**
		 * Donne une liste de domaines potentiels
		 *
		 * @return array
		 */
		public function domains() {
		   if ($this->_domains === null) {
			   $Controller = $this->_Collection->getController();

			   $controllerName = Inflector::underscore(Hash::get($Controller->request->params, 'controller'));
			   $actionName = Inflector::underscore(Hash::get($Controller->request->params, 'action'));
			   $pluginName = ltrim(Inflector::underscore(Hash::get($Controller->request->params, 'plugin')) . '_', '_');

			   $suffixConfigureKey = preg_replace( '/AutoloadComponent$/', '', __CLASS__ ).'.suffix';
			   $suffix = rtrim( '_'.Configure::read( $suffixConfigureKey ), '_' );

			   $this->_domains = array_values(
				   array_unique(
					   array(
						   $pluginName . $controllerName . '_' . $actionName . $suffix,
						   $pluginName . $controllerName . '_' . $actionName,
						   $controllerName . '_' . $actionName . $suffix,
						   $controllerName . '_' . $actionName,
						   $pluginName . $controllerName . $suffix,
						   $pluginName . $controllerName,
						   $controllerName . $suffix,
						   $controllerName,
						   'default' . $suffix,
						   'default'
					   )
				   )
			   );
		   }
		   return $this->_domains;
		}

		/**
		 * Returns the cache key to be used for the current URL.
		 *
		 * @return string
		 */
		public function cacheKey() {
		   if ($this->_cacheKey === null) {
			   $Controller = $this->_Collection->getController();
			   $pluginName = ltrim(Inflector::camelize(Hash::get($Controller->request->params, 'plugin')) . '.', '.');
			   $controllerName = Hash::get($Controller->request->params, 'controller');
			   $actionName = Hash::get($Controller->request->params, 'action');
			   $this->_cacheKey = "{$this->name}.{$pluginName}{$controllerName}.{$actionName}";
		   }
		   return $this->_cacheKey;
		}

		/**
		 * Returns the translator object, initializing it if needed.
		 *
		 * @return TranslatorInterface
		 * @throws RuntimeException
		 */
		protected function _translator() {
			if ($this->_translator === null) {
				$translatorClass = $this->settings['translatorClass'];
				if (!class_exists($translatorClass)) {
					$msg = sprintf(__d('cake_dev', 'Missing utility class %s'), $translatorClass);
					throw new RuntimeException($msg, 500);
				}
				if (!in_array('TranslatorInterface', class_implements($translatorClass))) {
					$msg = sprintf(__d('cake_dev', 'Utility class %s does not implement TranslatorInterface'), $translatorClass);
					throw new RuntimeException($msg, 500);
				}
				$this->_translator = $translatorClass::getInstance();
			}
			return $this->_translator;
		}

		/**
		 * Import the translation cache for the current domains.
		 *
		 * @return void
		 */
		public function load() {
			$translator = $this->_translator();
			$translator->domains($this->domains());
			$cacheKey = $this->cacheKey();
			$cache = Cache::read($cacheKey);
			if ($cache !== false) {
				$translator->import($cache);
			}
		}

		/**
		 * Export the translation cache for the current domains.
		 *
		 * @return void
		 */
		public function save() {
			$translator = $this->_translator();
			if ($translator->tainted()) {
				$cacheKey = $this->cacheKey();
				$cache = $translator->export();
				Cache::write($cacheKey, $cache);
			}
		}

		/**
		 * Dispatch an avent and call the corresponding component method when needed.
		 *
		 * @param string $eventName Le nom de la méthode de callback
		 * @return void
		 * @throws RuntimeException
		 */
		public function dispatchEvent($eventName) {
			$method = isset($this->settings['events'][$eventName]) ? $this->settings['events'][$eventName] : null;
			if (true === in_array($method, array('load', 'save'))) {
				call_user_func(array($this, $method));
			} elseif (null !== $method) {
				$msg = sprintf(__d('cake_dev', 'Method "%s" cannot be called. Use one of "load", "save"'), $method);
				throw new RuntimeException($msg, 500);
			}
		}

		/**
		 * Called after the Controller::beforeFilter() and before the controller action
		 *
		 * @param Controller $controller Controller with components to startup
		 * @return void
		 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::startup
		 */
		public function startup(Controller $controller) {
			parent::startup($controller);
			$this->dispatchEvent(__FUNCTION__);
		}

		/**
		 * Called before the Controller::beforeRender(), and before
		 * the view class is loaded, and before Controller::render()
		 *
		 * @param Controller $controller Controller with components to beforeRender
		 * @return void
		 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::beforeRender
		 */
		public function beforeRender(Controller $controller) {
			parent::beforeRender($controller);
			$this->dispatchEvent(__FUNCTION__);
		}

		/**
		 * Called after Controller::render() and before the output is printed to the browser.
		 *
		 * @param Controller $controller Controller with components to shutdown
		 * @return void
		 * @link @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::shutdown
		 */
		public function shutdown(Controller $controller) {
			parent::shutdown($controller);
			$this->dispatchEvent(__FUNCTION__);
		}

		/**
		 * Called before Controller::redirect().  Allows you to replace the url that will
		 * be redirected to with a new url. The return of this method can either be an array or a string.
		 *
		 * If the return is an array and contains a 'url' key.  You may also supply the following:
		 *
		 * - `status` The status code for the redirect
		 * - `exit` Whether or not the redirect should exit.
		 *
		 * If your response is a string or an array that does not contain a 'url' key it will
		 * be used as the new url to redirect to.
		 *
		 * @param Controller $controller Controller with components to beforeRedirect
		 * @param string|array $url Either the string or url array that is being redirected to.
		 * @param integer $status The status code of the redirect
		 * @param boolean $exit Will the script exit.
		 * @return array|null Either an array or null.
		 * @link @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::beforeRedirect
		 */
		public function beforeRedirect(Controller $controller, $url, $status = null, $exit = true) {
			parent::beforeRedirect($controller, $url, $status, $exit);
			$this->dispatchEvent(__FUNCTION__);
		}
	}