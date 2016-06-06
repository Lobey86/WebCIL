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
 * Classe GDO_IterationType
 * ---------------
 * Un objet de type Iteration permet de regrouper
 * plusieurs parties identiques du document cible.
 * Chaque partie est un objet de type GDO_PartType.
 *
 * Version 1.0
 */


Class GDO_IterationType {

var $name;
var $part = array();

	// }}}
    // {{{ GDO_IterationType ()

    /**
     * Constructeur
     *
     * @param    string      $name Non de l'itération
     * @since    1.0
     * @access   public
     */

function GDO_IterationType($name) {
	$this->name = $name;
}

	// }}}
    // {{{ addPart ()

    /**
     * Ajoute un objet de type GDO_PartType
     *
	 * @param    object		l'objet GDO_PartType à ajouter à l'itération
     * @since    1.0
     * @access   public
     */

function addPart($aPart) {

	$this->part[] = $aPart->finish();

}

}
?>
