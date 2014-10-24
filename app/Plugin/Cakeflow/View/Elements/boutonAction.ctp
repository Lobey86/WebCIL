<?php
/*
	Affiche un bouton de la colonne 'actions' (link sur une image)
	Paramètres :
		array $url : url du lien du bouton sous la forme array('action'=> , id)
		array $class : class du lien du bouton
		string $title = null : infobulle. Si null, initialisé en fonction de l'action de l'url
		string $confirmMessage = false : message de confirmation
		string $iconPath = null : chemin de l'icone du bouton. Si vide, initialisé en fonction de l'action de l'url
		string $iconHeight = "24" : hauteur de l'image du bouton
		$string $iconWidth = "24" : largeur de l'image du bouton
*/
/* Initialisation des paramètres */
if (empty($url))
	return;
if (empty($title)) {
	if ($url['action'] == 'view') $title = __('Visualiser', true);
	elseif ($url['action'] == 'add') $title = __('Ajouter', true);
	elseif ($url['action'] == 'edit') $title = __('Modifier', true);
	elseif ($url['action'] == 'delete') $title = __('Supprimer', true);
}
$confirmMessage = empty($confirmMessage) ? false : $confirmMessage;
/* Initialisation du path de l'icone si il est vide */
if (empty($iconPath) && !empty($url['action'])) {
	if ($url['action'] == 'view') $iconPath = '/cakeflow/img/icons/visualiser.png';
	elseif ($url['action'] == 'add') $iconPath = '/cakeflow/img/icons/ajouter.png';
	elseif ($url['action'] == 'edit') $iconPath = '/cakeflow/img/icons/modifier.png';
	elseif ($url['action'] == 'delete') $iconPath = '/cakeflow/img/icons/supprimer.png';
}
$iconHeight = empty($iconHeight) ? 24 : $iconHeight;
$iconWidth = empty($iconWidth) ? 24 : $iconWidth;
$class = empty($class) ? 'link_wkf_'.$url['action'] : $class;

echo $this->Html->link(
	$this->Html->image($iconPath, array(
        'style'=>"border:0; height:{$iconHeight}px; width:{$iconWidth}px;", 'alt'=>$title
    )),
	$url,
	array('title'=>$title,'class' => $class, 'escape' => false), $confirmMessage);
