<?php
	/**
	 * Code source de la classe Translator.
	 *
	 * @package Translator
	 * @subpackage Utility
	 */
	App::uses('CakeSession', 'Model/Datasource');
	App::uses('TranslatorHash', 'Translator.Utility');
	App::uses('TranslatorInterface', 'Translator.Utility');
	App::uses('I18n', 'I18n');

	/**
	 * Utilise I18n afin d'effectuer des traductions en permettant l'utilisation de multiples domaines
	 *
	 * @package Translator
	 * @subpackage Utility
	 */
	class Translator implements TranslatorInterface
	{
		/**
		 * Contient le nom du dernier domain utilisé dans une traduction
		 *
		 * @var string
		 * @static
		 */
		public static $lastDomain = null;

		/**
		 * @var Translator
		 * @static
		 */
		protected static $_instance = null;

		/**
		 * A cache of already translated messages.
		 *
		 * @var array
		 */
		protected static $_cache = array();

		/**
		 * Indicate wether the cache content has changed or not.
		 *
		 * @var bool
		 */
		protected static $_tainted = false;

		/**
		 * Liste des domaines où chercher la traduction
		 * @see self::domains()
		 * @var array
		 */
		protected $_domains = array('default');

		/**
		 * Clef issue de la liste des domains
		 * @see self::domains()
		 * @see self::_domains
		 * @var string
		 */
		protected $_domainsKey = 'default';

		/**
		 * Constructeur de la classe
		 */
		private function __construct() {
		}

		/**
		 * Méthode qui crée l'unique instance de la classe voulue
		 * si elle n'existe pas encore puis la retourne.
		 *
		 * @return Translator
		 */
		public static function getInstance() {
			$className = get_called_class();
			if (empty(self::$_instance)) {
				$className::$_instance = new $className();
			}

			return $className::$_instance;
		}

		/**
		 * Getter/setter des domains utilisé par le traducteur
		 *
		 * @param mixed $domains
		 * @return array
		 */
		public static function domains($domains = null) {
			$className = get_called_class();
			$instance = $className::getInstance();

			if ($domains !== null) {
				$instance->_domains = array_values((array)$domains);
				$instance->_domainsKey = json_encode($instance->_domains);
			}

			return $instance->_domains;
		}

		/**
		 * Retourne la clé de cache utilisée dans le cadre d'un appel à la méthode
		 * translate.
		 *
		 * @param string $singular
		 * @param string $plural
		 * @param string $domain
		 * @param string $category
		 * @param string $language
		 * @return array
		 */
		public static function path($singular, $plural = null, $category = 6, $count = null, $language = null) {
			$className = get_called_class();
			$instance = $className::getInstance();

			return array(
				$instance::lang(),
				$instance->_domainsKey,
				json_encode(
					array(
						'plural' => $plural,
						'category' => $category,
						'count' => $count,
						'language' => $language
					)
				),
				$singular
			);
		}

		/**
		 * Permet d'obtenir la traduction d'une phrase de façon automatique.
		 *
		 * @param string $singular String to translate
		 * @param string $plural Plural string (if any)
		 * @param integer $category Category The integer value of the category to use.
		 *				0=>'LC_ALL', 1=>'LC_COLLATE', 2=>'LC_CTYPE', 3=>'LC_MONETARY', 4=>'LC_NUMERIC', 5=>'LC_TIME', 6=>'LC_MESSAGES'
		 * @param integer $count Count Count is used with $plural to choose the correct plural form.
		 * @param string $language Language to translate string to.
		 *							If null it checks for language in session followed by Config.language configuration variable.
		 * @param boolean $useCache Use cache or not
		 * @return string translated string.
		 * @throws Exception
		 */
		public static function translate(
			$singular, $plural = null, $category = 6, $count = null, $language = null, $useCache = true
		) {
			$className = get_called_class();
			$instance = $className::getInstance();

			if (empty($instance->_domains)) {
				throw new Exception("Domaines non défini dans la classe ".get_called_class(), 500);
			}

			if ($useCache) {
				$path = $instance::path($singular, $plural, $category, $count, $language);
				$translated = TranslatorHash::exists($instance::$_cache, $path)
					? TranslatorHash::get($instance::$_cache, $path)
					: false;
			}

			if (!$useCache || !$translated) {
				foreach ($instance->domains() as $domain) {
					$translated = I18n::translate($singular, $plural, $domain, $category, $count, $language);
					if ($translated !== $singular) {
						self::$lastDomain = $domain;
						break;
					}
				}

				if ($useCache) {
					$instance::$_cache = TranslatorHash::insert($instance::$_cache, $path, $translated);
					$instance::$_tainted = true;
				}
			}

			return $translated;
		}

		/**
		 * Getter/setter du language
		 *
		 * @param string $language
		 * @return string
		 */
		public static function lang($language = null) {
			if (empty($language)) {
				$language = CakeSession::read( 'Config.language' );
				if (empty($language)) {
					$language = Configure::read('Config.language');
				}
			}
			return I18n::getInstance()->l10n->get($language);
		}

		/**
		 * Réinitialise le traducteur
		 */
		public static function reset() {
			$className = get_called_class();
			$className::$_instance = null;
			$className::$_cache = array();
			$className::$_tainted = false;
		}

		/**
		 * {@inheritdoc}
		 *
		 * @return string
		 */
		public static function domainsKey() {
			$className = get_called_class();
			$instance = $className::getInstance();
			return $instance->_domainsKey;
		}

		/**
		 * {@inheritdoc}
		 *
		 * @return array
		 */
		public static function export() {
			$className = get_called_class();
			$instance = $className::getInstance();
			return $instance::$_cache;
		}

		/**
		 * {@inheritdoc}
		 *
		 * @param array $cache The cache content to import
		 * @return void
		 */
		public static function import(array $cache) {
			$className = get_called_class();
			$instance = $className::getInstance();
			if (empty($instance::$_cache)) {
				$instance::$_cache = $cache;
			} else {
				foreach ($cache as $lang => $keys) {
					foreach ($keys as $key => $methods) {
						foreach ($methods as $method => $messages) {
							$path = array($lang, $key, $method);
							$instance::$_cache = TranslatorHash::insert(
								$instance::$_cache,
								$path,
								array_merge(
									(array)TranslatorHash::get($instance::$_cache, $path),
									$messages
								)
							);
						}
					}
				}
			}
		}

		/**
		 * {@inheritdoc}
		 *
		 * @return bool
		 */
		public static function tainted() {
			$className = get_called_class();
			$instance = $className::getInstance();
			return $instance::$_tainted;
		}
	}