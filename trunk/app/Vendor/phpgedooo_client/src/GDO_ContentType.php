<?php
namespace phpgedooo_client;
/**
 * Classe GDO_ContentType
 * 
 * Un objet de type GDO_ContentType contient les références à un document
 * Ce document a un type MIME et peut se trouver
 *  - a une url donnée
 *  - dans l'objet lui-même sous forme binaire
 *  - dans l'objet lui-même sous forme d'un texte en html.
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
Class GDO_ContentType implements \JsonSerializable
{

    public $name;
    public $target;
    public $mimeType;
    public $url;
    public $binary;
    public $text;
    private $mode;

    /**
     * Constructeur
     *
     * @param    string      $name 		Non du document
     * @param    string      $mimeType 	type MIME du document
     * @param    string      $mode		mode d'accés au contenu
     * @param    string      $value		url, valeur binaire ou texte (html), selon le mode
     * @since    1.0
     * @access   public
     */
    function __construct($target, $name, $mimeType, $mode, $value)
    {
        $this->mode = $mode;
        if ($target != "") {
            $this->target = $target;
        }
        if ($name != "") {
            $this->name = $name;
        }
        $this->mimeType = $mimeType;

        switch ($mode) {
            case "url" :
                $this->url = $value;
                break;
            case "binary" :
                $this->binary = $value;
                break;
            case "text" :
                $this->text = $value;
                break;
        }
    }

    /**
     * Renvoi le nom du document
     *
     * @return   string		Le nom du document
     * @since    1.0
     * @access   public
     */
    function getName()
    {
        return($this->name);
    }

    /**
     * Renvoi le type MIME du contenu
     *
     * @return   string		Le type MIME
     * @since    1.0
     * @access   public
     */
    function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Renvoi le type MIME du contenu
     *
     * @return   string		Le type MIME
     * @version 2.0.0
     * @since    1.0
     * @access   public
     */
    function getContent()
    {

        if (isset($this->url)) {
            return(file_get_contents($this->url));
        } elseif (isset($this->text)) {
            return($this->text);
        } elseif (isset($this->binary)) {
            return($this->binary);
        }

        throw new \Exception("Content not available.");
    }

    /**
     * Renvoi le contenu vers le client.
     * Si le contenu est spécifié par une URL, il est d'abord récupéré.
     *
     * @since    1.0
     * @access   public
     */
    function sendToClient()
    {

        //  Accéder à toutes les données avant de lancer le premier header
        $sMimeType = $this->getMimeType();
        $sFileName = $this->getName();
        $bContent = $this->getContent();

        header("Content-type: $sMimeType");
        header("Content-disposition: attachment; filename=" . $sFileName);
        header("Content-length: " . strlen($bContent));

        echo $bContent;
    }

    /**
     * Renvoie le contenu vers le fichier spécifié.
     *
     * @since    1.0
     * @access   public
     * @param    string     $sFile      Chemin du fichier où stocker le résultat
     */
    function sendToFile($sFile)
    {
        file_put_contents($sFile, $this->getContent());
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @version 2.0.0
     */
    function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'target' => $this->target,
            'mimeType' => $this->mimeType,
            'url' => $this->url,
            'binary' => base64_encode($this->binary),
            'text' => $this->text,
            'mode' => $this->mode
        ];
    }

}
