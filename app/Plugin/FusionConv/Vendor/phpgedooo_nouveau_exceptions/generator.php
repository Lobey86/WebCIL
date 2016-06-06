<?
require_once("GDO_wsdl.inc");
require_once("GDO_Utility.class");
require_once("GDO_FieldType.class");
require_once("GDO_ContentType.class");
require_once("GDO_IterationType.class");
require_once("GDO_PartType.class");
require_once("GDO_FusionType.class");
require_once("GDO_MatrixType.class");
require_once("GDO_MatrixRowType.class");
require_once("GDO_AxisTitleType.class");
require_once("GDO_XML2GEDOOo.class");


if ( isset($_FILES["data"])) {

// Methode POST avec envoi des fichiers
$methode = "POST";
$nomFichierXML = $_FILES["data"]["tmp_name"];
$nomFichierModele = $_FILES["model"]["tmp_name"];
$nomModele = $_FILES["model"]["name"];
$extension = $_POST["Format"];
$debug = $_POST["Debug"];

} else {
$methode = "GET";
// Methode GET avec transmission des URL
$nomFichierXML = $_GET["data"];
$nomFichierModele = $_GET["model"];
$nomModele="temp.ott";
$extension = $_GET["Format"];
$debug = $_GET["Debug"];
}

if ($extension == "") {
	$extension = "pdf";
	}
	
if ($debug == "true") {
?>
<h1>Mode Debug</h1>
<h2>Param&egrave;tres re&ccedil;us</h2>
<table>
<tr>
<td> Type de requ&ecirc;te: </td><td><? echo $methode;  ?></td>
</tr>
<tr>
<td> Fichier XML: </td><td><? echo $nomFichierXML;  ?></td>
</tr>
<tr>
<td> Fichier Mod&egrave;le: </td><td><? echo $nomFichierModele;  ?></td>
</tr>
<tr>
<td> Format demand&eacute;: </td><td><? echo $extension;  ?></td>
</tr>
</table>

<?php

}

	
//
// Classe de fonctions utilitaires
//
$u = new GDO_Utility();
//
// Classe qui interface le fichier XML
//
$x = new GDO_XML2GEDOOo();

$sXMLContent = $u->ReadFile($nomFichierXML);  
$sTemplateContent = $u->ReadFile($nomFichierModele);  

if ($debug == "true") {
?>
<h2>Lecture des fichiers</h2>
<table>
<tr>
<td> Nom </td><td>Longueur</td>
</tr>
<tr>
<td><? echo $nomFichierXML;  ?></td><td><? echo strlen($sXMLContent);  ?></td>
</tr>
<tr>
<td><? echo $nomFichierModele;  ?></td><td><? echo strlen($sTemplateContent);  ?></td>
</tr>
</table>

<?php

}




$newstring=utf8_encode($sXMLContent);          // it's important!
$mainPart = $x->XMLToPart($newstring);
//var_dump($mainPart);
//exit;

//$template = new GDO_ContentType("", $nomModele, GDO_getMimeType($nomModele), "url", $nomFichierModele);
$template = new GDO_ContentType("", $nomModele, $u->getMimeType($nomModele), "binary", $sTemplateContent);
$oFusion = new GDO_FusionType($template, $u->extensionToMimeType($extension), $mainPart);
//var_dump($oFusion);
//echo "<br/>";

$oFusion->process();

if ($debug == "true") exit;

$oFusion->SendContentToClient();


?>
