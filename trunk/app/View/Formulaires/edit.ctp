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
    <div class="col-md-8 form-oblig form-horizontal">
        <div class="row35"></div>
        <div class="row">
            <div class="col-md-6">
                <?php
                echo $this->Form->input('declarantraisonsociale', array(
                    'label' => array(
                        'text' => 'Raison Sociale <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => $organisation['Organisation']['raisonsociale']
                ));
                
                echo $this->Form->input('declarantservice', array(
                    'label' => array(
                        'text' => 'Service',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => $organisation['Organisation']['service']
                ));
                
                echo $this->Form->input('declarantadresse', array(
                    'label' => array(
                        'text' => 'Adresse <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'type' => 'textarea',
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => $organisation['Organisation']['adresse']
                ));
                
                echo $this->Form->input('declarantemail', array(
                    'label' => array(
                        'text' => 'E-mail <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => $organisation['Organisation']['email']
                ));
                ?>

            </div>
            <div class='col-md-6'>
                <?php
                echo $this->Form->input('declarantsigle', array(
                    'label' => array(
                        'text' => 'Sigle',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => $organisation['Organisation']['sigle']
                ));
                
                echo $this->Form->input('declarantsiret', array(
                    'label' => array(
                        'text' => 'N° de SIRET <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => $organisation['Organisation']['siret']
                ));
                
                echo $this->Form->input('declarantape', array(
                    'label' => array(
                        'text' => 'Code APE <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => $organisation['Organisation']['ape']
                ));
                
                echo $this->Form->input('declaranttelephone', array(
                    'label' => array(
                        'text' => 'Téléphone <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => $organisation['Organisation']['telephone']
                ));
                
                echo $this->Form->input('declarantfax', array(
                    'label' => array(
                        'text' => 'Fax',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => $organisation['Organisation']['fax']
                ));
                ?>
            </div>
        </div>
        <div class="row row35"></div>
        <div class="row">
            <div class="col-md-12">
                <span class='labelFormulaire'>Personne à contacter au sein de l'organisme déclarant si un complément doit être demandé et destinataire du récipissé:</span>
                <div class="row row35"></div>
            </div>
            <div class="col-md-6">
                <?php
                echo $this->Form->input('declarantpersonnenom', array(
                    'label' => array(
                        'text' => 'Nom <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'required' => 'required',
                    'div' => 'form-group',
                    'value' => 'Remplissage automatique',
                    'readonly' => 'readonly'
                ));
                ?>
            </div>
            <div class="col-md-6">
                <?php
                echo $this->Form->input('declarantpersonneemail', array(
                    'label' => array(
                        'text' => 'E-mail <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'required' => 'required',
                    'div' => 'form-group',
                    'value' => 'Remplissage automatique',
                    'readonly' => 'readonly'
                ));
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?php
                echo $this->Form->input('outilnom', array(
                    'label' => array(
                        'text' => 'Nom du traitement <span class="obligatoire">*</span>',
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
                echo $this->Form->input('finalitepricipale', array(
                    'label' => array(
                        'text' => 'Finalité <span class="obligatoire">*</span>',
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
    <div id="form-container" class="col-md-8">
        <?php
        $calendrier = array();
        foreach($champs as $key => $value) {
            $details = json_decode($value['Champ']['details'], true);
            $line = 35 * ($value['Champ']['ligne'] - 1);
            
            if($value['Champ']['colonne'] == 1) {
                $colonne = 'left: 0px;';
            } else {
                $colonne = 'right: 0px;';
            }
            
            if($details['obligatoire'] == true){
                $champObligatoire = "checked";
            } else {
                $champObligatoire = "unchecked";
            }
            
            switch($value['Champ']['type']) {
                case 'input':
                        echo '<div class="draggable form-group col-md-6 small-text" style="top:' . $line . 'px; '.$colonne.'" data="'.$champObligatoire.'">'
                                . '<div class="col-md-4">'
                                    . '<label>'
                                        . '<span class="labeler">' . $details['label'] . '</span>'
                                        .($details['obligatoire']?'<span class="obligatoire"> *</span>':'')
                                    . '</label>'
                                . '</div>'
                                . '<div class="col-md-8">'
                                    . '<input type="text" name="'.$details['name'].'" checked="'.$details['obligatoire'].'" placeholder="' . $details['placeholder'] . '" class="form-control"/>'
                                . '</div>'
                            . '</div>';
                    break;
                    
                case 'textarea':
                    echo '<div class="draggable form-group col-md-6 long-text" style="top:' . $line . 'px; ' . $colonne . '" data="'.$champObligatoire.'">'
                            . '<div class="col-md-4">'
                                . '<label>'
                                    . '<span class="labeler">' . $details['label'] . '</span>'
                                    .($details['obligatoire']?'<span class="obligatoire"> *</span>':'')
                                . '</label>'
                            . '</div>'
                            . '<div class="col-md-8">'
                                . '<textarea type="textarea" name="' . $details['name'] . '" checked="'.$details['obligatoire'].'" placeholder="' . $details['placeholder'] . '"class="form-control"></textarea>'
                            . '</div>'
                        . '</div>';
                    break;
                    
                case 'date':
                    echo '<div class="draggable form-group col-md-6 date" style="top:' . $line . 'px; ' . $colonne . '" data="'.$champObligatoire.'">'
                            . '<div class="col-md-4">'
                                . '<label>'
                                    . '<span class="labeler">' . $details['label'] . '</span>'
                                    .($details['obligatoire']?'<span class="obligatoire"> *</span>':'')
                                . '</label>'
                            . '</div>'
                            . '<div class="container">'
                                .'<div class="row">'
                                    .'<div class="col-sm-2">'
                                        .'<input type="date" class="form-control" id="'.$details['name'].'" name="'.$details['name'].'" required="'.$details['obligatoire'].'" placeholder="'.$details['placeholder'].'"></input>
'                                   .'</div>'
                                .'</div>'
                            .'</div>'
                        .'</div>';
                    
                    $calendrier[] = $details['name'];
                    break;
                    
                case 'title':
                    echo '<div class="draggable form-group col-md-6 title text-center" style="top:' . $line . 'px; ' . $colonne . '">'
                            . '<h1>' . $details['content'] . '</h1>'
                        . '</div>';
                    break;
                
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
                
                case 'checkboxes':
                    echo '<div class="draggable form-group col-md-6 checkboxes" style="top:' . $line . 'px; ' . $colonne .'" data="'.$champObligatoire.'">'
                            . '<div class="col-md-4">'
                                . '<label>'
                                    . '<span class="labeler">' . $details['label'] . '</span>'
                                    .($details['obligatoire']?'<span class="obligatoire"> *</span>':'')
                                . '</label>'
                            . '</div>'
                            . '<div class="col-md-8 contentCheckbox">';
                    
                    foreach($details['options'] as $val) {
                        echo '<div class="checkbox">'
                                . '<input type="checkbox" name="' . $details['name'] . '" value="' . $val . '">' . $val . '</div>';
                    }
                    
                    echo '</div></div>';
                    break;
                    
                case 'radios':
                    echo '<div class="draggable form-group col-md-6 radios" style="top:' . $line . 'px; ' . $colonne .'" data="'.$champObligatoire.'">'
                            . '<div class="col-md-4">'
                                . '<label>'
                                    . '<span class="labeler">' . $details['label'] . '</span>'
                                . '</label>'
                            . '</div>'
                            . '<div class="col-md-8 contentRadio">';
                                foreach($details['options'] as $val) {
                                    echo '<div class="radio"><input type="radio" name="' . $details['name'] . '" value="' . $val . '">' . $val . '</div>';
                                }
                        echo '</div></div>';
                    break;
                    
                case 'deroulant':
                    echo '<div class="draggable form-group col-md-6 deroulant" style="top:' . $line . 'px; ' . $colonne .'" data="'.$champObligatoire.'">'
                            .'<div class="col-md-4">'
                                .'<label>'
                                    .'<span class="labeler">' . $details['label'] . '</span>'
                                    .($details['obligatoire']?'<span class="obligatoire"> *</span>':'')
                                .'</label>'
                            .'</div>'
                            .'<select class="form-control" name ="'.$details['name'].'">';
                            foreach($details['options'] as $val) {
                                echo '<option type="deroulant" name ="'.$details['name'].'" value="' . $val . '"> '. $val . '</option></div>';
                            }
                            echo '</select>'
                        .'</div>';
                    break;
                    
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
    <div class="col-md-offset-4" id="field-affix">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default panel-affix">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Options du champ </h3>
                    </div>
                    <div class="panel-body" id="field-options">
                    </div>
                </div>
            </div>
            <div class="btn-group-vertical col-md-6">
                <button class="btn btn-default-default btn-sm btn-input" id="btn-small-text"><i
                        class="fa fa-font fa-fw"></i>
                    Petit champ texte
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-long-text"><i
                        class="fa fa-text-height fa-fw"></i> 
                    Grand champ texte
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-date"><i
                        class="fa fa-calendar fa-fw"></i>
                    Champ date
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-checkbox"><i
                        class="fa fa-check-square-o fa-fw"></i> 
                    Cases à cocher
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-radio"><i
                        class="fa fa-check-circle-o fa-fw"></i> 
                    Choix unique
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-deroulant"><i
                        class="fa  fa-list-alt fa-fw"></i>
                    Menu déroulant
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-title"><i class="fa fa-tag fa-fw"></i>
                    Titre de catégorie
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-help"><i
                        class="fa fa-info-circle fa-fw"></i>
                    Champ d'information
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-texte"><i
                        class="fa fa-pencil fa-fw"></i>
                    Texte
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-8 form-oblig form-horizontal">
        <?php
        echo $this->Form->input('file', array(
            'type' => 'file',
            'label' => 'Fichiers',
            'multiple',
            'class' => 'filestyle fichiers draggable',
            'data-buttonText' => 'Parcourir',
            'data-buttonName' => "btn-primary",
            'data-buttonBefore' => "true"
        ));
        ?>
    </div>
    <div class="top30 btn-group col-md-12">
        <button class="btn btn-default-success" id="successForm"><i class="fa fa-fw fa-check"></i> 
            Enregistrer ce formulaire
        </button>
    </div>
<?php

echo $this->Form->create('Formulaire', array(
    'action' => 'edit',
    'id' => 'addForm'
));
echo $this->Form->hidden('id', array('value' => $id));
echo $this->Form->hidden('json', array('id' => 'hiddenForm'));
echo $this->Form->end();
?>

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