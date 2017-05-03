<?php

/**
 * File bootstrap
 *
 * phpgedooo_client : Client php pour l'utilisation du serveur gedooo
 * Copyright (c) Libriciel SCOP <http://www.libriciel.fr>
 *
 * Licensed under The CeCiLL V2 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Libriciel SCOP <http://www.libriciel.fr>
 * @license     http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html CeCiLL V2 License
 * @version     2.0.0
 * 
 */

if( !defined( 'GEDOOO_PLUGIN_DIR' ) ) {
        define( 'GEDOOO_PLUGIN_DIR', dirname( __FILE__ ).DS.'..'.DS );
}
if( !defined( 'GEDOOO_WSDL' ) ) {
        define( 'GEDOOO_WSDL', Configure::read( 'FusionConv.Gedooo.wsdl' ) );
}
if( !defined( 'GEDOOO_REST' ) ) {
    if (!empty(Configure::read('FusionConv.Gedooo.rest'))) {
        define('GEDOOO_REST', Configure::read('FusionConv.Gedooo.rest'));
    }
}
if( !defined( 'GEDOOO_URL' ) ) {
    define( 'GEDOOO_URL', Configure::read( 'FusionConv.Gedooo.url' ) );
}

if( !defined( 'PLUGIN_TESTS' ) ) {
        define( 'PLUGIN_TESTS', GEDOOO_PLUGIN_DIR.'Test'.DS );
}

if( !defined( 'PLUGIN_TESTS_MODELE_DIR' ) ) {
        define( 'PLUGIN_TESTS_MODELE_DIR', GEDOOO_PLUGIN_DIR.'Test'.DS.'Data'.DS.'modelesodt'.DS );
}

if( !defined( 'PLUGIN_TESTS_VARIABLES_DIR' ) ) {
        define( 'PLUGIN_TESTS_VARIABLES_DIR', GEDOOO_PLUGIN_DIR.'Test'.DS.'Data'.DS.'variables'.DS );
}