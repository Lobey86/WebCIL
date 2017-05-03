<?php
namespace phpgedooo_client;
/**
 * Classe GDO_MatrixType
 * 
 * Un objet de type GDO_MatrixType permet de mettre à jour un diagramme
 * ou toute représentation d'une matrice numérique
 * Outre les valeurs numériques organisées en un tableau
 * de MatrixRowType,
 * il permet de changer le libellé des axes
 * ainsi que les valeurs textuelles associées à chaque ligne
 * ou à chaque colonne au moyen d'un objet de type AxisTitleType
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
class GDO_MatrixType
{

    public $target;
    public $title;
    public $rowTitles;
    public $columnTitles;
    public $rowData = array();

    public function __construct($target)
    {

        $this->target = $target;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setRowTitles($rowTitle)
    {

        $this->rowTitles = $rowTitle;
    }

    public function setColumnTitles($columnTitle)
    {

        $this->columnTitles = $columnTitle;
    }

    public function addRow($matrixElement)
    {

        $this->rowData[] = $matrixElement;
    }

}
