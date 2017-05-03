<?php
namespace phpgedooo_client;
/**
 * Classe GDO_MatrixRowType
 * 
 * Un objet de type GDO_MatrixRowType contient un tableau de
 * valeurs numériques.
 * Ces objets sont ensuite enregistrés dans un objet de type GDO_MatrixType.
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
class GDO_MatrixRowType
{

    public $value;

    public function __construct($aValue)
    {
        $this->value = $aValue;
    }

}
