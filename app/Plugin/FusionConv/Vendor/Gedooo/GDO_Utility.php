<?php

class GDO_Utility {

	// }}}
    // {{{ ReadFile()

    /**
     * renvoi le contenu d'un fichier
     *
     * @return   binary      Le contenu du fichier dans l'�tat
     * @since    1.0
     * @access   public
     */
function ReadFile($sFileName) {

//echo $sFileName;
$iFile = fopen($sFileName, "rb");

while ($tmp = fread($iFile, 1024))
    {
        @$sData .= $tmp;
    } 
//$sData = fread($iFile, filesize($sFileName));
fclose($iFile);

return $sData;
}

	// }}}
    // {{{ WriteFile()

    /**
     * Ecriture dans un fichier
     *
     * @param    string      $sFileName	le nom du fichier
     * @param    binary      $bData Le contenu � �crire
     * @since    1.0
     * @access   public
     */
function WriteFile($sFileName, $bData) {

	$iFile = fopen($sFileName, "wb");
	fwrite($iFile ,$bData);
	fclose($iFile);

}


//###############################################################################
/*
 * Gestion des types MIME
 * ----------------------*
 */


     // }}}
    // {{{ extensionToMimeType ()

    /**
     * D�termination d'un type MIME � partir d'une extension
     *
     * @param    string     $sExtension       Extension
     * @since    1.0
     * @access   public
     * @return   string     type MIME
     */

function extensionToMimeType($sExtension){

	switch (strtolower($sExtension)) {
		case "odt":
   			return "application/vnd.oasis.opendocument.text";
   			break;
		case "ods":
   			return "application/vnd.oasis.opendocument.spreadsheet";
   			break;
		case "odp":
   			return "application/vnd.oasis.opendocument.presentation";
   			break;
   		case "odg":
   			return "application/vnd.oasis.opendocument.graphics";
   			break;
   		case "odc":
   			return "application/vnd.oasis.opendocument.chart";
   			break;
   		case "odf":
   			return "application/vnd.oasis.opendocument.formula";
   			break;
   		case "odb":
   			return "application/vnd.oasis.opendocument.database";
   			break;
   		case "odi":
   			return "application/vnd.oasis.opendocument.image";
   			break;
   		case "odm":
   			return "application/vnd.oasis.opendocument.text-master";
   			break;
   		case "ott":
   			return "application/vnd.oasis.opendocument.text-template";
   			break;
   		case "ots":
   			return "application/vnd.oasis.opendocument.spreadsheet-template";
   			break;
   		case "otp":
   			return "application/vnd.oasis.opendocument.presentation-template";
   			break;
   		case "pdf":
   			return "application/pdf";
   			break;
   		case "doc":
   			return "application/msword";
   			break;
    	case "dxf":
   			return "application/dxf";
   			break;
  		case "jpg":
   		case "jpeg":
   		case "jpe":
  			return "image/jpeg";
   			break;
   		case "gif":
   			return "image/gif";
   			break;
   		case "png":
   			return "image/png";
   			break;
   		case "bmp":
   			return "image/bmp";
   			break;
    	case "tiff":
  		case "tif":
   			return "image/tiff";
   			break;
   		case "svg":
   			return "image/svg+xml";
   			break;
   		case "wmf":
   			return "image/wmf";
   			break;
   		case "emf":
   			return "image/emf";
   			break;
		default:
   			return "application/octet-stream";
   			 
   		
	}
}

    // }}}
    // {{{ getMimeType ()

    /**
     * D�termination d'un type MIME � partir d'un nom de fichier
     *
     * @param    string     $sNomFichier       Nom du fichier
     * @since    1.0
     * @access   public
     * @return   string     type MIME
     */
function getMimeType($sFileName){
	$aFileName = explode(".",$sFileName);
	$sExtension = $aFileName[count($aFileName)-1];
	return $this->extensionToMimeType($sExtension);
}

    // }}}
    // {{{ typeMimeToExtension ()

    /**
     * renvoi une extension en fonction d'un type MIME
     *
     * @param    string     $sTypeMime       Type MIME
     * @since    1.0
     * @access   public
     * @return   string     Extension
     */
function mimeTypeToExtension($sMimeType) {

	switch (strtolower($sMimeType)) {
   		case "application/vnd.oasis.opendocument.text":
			return "odt";
   			break;
   		case "application/vnd.oasis.opendocument.spreadsheet":
			return "ods";
   			break;
   		case "application/vnd.oasis.opendocument.presentation":
			return "odp";
   			break;
   		case "application/vnd.oasis.opendocument.graphics":
			return "odg";
   			break;
   		case "application/vnd.oasis.opendocument.chart":
			return "odc";
   			break;
   		case "application/vnd.oasis.opendocument.formula":
			return "odf";
   			break;
   		case "application/vnd.oasis.opendocument.database":
			return "odb";
   			break;
   		case "application/vnd.oasis.opendocument.image":
			return "odi";
   			break;
   		case "application/vnd.oasis.opendocument.text-master":
			return "odm";
   			break;
   		case "application/vnd.oasis.opendocument.text-template":
			return "ott";
   			break;
   		case "application/vnd.oasis.opendocument.spreadsheet-template":
			return "ots";
   			break;
   		case "application/vnd.oasis.opendocument.presentation-template":
			return "otp";
   			break;
   		case "application/pdf":
 			return "pdf";
  			break;
   		case "application/msword":
 			return "doc";
  			break;
		default:
			return "bin";
	}
}

    // }}}
    // {{{ completeFileName ()

    /**
     * Ajout d'une extension � un nom de fichier
     *
     * @param    string     $sName           Nom de fichier � compl�ter
     * @param    string     $sTypeMime       Type MIME
     * @since    1.0
     * @access   public
     * @return   string     Nom de fichier complet
     */
function completeFileName($sName, $sMimeType) {

	return $sName . "." . $this->mimeTypeToExtension($sMimeType);

}

}
?>
