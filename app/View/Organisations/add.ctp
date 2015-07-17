<?php
    echo $this->Html->script('organisations.js');
    if(isset($this->validationErrors['Organisation']) && !empty($this->validationErrors['Organisation'])) {
        ?>

        <div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            Ces erreurs se sont produites:
            <ul>
                <?php

                    foreach($this->validationErrors as $donnees) {
                        foreach($donnees as $champ) {
                            foreach($champ as $error) {
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
    <?php echo $this->Form->create('Organisation', [
        'action'       => 'add',
        'type'         => 'file',
        'autocomplete' => 'off',
        'class'        => 'form-horizontal'
    ]); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $this->Form->input('raisonsociale', [
                    'class'       => 'form-control',
                    'placeholder' => 'Raison sociale (requis)',
                    'label'       => [
                        'text'  => 'Raison sociale <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>',
                    'escape'      => TRUE
                ]); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('telephone', [
                    'class'       => 'form-control',
                    'placeholder' => 'Téléphone (requis)',
                    'label'       => [
                        'text'  => 'Téléphone <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('fax', [
                    'class'       => 'form-control',
                    'placeholder' => 'Fax (facultatif)',
                    'label'       => [
                        'text'  => 'Fax',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('adresse', [
                    'div'         => 'input-group inputsForm',
                    'label'       => [
                        'text'  => 'Adresse <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>',
                    'class'       => 'form-control',
                    'type'        => 'textarea',
                    'placeholder' => 'Adresse (requis)'
                ]); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('email', [
                    'class'       => 'form-control',
                    'placeholder' => 'E-mail (requis)',
                    'label'       => [
                        'text'  => 'E-mail <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $this->Form->input('sigle', [
                    'class'       => 'form-control',
                    'placeholder' => 'Sigle (facultatif)',
                    'label'       => [
                        'text'  => 'Sigle',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('siret', [
                    'class'       => 'form-control',
                    'placeholder' => 'N° SIRET (requis)',
                    'label'       => [
                        'text'  => 'N° Siret <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('ape', [
                    'class'       => 'form-control',
                    'placeholder' => 'Code APE (requis)',
                    'label'       => [
                        'text'  => 'Code APE <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]); ?>
            </div>

            <div>
                <?php
                    /*
                    if ( file_exists('files' . DS . 'modeles' . DS . $this->Session->read('Organisation.id') . '.odt') ) {
                        echo '
            <ul class="list-group">
                <li class="list-group-item itemfiles">
                ' . $this->Html->link('<span class="glyphicon glyphicon-download-alt"></span>', '/files/modeles/' . $this->Session->read('Organisation.id') . '.odt', array(
                                'class' => 'btn btn-default-default pull-right',
                                'escapeTitle' => false,
                                'target' => '_blank'
                            )) . '<span class="glyphicon glyphicon-file"></span> modele.odt</li>
            </ul> ';
                    }
                    ?>
                </div>
                <div class="login">
                    <?php echo $this->Form->input('model_file', array(
                        'div' => 'input-group inputsForm',
                        'type' => 'file',
                        'class' => 'filestyle',
                        'data-buttonText' => ' Changer le modèle',
                        'data-buttonName' => "btn-primary",
                        'data-buttonBefore' => "true",
                        'label' => false
                    )); */ ?>
            </div>
            <div class="col-md-8 col-md-offset-4">
                <?php echo $this->Form->input('logo_file', [
                    'div'               => 'input-group inputsForm',
                    'type'              => 'file',
                    'class'             => 'filestyle',
                    'data-buttonText'   => ' Ajouter un logo',
                    'data-buttonName'   => "btn-default-primary",
                    'data-buttonBefore' => "true",
                    'label'             => FALSE
                ]); ?>
            </div>
        </div>
    </div>
    <div class="text-center">

        <?php
            echo '<div class="btn-group send">';
            echo $this->Html->link('<i class="fa fa-arrow-left"></i> Annuler', $referer, [
                'class'  => 'btn btn-default-default',
                'escape' => FALSE
            ]);
            echo $this->Form->button('<i class="fa fa-check"></i> Enregistrer', [
                'type'  => 'submit',
                'class' => 'btn btn-default-success'
            ]);
            echo '</div>';
            debug($this->validationErrors);
        ?>
    </div>
</div>
