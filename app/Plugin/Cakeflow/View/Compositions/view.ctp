<?php

// Affichage du titre de la vue
if (!empty($contenuVue['titreVue']))
    echo '<h2>' . $contenuVue['titreVue'] . '</h2>';

// Affichage des sections principales
foreach ($contenuVue['sections'] as $section) {
    // affichage du titre de la section
    if (!empty($section['titreSection']))
        echo '<h4>' . $section['titreSection'] . '</h4>';
    echo '<dl>';
    // Parcours des lignes de la section
    foreach ($section['lignes'] as $iLigne => $ligne) {
        echo $this->element('viewLigne', array('ligne' => $ligne, 'altrow' => ($iLigne & 1)));
    }
    echo '</dl>';
}

// Affichage du lien de retour
echo '<div class=\'actions\'>';
echo $this->Html->link("<i class='fa fa-arrow-left'></i> ".$contenuVue['lienRetour']['title'], $contenuVue['lienRetour']['url'], array('escape' => false, 'class' => 'btn'));
echo '</div>';
?>
