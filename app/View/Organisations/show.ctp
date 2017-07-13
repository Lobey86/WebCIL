<?php

echo $this->Html->script('organisations.js');
if ( isset($this->validationErrors[ 'Organisation' ]) && !empty($this->validationErrors[ 'Organisation' ]) ) {
    ?>

<div class="alert alert-danger" role="alert">
    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
    <span class="sr-only">Error:</span>
    Ces erreurs se sont produites:
    <ul>
            <?php
            foreach ( $this->validationErrors as $donnees ) {
                foreach ( $donnees as $champ ) {
                    foreach ( $champ as $error ) {
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
        'action' => 'edit',
        'type' => 'file',
        'autocomplete' => 'off',
        'class' => 'form-horizontal'
    )); 
    ?>

    <h2>
        <?php
        // Texte : "L'entité"
        echo __d('organisation','organisation.textEntite');
        ?>
    </h2>

    </br>

    <div class="row">
        <!-- Colonne de gauche -->
        <div class="col-md-6">
            <!-- Champ Raison sociale * -->
            <div class="form-group">
                <?php echo $this->Form->input('raisonsociale', array(
                    'class' => 'form-control',
                    'placeholder' => 'Raison sociale (requis)',
                    'label' => array(
                        'text' => 'Raison sociale <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'escape' => true
                )); ?>
            </div>

            <!-- Champ Téléphone * -->
            <div class="form-group">
                <?php echo $this->Form->input('telephone', array(
                    'class' => 'form-control',
                    'placeholder' => 'Téléphone (requis)',
                    'label' => array(
                        'text' => 'Téléphone <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
            </div>

            <!-- Champ Fax -->
            <div class="form-group">
                <?php echo $this->Form->input('fax', array(
                    'class' => 'form-control',
                    'placeholder' => 'Fax (facultatif)',
                    'label' => array(
                        'text' => 'Fax',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
            </div>

            <!-- Champ Adresse * -->
            <div class="form-group">
                <?php echo $this->Form->input('adresse', array(
                    'div' => 'input-group inputsForm',
                    'label' => array(
                        'text' => 'Adresse <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'placeholder' => 'Adresse (requis)'
                )); ?>
            </div>

            <!-- Champ E-mail * -->
            <div class="form-group">
                <?php echo $this->Form->input('email', array(
                    'class' => 'form-control',
                    'placeholder' => 'E-mail (requis)',
                    'label' => array(
                        'text' => 'E-mail <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
            </div>
        </div>

        <!-- Colonne de droite -->
        <div class="col-md-6">
            <!-- Champ Sigle -->
            <div class="form-group">
                <?php echo $this->Form->input('sigle', array(
                    'class' => 'form-control',
                    'placeholder' => 'Sigle (facultatif)',
                    'label' => array(
                        'text' => 'Sigle',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
            </div>

            <!-- Champ N° Siret * -->
            <div class="form-group">
                <?php echo $this->Form->input('siret', array(
                    'class' => 'form-control',
                    'placeholder' => 'N° SIRET (requis)',
                    'label' => array(
                        'text' => 'N° Siret <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
            </div>

            <!-- Champ Code APE * -->
            <div class="form-group">
                <?php echo $this->Form->input('ape', array(
                    'class' => 'form-control',
                    'placeholder' => 'Code APE (requis)',
                    'label' => array(
                        'text' => 'Code APE <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
            </div>

            <!-- Affichage logo si l'entitée en a déjà un d'enregistré -->
            <div class="col-md-8 col-md-offset-4">
                <?php if ( file_exists(IMAGES . 'logos' . DS . $this->request->data[ 'Organisation' ][ 'id' ] . '.' . $this->request->data[ 'Organisation' ][ 'logo' ]) ) {
                    ?>

                <div class="thumbnail">
                        <?php echo $this->Html->image('logos/' . $this->request->data[ 'Organisation' ][ 'id' ] . '.' . $this->request->data[ 'Organisation' ][ 'logo' ], array(
                            'alt' => 'Logo',
                            'width' => '300px'
                        )); ?>
                </div>
                <?php
                }
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

    </br>
    <!-- Affichage du logo du CIL -->
    <?php
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
            <div class="form-group">
                <?php
                echo $this->Form->input('cil', array(
                    'options' => $users,
                    'div' => 'input-group inputsForm',
                    'class' => 'form-control usersDeroulant',
                    'empty' => 'Selectionnez un nouveau CIL',
                    'label' => array(
                        'text' => 'Cil',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                ));
                ?>
            </div>
        </div>
        <!-- Colonne de groite -->
        <div class="col-md-6">
            <div class="form-group">
                 <!-- Champ numerocil * -->
                <div class="form-group">
                    <?php
                    echo $this->Form->input('numerocil', [
                        'class' => 'form-control',
                        'placeholder' => __d('organisation', 'organisation.placeholderNumeroCIL'),
                        'required' => true,
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
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 top17 text-center">
            <div class="btn-group">
                <?php
                echo $this->Html->link('<i class="fa fa-arrow-left fa-lg"></i>' . __d('default', 'default.btnRetour'), $referer, array(
                    'class' => 'btn btn-default-default',
                    'escape' => false
                ));
                
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(":input").prop("disabled", true);
    $(".boutonDl").prop("disabled", false);
</script>