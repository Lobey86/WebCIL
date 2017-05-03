<?php
namespace phpgedooo_client;
/**
 * Classe GDO_FusionType
 * 
 * Un objet de type GDO_FusionType contient toutes les spécifications
 * permettant de produire un document.
 * Il fait référence au modéle de document au moyen d'un GDO_ContentType
 * Il spécifie les données à insérer au moyen d'un GDO_PartType.
 * Il indique également le type MIME du document à produire
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
Class GDO_FusionType
{

    public $template;
    public $mimeType;
    public $part;
    public $debug;
    private $oResultat;
    private $sCode;
    private $sMessage;

    /**
     * Constructeur
     *
     * @param    object      $template   Le modele
     * @param    string      $mimeType   Le type MIME du document à produire
     * @since    1.0
     * @access   public
     */
    public function __construct(GDO_ContentType $template, $mimeType, GDO_PartType $part)
    {

        $this->template = $template;
        $this->mimeType = $mimeType;
        $this->part = $part->finish();
    }

    public function setDebug()
    {
        $this->debug = true;
    }

    /**
     * L'array "classmap" établie la relation entre les types d'objets PHP
     * et les types utilisés dans le WSDL
     *
     * @return   array      La relation entre les types WSDL et les classes PHP
     * @since    1.0
     * @access   public
     */
    public function classMap()
    {
        return array(
            "FieldType" => "phpgedooo_client\GDO_FieldType",
            "ContentType" => "phpgedooo_client\GDO_ContentType",
            "DrawingType" => "phpgedooo_client\GDO_DrawingType",
            "FusionType" => "phpgedooo_client\GDO_FusionType",
            "IterationType" => "phpgedooo_client\GDO_IterationType",
            "PartType" => "phpgedooo_client\GDO_PartType",
            "MatrixType" => "phpgedooo_client\GDO_MatrixType",
            "MatrixRowType" => "phpgedooo_client\GDO_MatrixRowType",
            "MatrixTitleType" => "phpgedooo_client\GDO_MatrixTitleType"
        );
    }

    public function version()
    {
        if (defined('GEDOOO_REST') && GEDOOO_REST) {
            // Get cURL resource
            $curl = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => GEDOOO_REST . "/rest/version"
            ));
            // Send the request & save response to $resp
            $version = curl_exec($curl);
            // Close request to clear up some resources
            curl_close($curl);

            return $version;
        } else {
            try {
                $oService = new \SoapClient(GEDOOO_WSDL);
                return $oService->__soapCall("Version", array());
            } catch (Exception $e) {
                //Erreur lors de l'initialisation de la connexion : code 001
                $this->errNum = "001";
                $this->sMessage = "Erreur lors de la connexion au WSDL : " . $e->getMessage();

                return;
            }
        }
    }

    /**
     * Execution de la requéte
     * La requéte estlancée out de suite aprés que l'objet est créé.
     * @version 2.0.0
     * @since    1.0
     * @access   public
     */
    public function process()
    {
        if (defined('GEDOOO_REST') && GEDOOO_REST) {
            // In this case, we use REST api
            try {
                $object = (object) array_filter((array) $this);
                $data_string = json_encode($object);
                $ch = curl_init(GEDOOO_REST . "/rest/fusion");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
                );
                $this->oResultat = new \stdClass();
                $this->oResultat->content = new GDO_ContentType("", "", "", "binary", curl_exec($ch));
                curl_close($ch);

                $this->sCode = "OK";
                $this->sMessage = "The fusion was successful.";

                return;
            } catch (Exception $e) {
                
            }
        } else {
            $this->sCode = "Error";
            $classmap = $this->classmap();
            try {

                try {
                    $oService = new \SoapClient(GEDOOO_WSDL, array("cache_wsdl" => WSDL_CACHE_NONE,
                        "exceptions" => 1,
                        "trace" => 1,
                        "classmap" => $classmap));
                } catch (Exception $e) {
                    //Erreur lors de l'initialisation de la connexion : code 001
                    $this->errNum = "001";
                    $this->sMessage = "Erreur lors de la connexion au WSDL : " . $e->getMessage();
                    return;
                }
                $this->oResultat = $oService->Fusion($this);
                $this->sCode = "OK";
                $this->sMessage = "The fusion was successful.";
            } catch (Exception $e) {
                $soapfault = get_object_vars($e);
                $this->errNum = $soapfault["faultcode"];
                $this->sMessage = $soapfault["faultstring"];
            }
        }
    }

    /**
     * Renvoi le contenu retournà par la requéte
     * sous forme d'un objet de type ContentType
     * La requéte doit s'être exécutée avec succés.
     *
     * @since    1.0
     * @return	 object		Le résultat sous forme de ContentType
     * @access   public
     */
    public function getContent()
    {
        if ($this->sCode == "OK") {
            return($this->oResultat->content);
        } else {
            throw new \Exception("Fusion failed:\nMessage: " . $this->sMessage . "\nCode: " . $this->errNum);
        }
    }

    /**
     * Renvoi le code retour de la requéte.
     *
     * @since    1.0
     * @return	 string		Le code retour de la requéte SOAP
     * @access   public
     */
    public function getCode()
    {
        return $this->sCode;
    }

    /**
     * Renvoi le compte-rendu de la requéte.
     *
     * @since    1.0
     * @return	 string		Le compte-rendu de la requéte SOAP
     * @access   public
     */
    public function getMessage()
    {
        return $this->sCode . " : " . $this->sMessage;
    }

    /**
     * Envoie le contenu de lobjet ContentType vers le client
     *
     * @since    1.0
     * @access   public
     */
    public function sendContentToClient()
    {
        $oContent = $this->getContent();
        $oContent->sendToClient();
    }

    /**
     * Renvoie le contenu vers le fichier spécifié.
     *
     * @since    1.0
     * @access   public
     * @param    string     $sFile      Chemin du fichier où stocker le résultat
     */
    public function sendContentToFile($sFile)
    {
        $oContent = $this->getContent();
        $oContent->sendToFile($sFile);
    }

}
