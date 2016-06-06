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
$developers[] = array("nom"=>"Kijewska", "prenom"=>"Christophe");
$developers[] = array("nom"=>"Allart", "prenom"=>"Philippe");

$competences["Kijewska"][] = array("competence"=>"Java", "niveau"=>5);
$competences["Kijewska"][] = array("competence"=>"GED", "niveau"=>5);
$competences["Kijewska"][] = array("competence"=>"PHP", "niveau"=>4);
$competences["Kijewska"][] = array("competence"=>"SOA", "niveau"=>4);

$competences["Allart"][] = array("competence"=>"C", "niveau"=>5);
$competences["Allart"][] = array("competence"=>"OOOBasic", "niveau"=>4);
$competences["Allart"][] = array("competence"=>"PL/1", "niveau"=>4);
$competences["Allart"][] = array("competence"=>"SOA", "niveau"=>3);
$competences["Allart"][] = array("competence"=>"BPEL", "niveau"=>2);

$coordonnees["Kijewska"][] = array("coordonnee"=>"mail: ckijewska@cudl-lille.fr");

$coordonnees["Allart"][] = array("coordonnee"=>"tel: 0320212488");
$coordonnees["Allart"][] = array("coordonnee"=>"fax: 0320212499");
$coordonnees["Allart"][] = array("coordonnee"=>"mail: pallart@cudl-lille.fr");

$projet = "GED'OOo";
$description="Système d'intégration de contenu prenant en compte la production de document, l'indexation, la recherche et l'archivage";
$standards="SOAP, WSDL, CMIS, BPEL";
$lancement="01/08/07";

$listeNumerotee="Voici un élément;\nPuis un deuxième;\net un troisième.";

$sModele = "ressources/testgedooo.ott";

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

$oMainPart->addElement(new GDO_FieldType("projet", $projet, "string"));
$oMainPart->addElement(new GDO_FieldType("description", $description, "string"));
$oMainPart->addElement(new GDO_FieldType("standards", $standards, "string"));
$oMainPart->addElement(new GDO_FieldType("lancement", $lancement, "date"));


// La liste numÃ©rotÃ©e aprÃ¨s le dessin
$oMainPart->addElement(new GDO_FieldType("ListeNumerotee", $listeNumerotee, "text"));

$oIteration = new GDO_IterationType("developers");

foreach($developers as $aDeveloper) {
	$oDevPart = new GDO_PartType();
	$oDevPart->addElement(new GDO_FieldType("nom", $aDeveloper["nom"], "string"));
	$oDevPart->addElement(new GDO_FieldType("prenom", $aDeveloper["prenom"], "string"));
	$bImage = $u->ReadFile("ressources/".$aDeveloper["nom"].".png");
	$oDevPart->addElement(new GDO_ContentType("photo", "", "image/png", 
							"binary", $bImage));

	// Insertion des competences du développeur
	
	$oCompetences = new GDO_IterationType("Competences");
	foreach($competences[$aDeveloper["nom"]] as $aComp) {
	  $oCompPart = new GDO_PartType();
	  $oCompPart->addElement(new GDO_FieldType("competence", $aComp["competence"], "string"));
	  $oCompPart->addElement(new GDO_FieldType("niveau", $aComp["niveau"], "string"));
	  $oCompetences->addPart($oCompPart);
	  } 
	$oDevPart->AddElement($oCompetences);

	// Insertion des coordonnées du développeur
	
	$oCoordonnees = new GDO_IterationType("coordonnees");
	foreach($coordonnees[$aDeveloper["nom"]] as $aCoord) {
	  $oCoordPart = new GDO_PartType();
	  $oCoordPart->addElement(new GDO_FieldType("coordonnee", $aCoord["coordonnee"], "string"));
	  $oCoordonnees->addPart($oCoordPart);
	  }
	$oDevPart->AddElement($oCoordonnees);

	$oIteration->addPart($oDevPart);
	}
	
$oMainPart->addElement($oIteration);

//
// Coloriage du dessin
//

$shapes[] =  array("nom"=>"A", "style"=>"Bleu");
$shapes[] =  array("nom"=>"B", "style"=>"Rouge");
$shapes[] =  array("nom"=>"C", "style"=>"Jaune");

$oDrawing = new GDO_DrawingType("monDessin");

foreach($shapes as $aShape) {
	$oDrawing->addShape(new GDO_ShapeType($aShape["nom"], $aShape["style"], ""));
	}
$oMainPart->addElement($oDrawing);

// Insertion d'un document en le passant dans la requete
// delui-ci sera localisé à deux endroits dans le modèle de démonstration
$document1 = $u->ReadFile("ressources/document1.odt");
$oMainPart->addElement(new GDO_ContentType("Document1", "", 
		$u->getMimeType("document1.odt"),
		"binary", $document1));

$oMainPart->addElement(new GDO_ContentType("Document2", "", 
		$u->getMimeType("document2.odt"),
		"url", 
		$ressourcesURL . "document2.odt"));
		
// Ajout d'un contenu HTML inséré dans le requete
// delui-ci sera localisé à deux endroits dans le modèle de démonstration
$oMainPart->addElement(new GDO_ContentType("contenuHTML", "", 
		"text/html",
		"text", 
		"<html>
		<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
		</head><body><table border=1>
		<tr bgcolor=\"#ffff00\"><td>Nom</td><td>Prénom</td></tr>
		<tr bgcolor=\"#ffff88\"><td>Dupond</td><td>Jean</td></tr>
		<tr bgcolor=\"#ffff88\"><td>Smith</td><td>John</td></tr>
		</table></body></html>"));

// Ajout d'un autre contenu HTML inséré dans le requete

$oMainPart->addElement(new GDO_ContentType("contenuHTML2", "", 
		"text/html",
		"text", 
		"<html>
		<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
		</head><body><table border=1>
		<tr bgcolor=\"#00ff00\"><td>Nom</td><td>Prénom</td></tr>
		<tr bgcolor=\"#88ff88\"><td>Durand</td><td>Paul</td></tr>
		</table></body></html>"));

$url = $ressourcesURL . "test.html";
$url = "http://arrigo-dev/phpgedooo/index.html";

// Ajout d'un contenu HTML spécifié par une URL
$oMainPart->addElement(new GDO_ContentType("contenuHTML3", "", 
		"text/html",
		"url", 
		$url));
//
// Creation du contenu pour le modÃ¨le
// Ici on lit le document et on le charge dans l'objet
// Il est possible Ã©galement de donner seulement une URL
//
$bTemplate = $u->ReadFile($sModele);
$oTemplate = new GDO_ContentType("", 
				"modele.ott", 
				$u->getMimeType($sModele), 
				"binary",
				$bTemplate);		
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
