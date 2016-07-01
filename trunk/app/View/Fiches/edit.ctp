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
            <?php echo __d('fiche', 'fiche.textInfoContact'); ?>
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

    <hr/>

    <div class="col-md-6">
        <?php
        $incrementation_id = 0;
        foreach ($champs as $value) {
            if ($value['Champ']['colonne'] > $col) {
                ?>
            </div>
            <div class="col-md-6">
                <?php
                $line = 1;
                $col++;
            }

            if ($value['Champ']['ligne'] > $line) {
                for ($i = $line; $i < $value['Champ']['ligne']; $i++) {
                    ?>
                    <div class="row row35"></div>
                    <?php
                }
                $line = $value['Champ']['ligne'];
            }

            $options = json_decode($value['Champ']['details'], true);

            $afficherObligation = "";

            if ($options['obligatoire'] == true) {
                $afficherObligation = '<span class="obligatoire"> *</span>';
            }
            ?>
            <div class="row row35">
                <div class="col-md-12">
                    <?php
                    switch ($value['Champ']['type']) {
                        case 'input':
                            echo $this->Form->input($options['name'], [
                                'label' => [
                                    'text' => $options['label'] . $afficherObligation,
                                    'class' => 'col-md-4 control-label'
                                ],
                                'between' => '<div class="col-md-8">',
                                'after' => '</div>',
                                'class' => 'form-control',
                                'div' => 'form-group',
                                'placeholder' => $options['placeholder'],
                                'required' => $options['obligatoire']
                            ]);
                            break;

                        case 'textarea':
                            echo $this->Form->input($options['name'], [
                                'label' => [
                                    'text' => $options['label'] . $afficherObligation,
                                    'class' => 'col-md-4 control-label'
                                ],
                                'between' => '<div class="col-md-8">',
                                'after' => '</div>',
                                'class' => 'form-control',
                                'div' => 'form-group',
                                'placeholder' => $options['placeholder'],
                                'required' => $options['obligatoire'],
                                'type' => 'textarea'
                            ]);
                            break;

                        case 'date':
                            echo $this->Form->input($options['name'], [
                                'label' => [
                                    'text' => $options['label'] . $afficherObligation,
                                    'class' => 'col-md-4 control-label'
                                ],
                                'id' => 'datetimepicker' . $incrementation_id,
                                'between' => '<div class="col-md-8">',
                                'after' => '</div>',
                                'class' => 'form-control',
                                'div' => 'form-group',
                                'placeholder' => $options['placeholder'],
                                'required' => $options['obligatoire'],
                            ]);
                            $incrementation_id ++;
                            break;

                        case 'title':
                            ?>
                            <div class="col-md-12 text-center">
                                <h1>
                                    <?php echo $options['content']; ?>
                                </h1>
                            </div>
                            <?php
                            break;

                        case 'texte':
                            ?>
                            <div class="form-group">
                                <div class="container">
                                    <h5 class="col-md-4 control-label">
                                        <?php echo $options['content']; ?>
                                    </h5>
                                </div>
                            </div>
                            <?php
                            break;

                        case 'help':
                            ?>
                            <div class="col-md-12 alert alert-info text-center">
                                <div class="col-md-12">
                                    <i class="fa fa-fw fa-info-circle fa-2x"></i>
                                </div>
                                <div class="col-md-12">
                                    <?php echo $options['content']; ?>
                                </div>
                            </div>
                            <?php
                            break;

                        case 'checkboxes':
                            ?>
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    <?php echo $options['label']; ?>
                                </label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->input($options['name'], [
                                        'label' => false,
                                        'type' => 'select',
                                        'multiple' => 'checkbox',
                                        'options' => $options['options']
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <?php
                            break;

                        case 'deroulant':
                            ?>
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    <?php echo $options['label'] . $afficherObligation; ?>
                                </label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->select($options['label'], $options['options'], [
                                        'required' => $options['obligatoire'],
                                        'empty' => true,
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <?php
                            break;

                        case 'radios':
                            ?>
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    <?php echo $options['label']; ?>
                                </label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->radio($options['name'], $options['options'], [
                                        'label' => false,
                                        'legend' => false,
                                        'separator' => '<br/>'
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <?php
                            break;
                    }
                    $line++;
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<hr/>

<?php
if (!empty($files)) {
    ?>
    <div class="col-md-12 top30">
        <h4>
            <?php echo __d('fiche', 'fiche.textInfoPieceJointe'); ?>
        </h4>
        <table>
            <tbody>
                <?php
                foreach ($files as $val) {
                    ?>
                    <tr class="tr-file-"<?php echo $val['Fichier']['id']; ?>>
                        <td class="col-md-1">
                            <i class="fa fa-file-text-o fa-lg"></i>
                        </td>
                        <td class="col-md-9 tdleft">
                            <?php echo $val['Fichier']['nom']; ?>
                        </td>
                        <td class="col-md-2 boutonsFile boutonsFile"<?php echo $val['Fichier']['id']; ?>>
                            <?php
                            echo $this->Html->link('<span class="fa fa-download fa-lg"></span>', [
                                'controller' => 'fiches',
                                'action' => 'download',
                                $val['Fichier']['url'],
                                $val['Fichier']['nom']
                                    ], [
                                'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                'title' => 'Télécharger le fichier',
                                'escapeTitle' => false
                            ]);

                            echo $this->Form->button('<span class="fa fa-trash fa-lg"></span>', [
                                'type' => 'button',
                                'class' => 'btn btn-default-danger btn-sm my-tooltip left5 btn-del-file',
                                'title' => 'Supprimer ce fichier',
                                'escapeTitle' => false,
                                'data' => $val['Fichier']['id']
                            ]);
                            ?>
                        </td>
        <!--                        <td class="boutonsFileHide boutonsFileHide"<?php //echo $val['Fichier']['id'];     ?>>
                        <?php
//                            echo $this->Form->button('<span class="glyphicon glyphicon-arrow-left"></span> Annuler la suppression', [
//                                'type' => 'button',
//                                'class' => 'btn btn-default-default btn-sm my-tooltip left5 btn-cancel-file',
//                                'title' => 'Annuler la suppression',
//                                'escapeTitle' => false,
//                                'data' => $val['Fichier']['id']
//                            ]);
                        ?>
                        </td>-->
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <hr/>
    <?php
}
?>

<h4>
    <?php echo __d('fiche', 'fiche.textAjouterPieceJointe'); ?>
</h4>

<div class="alert alert-warning" role="alert">
    <?php echo __d('fiche', 'fiche.textTypeFichierAccepter'); ?>
</div>  

<div class="col-md-6 form-horizontal top17">
    <?php
    echo $this->Form->input('fichiers.', [
        'type' => 'file',
        'id' => 'fileAnnexe',
        'label' => [
            'text' => __d('fiche', 'fiche.textAjouterFichier'),
            'class' => 'col-md-4 control-label'
        ],
        'between' => '<div class="col-md-8">',
        'after' => '</div>',
        'class' => 'filestyle fichiers draggable',
        'div' => 'form-group',
        'accept' => ".odt",
        'multiple'
    ]);
    ?>
</div>
<div class="row hiddenfields">
    <?php
    echo $this->Form->hidden('id', ['value' => $id]);
    ?>
    <div class="col-md-12 top17 text-center">
        <div class="btn-group">
            <?php
            echo $this->Html->link('<i class="fa fa-fw fa-arrow-left"></i>' . __d('default', 'default.btnAnnuler'), [
                'controller' => $nameController,
                'action' => $nameView
                    ], [
                'class' => 'btn btn-default-default',
                'escape' => false
            ]);

            echo $this->Form->button('<i class="fa fa-fw fa-check"></i>' . __d('default', 'default.btnEnregistrer'), [
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

        for (var i = 0; i < incrementation_id; i++) {
            $('#datetimepicker' + i).datetimepicker({
                viewMode: 'year',
                startView: "decade", format: 'dd/mm/yyyy',
                minView: 2,
                language: 'fr'
            });
        }

        verificationExtension();
        
    });

</script>