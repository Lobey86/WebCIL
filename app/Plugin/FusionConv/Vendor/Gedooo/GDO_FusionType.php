<?php

// +----------------------------------------------------------------------+
// | PHP Version 5.3                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Lille Metropole Communaute Urbaine (LMCU)         |
// +----------------------------------------------------------------------+
// | This file is part of GED'OOo.                                        |
// |                                                                      |
// | GED'OOo is free software; you can redistribute it and/or modify      |
// | it under the terms of the GNU General Public License as published by |
// | the Free Software Foundation; either version 2 of the License, or    |
// | (at your option) any later version.                                  |
// |                                                                      |
// | Tiny is distributed in the hope that it will be useful,              |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with GED'OOo; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA        |
// +----------------------------------------------------------------------+
// | Authors:Philippe Allart                                              |
// +----------------------------------------------------------------------+
//

/*
 * Classe GDO_FusionType
 * ---------------
 * Un objet de type GDO_FusionType contient toutes les spécifications
 * permettant de produire un document.
 * Il fait référence au modéle de document au moyen d'un GDO_ContentType
 * Il spécifie les données à insérer au moyen d'un GDO_PartType.
 * Il indique également le type MIME du document à produire
 *
 * Version 1.0
 */


Class GDO_FusionType {
var $template;
var $mimeType;
var $part;
var $debug;

private $oResultat;
private $sCode;
private $sMessage;

	// }}}
    // {{{ GDO_FusionType ()

    /**
     * Constructeur
     *
     * @param    object      $template   Le modele
     * @param    string      $mimeType   Le type MIME du document à produire
     * @since    1.0
     * @access   public
     */
function GDO_FusionType(GDO_ContentType $template, $mimeType, GDO_PartType $part) {

	$this->template = $template;
	$this->mimeType = $mimeType;
	$this->part = $part->finish();
//	$this->process();
	}

	// }}}
    // {{{ GDO_classmap()

    /**
     * L'array "classmap" établie la relation entre les types d'objets PHP
	 * et les types utilisés dans le WSDL
     *
     * @return   array      La relation entre les types WSDL et les classes PHP
     * @since    1.0
     * @access   public
     */
function classMap() {
     return array(
		"FieldType" => "GDO_FieldType",
		"ContentType" => "GDO_ContentType",
		"DrawingType" => "GDO_DrawingType",
		"FusionType" => "GDO_FusionType",
		"IterationType" => "GDO_IterationType",
		"PartType" => "GDO_PartType",
		"MatrixType"=>"GDO_MatrixType",
		"MatrixRowType"=> "GDO_MatrixRowType",
		"MatrixTitleType"=>"GDO_MatrixTitleType");
}

//
// Variable "wsdl" insérée ici par commodité pendant les test
// mais ce n'est pas sa place.
//
function wsdl() {
//192.168.2.35:8980
	if( defined( 'GEDOOO_WSDL' ) ) {
		return GEDOOO_WSDL;
	}

	return "http://localhost:8880/ODFgedooo/OfficeService?wsdl";
//return GEDOOO_WSDL;
}

function version() {
    try {
	    $wsdl = $this->wsdl();
	    $oService = new SoapClient($wsdl);
	    return $oService -> __soapCall("Version", array());
    } catch (Exception $e) {
        //Erreur lors de l'initialisation de la connexion : code 001
        $this->errNum = "001";
        $this->sMessage = "Erreur lors de la connexion au WSDL : ".$e->getMessage();
        return;
    }
}


	// }}}
    // {{{ process ()

    /**
     * Execution de la requéte
	 * La requéte estlancée out de suite aprés que l'objet est créé.
     *
     * @since    1.0
     * @access   public
     */
function process() {
    $this->sCode = "Error";
	$wsdl = $this->wsdl();
	$classmap = $this->classmap();
	try {

		try {
		    $oService = new SoapClient($wsdl,
				    array("cache_wsdl"=>WSDL_CACHE_NONE,
				    "exceptions"=> 1,
				    "trace"=>1,
				    "classmap"=>$classmap));
	    } catch (Exception $e) {
	        //Erreur lors de l'initialisation de la connexion : code 001
            $this->errNum = "001";
	        $this->sMessage = "Erreur lors de la connexion au WSDL : ".$e->getMessage();
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

	// }}}
    // {{{ getContent ()

    /**
     * Renvoi le contenu retournà par la requéte
	 * sous forme d'un objet de type ContentType
	 * La requéte doit s'être exécutée avec succés.
     *
     * @since    1.0
	 * @return	 object		Le résultat sous forme de ContentType
     * @access   public
     */
function getContent() {
	if ($this->sCode == "OK") {
		return($this->oResultat->content);
		} else {
		throw new Exception("Fusion failed:\nMessage: ".$this->sMessage."\nCode: ".$this->errNum);
		}
}

	// }}}
    // {{{ getCode ()

    /**
     * Renvoi le code retour de la requéte.
     *
     * @since    1.0
	 * @return	 string		Le code retour de la requéte SOAP
     * @access   public
     */
function getCode() {
	return($this->sCode);
}


	// }}}
    // {{{ getMessage ()

    /**
     * Renvoi le compte-rendu de la requéte.
     *
     * @since    1.0
	 * @return	 string		Le compte-rendu de la requéte SOAP
     * @access   public
     */
function getMessage() {
	return($this->sCode." : ".$this->sMessage);
}

    // }}}
    // {{{ sendContentToClient ()

    /**
     * Envoie le contenu de lobjet ContentType vers le client
     *
     * @since    1.0
     * @access   public
     */
function sendContentToClient() {
	//return;
	$oContent = $this->getContent();
	$oContent->sendToClient();
}

    // }}}
    // {{{ sendContentToFile ()

    /**
     * Renvoie le contenu vers le fichier spécifié.
     *
     * @since    1.0
     * @access   public
     * @param    string     $sFile      Chemin du fichier où stocker le résultat
     */
    function sendContentToFile($sFile)
    {
        $oContent = $this->getContent();
        $oContent->sendToFile($sFile);
    }

	function setDebug() {
		$this->debug=true;
	}


}