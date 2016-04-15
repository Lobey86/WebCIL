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
            'inputDefaults' => ['div' => false],
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
                    'placeholder'  => __d('user','user.placeholderChampLogin'),
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
            <?php echo __d('user','user.textChangerInfoMotDePasse');?>
            
            <div class="form-group">
                <?php echo $this->Form->input('old_password', [
                    'class'        => 'form-control',
                    'placeholder'  => __d('user','user.placeholderChampMotDePasseActuel'),
                    'label'        => [
                        'text'  => __d('user','user.champMotDePasseActuel').'<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'      => '<div class="col-md-8">',
                    'after'        => '</div>',
                    'type'         => 'password',
                    'autocomplete' => 'off',
                ]); ?>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('new_password', [
                    'class'        => 'form-control',
                    'placeholder'  => __d('user','user.placeholderChampNouveauMotDePasse'),
                    'label'        => [
                        'text'  => __d('user','user.champNouveauMotDePasse').'<span class="requis">*</span>',
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
                    'placeholder'  => __d('user','user.placeholderChampVerifNouveauMotDePasse'),
                    'label'        => [
                        'text'  => __d('user','user.champVerifNouveauMotDePasse').'<span class="requis">*</span>',
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
                    'placeholder' => __d('user','user.placeholderChampNom'),
                    'label'       => [
                        'text'  => __d('default','default.champNom').'<span class="requis">*</span>',
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
                    'placeholder' => __d('user','user.placeholderChampPrenom'),
                    'label'       => [
                        'text'  => __d('user','user.champPrenom').'<span class="requis">*</span>',
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
                    'placeholder' => __d('user','user.placeholderChampE-mail'),
                    'label'       => [
                        'text'  => __d('default','default.champE-mail').'<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>'
                ]);
            ?>
        </div>
        
        <div class="alert alert-warning" role="alert">
            <?php echo __d('user','user.textInfoDeconnection');?>
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
