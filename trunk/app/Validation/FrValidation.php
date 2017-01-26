<?php
/**
 * Validation en Fr.
 * 
 * Copyright (c) Adullact (http://www.adullact.org)
 *
 * Licensed under The CeCiLL V2 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright   Copyright (c) Adullact (http://www.adullact.org)
 * @link        https://adullact.net/projects/webcil/
 * @since       webcil v0.9.0
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 * @version     v0.9.0
 * @package     Validation
 */

/**
 * Classe de validation des numéros de téléphone et des codes postaux
 * français.
 */
class FrValidation {

    /**
     * Vérifie que la valeur soit celle d'un numéro de téléphone français.
     *
     * @see http://fr.wikipedia.org/wiki/Plan_de_num%C3%A9rotation_t%C3%A9l%C3%A9phonique_en_France#Indicatif_1
     *
     * @param string $check
     * @return bool
     */
    public static function phone($check) {
        $pattern = '/^(0[1-9][0-9]{8}|1[0-9]{1,3}|11[0-9]{4}|3[0-9]{3})$/';
        return (bool) preg_match($pattern, $check);
    }

    /**
     * Vérifie que la valeur soit celle d'un code postal français.
     *
     * @param string $check
     * @return bool
     */
    public static function postal($check) {
        $pattern = '/^\d{5}$/';
        return (bool) preg_match($pattern, $check);
    }

}