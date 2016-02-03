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
        ]); ?>
    <div class="row">
        <div class="col-md-6">
            <?php
                if(empty($this->validationErrors['User']['username'])) {
                    echo '<div class="form-group">';
                } else {
                    echo '<div class="form-group has-error">';
                }

                echo $this->Form->input('username', [
                    'class'        => 'form-control',
                    'placeholder'  => 'Login',
                    'label'        => [
                        'text'  => 'Login <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'      => '<div class="col-md-8">',
                    'after'        => '</div>',
                    'autocomplete' => 'off'
                ]);
            ?>
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
    </div>
    <div class="col-md-6">
        <?php
            if($this->request->data['User']['id'] != $this->Session->read('Auth.User.id')) {
                ?>
                <div class="form-group">
                    <?php
                        $listeOrganisations = [];
                        foreach($tableau['Organisation'] as $key => $datas) {
                            $listeOrganisations[$datas['infos']['id']] = $datas['infos']['raisonsociale'];
                        }
                        echo $this->Form->input('Organisation.Organisation_id', [
                            'options'  => $listeOrganisations,
                            'class'    => 'form-control',
                            'id'       => 'deroulant',
                            'label'    => [
                                'text'  => 'Entités <span class="requis">*</span>',
                                'class' => 'col-md-4 control-label'
                            ],
                            'between'  => '<div class="col-md-8">',
                            'after'    => '</div>',
                            'multiple' => 'multiple',
                            'selected' => $tableau['Orgas']
                        ]); ?>
                </div>
                <?php
                foreach($tableau['Organisation'] as $key => $datas) {

                    $listeroles = [];
                    echo "<script type='text/javascript'>";

                    foreach($datas['roles'] as $clef => $value) {
                        $listeroles[$value['infos']['id']] = $value['infos']['libelle'];
                        echo 'var tableau_js' . $value['infos']['id'] . '= new Array();';
                        foreach($value['droits'] as $k => $v) {
                            echo "tableau_js" . $value['infos']['id'] . ".push(" . $v['liste_droit_id'] . ");";
                        }
                    }
                    echo "</script>";
                    ?>

                    <div class="form-group droitsVille" id="droitsVille<?php echo $key; ?>">
                        <?php
                            if(!empty($listeroles)) {
                                if(!empty($tableau['UserRoles'])) {
                                    echo $this->Form->input('Role.role_ida.' . $datas['infos']['id'], [
                                        'options'  => $listeroles,
                                        'class'    => 'form-control deroulantRoles' . $key,
                                        'selected' => $tableau['UserRoles'],
                                        'id'       => $key,
                                        'label'    => [
                                            'text'  => 'Profils au sein de ' . $datas['infos']['raisonsociale'] . '<span class="requis">*</span>',
                                            'class' => 'col-md-4 control-label'
                                        ],
                                        'between'  => '<div class="col-md-8">',
                                        'after'    => '</div>',
                                        'multiple' => 'multiple'
                                    ]);
                                } else {
                                    echo $this->Form->input('Role.role_ida.' . $datas['infos']['id'], [
                                        'options'  => $listeroles,
                                        'class'    => 'form-control deroulantRoles' . $key,
                                        'id'       => $key,
                                        'label'    => [
                                            'text'  => 'profils au sein de ' . $datas['infos']['raisonsociale'] . ' <span class="requis">*</span>',
                                            'class' => 'col-md-4 control-label'
                                        ],
                                        'between'  => '<div class="col-md-8">',
                                        'after'    => '</div>',
                                        'multiple' => 'multiple'
                                    ]);
                                }
                            } else {
                                echo "Aucun profil n'a été créé pour cette entité";
                            }
                        ?>
                    </div>

                    <?php
                }
            }
            echo '</div>';
        ?>
    </div>
    <?php
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
        echo '</div>';
    ?>

</div>
</div>
<?php
    echo $this->Html->script('users.js');
