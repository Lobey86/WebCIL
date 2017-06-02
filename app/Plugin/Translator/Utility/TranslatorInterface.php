<?php
	/**
	 * Code source de la classe TranslatorInterface.
	 *
	 * @package Translator
	 * @subpackage Component
	 */

	App::uses('Translator', 'Translator.Utility');

	/**
	 * La classe TranslatorInterface ...
	 *
	 * @package Translator
	 * @subpackage Interface
	 */
	interface TranslatorInterface
	{
		/**
		 * Returns an instance of the translator class.
		 *
		 * @return TranslatorInterface
		 */
		public static function getInstance();
		
		/**
		 * Resets the internal state of the translator (domains, cache, ...).
		 *
		 * @return void
		 */
		public static function reset();
		
		/**
		 * Returns the current language currently used by the application.
		 *
		 * @param string $language
		 * @return string
		 */
		public static function lang($language = null);
		
		/**
		 * Sets or returns the domains currently used by the translator.
		 *
		 * @param string|array $domains A (list of) domain name(s)
		 * @return array
		 */
		public static function domains($domains = null);
		
		/**
		 * Returns the current key for the current translation domains.
		 *
		 * @return string
		 */
		public static function domainsKey();
		
		/**
		 * Returns the currently cached translations.
		 *
		 * @return array
		 */
		public static function export();
		
		/**
		 * Import cached translations, merging previously set cached entries.
		 *
		 * @param array $cache The cache content to import
		 * @return void
		 */
		public static function import(array $cache);
		
		/**
		 * Returns true if new translations have been inserted into the cache.
		 *
		 * @return bool
		 */
		public static function tainted();
		
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
		 * @return string translated string.
		 * @throws Exception
		 */
		public static function translate($singular, $plural = null, $category = 6, $count = null, $language = null);
	}