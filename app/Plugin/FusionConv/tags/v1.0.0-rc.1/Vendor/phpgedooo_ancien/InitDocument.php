<?php
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

//
// Classe de fonctions utilitaires
//
$u = new GDO_Utility();

$repertoire = $_GET["repertoire"];
unset($_GET["repertoire"]);

$modele = $_GET["modele"];
unset($_GET["modele"]);

$nomFichierModele = $repertoire."/".$modele;

//echo $nomFichierModele;

$sTemplateContent = $u->ReadFile($nomFichierModele);

$oTemplate = new GDO_ContentType("", $modele, $u->getMimeType($modele), "binary", $sTemplateContent);


$oMainPart = new GDO_PartType();

foreach($_GET as $field => $value) {
	$oMainPart->addElement(new GDO_FieldType($field, $value, "string"));
	}

$oFusion = new GDO_FusionType($oTemplate, $u->extensionToMimeType("ott"), $oMainPart);

$oFusion->process();
$oFusion->SendContentToClient();


?>