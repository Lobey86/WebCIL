<?php
$col = 1;
$line = 1;

echo $this->Form->create('Fiche', [
    'action' => 'add',
    'class' => 'form-horizontal',
    'type' => 'file'
]);
?>

<!-- Champs des informations de l'organisme (remplissage automatique) -->
<div class="row">

    <div class="col-md-12">
        <span class='labelFormulaire'>
            <?php
            // Texte
            echo('Information sur l\'entitée :');
            ?>
        </span>
        <div class="row row35"></div>
    </div>

    <!-- Colonne de gauche -->
    <div class="col-md-6">
        <?php
        // Champ Raison Sociale * de l'organisme
        echo $this->Form->input('declarantraisonsociale', [
            'label' => [
                'text' => 'Raison Sociale <span class="obligatoire">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'readonly' => 'readonly',
            'div' => 'form-group',
            'value' => $this->Session->read('Organisation.raisonsociale'),
        ]);

        // Champ Adresse * de l'organisme
        echo $this->Form->input('declarantadresse', [
            'label' => [
                'text' => 'Adresse <span class="obligatoire">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'type' => 'textarea',
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'readonly' => 'readonly',
            'div' => 'form-group',
            'value' => $this->Session->read('Organisation.adresse')
        ]);

        // Champ E-mail* de l'organisme
        echo $this->Form->input('declarantemail', [
            'label' => [
                'text' => 'E-mail <span class="obligatoire">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'readonly' => 'readonly',
            'div' => 'form-group',
            'value' => $this->Session->read('Organisation.email')
        ]);
        ?>
    </div>

    <!-- Colonne de droite -->
    <div class='col-md-6'>
        <?php
        // Champ Sigle de l'organisme
        echo $this->Form->input('declarantsigle', [
            'label' => [
                'text' => 'Sigle',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'readonly' => 'readonly',
            'div' => 'form-group',
            'value' => $this->Session->read('Organisation.sigle')
        ]);

        // Champ N° de SIRET * de l'organisme
        echo $this->Form->input('declarantsiret', [
            'label' => [
                'text' => 'N° de SIRET <span class="obligatoire">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'readonly' => 'readonly',
            'div' => 'form-group',
            'value' => $this->Session->read('Organisation.siret')
        ]);

        // Champ Code APE * de l'organisme
        echo $this->Form->input('declarantape', [
            'label' => [
                'text' => 'Code APE <span class="obligatoire">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'readonly' => 'readonly',
            'div' => 'form-group',
            'value' => $this->Session->read('Organisation.ape')
        ]);

        // Champ Téléphone * de l'organisme
        echo $this->Form->input('declaranttelephone', [
            'label' => [
                'text' => 'Téléphone <span class="obligatoire">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'readonly' => 'readonly',
            'div' => 'form-group',
            'value' => $this->Session->read('Organisation.telephone')
        ]);

        // Champ Fax de l'organisme
        echo $this->Form->input('declarantfax', [
            'label' => [
                'text' => 'Fax',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'readonly' => 'readonly',
            'div' => 'form-group',
            'value' => $this->Session->read('Organisation.fax')
        ]);
        ?>
    </div>
</div>

<!-- Champs des informations du responsable de l'organisme (remplissage automatique) -->
<div class="row">
    <div class="col-md-12">
        <span class='labelFormulaire'>
            <?php
            // Texte
            echo('Information sur le responsable de l\'entitée :');
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

<!-- Champs concernant le rédacteur -->
<div class="row">
    <div class="col-md-12">
        <span class='labelFormulaire'>
            <?php
            // Texte
            echo __d('fiche', 'fiche.textInfoContact');
            ?>
        </span>
        <div class="row row35"></div>
    </div>

    <div class="col-md-6">
        <?php
        // Champ Nom et prénom * , du créateur du traitement (remplissage automatique) 
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
            'value' => $this->Session->read('Auth.User.prenom') . ' ' . $this->Session->read('Auth.User.nom')
        ]);
        ?>
    </div>

    <div class="col-md-6">
        <?php
        // Champ E-mail * , du créateur du traitement (remplissage automatique) 
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
            'value' => $this->Session->read('Auth.User.email')
        ]);
        ?>
    </div>

    <div class="col-md-6">
        <?php
        // Champ Service
        echo $this->Form->input('declarantservice', [
            'label' => [
                'text' => 'Service',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'readonly' => 'readonly',
            'div' => 'form-group',
            'value' => $this->Session->read('User.service')
        ]);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php
        // Champ Nom du traitement * , à remplire par le créateur du traitement
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
        // Champ Finalité * , à remplire par le créateur du traitement
        echo $this->Form->input('finaliteprincipale', [
            'label' => [
                'text' => __d('default', 'default.champFinalite') . '<span class="obligatoire">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'class' => 'form-control',
            'div' => 'form-group',
            'type' => 'textarea',
            'required' => 'required'
        ]);
        ?>
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
                        // Petit champ texte
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

                        // Grand champ texte
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

                        // Champ date
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

                        // Titre de catégorie
                        case 'title':
                            ?>
                            <div class="col-md-12 text-center">
                                <h1>
                                    <?php echo $options['content']; ?>
                                </h1>
                            </div>
                            <?php
                            break;

                        // Label
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

                        // Champ d'information
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

                        // Cases à cocher
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

                        // Menu déroulant
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

                        // Choix unique
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

<!-- Texte Ajouter une pièce jointe -->
<h4> 
    <?php echo __d('fiche', 'fiche.textAjouterPieceJointe'); ?>
</h4>

<!-- Texte format fichier accepté -->
<div class="alert alert-warning" role="alert">
    <?php echo __d('fiche', 'fiche.textTypeFichierAccepter'); ?>
</div>    

<div class="col-md-6 form-horizontal top17">
    <?php
    // Gestion fichier
    echo $this->Form->input('fichiers.', [
        'type' => 'file',
        'id' => 'fileAnnexe',
        'label' => [
            'text' => __d('fiche', 'fiche.champFichier'),
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

<div class="row">
    <?php
    echo $this->Form->hidden('formulaire_id', ['value' => $formulaireid]);
    ?>

    <!-- Groupe bouton -->
    <div class="col-md-12 top17 text-center">
        <div class="btn-group">
            <?php
            // Bouton Annuler
            echo $this->Html->link('<i class="fa fa-fw fa-arrow-left"></i>' . __d('default', 'default.btnAnnuler'), [
                'controller' => 'pannel',
                'action' => 'index'
                    ], [
                'class' => 'btn btn-default-default',
                'escape' => false
            ]);

            // Bouton Enregistrer
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
                startView: "decade",
                format: 'dd/mm/yyyy',
                minView: 2,
                language: 'fr'
            });
        }

        verificationExtension();
    });

</script>