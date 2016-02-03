<div class="users form">
    <?php
        if(isset($this->validationErrors['User']) && !empty($this->validationErrors['User'])) {
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
        echo $this->Form->create('User', [
            'autocomplete'  => 'off',
            'inputDefaults' => ['div' => FALSE],
            'class'         => 'form-horizontal'
        ]); 
            ?>
    
    <div class="row">
        
        <div class="col-md-6">
            <?php
                if(empty($this->validationErrors['User']['username']))
                    echo '<div class="form-group">'; else echo '<div class="form-group has-error">';

                echo $this->Form->input('username', [
                    'class'        => 'form-control',
                    'placeholder'  => 'Login',
                    'label'        => [
                        'text'  => 'Login <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'      => '<div class="col-md-8">',
                    'after'        => '</div>',
                ]);
            ?>
        </div>
        
        <div class="alert alert-info">
            Si vous ne voulez pas modifier votre mot de passe laisser les 3 champs vides
            
            <div class="form-group">
                <?php echo $this->Form->input('old_password', [
                    'class'        => 'form-control',
                    'placeholder'  => 'Votre mot de passe actuel',
                    'label'        => [
                        'text'  => 'Votre mot de passe actuel <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'      => '<div class="col-md-8">',
                    'after'        => '</div>',
                    'type'         => 'password',
                    'autocomplete' => 'off'
                ]); ?>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('new_password', [
                    'class'        => 'form-control',
                    'placeholder'  => 'Nouveau mot de passe',
                    'label'        => [
                        'text'  => 'Nouveau mot de passe <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'      => '<div class="col-md-8">',
                    'after'        => '</div>',
                    'type'         => 'password',
                    'autocomplete' => 'off'
                ]); ?>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('new_passwd', [
                    'class'        => 'form-control',
                    'placeholder'  => 'Nouveau mot de passe (verification)',
                    'label'        => [
                        'text'  => 'Vérification du nouveau mot de passe <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'      => '<div class="col-md-8">',
                    'after'        => '</div>',
                    'type'         => 'password',
                    'autocomplete' => 'off'
                ]); ?>
            </div>
        </div>
        
        <div class="form-group">
            <?php
                echo $this->Form->input('nom', [
                    'class'       => 'form-control',
                    'placeholder' => 'Nom',
                    'label'       => [
                        'text'  => 'Nom <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]);
            ?>
        </div>
        <div class="form-group">
            <?php
                echo $this->Form->input('prenom', [
                    'class'       => 'form-control',
                    'placeholder' => 'Prenom',
                    'label'       => [
                        'text'  => 'Prénom <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]);
            ?>
        </div>
        <div class="form-group">
            <?php
                echo $this->Form->input('email', [
                    'class'       => 'form-control',
                    'placeholder' => 'E-mail',
                    'label'       => [
                        'text'  => 'E-mail <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]);
            ?>
        </div>
        
        <div class="alert alert-warning" role="alert">
            Si vous enregistrez vos informations, vous serez déconnecté de la session pour mettre a jour les informations.
        </div>
        
    </div>
    <?php
        echo '</div>';
        
        echo '<div class="text-center">';
        echo '<div class="btn-group send">';
    
        echo $this->Html->link('<i class="fa fa-arrow-left"></i> Annuler', $referer, [
            'class'  => 'btn btn-default-default',
            'escape' => false
        ]);
        echo $this->Form->button('<i class="fa fa-check"></i> Enregistrer', [
            'type'  => 'submit',
            'class' => 'btn btn-default-success'
        ]);
    ?>
</div>
</div>
<?php
    echo $this->Html->script('users.js');
