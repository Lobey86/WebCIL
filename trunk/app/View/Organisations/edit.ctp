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
        'action'       => 'edit',
        'type'         => 'file',
        'autocomplete' => 'off',
        'class'        => 'form-horizontal'
    ]); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $this->Form->input('raisonsociale', [
                    'class'       => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderRaisonSociale'),
                    'required' => true,
                    'label'       => [
                        'text'  => __d('organisation', 'organisation.textRaisonSociale').'<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>',
                    'escape'      => true
                ]); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('telephone', [
                    'class'       => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderTelephone'),
                    'required' => true,
                    'label'       => [
                        'text'  => __d('organisation', 'organisation.textTelephone').'<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('fax', [
                    'class'       => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderFax'),
                    'label'       => [
                        'text'  => __d('organisation', 'organisation.textFax'),
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
                        'text'  => __d('organisation', 'organisation.textAdresse').'<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>',
                    'class'       => 'form-control',
                    'type'        => 'textarea',
                    'placeholder' => __d('organisation', 'organisation.placeholderAdresse'),
                    'required' => true
                ]); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('email', [
                    'class'       => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderE-mail'),
                    'required' => true,
                    'label'       => [
                        'text'  => __d('organisation', 'organisation.textE-mail').'<span class="requis">*</span>',
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
                    'placeholder' => __d('organisation', 'organisation.placeholderSigle'),
                    'label'       => [
                        'text'  => __d('organisation', 'organisation.textSigle'),
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('siret', [
                    'class'       => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderSIRET'),
                    'required' => true,
                    'label'       => [
                        'text'  => __d('organisation', 'organisation.textSIRET').'<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('ape', [
                    'class'       => 'form-control',
                    'placeholder' => __d('organisation', 'organisation.placeholderAPE'),
                    'required' => true,
                    'label'       => [
                        'text'  => __d('organisation', 'organisation.textAPE').'<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]); ?>
            </div>
            <div class="form-group">
                <?php
                    echo $this->Form->input('cil', [
                        'options' => $users,
                        'div'     => 'input-group inputsForm',
                        'class'   => 'form-control usersDeroulant',
                        'empty'   => __d('organisation', 'organisation.placeholderCIL'),
                        'label'   => [
                            'text'  => __d('organisation', 'organisation.textCIL'),
                            'class' => 'col-md-4 control-label'
                        ],
                        'between' => '<div class="col-md-8">',
                        'after'   => '</div>'
                    ]);
                ?>
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
                <?php if(file_exists(IMAGES . 'logos' . DS . $this->request->data['Organisation']['id'] . '.' . $this->request->data['Organisation']['logo'])) {
                    ?>

                    <div class="thumbnail">
                        <?php echo $this->Html->image('logos/' . $this->request->data['Organisation']['id'] . '.' . $this->request->data['Organisation']['logo'], [
                            'alt'   => 'Logo',
                            'width' => '300px'
                        ]); ?>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div class="col-md-8 col-md-offset-4">
                <?php echo $this->Form->input('logo_file', [
                    'div'               => 'input-group inputsForm',
                    'type'              => 'file',
                    'class'             => 'filestyle',
                    'data-buttonText'   => __d('organisation', 'organisation.btnLogo'),
                    'data-buttonName'   => "btn-default-primary",
                    'data-buttonBefore' => "true",
                    'label'             => false
                ]); ?>
            </div>
        </div>
    </div>
    <div class="text-center">

        <?php
            echo '<div class="btn-group send">';
            echo $this->Html->link('<i class="fa fa-arrow-left"></i>'. __d('default', 'default.btnAnnuler'), $referer, [
                'class'  => 'btn btn-default-default',
                'escape' => false
            ]);
            echo $this->Form->button('<i class="fa fa-check"></i>' . __d('default', 'default.btnEnregistrer'),[
                'type'  => 'submit',
                'class' => 'btn btn-default-success'
            ]);
            echo '</div>';
        ?>
    </div>
</div>
