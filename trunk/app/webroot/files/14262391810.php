<?php
/**
 **
 ** File created by Aurélien Massé
 ** 10/02/2015 11:15
 **
 */






//PHP TP 1
// EXERCICE 1 
// Ecrire un programme PHP qui teste si une année est bissextile
/*Une année est bissextile si :
condition 1 : l'année est divisible par 4 mais non divisible par 100
condition 2 : L'année est divisible par 400
Ce calcul est exact à compter du 15 octobre 1582, date de la réforme gréorienne
A partir de ces conditions on a juste à vérifier si les conditions 1 et 2 renvoient un entier
integrer. */


$SaisieAnnee = 1834; // ici on crée la variable et on l'initialise à 1834 (c'est arbitraire) plus tard on verra comment récupérer la valeur donnée par l'utilisateur
	if ($SaisieAnnee <1582 ) // si l'année est hors du calendrier grégorien on le dit et on ne fait rien d'autre
		{
			echo "Cette année ne fait pas partie du calendrier Grégorien.";
		}
	else // sinon ca veut dire que la date est dans le calendrier grégorien. Dans ce cas on fait nos calculs
		{
			if ((is_int($SaisieAnnee/4) && is_int($SaisieAnnee /100)) || is_int($SaisieAnnee /400)){ // on vérifie si (la variable est divisible par 4 ET par 100) OU (la variable est divisible par 400). Si c'est divisible, la valeur retournée est un entier (int) c'est pour ca que l'on teste avec is_int car si ce n'est pas divisible ca retournera un float dans ce cas on ne rentrera pas dans le if
				echo "l'année ".$SaisieAnnee." est bisextile."; // si on rentre dans le if c'est que l'année est bisextile
			}
			else{
				echo "l'année ".$SaisieAnnee." n'est pas bisextile"; // Sinon ca veut dire que l'année ne l'est pas
			}
		}
	
/**
 * s'il y a certaines choses que tu ne comprend pas, n'hesite pas à le dire
 */

?>

<?php // Attention il n'y a pas d'espace entre <? et php 
/* EXERCICE 2
Créer une fonction bissextile
Entrer un intervalle d 'année, par exemple : 1985, 2012 et itérer sur chaque année pour savoir si elle est bissextile.
*/
//$f_bissextile = is_int ($SaisieAnnee) ;

// Tout d'abord on va créer notre fonction (on peut la mettre au début ou à la fin mais je la met au début pour que tu comprenne l'évolution)

function isBisextile($annee){ //on déclare notre fontion qu'on apelle isBisextile et on précise qu'elle attend un paramètre que j'apelle annee
// Je réutilise ensuite le code qu'on a mis au dessus pour tester si c'est bisextile, mais au lieu d'écrire du texte, ce code va me retourner TRUE si l'année est bisextile et FALSE si elle ne l'est pas
// Tous les tests se font sur la variable $annee qui correspond à la date entrée en paramètre
if ($annee<1582) // Si $annee n'est pas dans la calendrier grégorien
		{
			return FALSE; // je retourne FALSE car la date est hors du calendrier grégorien
		}
	else // sinon ca veut dire que la date est dans le calendrier grégorien. Dans ce cas on fait nos calculs
		{
			if ((is_int($annee/4) && is_int($Sannee /100)) || is_int($annee/400)){ // on vérifie si (la variable est divisible par 4 ET par 100) OU (la variable est divisible par 400). Si c'est divisible, la valeur retournée est un entier (int) c'est pour ca que l'on teste avec is_int car si ce n'est pas divisible ca retournera un float dans ce cas on ne rentrera pas dans le if
				return TRUE; // si on rentre dans le if c'est que l'année est bisextile on retourne donc TRUE
			}
			else{
				return FALSE; // Sinon ca veut dire que l'année ne l'est pas
			}
		}
}

// DONC quand tu va apeller la focntion isBisextile en lui donnant une année, il retournera TRUE si l'année demandée est bisextile, FALSE si elle ne l'est pas.
// On va donc faire notre boucle afin de tester toutes les années entre deux années.

$anneeDebut=1985; // On crée une variable contenant l'année à laquelle doit démarer ma boucle
$anneeFin=2012; // On crée une variable contenant l'année à laquelle doit finir la boucle

for ($i=$anneeDebut; $i <= $anneeFin; $i++) //on crée notre boucle FOR pour i commencant à anneeDebut et finissant a anneeFin, a chaque itération de la boucle on incrémente i
{ 
	if(isBisextile($i)){  // on teste si la fonction isBisextile retourne TRUE. Si c'est le cas alors l'année est bisextile
		echo "L'année est bisextile";
	}
	else{ // sinon l'année n'est pas bisextile
		echo "l'année n'est pas bisextile";
	}
}
?>




<?php
// EXERCICE 3 
//Calculer la table de multiplication d'un entier donné en paramètre, avec l 'affichage HTML suivant : 

// Alors si j'ai bien compris le tableau qu'il te donne dans l'énnoncé, c'est la table de multiplication de 12 dans ce cas voici la correction avec l'affichage HTML:

function multiplication($nombre){ // dans l'énoncé il dit qu'il faut passer le nombre en paramètre ce qui veut dire: utiliser une fonction. Cependant s'il n'avait pas précisé ça on aurait pu le faire en dehors d'une focntion
	echo "<table>"; //On ouvre le tableau HTML
	echo "<tr>"; //On ouvre la première ligne. En effet la première ligne doit etre faite dans une boucle à part sinon elle contiendra 0 partout
	echo "<td>"; // On ouvre la première cellule de la ligne. En effet cette cellule est vide et correspond à la croisée.

	for ($a=0; $a <=$nombre ; $a++) { // Cette première boucle permet d'afficher tous les nombres entre 0 et $nombre pour faire l'entête de la table 
		echo "<th>"; // On ouvre une cellule (on utilise <th> car on fait la ligne d'entête et la balise TH est faite pour faire des entetes);
		 echo $a; // On affiche a (a est incrémenté à chaque tour dans la boucle on va donc écrire chaque valeur dans une nouvelle cellule)
		 echo "</th>"; // On ferme la cellule d'entête
	}
	echo "<tr/>"; // On referme la première ligne
		// Cette boucle a écrit la première ligne de notre tableau on peut ensuite faire une double boucle pour remplir le reste

	// On va utiliser une double boucle afin d'écrire X cellules par ligne. Dans chaque ligne on va créer la première cellule 
	// (car ce n'est pas une multiplication mais une entete latérale) puis on va entrer dans la boucle qui va calculer toutes les valeurs

	for ($i=0; $i <= $nombre; $i++) { //cette boucle permet de faire les lignes. Il y a autant de ligne que $nombre 
		echo "<tr>"; // On ouvre une ligne
		echo "<th>"; // On ouvre la cellule d'entête latérale
		echo $i; // On inscrit dans la cellule la valeur de $i (pour rappel $i est la valeur de chaque ligne et on commence par 0)
		echo "</th>"; // On referme la cellule d'entete latérale

		for ($j=0; $j <= $nombre; $j++) { // Cette boucle permet de faire les celulles. Il y a autant de cellules par ligne que $nombre.
			echo "<td>"; // On ouvre la cellule
			echo $i*$j; // On affiche la produit de la colonne et de la ligne
			echo "</td>"; // On ferme la cellule et on passe à la suivante
		}
		echo "</tr>"; // On ferme la ligne pour passer à la suivante
	}
	echo "</table>"; // On ferme le tableau HTML
}

/**
 * Voilà ta correction. J'éspère avoir été assez clair et j'éspère que tu as compris tout ce que j'ai fait. Une fois lue, éssaye de refaire les exos sans la correction.
 */


?>

