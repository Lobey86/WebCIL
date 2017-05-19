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
    echo $this->Form->create('Organisation', array(
        'action' => 'add',
        'type' => 'file',
        'autocomplete' => 'off',
        'class' => 'form-horizontal',
        'novalidate' => 'novalidate'
    ));
    ?>

    <h2>
        <?php
        // Texte : "L'entité"
        echo __d('organisation', 'organisation.textEntite');
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
                echo $this->Form->input('raisonsociale', array(
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderRaisonSociale'),
                    'label' => array(
                        'text' => __d('organisation', 'organisation.textRaisonSociale') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'escape' => true
                ));
                ?>
            </div>

            <!-- Champ Téléphone * -->
            <div class="form-group">
                <?php
                echo $this->Form->input('telephone', array(
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderTelephone'),
                    'label' => array(
                        'text' => __d('organisation', 'organisation.textTelephone') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                ));
                ?>
            </div>

            <!-- Champ Fax -->
            <div class="form-group">
                <?php
                echo $this->Form->input('fax', array(
                    'class' => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderFax'),
                    'label' => array(
                        'text' => __d('organisation', 'organisation.textFax'),
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                ));
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
                    'required' => true
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
                    'label' => [
                        'text' => __d('organisation', 'organisation.textAPE') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                ]);
                ?>
            </div>

            <!-- Ajouter un logo -->
            <div class="col-md-8 col-md-offset-4">
                <?php
                echo $this->Form->input('logo_file', array(
                    'div' => 'input-group inputsForm',
                    'type' => 'file',
                    'class' => 'filestyle',
                    'data-buttonText' => ' Ajouter un logo',
                    'data-buttonName' => "btn-default-primary",
                    'data-buttonBefore' => "true",
                    'label' => false
                ));
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

    <!-- Groupe de bouton centré -->
    <div class="text-center">
        <div class="btn-group send">
            <?php
            // Bouton Annuler
            echo $this->Html->link('<i class="fa fa-times-circle fa-lg"></i>'.__d('default', 'default.btnAnnuler'), $referer, array(
                'class' => 'btn btn-default-default',
                'escape' => false
            ));

            // Bouton Enregistrer
            echo $this->Form->button('<i class="fa fa-floppy-o fa-lg"></i>'.__d('default', 'default.btnEnregistrer'), array(
                'type' => 'submit',
                'class' => 'btn btn-default-success'
            ));
            ?>
        </div>
    </div>
</div>
