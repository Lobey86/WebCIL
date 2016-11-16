<?php
echo $this->Html->script(array(
    'FormGenerator.jquery-ui.min',
    'FormGenerator.createForm'
));
echo $this->Html->css(array(
    'FormGenerator.jquery-ui.min',
    'jquery-ui.structure.min'
));
?>

<!-- En-tête du formulaire -->
<div class="col-md-12 form-oblig form-horizontal">
    <div class="row">
        <!-- Texte -->
        <div class="col-md-12">
            <span class='labelFormulaire'><?php echo __d('formulaire', 'formulaire.textInfo'); ?></span>
            <div class="row row35"></div>
        </div>
        
        <div class="col-md-6">
            <?php
            // Champ Nom * grisé (Remplissage automatique) => Nom de l'utilisateur qui crée le traitement
            echo $this->Form->input('declarantpersonnenom', array(
                'label' => array(
                    'text' => __d('default', 'default.champNom') . '<span class="obligatoire">*</span>',
                    'class' => 'col-md-4 control-label'
                ),
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'required' => 'required',
                'div' => 'form-group',
                'value' => __d('default', 'default.valueChampRemplissageAuto'),
                'readonly' => 'readonly'
            ));
            ?>
        </div>
        
        <div class="col-md-6">
            <?php
            // Champ E-mail * grisé (Remplissage automatique) => E-mail de l'utilisateur qui crée le traitement
            echo $this->Form->input('declarantpersonneemail', array(
                'label' => array(
                    'text' => __d('default', 'default.champE-mail') . '<span class="obligatoire">*</span>',
                    'class' => 'col-md-4 control-label'
                ),
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'required' => 'required',
                'div' => 'form-group',
                'value' => __d('default', 'default.valueChampRemplissageAuto'),
                'readonly' => 'readonly'
            ));
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php
            // Champ Nom du traitement * grisé => il sera rempli par l'utilisateur qui crée le traitement
            echo $this->Form->input('outilnom', array(
                'label' => array(
                    'text' => __d('default', 'default.champNomTraitement') . '<span class="obligatoire">*</span>',
                    'class' => 'col-md-4 control-label'
                ),
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'div' => 'form-group',
                'readonly' => 'readonly'
            ));
            ?>
        </div>
        
        <div class="col-md-6">
            <?php
            // Champ Finalité * grisé => il sera rempli par l'utilisateur qui crée le traitement
            echo $this->Form->input('finalitepricipale', array(
                'label' => array(
                    'text' => __d('default', 'default.champFinalite') . '<span class="obligatoire">*</span>',
                    'class' => 'col-md-4 control-label'
                ),
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'div' => 'form-group',
                'readonly' => 'readonly',
                'type' => 'textarea'
            ));
            ?>
        </div>
    </div>
</div>

<!-- Corps du formulaire -->
<div id="form-container" class="col-md-12">
    <?php
    $calendrier = array();
    foreach ($champs as $key => $value) {
        $details = json_decode($value['Champ']['details'], true);
        
        // Possitionnement du champ sur la ligne
        $line = 35 * ($value['Champ']['ligne'] - 1);

        // Possitionnement du champ dans la colonne de droite ou gauche
        if ($value['Champ']['colonne'] == 1) {
            $colonne = 'left: 0px;';
        } else {
            $colonne = 'right: 0px;';
        }

        // Champ obligatoire ou non
        if ($details['obligatoire'] == true) {
            $champObligatoire = "checked";
        } else {
            $champObligatoire = "unchecked";
        }

        switch ($value['Champ']['type']) {
            // Petit champ texte
            case 'input':
                echo '<div class="draggable form-group col-md-6 small-text" style="top:' . $line . 'px; ' . $colonne . '" data="' . $champObligatoire . '">'
                . '<div class="col-md-4">'
                . '<label>'
                . '<span class="labeler">' . $details['label'] . '</span>'
                . ($details['obligatoire'] ? '<span class="obligatoire"> *</span>' : '')
                . '</label>'
                . '</div>'
                . '<div class="col-md-8">'
                . '<input type="text" name="' . $details['name'] . '" checked="' . $details['obligatoire'] . '" placeholder="' . $details['placeholder'] . '" class="form-control"/>'
                . '</div>'
                . '</div>';
                break;

            // Grand champ texte
            case 'textarea':
                echo '<div class="draggable form-group col-md-6 long-text" style="top:' . $line . 'px; ' . $colonne . '" data="' . $champObligatoire . '">'
                . '<div class="col-md-4">'
                . '<label>'
                . '<span class="labeler">' . $details['label'] . '</span>'
                . ($details['obligatoire'] ? '<span class="obligatoire"> *</span>' : '')
                . '</label>'
                . '</div>'
                . '<div class="col-md-8">'
                . '<textarea type="textarea" name="' . $details['name'] . '" checked="' . $details['obligatoire'] . '" placeholder="' . $details['placeholder'] . '"class="form-control"></textarea>'
                . '</div>'
                . '</div>';
                break;

            // Champ date
            case 'date':
                echo '<div class="draggable form-group col-md-6 date" style="top:' . $line . 'px; ' . $colonne . '" data="' . $champObligatoire . '">'
                . '<div class="col-md-4">'
                . '<label>'
                . '<span class="labeler">' . $details['label'] . '</span>'
                . ($details['obligatoire'] ? '<span class="obligatoire"> *</span>' : '')
                . '</label>'
                . '</div>'
                . '<div class="container">'
                . '<div class="row">'
                . '<div class="col-sm-2">'
                . '<input type="date" class="form-control" id="' . $details['name'] . '" name="' . $details['name'] . '" required="' . $details['obligatoire'] . '" placeholder="' . $details['placeholder'] . '"></input>'
                . '</div>'
                . '</div>'
                . '</div>'
                . '</div>';

                $calendrier[] = $details['name'];
                break;

            // Titre de catégorie
            case 'title':
                echo '<div class="draggable form-group col-md-6 title text-center" style="top:' . $line . 'px; ' . $colonne . '">'
                . '<h1>' . $details['content'] . '</h1>'
                . '</div>';
                break;

            // Champ d'information
            case 'help':
                echo '<div class="draggable form-group col-md-6 help text-center" style="top:' . $line . 'px; ' . $colonne . '">'
                . '<div class="col-md-12 alert alert-info">'
                . '<div class="col-md-12">'
                . '<i class="fa fa-fw fa-info-circle fa-2x"></i>'
                . '</div>'
                . '<div class="col-md-12 messager">' . $details['content'] . '</div>'
                . '</div>'
                . '</div>';
                break;

            // Cases à cocher
            case 'checkboxes':
                echo '<div class="draggable form-group col-md-6 checkboxes" style="top:' . $line . 'px; ' . $colonne . '" data="' . $champObligatoire . '">'
                . '<div class="col-md-4">'
                . '<label>'
                . '<span class="labeler">' . $details['label'] . '</span>'
                . ($details['obligatoire'] ? '<span class="obligatoire"> *</span>' : '')
                . '</label>'
                . '</div>'
                . '<div class="col-md-8 contentCheckbox">';

                foreach ($details['options'] as $val) {
                    echo '<div class="checkbox">'
                    . '<input type="checkbox" name="' . $details['name'] . '" value="' . $val . '">' . $val . '</div>';
                }

                echo '</div></div>';
                break;

            // Choix unique
            case 'radios':
                echo '<div class="draggable form-group col-md-6 radios" style="top:' . $line . 'px; ' . $colonne . '" data="' . $champObligatoire . '">'
                . '<div class="col-md-4">'
                . '<label>'
                . '<span class="labeler">' . $details['label'] . '</span>'
                . '</label>'
                . '</div>'
                . '<div class="col-md-8 contentRadio">';
                foreach ($details['options'] as $val) {
                    echo '<div class="radio"><input type="radio" name="' . $details['name'] . '" value="' . $val . '">' . $val . '</div>';
                }
                echo '</div></div>';
                break;
                
            // Menu déroulant    
            case 'deroulant':
                echo '<div class="draggable form-group col-md-6 deroulant" style="top:' . $line . 'px; ' . $colonne . '" data="' . $champObligatoire . '">'
                . '<div class="col-md-4">'
                . '<label>'
                . '<span class="labeler">' . $details['label'] . '</span>'
                . ($details['obligatoire'] ? '<span class="obligatoire"> *</span>' : '')
                . '</label>'
                . '</div>'
                . '<select class="form-control" name ="' . $details['name'] . '">';
                foreach ($details['options'] as $val) {
                    echo '<option type="deroulant" name ="' . $details['name'] . '" value="' . $val . '"> ' . $val . '</option></div>';
                }
                echo '</select>'
                . '</div>';
                break;

            // Label 
            case 'texte':
                echo '<div class="draggable form-group col-md-6 texte" style="top:' . $line . 'px;' . $colonne . '">'
                . '<h5>' . $details['content'] . '</h5>'
                . '</div>';
                break;

            default :
                break;
        }
    }
    ?>
</div>

<div class="row">
    <div class="col-md-12 top17 text-center">
        <div class="btn-group">
            <?php
            // Bouton Revenir
            echo $this->Html->link('<i class="fa fa-fw fa-arrow-left"></i>' . __d('fiche', 'fiche.btnRevenir'), array(
                'controller' => 'formulaires',
                'action' => 'index'
                    ), array(
                'class' => 'btn btn-default-default',
                'escape' => false
            ));
            echo $this->Form->end();
            ?>
        </div>
    </div>
</div>

<!-- Affichage d'un calendrier en JS sur le champ date -->
<script type="text/javascript">

    $(document).ready(function () {

        var afficherCalendrier = <?php echo json_encode($calendrier); ?>;
        var nb = afficherCalendrier.length;

        for (var i = 0; i < nb; i++) {

            $('#' + afficherCalendrier[i]).datetimepicker({
                viewMode: 'year',
                startView: "decade",
                format: 'dd/mm/yyyy',
                minView: 2,
                language: 'fr'
            });
        }

    });
</script>