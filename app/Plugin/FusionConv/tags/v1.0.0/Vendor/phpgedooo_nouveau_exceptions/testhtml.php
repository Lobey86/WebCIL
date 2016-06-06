<?php
require_once("GDO_wsdl.inc");
require_once("GDO_Utility.class");
require_once("GDO_FieldType.class");
require_once("GDO_ContentType.class");
require_once("GDO_DrawingType.class");
require_once("GDO_ShapeType.class");
require_once("GDO_IterationType.class");
require_once("GDO_PartType.class");
require_once("GDO_FusionType.class");
require_once("GDO_MatrixType.class");
require_once("GDO_MatrixRowType.class");
require_once("GDO_AxisTitleType.class");

$extension = $_GET["format"];

$server = $_SERVER["SERVER_NAME"];
$script = $_SERVER["PHP_SELF"];
$dir = dirname($script);

$ressourcesURL="http://" . $server . $dir . "/ressources/";

//
// initialisation des donnée pour la démo
//

if ($extension == "odt") {
$sMimeType = "application/vnd.oasis.opendocument.text";
} else {
$sMimeType = "application/pdf";
}
//
// Organisation des données
//
$u = new GDO_Utility();

$oMainPart = new GDO_PartType();
$url = $ressourcesURL . "test.html";
$url = "http://arrigo-dev/phpgedooo/";
//$url = "http://sezam/";

// Création du modèle en HTML
$oTemplate = new GDO_ContentType("", "contenu.html", 
		"text/html",
		"url", 
		$url);

//
// Lancement de la fusion
//
$oFusion = new GDO_FusionType($oTemplate, $sMimeType, $oMainPart);
// $oFusion->setDebug();
$oFusion->process();

//
// Envoi du rÃ©sultat
//
$oFusion->SendContentToClient();		
?>
