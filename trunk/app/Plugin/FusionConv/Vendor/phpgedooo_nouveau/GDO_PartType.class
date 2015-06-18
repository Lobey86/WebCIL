<?php

// +----------------------------------------------------------------------+
// | PHP Version 5.3                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Lille Metropole Communaute Urbaine (LMCU)         |
// +----------------------------------------------------------------------+
// | This file is part of GED'OOo.                                        |
// |                                                                      |
// | GED'OOo is free software; you can redistribute it and/or modify      |
// | it under the terms of the GNU General Public License as published by |
// | the Free Software Foundation; either version 2 of the License, or    |
// | (at your option) any later version.                                  |
// |                                                                      |
// | Tiny is distributed in the hope that it will be useful,              |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with GED'OOo; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA        |
// +----------------------------------------------------------------------+
// | Authors:Philippe Allart                                              |
// +----------------------------------------------------------------------+
//

/*
 * Classe GDO_PartType
 * ---------------
 * Un objet de type GDO_PartType contient les données à insérer dans document
 * Ces données peuvent être implémentées sous forme de GDO_FieldType, de ContenType
 * ou d'GDO_IterationType. 
 *
 * Version 1.0
 */

Class GDO_PartType {
//
// Les propriétés de l'objet vont rester non initialisées
// jusqu'à ce qu'on appelle la fonction "finish".
//
// L'object est de n'initaliser que les propriétés qui contiennent
// au moins un élément.

var $field;
var $matrix;
var $content;
var $iteration;

//private $aField = array();
//private $aContent = array();
//private $aMatrix = array();
//private $aIteration = array();

	// }}}
    // {{{ addElement ()

    /**
     * Ajoute un element à une Part
     *
	 * @param    string	 l'objet à ajouter, qui peut être de type GDO_FieldType, GDO_ContentType our GDO_IterationType
     * @since    1.0
     * @access   public
     */
function addElement($obj) {

	switch(get_class($obj)) {
	case "GDO_FieldType" :
		$this->field[] = $obj;
		break;
	case "GDO_MatrixType" :
		$this->matrix[] = $obj;
		break;
	case "GDO_ContentType" :
		$this->content[] = $obj;
		break;
	case "GDO_IterationType" :
		$this->iteration[] = $obj;
		break;
	}
}

	// }}}
    // {{{ finish ()

    /**
     * Initialise les propriétés  publiques
	 * Cette fonction doit être appelée quand tous les element ont été insérés
	 * dans la Part.
     *
	 * @return   object  la Part elle-même pour qu'elle puisse être insérée dans le document.
     * @since    1.0
     * @access   public
     */
function finish() {

//	if (count($this->aField)) $this->field = $this->aField;
//	if (count($this->aMatrix)) $this->matrix = $this->aMatrix;
//	if (count($this->aContent)) $this->content = $this->aContent;
//	if (count($this->aIteration)) $this->iteration = $this->aIteration;
	
//	unset($this->aField);
//	unset($this->aMatrix);
//	unset($this->aContent);
//	unset($this->aIteration);

	return($this);

}

}

?>