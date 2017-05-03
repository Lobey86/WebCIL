<?php
namespace phpgedooo_client;
/**
 * Classe GDO_PartType
 * 
 * Un objet de type GDO_PartType contient les données à insérer dans document
 * Ces données peuvent être implémentées sous forme de GDO_FieldType, de ContenType
 * ou d'GDO_IterationType.
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
class GDO_PartType
{

//
// Les propriétés de l'objet vont rester non initialisées
// jusqu'à ce qu'on appelle la fonction "finish".
//
// L'object est de n'initaliser que les propriétés qui contiennent
// au moins un élément.

    public $field;
    public $matrix;
    public $content;
    public $drawing;
    public $iteration;

    /**
     * Ajoute un element à une Part
     *
     * @param    string  l'objet à ajouter, qui peut être de type GDO_FieldType, GDO_ContentType our GDO_IterationType
     * @since    1.0
     * @access   public
     */
    public function addElement($obj)
    {

        switch (get_class($obj)) {
            case "phpgedooo_client\GDO_FieldType":
                $this->field[] = $obj;
                break;
            case "phpgedooo_client\GDO_MatrixType":
                $this->matrix[] = $obj;
                break;
            case "phpgedooo_client\GDO_ContentType":
                $this->content[] = $obj;
                break;
            case "phpgedooo_client\GDO_DrawingType":
                $this->drawing[] = $obj;
                break;
            case "phpgedooo_client\GDO_IterationType":
                $this->iteration[] = $obj;
                break;
        }
    }

    /**
     * Initialise les propriétés  publiques
     * Cette fonction doit être appelée quand tous les element ont été insérés
     * dans la Part.
     *
     * @return   object  la Part elle-même pour qu'elle puisse être insérée dans le document.
     * @since    1.0
     * @access   public
     */
    public function finish()
    {

        //  if (count($this->aField)) $this->field = $this->aField;
        //  if (count($this->aMatrix)) $this->matrix = $this->aMatrix;
        //  if (count($this->aContent)) $this->content = $this->aContent;
        //  if (count($this->aIteration)) $this->iteration = $this->aIteration;
        //  unset($this->aField);
        //  unset($this->aMatrix);
        //  unset($this->aContent);
        //  unset($this->aIteration);

        return($this);
    }

}
