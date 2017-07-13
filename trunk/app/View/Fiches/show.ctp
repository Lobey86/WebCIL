<?php
$col = 1;
$line = 1;

$nameController = $this->Session->read('nameController');
$nameView = $this->Session->read('nameView');
unset($_SESSION['nameController']);
unset($_SESSION['nameView']);

if ($nameController == null) {
    $nameController = 'pannel';
}

if ($nameView == null) {
    $nameView = 'index';
}

echo $this->Form->create('Fiche', array(
    'action' => 'edit',
    'class' => 'form-horizontal'
));
?>
<div class="form-horizontal">
    <div class="row">
        <!-- Information sur l'organisation -->
        <div class="col-md-12">
            <span class='labelFormulaire'>
                <?php echo __d('fiche', 'fiche.textInfoOrganisation'); ?>
            </span>
            <div class="row row35"></div>
        </div>

        <div class="col-md-6">
            <?php
            // Champ Raison Sociale *
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
            ));

            // Champ Adresse *
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
            ));

            // Champ E-mail *
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
            ));
            ?>
        </div>

        <div class='col-md-6'>
            <?php
            // Champ Sigle
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
            ));

            // Champ N° de SIRET *
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
            ));

            // Champ Code APE *
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
            ));

            // Champ Téléphone *
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
            ));

            // Champ Fax
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
            ));
            ?>
        </div>
    </div>

    <div class="row row35"></div>

    <!-- Champs des informations du responsable de l'organisme (remplissage automatique) -->
    <div class="row">
        <div class="col-md-12">
            <span class='labelFormulaire'>
                <?php
                // Texte
                echo( __d('fiche', 'fiche.textInfoResponsableEntite'));
                ?>
            </span>
            <div class="row row35"></div>
        </div>

        <!-- Colonne de gauche -->
        <div class="col-md-6">
            <?php
            // Champ Nom et prénom * , du responsable de l'entitée (remplissage automatique) 
            echo $this->Form->input('personneresponsable', [
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
                'value' => $this->Session->read('Organisation.prenomresponsable') . ' ' . $this->Session->read('Organisation.nomresponsable')
            ]);

            // Champ Fonction du responsable * de l'entitée (remplissage automatique) 
            echo $this->Form->input('fonctionresponsable', [
                'label' => [
                    'text' => __d('organisation', 'organisation.textFonctionResponsable') . '<span class="obligatoire">*</span>',
                    'class' => 'col-md-4 control-label'
                ],
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'required' => 'required',
                'readonly' => 'readonly',
                'div' => 'form-group',
                'value' => $this->Session->read('Organisation.fonctionresponsable')
            ]);
            ?>
        </div>

        <!-- Colonne de droite -->
        <div class='col-md-6'>
            <?php
            // Champ E-mail du responsable * (remplissage automatique) 
            echo $this->Form->input('emailresponsable', [
                'label' => [
                    'text' => __d('organisation', 'organisation.textE-mailResponsable') . '<span class="obligatoire">*</span>',
                    'class' => 'col-md-4 control-label'
                ],
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'readonly' => 'readonly',
                'div' => 'form-group',
                'value' => $this->Session->read('Organisation.emailresponsable')
            ]);

            // Champ Téléphone du responsable * (remplissage automatique) 
            echo $this->Form->input('telephoneresponsable', [
                'label' => [
                    'text' => __d('organisation', 'organisation.textTelephoneResponsable') . '<span class="obligatoire">*</span>',
                    'class' => 'col-md-4 control-label'
                ],
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'readonly' => 'readonly',
                'div' => 'form-group',
                'value' => $this->Session->read('Organisation.telephoneresponsable')
            ]);
            ?>
        </div>
    </div>

    <div class="row row35"></div>

    <!-- Affichage du logo CIL -->
    <?php
    if (file_exists(IMAGES . DS . 'logos' . DS . 'logo_cil.jpg')) {
        echo $this->Html->image('logos' . DS . 'logo_cil.jpg', [
            'class' => 'logo-well',
        ]);
    }
    ?>

    <!-- Champs des informations du CIL (remplissage automatique) -->
    <div class="row">
        <!-- Colonne de gauche -->
        <div class="col-md-6">
            <div class="form-group">
                <?php
                // Champ Nom et prénom * , du CIL (remplissage automatique) 
                echo $this->Form->input('personnecil', [
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
                    'value' => $userCil['User']['prenom'] . ' '. $userCil['User']['nom']
                ]);
                ?>

                <!-- Champ numerocil * -->
                <?php
                echo $this->Form->input('numerocil', [
                    'label' => [
                        'text' => __d('organisation', 'organisation.numeroCIL') . '<span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'required' => 'required',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => $this->Session->read('Organisation.numerocil')
                ]);
                ?>
            </div>
        </div>

        <!-- Colonne de droite -->
        <div class='col-md-6'>
            <?php
            // Champ E-mail du CIL  * (remplissage automatique) 
            echo $this->Form->input('emailcil', [
                'label' => [
                    'text' => __d('organisation', 'organisation.textEmailCIL') . '<span class="obligatoire">*</span>',
                    'class' => 'col-md-4 control-label'
                ],
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'readonly' => 'readonly',
                'div' => 'form-group',
                'value' => $userCil['User']['email']
            ]);
            ?>
        </div>
    </div>
    
    <div class="row row35"></div>
    
    <!-- Information sur le rédacteur -->
    <div class="row">
        <div class="col-md-12">
            <span class='labelFormulaire'>
                <?php echo __d('fiche', 'fiche.textInfoContact'); ?>
            </span>
            <div class="row row35"></div>
        </div>
        
        <div class="col-md-6">
            <?php
            // Champ Nom et prénom *
            echo $this->Form->input('declarantpersonnenom', array(
                'label' => array(
                    'text' => __d('fiche', 'fiche.champNomPrenom') . '<span class="obligatoire">*</span>',
                    'class' => 'col-md-4 control-label'
                ),
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'required' => 'required',
                'div' => 'form-group',
            ));

            // Champ Service
            echo $this->Form->input('declarantservice', [
                'label' => [
                    'text' =>  __d('fiche', 'fiche.champServiceDeclaration'),
                    'class' => 'col-md-4 control-label'
                ],
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'readonly' => 'readonly',
                'div' => 'form-group',
            ]);
            
            ?>
        </div>
        
        <div class="col-md-6">
            <?php
            // Champ E-mail *
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
            ));
            ?>
        </div>
    </div>
    
    <div class="row row35"></div>

    <!-- Champs concernant le traitement -->
    <div class="row">
        <div class="col-md-12">
            <span class='labelFormulaire'>
                <?php
                // Texte
                echo __d('fiche', 'fiche.textInfoTraitement');
                ?>
            </span>
            <div class="row row35"></div>
        </div>

        <!-- Champs des informations du traitement -->
        <div class="row">
            <!-- Colonne de gauche -->
            <div class="col-md-6">
                <?php
                // Champ Nom du traitement * 
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

                // Champ type de déclaration * , à remplire par le CIL
                echo $this->Form->input('typedeclaration', [
                    'label' => [
                        'text' => __d('default', 'Type de déclaration '),
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'div' => 'form-group'
                ]);
                ?>
            </div>

            <!-- Colonne de droite -->
            <div class="col-md-6">
                <?php
                // Champ Finalité *
                echo $this->Form->input('finaliteprincipale', array(
                    'label' => array(
                        'text' => __d('default', 'default.champFinalite') . '<span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'type' => 'textarea'
                ));
                ?>
            </div>
        </div>
    </div>
    
    <!-- Champs du formulaire -->
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
                                        echo $this->Form->select($options['name'], $options['options'], [
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
    <?php
    if ($files == null) {
        ?>
        <div class="col-md-12 top30">
            <h4>
                <?php echo __d('fiche', 'fiche.textInfoAucunePieceJointe'); ?>
            </h4>
        </div>
        <?php
    } else {
        ?>

        <hr/>

        <div class="col-md-12 top30">
            <h4>
                <?php echo __d('fiche', 'fiche.textInfoPieceJointe'); ?>
            </h4>
            <table>
                <tbody>
                    <tr>
                        <?php
                        foreach ($files as $val) {
                            ?>
                        <tr>
                            <td class="col-md-1">
                                <i class="fa fa-file-text-o fa-fw"></i>
                            </td>
                            <td class="col-md-9 tdleft">
                                <?php echo $val['Fichier']['nom']; ?>
                            </td>
                            <td class="col-md-2">
                                <?php
                                echo $this->Html->link('<span class="glyphicon glyphicon-download-alt"></span>', array(
                                    'controller' => 'fiches',
                                    'action' => 'download',
                                    $val['Fichier']['url'],
                                    $val['Fichier']['nom']
                                        ), array(
                                    'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                    'title' => 'Télécharger le fichier',
                                    'escapeTitle' => false
                                ));
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }
    ?>
    <div class="row">
        <div class="col-md-12 top17 text-center">
            <div class="btn-group">
                <?php
                echo $this->Html->link('<i class="fa fa-arrow-left fa-lg"></i>' . __d('fiche', 'fiche.btnRevenir'), $referer, array(
                    'class' => 'btn btn-default-default',
                    'escape' => false
                ));
                
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(":input").prop("disabled", true);
        $(".boutonDl").prop("disabled", false);
    </script>