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
 * Classe GDO_ContentType
 * ---------------
 * Un objet de type GDO_ContentType contient les références à un document
 * Ce document a un type MIME et peut se trouver
 *  - a une url donnée
 *  - dans l'objet lui-même sous forme binaire
 *  - dans l'objet lui-même sous forme d'un texte en html.
 *
 * Version 1.0
 */
 
Class GDO_ContentType {
var $name;
var $target;
var $mimeType;
var $url;
var $binary;
var $text;

private $mode;


function is_utf8($str) {
    $c=0; $b=0;
    $bits=0;
    $len=strlen($str);
    for($i=0; $i<$len; $i++){
        $c=ord($str[$i]);
        if($c > 128){
            if(($c >= 254)) return false;
            elseif($c >= 252) $bits=6;
            elseif($c >= 248) $bits=5;
            elseif($c >= 240) $bits=4;
            elseif($c >= 224) $bits=3;
            elseif($c >= 192) $bits=2;
            else return false;
            if(($i+$bits) > $len) return false;
            while($bits > 1){
                $i++;
                $b=ord($str[$i]);
                if($b < 128 || $b > 191) return false;
                $bits--;
            }
        }
    }
    return true;
}

function convertText($text) {
         $cp1252_map = array(
              "\xc2\x80" => "\xe2\x82\xac", /* EURO SIGN */
              "\xc2\x82" => "\xe2\x80\x9a", /* SINGLE LOW-9 QUOTATION MARK */
              "\xc2\x83" => "\xc6\x92", /* LATIN SMALL LETTER F WITH HOOK */
              "\xc2\x84" => "\xe2\x80\x9e", /* DOUBLE LOW-9 QUOTATION MARK */
              "\xc2\x85" => "\xe2\x80\xa6", /* HORIZONTAL ELLIPSIS */
              "\xc2\x86" => "\xe2\x80\xa0", /* DAGGER */
              "\xc2\x87" => "\xe2\x80\xa1", /* DOUBLE DAGGER */
              "\xc2\x88" => "\xcb\x86", /* MODIFIER LETTER CIRCUMFLEX ACCENT */
              "\xc2\x89" => "\xe2\x80\xb0", /* PER MILLE SIGN */
              "\xc2\x8a" => "\xc5\xa0", /* LATIN CAPITAL LETTER S WITH CARON */
              "\xc2\x8b" => "\xe2\x80\xb9", /* SINGLE LEFT-POINTING ANGLE QUOTATION */
              "\xc2\x8c" => "\xc5\x92", /* LATIN CAPITAL LIGATURE OE */
              "\xc2\x8e" => "\xc5\xbd", /* LATIN CAPITAL LETTER Z WITH CARON */
              "\xc2\x91" => "\xe2\x80\x98", /* LEFT SINGLE QUOTATION MARK */
              "\xc2\x92" => "\xe2\x80\x99", /* RIGHT SINGLE QUOTATION MARK */
              "\xc2\x93" => "\xe2\x80\x9c", /* LEFT DOUBLE QUOTATION MARK */
              "\xc2\x94" => "\xe2\x80\x9d", /* RIGHT DOUBLE QUOTATION MARK */
              "\xc2\x95" => "\xe2\x80\xa2", /* BULLET */
              "\xc2\x96" => "\xe2\x80\x93", /* EN DASH */
              "\xc2\x97" => "\xe2\x80\x94", /* EM DASH */
              "\xc2\x98" => "\xcb\x9c", /* SMALL TILDE */
              "\xc2\x99" => "\xe2\x84\xa2", /* TRADE MARK SIGN */
              "\xc2\x9a" => "\xc5\xa1", /* LATIN SMALL LETTER S WITH CARON */
              "\xc2\x9b" => "\xe2\x80\xba", /* SINGLE RIGHT-POINTING ANGLE QUOTATION*/
              "\xc2\x9c" => "\xc5\x93", /* LATIN SMALL LIGATURE OE */
              "\xc2\x9e" => "\xc5\xbe", /* LATIN SMALL LETTER Z WITH CARON */
              "\xc2\x9f" => "\xc5\xb8" /* LATIN CAPITAL LETTER Y WITH DIAERESIS*/
              );
              return strtr($text, $cp1252_map);
}

function fixEncoding($in_str)
{
  $cur_encoding = mb_detect_encoding($in_str) ;
  if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))
    return $in_str;
  else
    return utf8_encode($in_str);
} // fixEncoding 


	// }}}
    // {{{ GDO_ContentType ()

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
function GDO_ContentType($target, $name, $mimeType, $mode, $value) {
	$this->mode = $mode;
	if ($target != "") 
            $this->target = $target;
	if ($name != "") 
            $this->name = $name;
	$this->mimeType = $mimeType;
	
	switch($mode) {
		case "url" :
			$this->url = $value;
			break;
		case "binary" :
			$this->binary = $value;
			break;
		case "text" :
                        $value = $this->convertText($value);
                        $value = $this->fixEncoding($value);
			$this->text = $value;
			break;
		}
}

	// }}}
    // {{{ getName ()

    /**
     * Renvoi le nom du document
     *
	 * @return   string		Le nom du document
     * @since    1.0
     * @access   public
     */
function getName() {
	return($this->name);
}


	// }}}
    // {{{ getMimeType ()

    /**
     * Renvoi le type MIME du contenu
     *
	 * @return   string		Le type MIME
     * @since    1.0
     * @access   public
     */
function getMimeType() {
	return($this->mimeType);

}

	// }}}
    // {{{ getMimeType ()

    /**
     * Renvoi le type MIME du contenu
     *
	 * @return   string		Le type MIME
     * @since    1.0
     * @access   public
     */
function getContent() {

	if (isset($this->url)) return(file_get_contents($this->url));
	if (isset($this->text)) return($this->text);
	if (isset($this->binary)) return($this->binary);

	throw new Exception("Content not available.");
}

	// }}}
    // {{{ sendToClient ()

    /**
     * Renvoi le contenu vers le client.
	 * Si le contenu est spécifié par une URL, il est d'abord récupéré.
     *
     * @since    1.0
     * @access   public
     */
function sendToClient($sFileName=null, $sMimeType=null) {

//
//  Accéder à toutes les données avant de lancer le premier header
//

if ($sMimeType == null)
    $sMimeType = $this->getMimeType();
if ($sFileName == null)
    $sFileName = $this->getName();

$bContent  = $this->getContent();

header("Content-type: $sMimeType");
header("Content-disposition: attachment; filename=".$sFileName);
header("Content-length: " . strlen($bContent));
echo $bContent;
exit;
}


    // }}}
    // {{{ sendToFile ()

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

}
?>
