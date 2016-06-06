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

} else {
$methode = "GET";
// Methode GET avec transmission des URL
$nomFichierXML = $_GET["data"];
$nomFichierModele = $_GET["model"];
$nomModele="temp.ott";
$extension = $_GET["Format"];
}

if ($extension == "") {
	$extension = "pdf";
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
$oFusion->SendContentToClient();


?>
