<?php
require_once("Publicator.pkg");
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Lille Metropole Communaute Urbaine (LMCU)         |
// +----------------------------------------------------------------------+
// | This file is part of Tiny.                                           |
// |                                                                      |
// | Tiny is free software; you can redistribute it and/or modify         |
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
// | along with Tiny; if not, write to the Free Software Foundation,      |
// | Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA        |
// +----------------------------------------------------------------------+
// | Authors: Dimitri Manchuelle, Philippe Allart                         |
// +----------------------------------------------------------------------+
//

/*
 * Classe OpenOfficeWS
 * ---------------
 * Objet gérant l'appel du web service OpenOffice pour le publipostage
 *
 * Version 2.0
 */


class OpenOfficeWS
{
    // Attributs systèmes (non modifiables)
    // --------------------------------------------
    // Objet Environment courant (référence)
    var $oEnv;

    // Attributs : tout est stocké dans le tableau aAttribs pour n'avoir qu'un seul getter et qu'un seul setter
    // Les attributs disponibles sont :
    //  sUrl  (obsolète)    string          URL d'accès au web service de publipostage
    //  sHost               string          Nom du serveur
    //  sDocument           string          Chemin d'accès au document maître
    //  sDocumentFinal      string          Où déposer le document produit
    //  sXMLData            string          Données XML de publipostage

    //  sNomCaddy          string          Nom du caddie utilisé pour les échanges de fichier
    //  sRepertoire         string          Nom du répertoire utilisé par le caddie

	//  sDisc				string			Discriminent unique associé à l'objet lors de sa création
	//  sTmpTemplate		string			Nom provisoire du modèle dans le caddie
	//  sOutputDocument		string			Nom provisoire du documents généré dans le caddie
	//  sBinaryOutputDocument	string		Le document produit au format binaire
	//  sMimeType			string			type MIME du document
    var $aAttribs = array();
    // --------------------------------------------

    //********  METHODES PRIVEES  ******************
    //
	//******** METHODES D'INTERET GENERAL **********

	// }}}
    // {{{ generateDocument ()

    /**
     * Generation d'un document dans un format donné
     *
     * @param    string     $sFormat        Format: pdf, odt
     * @since    2.0
     * @access   private
     * @return   rien
     */

	Function generateDocument($sFormat)
	 {
	 	$this->setValue("sMimeType", GDO_extensionToMimeType($sFormat));

		$sXMLData = $this->getValue("sXMLData");
		
		
		$oMainPart = GDO_XMLtoPart(utf8_encode($sXMLData));
		$sModele = $this->getValue("sDocument");
		
		$bTemplate = GDO_ReadFile($sModele);
		
		$sMimeType = $this->getValue("sMimeType");
		
		$oTemplate = new GDO_ContentType("", 
				"modele.ott", 
				GDO_getMimeType($sModele), 
				"binary",
				$bTemplate);
		
		$oFusion = new GDO_FusionType($oTemplate, $sMimeType, $oMainPart);
		$oFusion->process();
		$this->setValue("oFusion", $oFusion);
		$this->putContent();
	}

	// }}}
    // {{{ convertDocument ()

    /**
     * Conversion d'un document dans un format donné
     * En fait il s'agit d'une fusion "à vide".
     *
     * @param    string     $sFormat        Format: pdf, odt
     * @since    2.0
     * @access   private
     * @return   rien
     */

	Function convertDocument($sFormat)
	{
		// Attention: Non testé
		// Valider le code XML produit avec Christophe.
		//

		$this->setValue("sMimeType", $this->extensionToMimeType($sFormat));
		$sDocument = $this->getValue("sDocument");
		$sMimeType = $this->getValue("sMimeType");
		
		$bDocument = GDO_ReadFile($sDocument);
		$oDocument = new GDO_ContentType("", 
				"modele.ott", 
				GDO_getMimeType($sDocument), 
				"binary",
				$bDocument);

		$oFusion = new GDO_FusionType($oDocument, $sMimeType, new GDO_PartType);
		$this->setValue("oFusion", $oFusion);
		$this->putContent();


	}


	// }}}
    // {{{ putContent ()

    /**
     * Dépose le résultat à l'endroit demandé
     *
     * @since    2.0
     * @access   private
     * @return   rien
     */
	function putContent() {
	
	$oFusion = $this->getValue("oFusion");
	if ($oFusion->getCode() == "OK") {
		$sTargetName = $this->getValue("sDocumentFinal");
		$oContent = $oFusion->getContent();
		GDO_WriteFile($sTargetName, $oContent->getContent());
		}
		
		
	}

    //********  METHODES PUBLIQUES  ******************
    //

    // }}}
    // {{{ OpenOfficeWS ()

    /**
     * Constructeur
     *
     * @param    object     $oEnv          Pointeur sur environnement
     * @param    array      $aPrimaryKey   Tableau associatif contenant la clé primaire
     * @since    1.0
     * @access   public
     */

    Function OpenOfficeWS(&$oEnv, $aParams=array())
	{
        $this->oEnv =& $oEnv;

        // Initialisation
        if (is_array($aParams) && count($aParams)) {
            while (list($sNomChamp, $sValeurChamp) = each($aParams)) {
                $this->setValue($sNomChamp, $sValeurChamp);
            }
        }

    }


    // }}}
    // {{{ getValue()

    /**
     * Getter
     *
     * @param    string     $sKey       Clé
     * @since    1.0
     * @access   public
     * @return   mixed      valeur associée
     */

    function getValue($sKey)
	{
        if (isset($this->aAttribs[$sKey])) {
            return $this->aAttribs[$sKey];
        } else {
            return "";
        }
    }


    // }}}
    // {{{ setValue()

    /**
     * Setter
     *
     * @param    string     $sKey       Clé
     * @param    mixed      $sValue     Valeur
     * @since    1.0
     * @access   public
     */
    function setValue($sKey, $sValue)
	{
        $this->aAttribs[$sKey] = $sValue;
    }


    // }}}
    // {{{ process()

    /**
     * Lance l'opération de publipostage : envoie le document maître, les données XML et appelle le webservice
     *
     * @since    1.0
     * @access   public
     * @param    sFormat        Format de sortie (par défaut, PDF)
     */
    function process($sFormat="pdf")
	{

	$this->generateDocument($sFormat);

    }


    // }}}
    // {{{ docToPDF()

    /**
     * Transforme un .doc en .pdf : envoie le document word et appelle le webservice
     *
     * @since    1.0
     * @access   public
     */

	function convert($sFormat="pdf")
	{


        // envoi des données XML et lancement de la conversion
	
		$this->convertDocument($sFormat);


	}


    function docToPDF()
	{
		$this->convert("pdf");
    }

}

?>
