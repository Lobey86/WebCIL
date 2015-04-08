<?php
/**
 * Boostrap du plugin Gedooo2.
 *
 * PHP 5.3
 *
 * @package Gedooo2
 * @subpackage Config
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */
if( !defined( 'GEDOOO_PLUGIN_DIR' ) ) {
        define( 'GEDOOO_PLUGIN_DIR', dirname( __FILE__ ).DS.'..'.DS );
}

if( !defined( 'GEDOOO_WSDL' ) ) {
        define( 'GEDOOO_WSDL', Configure::read( 'Gedooo.wsdl' ) );
}

if( !defined( 'PLUGIN_TESTS' ) ) {
        define( 'PLUGIN_TESTS', GEDOOO_PLUGIN_DIR.'Test'.DS );
}

if( !defined( 'PLUGIN_TESTS_MODELE_DIR' ) ) {
        define( 'PLUGIN_TESTS_MODELE_DIR', GEDOOO_PLUGIN_DIR.'Test'.DS.'Data'.DS.'modelesodt'.DS );
}

if( !defined( 'PHPGEDOOO_DIR' ) ) {
        switch( Configure::read( 'Gedooo.method' ) ) {
                case 'GedoooCloudooo':
                    define( 'PHPGEDOOO_DIR', GEDOOO_PLUGIN_DIR.'Vendor'.DS.'Gedooo'.DS );
                    libGedoooNew();
                case 'classic':
                        define( 'PHPGEDOOO_DIR', GEDOOO_PLUGIN_DIR.'Vendor'.DS.'phpgedooo_ancien'.DS );
                    libGedooolast();
                        break;
                case 'cloudooo':
                case 'unoconv':
                default:
                    define( 'PHPGEDOOO_DIR', GEDOOO_PLUGIN_DIR.'Vendor'.DS.'phpgedooo_nouveau'.DS );
                    libGedooolast();
                    
                    
        }
}

function libGedooolast()
{
    require_once( PHPGEDOOO_DIR.'GDO_Utility.class' );
    require_once( PHPGEDOOO_DIR.'GDO_FieldType.class' );
    require_once( PHPGEDOOO_DIR.'GDO_ContentType.class' );
    require_once( PHPGEDOOO_DIR.'GDO_IterationType.class' );
    require_once( PHPGEDOOO_DIR.'GDO_PartType.class' );
    require_once( PHPGEDOOO_DIR.'GDO_FusionType.class' );
    require_once( PHPGEDOOO_DIR.'GDO_MatrixType.class' );
    require_once( PHPGEDOOO_DIR.'GDO_MatrixRowType.class' );
    require_once( PHPGEDOOO_DIR.'GDO_AxisTitleType.class' );
}

function libGedoooNew()
{
    require_once( PHPGEDOOO_DIR.'GDO_Utility.php' );
    require_once( PHPGEDOOO_DIR.'GDO_FieldType.php' );
    require_once( PHPGEDOOO_DIR.'GDO_ContentType.php' );
    require_once( PHPGEDOOO_DIR.'GDO_IterationType.php' );
    require_once( PHPGEDOOO_DIR.'GDO_PartType.php' );
    require_once( PHPGEDOOO_DIR.'GDO_FusionType.php' );
    require_once( PHPGEDOOO_DIR.'GDO_MatrixType.php' );
    require_once( PHPGEDOOO_DIR.'GDO_MatrixRowType.php' );
    require_once( PHPGEDOOO_DIR.'GDO_AxisTitleType.php' );
}