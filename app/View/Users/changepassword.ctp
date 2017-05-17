<div class="users form">
    <?php
    echo $this->Html->script('users.js');
    if (isset($this->validationErrors['User']) && !empty($this->validationErrors['User'])) {
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
    
    echo $this->Form->create('User', [
        'autocomplete' => 'off',
        'inputDefaults' => ['div' => false],
        'class' => 'form-horizontal',
        'novalidate' => 'novalidate'
    ]);
    ?>
    
    <!--<div class="row">-->
    <div class="col-md-6">
        <div class="form-group">
            <?php
            // Champs Login *
            echo $this->Form->input('username', [
                'class' => 'form-control',
                'placeholder' => __d('user', 'user.placeholderChampLogin'),
                'label' => [
                    'text' => __d('user', 'user.champLogin') . '<span class="requis">*</span>',
                    'class' => 'col-md-4 control-label'
                ],
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'autocomplete' => 'off',
                'required' => true
            ]);
            ?>
        </div>
        
        <div class="alert alert-info">
            <?php echo __d('user','user.textChangerInfoMotDePasse');?>
            
            <div class="form-group">
                <?php 
                // Champ caché pour éviter l'autocomplete du navigateur pour le mot de passe
                echo $this->Form->input('old_password1', [
                    'style' => 'display: none;',
                    'type' => 'password',
                    'label' => false,
                    'id' => false
                ]);
                
                echo $this->Form->input('old_password', [
                    'class'        => 'form-control',
                    'placeholder'  => __d('user','user.placeholderChampMotDePasseActuel'),
                    'label'        => [
                        'text'  => __d('user','user.champMotDePasseActuel').'<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'      => '<div class="col-md-8">',
                    'after'        => '</div>',
                    'type'         => 'password'
                ]); 
                ?>
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
            //Champ Civilité *
            echo $this->Form->input('civilite', [
                'class' => 'form-control',
                'label' => [
                    'text' => __d('user', 'user.champCivilite') . '<span class="requis">*</span>',
                    'class' => 'col-md-4 control-label'
                ],
                'options' => $options['User']['civilite'],
                'empty' => true,
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'required' => true
            ]);
            ?>
        </div>

        <!-- Champs Nom * -->
        <div class="form-group">
            <?php
            echo $this->Form->input('nom', [
                'class' => 'form-control',
                'placeholder' => __d('user', 'user.placeholderChampNom'),
                'label' => [
                    'text' => __d('default', 'default.champNom') . '<span class="requis">*</span>',
                    'class' => 'col-md-4 control-label'
                ],
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'required' => true
            ]);
            ?>
        </div>
        
        <!-- Champs Prénom * -->
        <div class="form-group">
            <?php
            echo $this->Form->input('prenom', [
                'class' => 'form-control',
                'placeholder' => __d('user', 'user.placeholderChampPrenom'),
                'label' => [
                    'text' => __d('user', 'user.champPrenom') . '<span class="requis">*</span>',
                    'class' => 'col-md-4 control-label'
                ],
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'required' => true
            ]);
            ?>
        </div>
        
        <!-- Champs E-mail * -->
        <div class="form-group">
            <?php
            echo $this->Form->input('email', [
                'class' => 'form-control',
                'placeholder' => __d('user', 'user.placeholderChampE-mail'),
                'label' => [
                    'text' => __d('default', 'default.champE-mail') . '<span class="requis">*</span>',
                    'class' => 'col-md-4 control-label'
                ],
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'required' => true
            ]);
            ?>
        </div>
        
        <div class="form-group">
            <?php
            // Champ Téléphone fixe
            echo $this->Form->input('telephonefixe', array(
                'class' => 'form-control',
                'placeholder' => __d('user', 'user.placeholderTelephoneFixe'),
                'label' => array(
                    'text' => __d('user', 'user.textTelephoneFixe'),
                    'class' => 'col-md-4 control-label'
                ),
                'between' => '<div class="col-md-8">',
                'after' => '</div>'
            ));
            ?>
        </div>

        <div class="form-group">
            <?php
            // Champ Téléphone portable
            echo $this->Form->input('telephoneportable', array(
                'class' => 'form-control',
                'placeholder' => __d('user', 'user.placeholderTelephonePortable'),
                'label' => array(
                    'text' => __d('user', 'user.textTelephonePortable'),
                    'class' => 'col-md-4 control-label'
                ),
                'between' => '<div class="col-md-8">',
                'after' => '</div>'
            ));
            ?>
        </div>
    </div>
</div>

</div>

<!-- Groupe de bouton -->
<div class="text-center">
    <div class="btn-group send">
        <?php
        //Bouton Annuler
        echo $this->Html->link('<i class="fa fa-arrow-left"></i>' . __d('default', 'default.btnAnnuler'), [
            'controller' => 'pannel',
            'action' => 'index'
        ], [
            'class' => 'btn btn-default-default',
            'escape' => false
        ]);

        //Bouton Enregistrer
        echo $this->Form->button('<i class="fa fa-check"></i>' . __d('default', 'default.btnEnregistrer'), [
            'type' => 'submit',
            'class' => 'btn btn-default-success'
        ]);
        ?>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $("#deroulant").select2({
            placeholder: "Sélectionnez une ou plusieurs entitées",
            allowClear: true
        });

        $("#deroulantservice").select2({
            placeholder: "Sélectionnez un ou plusieurs service",
            allowClear: true
        });
    });

</script>