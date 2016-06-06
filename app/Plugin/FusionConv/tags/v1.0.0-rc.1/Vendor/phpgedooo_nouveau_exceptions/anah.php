<?php
require_once("GDO_wsdl.inc");
//define("GEDOOO_WSDL",  "http://arrigo-test:8081/axis2/services/OfficeService?wsdl");

require_once("GDO_Utility.class");
require_once("GDO_FieldType.class");
require_once("GDO_ContentType.class");
require_once("GDO_IterationType.class");
require_once("GDO_PartType.class");
require_once("GDO_FusionType.class");
require_once("GDO_CSV.class");

if ( isset($_FILES["data"])) {

// Methode POST avec envoi des fichiers
$methode = "POST";
$nomData = $_FILES["data"]["tmp_name"];
$nomColumns = $_FILES["columns"]["tmp_name"];
$nomFichierModele = $_FILES["model"]["tmp_name"];
$nomModele = $_FILES["model"]["name"];
$extension = $_POST["Format"];
$session = $_POST["session"];
$notification = $_POST["notification"];
$limite = $_POST["limite"];
$debug = $_POST["Debug"];

} else {
$methode = "GET";
// Methode GET avec transmission des URL
$nomData = $_GET["data"];
$nomColumns = $_GET["columns"];
$nomFichierModele = $_GET["model"];
$nomModele="temp.ott";
$extension = $_GET["Format"];
$session = $_GET["session"];
$notification = $_GET["notification"];
$limite = $_GET["limite"];
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
<td> Fichier CSV de données: </td><td><? echo $nomData;  ?></td>
</tr>
<tr>
<td> Fichier CSV des noms de colonnes: </td><td><? echo $nomColumns;  ?></td>
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
// Generation d'une iteration à partir du fichier CSV
//
$csv = new GDO_CSV("csv");
$csv->setCSVFile($nomData, ";", "\"", "UTF8");
$csv->setMapFile($nomColumns);
if ($session != "") $csv->addConstant("session", $session, "date"); 
if ($notification != "") $csv->addConstant("notification", $notification, "date"); 
if ($limite != "") $csv->addConstant("limite", $limite, "date"); 

$oIteration = $csv->getIteration();


$sTemplateContent = $u->ReadFile($nomFichierModele);  


$newstring=utf8_encode($sXMLContent);          // it's important!
$mainPart = new GDO_PartType();

$mainPart->addElement($oIteration);

$template = new GDO_ContentType("", $nomModele, $u->getMimeType($nomModele), "binary", $sTemplateContent);
$oFusion = new GDO_FusionType($template, $u->extensionToMimeType($extension), $mainPart);

if ($debug == "true") {

var_dump($oIteration);

exit;
}

ini_set('default_socket_timeout', 300); 
$oFusion->process();



$oFusion->SendContentToClient();



?>