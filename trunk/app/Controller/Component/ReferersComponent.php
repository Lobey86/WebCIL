<?php

/**
 * Code source de la classe ReferersComponent.
 *
 * PHP 5.3
 *
 * @package app.Controller.Component
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */
App::uses('Component', 'Controller');

/**
 * La classe ReferersComponent ...
 *
 * @package app.Controller.Component
 */
class ReferersComponent extends Component {

    /**
     * Paramètres de ce component
     *
     * @todo defaultSettings
     *
     * @var array
     */
    public $defaultSettings = array(
        'sessionKeyPrefix' => 'Referers',
        //@todo clearOn...
        'clearOnBeforeRedirect' => '/users/logout'
    );

    /**
     * Components utilisés par ce component.
     *
     * @var array
     */
    public $components = array('Session');

    /**
     *
     * @param ComponentCollection $collection
     * @param array $settings
     */
    public function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings + $this->defaultSettings);

        $this->settings['clearOnBeforeRedirect'] = (array)$this->settings['clearOnBeforeRedirect'];
    }

    /**
     *
     * @todo pas besoin du component
     * @todo App::uses Router
     *
     * @param string|array $url
     * @return string
     */
    public function normalizeUrl($url) {
        if (false === is_array($url)) {
            $params = Router::parse($url);
        } else {
            $params = $url;
        }

        $params['url'] = true === isset($params['url']) ? $params['url'] : array();
        $params['controller'] = Inflector::underscore($params['controller']);

        return Router::normalize(Router::reverse($params));
    }

    public function startup(Controller $controller) {
        $here = $this->normalizeUrl($controller->request->here(false));
        $referer = $this->normalizeUrl($controller->request->referer(true));
        $sessionKey = "{$this->settings['sessionKeyPrefix']}.{$here}";
        $stored = $this->Session->read($sessionKey);

        if (('/' !== $referer || null === $stored) && $stored !== $referer && $here !== $referer) {
            $this->Session->write($sessionKey, $referer);
        }

        // @fixme ?
        $controller->set('referer', $this->Session->read($sessionKey));
    }

    /**
     *
     * @param string|array $url
     * @return type
     */
    public function get($url = null) {//@todo: param defaults = /
        $controller = $this->_Collection->getController();

        if (null === $url) {
            $url = $controller->request->here(false);
        }
        $url = $this->normalizeUrl($url);

        $sessionKey = "{$this->settings['sessionKeyPrefix']}.{$url}";
        return $this->Session->read($sessionKey);
    }

    /**
     *
     * @return type
     */
    public function clear() {
        return $this->Session->delete($this->settings['sessionKeyPrefix']);
    }

    protected function _clearOnCallback($method) {
        $controller = $this->_Collection->getController();
        $key = Inflector::variable('clear_on_' . $method);

        if (true === isset($this->settings[$key]) && false === empty($this->settings[$key])) {
            $here = $this->normalizeUrl($controller->request->here(false));
            if (true === in_array($here, $this->settings[$key])) {
                $this->clear();
            }
        }
    }

    public function beforeRedirect(Controller $controller, $url, $status = null, $exit = true) {
        $this->_clearOnCallback(__FUNCTION__);
        return parent::beforeRedirect($controller, $url, $status, $exit);
    }

}
