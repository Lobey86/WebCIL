<?php
$col = 1;
$line = 1;

$nameController = $this->Session->read('nameController');
$nameView = $this->Session->read('nameView');
unset($_SESSION['nameController']);
unset($_SESSION['nameView']);

echo $this->Form->create('Fiche', [
    'action' => 'edit',
    'class' => 'form-horizontal',
    'type' => 'file'
]);
?>
<!--<div class="row">
    <div class="col-md-6">-->
        <?php
//        echo $this->Form->input('declarantraisonsociale', [
//            'label' => [
//                'text' => 'Raison Sociale <span class="obligatoire">*</span>',
//                'class' => 'col-md-4 control-label'
//            ],
//            'between' => '<div class="col-md-8">',
//            'after' => '</div>',
//            'class' => 'form-control',
//            'readonly' => 'readonly',
//            'div' => 'form-group',
//        ]);
//        echo $this->Form->input('declarantservice', [
//            'label' => [
//                'text' => 'Service',
//                'class' => 'col-md-4 control-label'
//            ],
//            'between' => '<div class="col-md-8">',
//            'after' => '</div>',
//            'class' => 'form-control',
//            'readonly' => 'readonly',
//            'div' => 'form-group',
//        ]);
//        echo $this->Form->input('declarantadresse', [
//            'label' => [
//                'text' => 'Adresse <span class="obligatoire">*</span>',
//                'class' => 'col-md-4 control-label'
//            ],
//            'type' => 'textarea',
//            'between' => '<div class="col-md-8">',
//            'after' => '</div>',
//            'class' => 'form-control',
//            'readonly' => 'readonly',
//            'div' => 'form-group',
//        ]);
//        echo $this->Form->input('declarantemail', [
//            'label' => [
//                'text' => 'E-mail <span class="obligatoire">*</span>',
//                'class' => 'col-md-4 control-label'
//            ],
//            'between' => '<div class="col-md-8">',
//            'after' => '</div>',
//            'class' => 'form-control',
//            'readonly' => 'readonly',
//            'div' => 'form-group',
//        ]);
        ?>

<!--    </div>
    <div class='col-md-6'>-->
        <?php
//        echo $this->Form->input('declarantsigle', [
//            'label' => [
//                'text' => 'Sigle',
//                'class' => 'col-md-4 control-label'
//            ],
//            'between' => '<div class="col-md-8">',
//            'after' => '</div>',
//            'class' => 'form-control',
//            'readonly' => 'readonly',
//            'div' => 'form-group',
//        ]);
//        echo $this->Form->input('declarantsiret', [
//            'label' => [
//                'text' => 'N° de SIRET <span class="obligatoire">*</span>',
//                'class' => 'col-md-4 control-label'
//            ],
//            'between' => '<div class="col-md-8">',
//            'after' => '</div>',
//            'class' => 'form-control',
//            'readonly' => 'readonly',
//            'div' => 'form-group',
//        ]);
//        echo $this->Form->input('declarantape', [
//            'label' => [
//                'text' => 'Code APE <span class="obligatoire">*</span>',
//                'class' => 'col-md-4 control-label'
//            ],
//            'between' => '<div class="col-md-8">',
//            'after' => '</div>',
//            'class' => 'form-control',
//            'readonly' => 'readonly',
//            'div' => 'form-group',
//        ]);
//        echo $this->Form->input('declaranttelephone', [
//            'label' => [
//                'text' => 'Téléphone <span class="obligatoire">*</span>',
//                'class' => 'col-md-4 control-label'
//            ],
//            'between' => '<div class="col-md-8">',
//            'after' => '</div>',
//            'class' => 'form-control',
//            'readonly' => 'readonly',
//            'div' => 'form-group',
//        ]);
//        echo $this->Form->input('declarantfax', [
//            'label' => [
//                'text' => 'Fax',
//                'class' => 'col-md-4 control-label'
//            ],
//            'between' => '<div class="col-md-8">',
//            'after' => '</div>',
//            'class' => 'form-control',
//            'readonly' => 'readonly',
//            'div' => 'form-group',
//        ]);
        ?>
<!--    </div>
</div>-->
<div class="row row35"></div>
<div class="row">
    <div class="col-md-12">
        <span class='labelFormulaire'>
            <?php echo __d('fiche', 'fiche.textInfoContact');?>
        </span>
        <div class="row row35"></div>
    </div>
    <div class="col-md-6">
        <?php
        echo $this->Form->input('declarantpersonnenom', [
            'label' => [
                'text' => __d('fiche', 'fiche.champNomPrenom') . '<span class="obligatoire">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'required' => 'required',
            'readonly' => 'readonly',
            'div' => 'form-group',
        ]);
        ?>
    </div>
    <div class="col-md-6">
        <?php
        echo $this->Form->input('declarantpersonneemail', [
            'label' => [
                'text' => __d('default', 'default.champE-mail') . '<span class="obligatoire">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'required' => 'required',
            'readonly' => 'readonly',
            'div' => 'form-group',
        ]);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php
        echo $this->Form->input('outilnom', [
            'label' => [
                'text' => __d('default', 'default.champNomTraitement') . '<span class="obligatoire">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'div' => 'form-group',
            'required' => 'required'
        ]);
        ?>

    </div>
    <div class="col-md-6">
        <?php
        echo $this->Form->input('finaliteprincipale', [
            'label' => [
                'text' => __d('default', 'default.champFinalite') . '<span class="obligatoire">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'div' => 'form-group',
            'type' => 'textarea'
        ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="row row35"></div>
    <?php
    $incrementation_id = 0;

    echo '<div class="col-md-6">';

    foreach ($champs as $value) {
        if ($value['Champ']['colonne'] > $col) {
            echo '</div>';
            echo '<div class="col-md-6">';
            $line = 1;
            $col++;
        }
        
        if ($value['Champ']['ligne'] > $line) {
            for ($i = $line; $i < $value['Champ']['ligne']; $i++) {
                echo '<div class="row row35"></div>';
            }
            $line = $value['Champ']['ligne'];
        }
        
        $options = json_decode($value['Champ']['details'], true);

        $afficherObligation = "";

        if ($options['obligatoire'] == true) {
            $afficherObligation = '<span class="obligatoire"> *</span>';
        }

        echo '<div class="row row35"><div class="col-md-12">';

        switch ($value['Champ']['type']) {
            case 'input':
                echo $this->Form->input($options['name'], [
                    'label'       => [
                        'text'  => $options['label'].$afficherObligation,
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>',
                    'class'       => 'form-control',
                    'div'         => 'form-group',
                    'placeholder' => $options['placeholder'],
                    'required'    => $options['obligatoire']
                ]);
                break;
            
            case 'textarea':
                echo $this->Form->input($options['name'], [
                    'label'       => [
                        'text'  => $options['label'].$afficherObligation,
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>',
                    'class'       => 'form-control',
                    'div'         => 'form-group',
                    'placeholder' => $options['placeholder'],
                    'required'    => $options['obligatoire'],
                    'type'        => 'textarea'
                ]);
                break;

            case 'date':
                echo $this->Form->input($options['name'], [
                    'label'       => [
                        'text'  => $options['label'].$afficherObligation,
                        'class' => 'col-md-4 control-label'
                    ],
                    'id'          => 'datetimepicker'.$incrementation_id,
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>',
                    'class'       => 'form-control',
                    'div'         => 'form-group',
                    'placeholder' => $options['placeholder'],
                    'required'    => $options['obligatoire'],
                ]);
                $incrementation_id ++;
                break;
            
            case 'title':
                echo '<div class="col-md-12 text-center"><h1>' . $options['content'] . '</h1></div>';

            case 'texte':
                echo '<div class="form-group"><div class="container"><h5 class="col-md-4 control-label">' . $options['content'] . '</h5></div></div>';
                break;    
                
            case 'help':
                echo '<div class="col-md-12 alert alert-info text-center">
                        <div class="col-md-12">
                            <i class="fa fa-fw fa-info-circle fa-2x"></i>
                        </div>
                        <div class="col-md-12">' . $options['content'] . '</div>
                    </div>';
                break;

            case 'checkboxes':
                echo '<div class="form-group">
                        <label class="col-md-4 control-label">' . $options['label']. '</label>
                    <div class="col-md-8">';

                echo $this->Form->input($options['name'], [
                    'label'    => false,
                    'type'     => 'select',
                    'multiple' => 'checkbox',
                    'options'  => $options['options']
                ]);
                echo '</div></div>';
                break;

            case 'deroulant':
                echo '<div class="form-group">
                        <label class="col-md-4 control-label">' . $options['label'].$afficherObligation . '</label>
                    <div class="col-md-8">';
                
                echo $this->Form->select($options['label'], $options['options'], [
                    'required' => $options['obligatoire'],
                    'empty' => true,
                ]);

                echo '</div></div>';
                break;    

           case 'radios':
                echo '<div class="form-group">
                        <label class="col-md-4 control-label">' . $options['label'] . '</label>
                    <div class="col-md-8">';
                
                echo $this->Form->radio($options['name'], $options['options'], [
                    'label'     => false,
                    'legend'    => false,
                    'separator' => '<br/>'
                ]);
                
                echo '</div></div>';
                break;
        }
        $line++;
        echo '</div></div>';
    }
    echo '</div></div>';
    ?>
    <div class="col-md-12 top30">
        <h4>Pièces jointes</h4>
        <table>
            <tbody>
                <?php
                foreach ($files as $val) {
                    $ext = explode('.', $val['File']['url']);
                    $ext = strtolower(end($ext));
                    switch ($ext) {
                        case 'pdf':
                            $icone = 'file-pdf-o';
                            break;
                        case 'xls':
                        case 'xlsx':
                            $icone = 'file-excel-o';
                            break;
                        case 'doc':
                        case 'docx':
                            $icone = 'file-word-o';
                            break;
                        case 'zip':
                        case 'rar':
                        case 'tar':
                        case'gz':
                            $icone = 'file-archive-o';
                            break;
                        case 'avi':
                        case 'mpg':
                        case 'mpeg':
                        case 'mkv':
                        case 'mp4':
                        case 'mov':
                            $icone = 'file-video-o';
                            break;
                        case 'jpg':
                        case 'jpeg':
                        case 'gif':
                        case 'png':
                        case 'bmp':
                            $icone = 'file-image-o';
                            break;
                        case 'ppt':
                        case 'pptx':
                            $icone = 'file-powerpoint-o';
                            break;
                        case 'odt':
                        case 'ods':
                        case 'txt':
                            $icone = 'file-text-o';
                            break;
                        default:
                            $icone = 'file-o';
                            break;
                    }
                    ?>
                    <tr class="tr-file-"<?php echo $val['File']['id'];?>>
                        <td class="col-md-1">
                            <i class="fa fa-fw fa-"<?php echo $icone;?>></i>
                        </td>
                        <td class="col-md-9 tdleft">
                            <?php echo $val['File']['nom'];?>
                        </td>
                        <td class="col-md-2 boutonsFile boutonsFile"<?php echo $val['File']['id'];?>>
                            <?php 
                            echo $this->Html->link('<span class="glyphicon glyphicon-download-alt"></span>', [
                                'controller' => 'files',
                                'action' => 'download',
                                $val['File']['id']
                                    ], [
                                    'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                    'title' => 'Télécharger le fichier',
                                    'escapeTitle' => false
                                ]) . $this->Form->button('<span class="glyphicon glyphicon-trash"></span>', [
                                'type' => 'button',
                                'class' => 'btn btn-default-danger btn-sm my-tooltip left5 btn-del-file',
                                'title' => 'Supprimer ce fichier',
                                'escapeTitle' => false,
                                'data' => $val['File']['id']
                            ]);
                            ?>
                        </td>
                        <td class="boutonsFileHide boutonsFileHide"<?php echo $val['File']['id'];?>>
                            <?php 
                            echo $this->Form->button('<span class="glyphicon glyphicon-arrow-left"></span> Annuler la suppression', [
                                'type' => 'button',
                                'class' => 'btn btn-default-default btn-sm my-tooltip left5 btn-cancel-file',
                                'title' => 'Annuler la suppression',
                                'escapeTitle' => false,
                                'data' => $val['File']['id']
                            ]);
                            ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-12 form-horizontal top17">
        <?php 
        echo $this->Form->input('fichiers.', [
            'type' => 'file',
            'label' => [
                'text' => 'Ajouter des fichiers',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'filestyle fichiers draggable',
            'div' => 'form-group',
            'multiple'
        ]);
        ?>
    </div>';
    <div class="row hiddenfields">
        <?php
        echo $this->Form->hidden('id', ['value' => $id]);
        ?>
        <div class="col-md-12 top17 text-center">
            <div class="btn-group">
                <?php
                echo $this->Html->link('<i class="fa fa-fw fa-arrow-left"></i>'.__d('default','default.btnAnnuler'), [
                    'controller' => $nameController,
                    'action' => $nameView
                        ], [
                    'class' => 'btn btn-default-default',
                    'escape' => false
                ]);
                
                echo $this->Form->button('<i class="fa fa-fw fa-check"></i>'.__d('default','default.btnEnregistrer'), [
                    'class' => 'btn btn-default-success',
                    'escape' => false,
                    'type' => 'submit'
                ]);
                
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
    
<script type="text/javascript">

        $(document).ready(function () {
            var incrementation_id = <?php echo $incrementation_id ?>; 
            
            for (var i = 0; i < incrementation_id; i++){
                $('#datetimepicker'+ i).datetimepicker({
                    viewMode: 'year',
                    startView: "decade",
                    format: 'dd/mm/yyyy',
                    minView: 2,
                    language: 'fr'
                });
            }
        });

</script>