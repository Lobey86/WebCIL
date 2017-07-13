<?php
echo $this->Html->script('organisations.js');

if (isset($this->validationErrors['Organisation']) && !empty($this->validationErrors['Organisation'])) {
    ?>

    <div class="alert alert-danger" role="alert">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        <span class="sr-only">Error:</span>
        Ces erreurs se sont produites:
        <ul>
            <?php
            foreach ($this->validationErrors as $donnees) {
                foreach ($donnees as $champ) {
                    foreach ($champ as $error) {
                        echo '<li>' . $error . '</li>';
                    }
                }
            }
            ?>
        </ul>
    </div>
    <?php
}
?>

<div class="users form">
    <?php
    echo $this->Form->create('Organisation', [
        'type' => 'file',
        'autocomplete' => 'off',
        'class' => 'form-horizontal',
        'novalidate' => 'novalidate'
    ]);
    ?>

    <h2>
        <?php
        // Texte : "L'entité"
        echo __d('organisation','organisation.textEntite');
        ?>
    </h2>

    </br>
    <!-- Information générale sur l'entitée -->
    <div class="row">
        <!-- Colonne de gauche -->
        <div class="col-md-6">
            <!-- Champ Raison sociale * -->
            <div class="form-group">
                <?php
                echo $this->Form->input('raisonsociale', [
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderRaisonSociale'),
                    'required' => true,
                    'label' => [
                        'text' => __d('organisation', 'organisation.textRaisonSociale') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'escape' => true,
                    //'value' => $organisation['Organisation']['raisonsociale']
                ]);
                ?>
            </div>

            <!-- Champ Téléphone * -->
            <div class="form-group">
                <?php
                echo $this->Form->input('telephone', [
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderTelephone'),
                    'required' => true,
                    //'value' => $organisation['Organisation']['telephone'],
                    'label' => [
                        'text' => __d('organisation', 'organisation.textTelephone') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                ]);
                ?>
            </div>

            <!-- Champ Fax -->
            <div class="form-group">
                <?php
                echo $this->Form->input('fax', [
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderFax'),
                    //'value' => $organisation['Organisation']['fax'],
                    'label' => [
                        'text' => __d('organisation', 'organisation.textFax'),
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                ]);
                ?>
            </div>

            <!-- Champ Adresse * -->
            <div class="form-group">
                <?php
                echo $this->Form->input('adresse', [
                    'div' => 'input-group inputsForm',
                    'label' => [
                        'text' => __d('organisation', 'organisation.textAdresse') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'placeholder' => __d('organisation', 'organisation.placeholderAdresse'),
                    'required' => true,
                    //'value' => $organisation['Organisation']['adresse'],
                ]);
                ?>
            </div>

            <!-- Champ E-mail * -->
            <div class="form-group">
                <?php
                echo $this->Form->input('email', [
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderE-mail'),
                    'required' => true,
                    //'value' => $organisation['Organisation']['email'],
                    'label' => [
                        'text' => __d('organisation', 'organisation.textE-mail') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                ]);
                ?>
            </div>
        </div>

        <!-- Colonne de droite -->
        <div class="col-md-6">
            <!-- Champ Sigle -->
            <div class="form-group">
                <?php
                echo $this->Form->input('sigle', [
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderSigle'),
                    //'value' => $organisation['Organisation']['sigle'],
                    'label' => [
                        'text' => __d('organisation', 'organisation.textSigle'),
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                ]);
                ?>
            </div>

            <!-- Champ N° Siret * -->
            <div class="form-group">
                <?php
                echo $this->Form->input('siret', [
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderSIRET'),
                    'required' => true,
                    //'value' => $organisation['Organisation']['siret'],
                    'label' => [
                        'text' => __d('organisation', 'organisation.textSIRET') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                ]);
                ?>
            </div>

            <!-- Champ Code APE * -->
            <div class="form-group">
                <?php
                echo $this->Form->input('ape', [
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderAPE'),
                    'required' => true,
                    //'value' => $organisation['Organisation']['ape'],
                    'label' => [
                        'text' => __d('organisation', 'organisation.textAPE') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                ]);
                ?>
            </div>

            <!-- Affichage logo si l'entitée en a déjà un d'enregistré -->
            <div class="col-md-8 col-md-offset-4">
                <?php if (file_exists(IMAGES . 'logos' . DS . $organisation['Organisation']['id'] . '.' . $organisation['Organisation']['logo'])) {
                    ?>

                    <div class="thumbnail">
                        <?php
                        echo $this->Html->image('logos' . DS . $organisation['Organisation']['id'] . '.' . $organisation['Organisation']['logo'], [
                            'alt' => 'Logo',
                            'width' => '300px'
                        ]);
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- Champ charger un logo -->
            <div class="col-md-8 col-md-offset-4">
                <?php
                echo $this->Form->input('logo_file', [
                    'div' => 'input-group inputsForm',
                    'type' => 'file',
                    'class' => 'filestyle',
                    'data-buttonText' => __d('organisation', 'organisation.btnLogo'),
                    'data-buttonName' => "btn-default-primary",
                    'data-buttonBefore' => "true",
                    'label' => false
                ]);
                ?>
            </div>
        </div>
    </div>

    <h2>
        <?php
        // Texte : "Responsable de l'entité"
        echo __d('organisation', 'organisation.titreResponsableEntitee');
        ?>
    </h2>

    </br>
    <!-- Information sur le responsable de l'entitée -->
    <div class="row">
        <!-- Colonne de gauche -->
        <div class="col-md-6">
            <!-- Champ Nom du responsable * -->
            <div class="form-group">
                <?php
                echo $this->Form->input('nomresponsable', [
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderNomResponsable'),
                    'required' => true,
                    //'value' => $organisation['Organisation']['nomresponsable'],
                    'label' => [
                        'text' => __d('organisation', 'organisation.textNomResponsable') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'escape' => true
                ]);
                ?>
            </div>

            <!-- Champ Prénom du responsable * -->
            <div class="form-group">
                <?php
                echo $this->Form->input('prenomresponsable', [
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderPrenomResponsable'),
                    'required' => true,
                    //'value' => $organisation['Organisation']['prenomresponsable'],
                    'label' => [
                        'text' => __d('organisation', 'organisation.textPrenomResponsable') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'escape' => true
                ]);
                ?>
            </div>
            
            <!-- Champ Fonction du responsable * -->
            <div class="form-group">
                <?php
                echo $this->Form->input('fonctionresponsable', [
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderFonctionResponsable'),
                    'required' => true,
                    //'value' => $organisation['Organisation']['fonctionresponsable'],
                    'label' => [
                        'text' => __d('organisation', 'organisation.textFonctionResponsable') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'escape' => true
                ]);
                ?>
            </div>
        </div>

        <!-- Colonne de droite -->
        <div class="col-md-6">
            <!-- Champ E-mail du responsable * -->
            <div class="form-group">
                <?php
                echo $this->Form->input('emailresponsable', [
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderE-mailResponsable'),
                    'required' => true,
                    //'value' => $organisation['Organisation']['emailresponsable'],
                    'label' => [
                        'text' => __d('organisation', 'organisation.textE-mailResponsable') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                ]);
                ?>
            </div>

            <!-- Champ Téléphone du responsable * -->
            <div class="form-group">
                <?php
                echo $this->Form->input('telephoneresponsable', [
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderTelephoneResponsable'),
                    'required' => true,
                    //'value' => $organisation['Organisation']['telephoneresponsable'],
                    'label' => [
                        'text' => __d('organisation', 'organisation.textTelephoneResponsable') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                ]);
                ?>
            </div>
        </div>
    </div>

    </br>
    <!-- Affichage du logo du CIL -->
    <?php
    if($array_users != null) {
        if (file_exists(IMAGES . DS . 'logos' . DS . 'logo_cil.jpg')) {
            echo $this->Html->image('logos' . DS . 'logo_cil.jpg', [
                'class' => 'logo-well',
            ]);
        }
        ?>
        </br>

        <!-- Information sur le CIL -->
        <div class="row">
            <!-- Colonne de gauche -->
            <div class="col-md-6">
                <!-- Menu déroulant CIL -->
                <div class="form-group">
                    <?php
                    echo $this->Form->input('cil', [
                        'options' => $array_users,
                        'div' => 'input-group inputsForm',
                        'class' => 'form-control usersDeroulant',
                        'empty' => __d('organisation', 'organisation.placeholderCIL'),
                        //'value' => $organisation['Organisation']['cil'],
                        'required' => true,
                        'label' => [
                            'text' => __d('organisation', 'organisation.textCIL') . '<span class="requis">*</span>',
                            'class' => 'col-md-4 control-label'
                        ],
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                    ]);
                    ?>
                </div>

                <!-- Champ numerocil * -->
                <div class="form-group">
                    <?php
                    echo $this->Form->input('numerocil', [
                        'class' => 'form-control',
                        'placeholder' => __d('organisation', 'organisation.placeholderNumeroCIL'),
                        'required' => true,
                        //'value' => $organisation['Organisation']['numerocil'],
                        'label' => [
                            'text' => __d('organisation', 'organisation.numeroCIL') . '<span class="requis">*</span>',
                            'class' => 'col-md-4 control-label'
                        ],
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'escape' => true
                    ]);
                    ?>
                </div>
            </div>
            <!-- Colonne de groite -->
            <div class="col-md-6">
                <div class="form-group">
                    <!-- Champ E-mail du CIL * -->
                    <div class="form-group">
                        <?php
                        $idCil = $this->request->data['Organisation']['cil'];
                        
                        echo $this->Form->input('emailCil', [
                            'class' => 'form-control',
                            'required' => true,
                            'value' => $informationsUsers[$idCil]['email'],
                            'label' => [
                                'text' => __d('organisation', 'organisation.textEmailCIL') . '<span class="requis">*</span>',
                                'class' => 'col-md-4 control-label'
                            ],
                            'between' => '<div class="col-md-8">',
                            'after' => '</div>',
                            'readonly' => true
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <!-- Groupe de bouton centré -->
    <div class="text-center">
        <div class="btn-group send">
            <?php
                // Groupe de boutons
                echo $this->WebcilForm->buttons( array( 'Cancel', 'Save' ) );

                echo $this->Form->end();
            ?>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        var infoUsers = <?php echo json_encode($informationsUsers); ?>;

        $('#OrganisationCil').on("change", function () {
            if (this.value === "") {
                $("#OrganisationEmailCil").val("Sélectionner un CIL (requis)");
            } else {
                $("#OrganisationEmailCil").val(infoUsers[this.value].email);
            }

        });
    });

</script>
