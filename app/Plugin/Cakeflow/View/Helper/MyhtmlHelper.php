<?php
/*
 * Created on 24 janv. 09
 */
class MyhtmlHelper extends HtmlHelper {

	function bouton($url, $title = null, $confirmMessage = false, $path = null, $height = "24", $width = "24", $class=false) {
		/* Initialisation du title si il est vide */
		if (empty($title) && !empty($url['action'])) {
			if ($url['action'] == 'view') $title = __('Visualiser', true);
			elseif ($url['action'] == 'add') $title = __('Ajouter', true);
			elseif ($url['action'] == 'edit') $title = __('Modifier', true);
			elseif ($url['action'] == 'delete') $title = __('Supprimer', true);
		}
		/* Initialisation du path de l'icone si il est vide */
		if (empty($path) && !empty($url['action'])) {
			if ($url['action'] == 'view') $path = '/cakeflow/img/icons/visualiser.png';
			elseif ($url['action'] == 'add') $path = '/cakeflow/img/icons/ajouter.png';
			elseif ($url['action'] == 'edit') $path = '/cakeflow/img/icons/modifier.png';
			elseif ($url['action'] == 'delete') $path = '/cakeflow/img/icons/supprimer.png';
		}
        $class = empty($class) ? 'link_wkf_'.$url['action'] : $class;

		return $this->link(
				$this->image($path, array(
                    'style'=>"border:0; height:{$height}px; width:{$width}px;", 'alt'=>$title
                )),
				$url,
				array('title'=> $title, 'class' => $class, 'escape' => false), $confirmMessage);
	}

}
?>
