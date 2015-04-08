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
 * Un objet de type GDO_FusionType contient toutes les sp�cifications
 * permettant de produire un document.
 * Il fait r�f�rence au mod�le de document au moyen d'un GDO_ContentType
 * Il sp�cifie les donn�es � ins�rer au moyen d'un GDO_PartType.
 * Il indique �galement le type MIME du document � produire
 * 
 * Version 1.0
 */


Class GDO_FusionType {
var $template;
var $mimeType;
var $part;
private $oResultat;
private $sCode;
private $sMessage;

	// }}}
    // {{{ GDO_FusionType ()

    /**
     * Constructeur
     *
     * @param    object      $template   Le modele
     * @param    string      $mimeType   Le type MIME du document � produire
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
     * L'array "classmap" �tablie la relation entre les types d'objets PHP
	 * et les types utilis�s dans le WSDL
     *
     * @return   array      La relation entre les types WSDL et les classes PHP
     * @since    1.0
     * @access   public
     */
function classMap() {
     return array(
		"FieldType" => "GDO_FieldType",
		"ContentType" => "GDO_ContentType",
		"FusionType" => "GDO_FusionType",
		"IterationType" => "GDO_IterationType",
		"PartType" => "GDO_PartType",
		"MatrixType"=>"GDO_MatrixType",
		"MatrixRowType"=> "GDO_MatrixRowType",
		"MatrixTitleType"=>"GDO_MatrixTitleType");
}

	// }}}
    // {{{ process ()

    /**
     * Execution de la requ�te
	 * La requ�te estlanc�e out de suite apr�s que l'objet est cr��.
     *
     * @since    1.0
     * @access   public
     */
function process() {
	$wsdl = GEDOOO_WSDL;
	$classmap = $this->classmap();
	try {
		$this->sCode = "OK";
		$this->sMessage = "The fusion was successful.";
		$oService = new SoapClient($wsdl,
				array("cache_wsdl"=>WSDL_CACHE_NONE,
				"exceptions"=> 1,
				"trace"=>1,
				"classmap"=>$classmap));
		$this->oResultat = $oService->Fusion($this);

                //$fp = fopen('/tmp/soap.log', 'w');
                //fwrite($fp,  "Request :\n".$oService->__getLastRequest() ."\n" );
                //fwrite($fp,  "Response:\n".$oService->__getLastResponse() ."\n" );
                //fclose($fp);

		} catch (Exception $e) {
                    
                    $this->errNum=$e->getCode();
                    $this->sMessage=$e->getMessage();
			
                        /*$soapfault = get_object_vars($e);
			if (!empty($soapfault["faultstring"]) && is_object($soapfault["faultstring"])) {
                            
				$detail = get_object_vars($soapfault["detail"]);
				foreach ($detail as $exception => $body) {
                                    var_dump($body);
					foreach ($body as $e2 => $b2) { 
						$details = get_object_vars($b2);
						$this->errNum = $details["faultcode"];
						$this->sMessage = $details["faultstring"];
						}
					}
                        } else {
                            $this->errNum = "000";
                            $this->sMessage =  $soapfault["faultstring"];
                        }*/
			$this->sCode="Error";
		}
}

	// }}}
    // {{{ getContent ()

    /**
     * Renvoi le contenu retourn� par la requ�te
	 * sous forme d'un objet de type ContentType
	 * La requ�te doit s'�tre ex�cut�e avec succ�s.
     *
     * @since    1.0
	 * @return	 object		Le r�sultat sous forme de ContentType
     * @access   public
     */
function getContent() {
    if ($this->sCode == "OK") {
            return($this->oResultat->content);
    } else {
        throw new Exception($this->sMessage, $this->errNum);
    }
}

	// }}}
    // {{{ getCode ()

    /**
     * Renvoi le code retour de la requ�te.
     *
     * @since    1.0
	 * @return	 string		Le code retour de la requ�te SOAP
     * @access   public
     */
function getCode() {
	return($this->sCode);
}


	// }}}
    // {{{ getMessage ()

    /**
     * Renvoi le compte-rendu de la requ�te.
     *
     * @since    1.0
	 * @return	 string		Le compte-rendu de la requ�te SOAP
     * @access   public
     */
function getMessage() {
	return($this->sCode.": ".$this->sMessage);
}

    // }}}
    // {{{ sendContentToClient ()

    /**
     * Envoie le contenu de lobjet ContentType vers le client
     *
     * @since    1.0
     * @access   public
     */
function sendContentToClient($sFileName=null, $sMimeType=null) {
	//return;
	$oContent = $this->getContent();
	$oContent->sendToClient($sFileName, $sMimeType);
}

    // }}}
    // {{{ sendContentToFile ()

    /**
     * Renvoie le contenu vers le fichier sp�cifi�.
     *
     * @since    1.0
     * @access   public
     * @param    string     $sFile      Chemin du fichier o� stocker le r�sultat
     */
    function sendContentToFile($sFile)
    {
        $oContent = $this->getContent();
        $oContent->sendToFile($sFile);
    }

}
?>
