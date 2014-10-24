<?php
class CakeflowSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'alias' => array('type' => 'string', 'null' => false),
		'lft' => array('type' => 'integer', 'null' => true),
		'rght' => array('type' => 'integer', 'null' => true),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'model' => array('type' => 'string', 'null' => true),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'acos_idx1' => array('unique' => false, 'column' => array('lft', 'rght')),
			'acos_idx2' => array('unique' => false, 'column' => 'alias'),
			'acos_idx3' => array('unique' => false, 'column' => array('model', 'foreign_key')),
			'acos_leftright' => array('unique' => false, 'column' => array('lft', 'rght')),
			'lft' => array('unique' => false, 'column' => 'lft')
		),
		'tableParameters' => array()
	);
	public $aros = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'foreign_key' => array('type' => 'integer', 'null' => true),
		'alias' => array('type' => 'string', 'null' => false),
		'lft' => array('type' => 'integer', 'null' => true),
		'rght' => array('type' => 'integer', 'null' => true),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'model' => array('type' => 'string', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'alias' => array('unique' => false, 'column' => 'alias'),
			'alias_2' => array('unique' => false, 'column' => 'alias'),
			'aros_idx1' => array('unique' => false, 'column' => array('lft', 'rght')),
			'aros_idx2' => array('unique' => false, 'column' => 'alias'),
			'aros_idx3' => array('unique' => false, 'column' => array('model', 'foreign_key')),
			'aros_leftright' => array('unique' => false, 'column' => array('lft', 'rght')),
			'parent_id' => array('unique' => false, 'column' => 'parent_id'),
			'rght' => array('unique' => false, 'column' => 'rght')
		),
		'tableParameters' => array()
	);
	public $aros_acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'aro_id' => array('type' => 'integer', 'null' => false),
		'aco_id' => array('type' => 'integer', 'null' => false),
		'_create' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'_read' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'_update' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'_delete' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'aco_id' => array('unique' => false, 'column' => 'aco_id')
		),
		'tableParameters' => array()
	);
	public $wkf_circuits = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'nom' => array('type' => 'string', 'null' => false, 'length' => 250),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'actif' => array('type' => 'boolean', 'null' => false, 'default' => true),
		'defaut' => array('type' => 'boolean', 'null' => false),
		'created_user_id' => array('type' => 'integer', 'null' => true),
		'modified_user_id' => array('type' => 'integer', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'created_user_id' => array('unique' => false, 'column' => 'created_user_id'),
			'modified_user_id' => array('unique' => false, 'column' => 'modified_user_id')
		),
		'tableParameters' => array()
	);
	public $wkf_compositions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'etape_id' => array('type' => 'integer', 'null' => false),
		'type_validation' => array('type' => 'string', 'null' => false, 'length' => 1),
		'soustype' => array('type' => 'integer', 'null' => true),
		'type_composition' => array('type' => 'string', 'null' => true, 'default' => 'USER', 'length' => 20),
		'trigger_id' => array('type' => 'integer', 'null' => true),
		'created_user_id' => array('type' => 'integer', 'null' => true),
		'modified_user_id' => array('type' => 'integer', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'etape_id' => array('unique' => false, 'column' => 'etape_id'),
			'trigger' => array('unique' => false, 'column' => 'trigger_id')
		),
		'tableParameters' => array()
	);
	public $wkf_etapes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'circuit_id' => array('type' => 'integer', 'null' => false),
		'nom' => array('type' => 'string', 'null' => false, 'length' => 250),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'type' => array('type' => 'integer', 'null' => false),
		'soustype' => array('type' => 'integer', 'null' => true),
		'ordre' => array('type' => 'integer', 'null' => false),
		'created_user_id' => array('type' => 'integer', 'null' => false),
		'modified_user_id' => array('type' => 'integer', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'cpt_retard' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'circuit_id' => array('unique' => false, 'column' => 'circuit_id'),
			'nom' => array('unique' => false, 'column' => 'nom')
		),
		'tableParameters' => array()
	);
	public $wkf_signatures = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'type_signature' => array('type' => 'string', 'null' => false, 'length' => 100),
		'signature' => array('type' => 'text', 'null' => false, 'length' => 1073741824),
		'visa_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $wkf_traitements = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'circuit_id' => array('type' => 'integer', 'null' => false),
		'target_id' => array('type' => 'integer', 'null' => false),
		'numero_traitement' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'treated_orig' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created_user_id' => array('type' => 'integer', 'null' => true),
		'modified_user_id' => array('type' => 'integer', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'treated' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'circuits' => array('unique' => false, 'column' => 'circuit_id'),
			'target' => array('unique' => false, 'column' => 'target_id'),
			'traitements_treated' => array('unique' => false, 'column' => 'treated_orig')
		),
		'tableParameters' => array()
	);
	public $wkf_visas = array(
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
		'date_retard' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'wkf_visas_traitements' => array('unique' => false, 'column' => 'traitement_id')
		),
		'tableParameters' => array()
	);
}
