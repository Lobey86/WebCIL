
<?php
/**
* Code source de la classe TraitementFixture.
*
* PHP 5.3
*
* @package app.Test.Fixture
* @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
*/

/**
* Classe TraitementFixture.
*
* @package Cakeflow.Test.Fixture
*/

/*class TraitementFixture extends CakeTestFixture {
        var $import = array( 'table' => 'wkf_traitements', 'records' => false);
        var $records = array(
        );
}*/

class VisaFixture extends CakeTestFixture {
        var $name = 'Visa';
        var $table = 'wkf_visas';
        
        public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'traitement_id' => array('type' => 'integer', 'null' => false),
		'trigger_id' => array('type' => 'integer', 'null' => false),
		'signature_id' => array('type' => 'integer', 'null' => true),
		'etape_nom' => array('type' => 'string', 'null' => true, 'length' => 250),
		'etape_type' => array('type' => 'integer', 'null' => false),
		'action' => array('type' => 'string', 'null' => false, 'length' => 2),
		'commentaire' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'date' => array('type' => 'datetime', 'null' => true),
		'numero_traitement' => array('type' => 'integer', 'null' => false),
		'type_validation' => array('type' => 'string', 'null' => false, 'length' => 1),
		'etape_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'wkf_visas_traitements' => array('unique' => false, 'column' => 'traitement_id')
		),
		'tableParameters' => array()
	);
        
        var $records = array(
        );
}

?>