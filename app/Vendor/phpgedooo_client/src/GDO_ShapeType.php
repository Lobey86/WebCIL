<?php
namespace phpgedooo_client;
/**
 * Classe GDO_ShapeType
 * 
 * Un GDO_ShapeType est un objet servant à changer le
 * style d'une figure graphique dans un dessin
 * inséré dans le document
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
class GDO_ShapeType
{

    public $name;
    public $style;
    public $text;

    /**
     * Constructeur
     *
     * @param    string      $name      Non de a figure
     * @param    string      $style     nom du style  à affecter
     * @since    1.0
     * @access   public
     */
    public function __construct($name, $style, $text)
    {
        $this->name = $name;
        $this->style = $style;
        $this->text = $text;
    }

}
