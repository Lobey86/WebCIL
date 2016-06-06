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
 * Classe GDO_MatrixType
 * ---------------
 * Un objet de type GDO_MatrixType permet de mettre à jour un diagramme
 * ou toute représentation d'une matrice numérique
 * Outre les valeurs numériques organisées en un tableau
 * de MatrixRowType,
 * il permet de changer le libellé des axes
 * ainsi que les valeurs textuelles associées à chaque ligne
 * ou à chaque colonne au moyen d'un objet de type AxisTitleType
 * 
 *
 * Version 1.0
 */
 
class GDO_MatrixType {

var $target;
var $title;
var $rowTitles;
var $columnTitles;
var $rowData = array();

function GDO_MatrixType($target) {

	$this->target = $target;
}

function setTitle($title) {
	$this->title = $title;
}

function setRowTitles($rowTitle) {

	$this->rowTitles = $rowTitle;

}

function setColumnTitles($columnTitle) {

	$this->columnTitles = $columnTitle;

}


function addRow($matrixElement) {
	
	$this->rowData[] = $matrixElement;
	}

}
?>
