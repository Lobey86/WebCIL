<?php

/**
 * Code source de la classe WebcilTranslator.
 *
 * @package Translator
 * @subpackage Utility
 */
App::uses('Translator', 'Translator.Utility');

/**
 * Utilise I18n afin d'effectuer des traductions en permettant l'utilisation de multiples domaines
 *
 * @package Translator
 * @subpackage Utility
 */
class WebcilTranslator extends Translator {

    /**
     * Permet d'obtenir la traduction d'une phrase de façon automatique.
     * 
     * @param string $singular String to translate
     * @param string $plural Plural string (if any)
     * @param integer $category Category The integer value of the category to use.
     *                              0=>'LC_ALL', 1=>'LC_COLLATE', 2=>'LC_CTYPE', 3=>'LC_MONETARY', 4=>'LC_NUMERIC', 5=>'LC_TIME', 6=>'LC_MESSAGES'
     * @param integer $count Count Count is used with $plural to choose the correct plural form.
     * @param string $language Language to translate string to.
     *                                                      If null it checks for language in session followed by Config.language configuration variable.
     * @return string translated string.
     * @throws Exception
     */
    public static function translate($singular, $plural = null, $category = 6, $count = null, $language = null) {
        $className = get_called_class();
        $instance = $className::getInstance();

        $translated = parent::translate($singular, $plural, $category, $count, $language);
        if ($translated === $singular && preg_match('/([\w]+)\.[\w]+$/', $singular, $matches)) {
            $domain = Inflector::underscore($matches[1]);
            $path = $instance::path($singular, $plural, $category, $count, $language);

            $translated = I18n::translate($singular, $plural, $domain, $category, $count, $language);

            $instance::$_cache = TranslatorHash::insert($instance::$_cache, $path, $translated);
            $instance::$_tainted = true;
        }

        return $translated;
    }

}
