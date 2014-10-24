<?php
/**
 * Préparation du contenu des vues détaillées (view) dans les controleurs.
 *
 */

class VueDetailleeComponent extends Component{
	private $contenuVue = array();

	function __construct($titreVue = 'Vue d&eacute;taill&eacute;e', $lienRetourTitle = 'Retour', $lienRetourUrl = array('action' => 'index')) {
		$this->contenuVue['titreVue'] = $titreVue;
		$this->contenuVue['lienRetour'] = array(
			'title' => $lienRetourTitle,
			'url' => $lienRetourUrl);
		$this->contenuVue['sections'] = array();
	}

/*
 * Ajoute une section générale
 */
	function ajouteSection($nom = '') {
		$this->contenuVue['sections'][] = array(
			'titreSection' => $nom,
			'lignes' => array()
		);
	}

/*
 * ajoute une nouvelle ligne à la dernière section
 */
	function ajouteLigne($libelle, $valeur = '', $class='') {
		$iSection = count($this->contenuVue['sections'])-1;
		$this->contenuVue['sections'][$iSection]['lignes'][][] = array(
			'libelle' => $libelle,
			'valeur' => $valeur,
			'class' => $class
		);
	}

/*
 * ajoute un nouvel élément à la dernière ligne de la dernière section
 */
	function ajouteElement($libelle, $valeur = '') {
		$iSection = count($this->contenuVue['sections'])-1;
		$iLigne = count($this->contenuVue['sections'][$iSection]['lignes'])-1;
		$this->contenuVue['sections'][$iSection]['lignes'][$iLigne][] = array(
			'libelle' => $libelle,
			'valeur' => $valeur
		);
	}

/*
 * retourne le contenue de la vue
 */
	function getContenuVue() {
		return $this->contenuVue;
	}

}
?>
