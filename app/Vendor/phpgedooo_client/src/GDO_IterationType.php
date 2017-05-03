<?php
namespace phpgedooo_client;
/**
 * Classe GDO_IterationType
 * 
 * Un objet de type Iteration permet de regrouper
 * plusieurs parties identiques du document cible.
 * Chaque partie est un objet de type GDO_PartType.
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
class GDO_IterationType
{

    public $name;
    public $part = array();

    /**
     * Constructeur
     *
     * @param    string      $name Non de l'itération
     * @since    1.0
     * @access   public
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Ajoute un objet de type GDO_PartType
     *
     * @param    object     l'objet GDO_PartType à ajouter à l'itération
     * @since    1.0
     * @access   public
     */
    public function addPart($aPart)
    {

        $this->part[] = $aPart->finish();
    }

}
