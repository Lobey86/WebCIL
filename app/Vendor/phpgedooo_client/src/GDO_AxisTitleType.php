<?php
namespace phpgedooo_client;
/**
 * Classe GDO_MatrixTitleType
 * 
 * Un objet de type GDO_MatrixTitleType contient
 * optionnellement un titre qui sera affecté à l'un des axes
 * et une série de description textuelles qui seront
 * affichée pour chaque ligne ou chaque colonne
 * de cet axe
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
class GDO_AxisTitleType
{

    public function setTitle($sTitle)
    {
        $this->title = $sTitle;
    }

    public function setDescription($aDescription)
    {
        $this->description = $aDescription;
    }
}
