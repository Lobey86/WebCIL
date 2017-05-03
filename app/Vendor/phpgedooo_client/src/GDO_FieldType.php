<?php
namespace phpgedooo_client;
/**
 * Classe GDO_FieldType
 * 
 * Un GDO_FieldType est un objet servant à alimenter la valeur
 * d'un champ utilisateur dans le modèle de document
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
class GDO_FieldType
{

    public $target;
    public $value;
    public $dataType;

    /**
     * Constructeur
     *
     * @param    string      $name      Non du champ utilisateur
     * @param    string      $value     Valeur à insérer
     * @param    string      $sDataType     type de donnée ("string", "number", "date", "text")
     * @since    1.0
     * @access   public
     */
    public function __construct($target, $value, $sDataType)
    {
        $this->target = $target;
        $this->value = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $value); //Remove CTRL-CHAR
        if ($sDataType == "date" || $sDataType == "number" || $sDataType == "text") {
            $this->dataType = $sDataType;
        } else {
            $this->dataType = "string";
        }
    }

}
