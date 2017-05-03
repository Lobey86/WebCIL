<?php
namespace phpgedooo_client;
/**
 * Classe GDO_DrawingType
 * 
 * Un objet de type Drawing permet de spécifier
 * le nom d'un dessin Draw inséré dans le documents
 * et la liste des figures graphique dont il
 * faut changer le style
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
class GDO_DrawingType
{

    public $name;
    public $shapes = array();

    /**
     * Constructeur
     *
     * @param    string      $name Non du dessin
     * @since    1.0
     * @access   public
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Ajoute un objet de type GDO_ShapeType
     *
     * @param    object     l'objet GDO_ShapeType à ajouter à l'objet
     * @since    1.0
     * @access   public
     */
    public function addShape($aShape)
    {
        $this->shapes[] = $aShape;
    }

}
