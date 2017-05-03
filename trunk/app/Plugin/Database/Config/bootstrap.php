<?php
	/**
	 * Boostrap du plugin Database.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Config
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	require_once CakePlugin::path( 'Database' ).'Lib'.DS.'basics.php';
	require_once CakePlugin::path( 'Database' ).'Lib'.DS.'Error'.DS.'exceptions.php';

	// @see http://api.cakephp.org/2.7/annotation-group-deprecated.html
	if( false === defined( 'NOT_BLANK_RULE_NAME' ) ) {
		define( 'NOT_BLANK_RULE_NAME', version_compare( core_version(), '2.7.0', '<' ) ? 'notEmpty' : 'notBlank' );
	}

	// @see http://api.cakephp.org/2.6/annotation-group-deprecated.html
	if( false === defined( 'LENGTH_BETWEEN_RULE_NAME' ) ) {
		define( 'LENGTH_BETWEEN_RULE_NAME', version_compare( core_version(), '2.6.0', '<' ) ? 'between' : 'lengthBetween' );
	}
?>
