<?php

require_once("Publicator.pkg");

$wsdl = GDO_wsdl();
$classmap = GDO_classmap();

$oService = new SoapClient($wsdl,
				array("cache_wsdl"=>WSDL_CACHE_NONE,
				"exceptions"=> 0,
				"trace"=>1,
				"classmap"=>$classmap));

//
// Partie principale du document
//
$oMainPart = new GDO_PartType;

//
// Insertion de quelques valeurs dans la partie principale
//
$oMainPart->addElement(new GDO_FieldType("Nom", "Allart", "string"));
$oMainPart->addElement(new GDO_FieldType("Prenom", "Philippe", "string"));
$oMainPart->addElement(new GDO_FieldType("Fonction", "Chef de projet", "string"));

//
// Une zone répétitive (anciennement nommée "bloc")
//

$oIteration = new GDO_IterationType("Projets");

//
// Création de plusieurs occurences, avec insertion de valeurs
//

for ($i = 1; $i <=3 ; $i++) {
	$oSubPart = new GDO_PartType();
	$oSubPart->addElement(new GDO_FieldType("Projet", "projet_".$i, "string"));
	$oSubPart->addElement(new GDO_FieldType("DateFin", "date_".$i, "string"));
	$oIteration->addPart($oSubPart);
	}
//
// Insertion de l'itération dans la partie principale
//
$oMainPart->addElement($oIteration);

//
// Definition du modèle de document (ici avec une url)
//

$oTemplate = new GDO_ContentType(
		 "Modele.ott",
		 "application/vnd.oasis.opendocument.text-template",
		 "binary",
		 GDO_ReadFile("file:///var/office/tmp/Fiche_immo_portrait.ott"));

//
// Creation de la requete de fusion, en lui passant tous les éléments
//

$oFusion = new GDO_FusionType($oTemplate,
			"application/vnd.oasis.opendocument.text",
			 $oMainPart);

echo($oFusion->getMessage());

if ($oFusion->getCode() == "OK") {
	$sResultat = $oFusion->getContent();
//	var_dump($sResultat);
	echo "<br/>";
	echo "Classe: ".get_class($sResultat);
	
}

//var_dump($oService->__getlastRequest());
//var_dump($oService->__getLastResponse());

//var_dump($oResultat);

?>