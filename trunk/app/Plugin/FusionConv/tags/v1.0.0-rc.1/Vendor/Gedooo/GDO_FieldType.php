<?php
/*
 * Classe GDO_FieldType
 * ---------------
 * Un GDO_FieldType est un objet servant ï¿½ alimenter la valeur
 * d'un champ utilisateur dans le modï¿½le de document
 *
 * Version 1.0
 */
 

Class GDO_FieldType {

var $target;
var $value;
var $dataType;

	// }}}
	// {{{ GDO_FieldType ()

    /**
     * Constructeur
     *
     * @param    string      $name 		Non du champ utilisateur
     * @param    string      $value 	Valeur ï¿½ insï¿½rer
     * @param    string      $sDataType 	type de donnÃ©e ("string", "number", "date", "text")
     * @since    1.0
     * @access   public
     */
Function GDO_FieldType($target, $value, $sDataType) {
	$this->target= $target;
	$this->value= preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $value); //Remove CTRL-CHAR
	if ($sDataType == "date" || $sDataType == "number" || $sDataType == "text" ) { 
		$this->dataType = $sDataType;
	} else {
	    $this->dataType = "string";
	}
}

}