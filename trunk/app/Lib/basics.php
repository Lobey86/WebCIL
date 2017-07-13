<?php

/**
 * Retourne le numéro de révision de l'application dès lors que l'on se trouve
 * sur une machine *nix, que le binaire svn est accessible et que la commande
 * svn info retourne un résultat.
 *
 * @staticvar boolean $result
 * @param string $root Le répertoire dans lequel lancer la commande svn info
 * @return integer
 */
function current_revision_number($root = APP) {
    static $result = null;

    if (null === $result) {
        $result = Cache::read('revision');
        if (false === $result) {
            $result = null;
            $command = sprintf('which svn > /dev/null && svn info --xml %s', $root);
            $output = [];
            $return_var = null;
            exec($command, $output, $return_var);
            if (0 == $return_var) {
                $use_errors = libxml_use_internal_errors(true);
                $xml = simplexml_load_string(implode("\n", $output), 'SimpleXMLElement', LIBXML_NOWARNING & LIBXML_NOERROR);
                libxml_clear_errors();
                libxml_use_internal_errors($use_errors);
                if (true === is_a($xml, 'SimpleXMLElement') && true === isset($xml->entry)) {
                    $attributes = $xml->entry->attributes();
                    if (true === is_a($attributes, 'SimpleXMLElement') && true === isset($attributes->revision)) {
                        $result = (string)$attributes->revision;
                    }
                }
                Cache::write('revision', $result);
            }
        }
    }

    return $result;
}