<?
// Ce script est un exemple permettant de traiter la fin d'une requête de
// publipostage.
// il fait partie de l'application qui intègre GED'OOo.
// Voir la documentation PHP du projet pour plus d'informations.

if ($_GET["code"] == 0) {

// Tous s'est bien passé
// On peut procéder à certains nettoyages
// et quitter sans rien renvoyer

} else {
// Cas d'erreur
?>
<HTML>
<BODY>
<H1> Erreur pendant le traitement</H1>

<p>Le serveur de publipostage a retourn&eacute; le message suivant:</p>

<p><font color="red"><i><#MESSAGE></i></font><p>

<p>Veuillez prendre contact avec votre administrateur</p>


</BODY>
</HTML>

<?
// Insérer ici du code pour gérer l'erreur
// au niveau de l'application

}
?>