<?php

App::uses('Hash', 'Utility');
App::uses('Router', 'Routing');

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

/**
 * Remplace les caractères accentués par des caractères non accentués dans
 * une chaîne de caractères.
 *
 * @info il faut utiliser les fonctions mb_internal_encoding et mb_regex_encoding
 * 	pour que le système sache quels encodages il traite, afin que le remplacement
 *  d'accents se passe bien.
 *
 * @param string $string
 * @return string
 */
function replace_accents($string) {
    $accents = array(
        '[ÂÀ]',
        '[âà]',
        '[Ç]',
        '[ç]',
        '[ÉÊÈË]',
        '[éêèë]',
        '[ÎÏ]',
        '[îï]',
        '[ÔÖ]',
        '[ôö]',
        '[ÛÙ]',
        '[ûù]'
    );

    $replace = array(
        'A',
        'a',
        'C',
        'c',
        'E',
        'e',
        'I',
        'i',
        'O',
        'o',
        'U',
        'u'
    );

    foreach ($accents as $key => $accent) {
        $string = mb_ereg_replace($accent, $replace[$key], $string);
    }

    return $string;
}

/**
 * Remplace les caractères accentués par des caractères non accentués et met
 * en majuscules dans une chaîne de caractères.
 *
 * @see replace_accents
 *
 * @param string $string
 * @return string
 */
function noaccents_upper($string) {
    return strtoupper(replace_accents($string));
}

/**
 * Retourne la chaîne de caractères $string dont les occurences de
 * #Model.champ# ont été remplacées par leur valeur extraite depuis $data.
 *
 * @param array $data
 * @param string $string
 * @return string
 */
function evaluate_string(array $data, $string) {
    if (strpos($string, '#') !== false) {
        $pattern = '/("#[^#]+#"|\'#[^#]#\'|#[^#]+#)/';
        if (preg_match_all($pattern, $string, $out)) {
            $tokens = $out[0];
            foreach (array_unique($tokens) as $token) {
                // Pour échapper efficacement les guillemets simples et doubles
                if ($token[0] === '"') {
                    $escape = '"';
                    $token = trim($token, '"');
                } else if ($token[0] === "'") {
                    $escape = "'";
                    $token = trim($token, "'");
                } else {
                    $escape = false;
                }

                $token = trim($token, '#');
                $value = Hash::get($data, $token);

                if (false !== $escape) {
                    $value = str_replace($escape, "\\{$escape}", $value);
                }

                $string = str_replace("#{$token}#", $value, $string);
            }
        }
        $string = preg_replace('/^\/\//', '/', $string);
    }

    return $string;
}

/**
 * Retourne le paramètre $mixed dont les occurences de #Model.champ# ont
 * été remplacées par leur valeur extraite depuis $data.
 *
 * @param array $data
 * @param string|array $mixed
 * @return string|array
 */
function evaluate(array $data, $mixed) {
    if (is_array($mixed)) {
        $array = array();
        if (!empty($mixed)) {
            foreach ($mixed as $key => $value) {
                $array[evaluate_string($data, $key)] = evaluate($data, $value);
            }
        }
        return $array;
    }

    return evaluate_string($data, $mixed);
}

/**
 * Retourne une URL relative et normalisée, sous forme de chaine de caractères.
 *
 * @param string|array|CakeRequest $url
 * @return string
 */
function url_to_string($url) {
    if (false === is_string($url)) {
        $params = $url;
    } else {
        $params = Router::parse($url);
    }

    $params['url'] = true === isset($params['url']) ? $params['url'] : array();
    $params['controller'] = Inflector::underscore($params['controller']);

    return Router::normalize(Router::reverse($params));
}

/**
 * Retourne un array représentant l'URL, à utiliser par les méthodes Router::url,
 * HtmlHelper::link, Controller::redirect, ...
 *
 * @param string|array|CakeRequest $url
 * @return array
 */
function url_to_array($url) {
    $url = url_to_string($url);
    $params = Router::parse($url);

    $pass = true === isset($params['pass']) ? (array)$params['pass'] : [];
    unset($params['pass']);

    $named = true === isset($params['named']) ? (array)$params['named'] : [];
    unset($params['named']);

    return array_merge($params, $pass, $named);
}
