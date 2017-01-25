<div class="users form">
    <?php
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
    <div class="row">
        <div class="col-md-6">
            <?php
            if (empty($this->validationErrors['User']['username'])) {
                echo '<div class="form-group">';
            } else {
                echo '<div class="form-group has-error">';
            }

            //Champs Login *
            echo $this->Form->input('username', [
                'class' => 'form-control',
                'placeholder' => __d('user', 'user.placeholderChampLogin'),
                'label' => [
                    'text' => __d('user', 'user.champLogin') . '<span class="requis">*</span>',
                    'class' => 'col-md-4 control-label'
                ],
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'required' => true
            ]);
            ?>
        </div>

        <?php
        if (empty($this->validationErrors['User']['password'])) {
            echo '<div class="form-group">';
        } else {
            echo '<div class="form-group has-error">';
        }

        //Champs Mot de passe *
        echo $this->Form->input('password', [
            'class' => 'form-control',
            'placeholder' => __d('user', 'user.placeholderChampMotDePasse'),
            'label' => [
                'text' => __d('user', 'user.champMotDePasse') . '<span class="requis">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'required' => true
        ]);
        ?>
    </div>

    <?php
    if (empty($this->validationErrors['User']['passwd'])) {
        echo '<div class="form-group">';
    } else {
        echo '<div class="form-group has-error">';
    }

    //Champs Vérification du mot de passe *
    echo $this->Form->input('passwd', [
        'class' => 'form-control',
        'placeholder' => __d('user', 'user.placeholderChampVerifMotDePasse'),
        'label' => [
            'text' => __d('user', 'user.champVerifMotDePasse') . '<span class="requis">*</span>',
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

<div class="form-group">
    <?php
    //Champ Nom *
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

<div class="form-group">
    <?php
    //Champ Prénom *
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

<?php
if (empty($this->validationErrors['User']['email'])) {
    echo '<div class="form-group">';
} else {
    echo '<div class="form-group has-error">';
}

//Champ E-mail *
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
</div>

<div class="col-md-6">
    <div class="form-group">
        <?php
        $listeOrganisations = [];
        foreach ($tableau['Organisation'] as $key => $datas) {
            $listeOrganisations[$datas['infos']['id']] = $datas['infos']['raisonsociale'];
        }

        //Champ Entité *
        echo $this->Form->input('Organisation.Organisation_id', [
            'options' => $listeOrganisations,
            'class' => 'form-control',
            'id' => 'deroulant',
            'label' => [
                'text' => __d('user', 'user.champEntite') . '<span class="requis">*</span>',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after' => '</div>',
            'multiple' => 'multiple',
            'required' => true
        ]);
        ?>
    </div>

    <?php
    foreach ($tableau['Organisation'] as $key => $datas) {
        $listeroles = [];
        echo "<script type='text/javascript'>";

        foreach ($datas['roles'] as $clef => $value) {
            $listeroles[$value['infos']['id']] = $value['infos']['libelle'];
            echo 'var tableau_js' . $value['infos']['id'] . '= new Array();';
            foreach ($value['droits'] as $k => $v) {
                echo "tableau_js" . $value['infos']['id'] . ".push(" . $v['liste_droit_id'] . ");";
            }
        }
        echo "</script>";
        ?>

        <div class="form-group droitsVille" id="droitsVille<?php echo $key; ?>">
            <div class="titreDiv text-center">
                <h4><?php echo $datas['infos']['raisonsociale']; ?></h4>
            </div>

            <div class="form-group">
                <?php
                if (!empty($listeservices[$datas['infos']['id']])) {
                    //Champ Service *
                    echo $this->Form->input('Service.' . $datas['infos']['id'], [
                        'options' => $listeservices[$datas['infos']['id']],
                        'class' => 'form-control',
                        'id' => 'deroulantService',
                        'label' => [
                            'text' => __d('user', 'user.champService'),
                            'class' => 'col-md-4 control-label'
                        ],
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'multiple' => 'multiple'
                    ]);
                }
                ?>
            </div>

            <div class="form-group">
                <?php
                if (!empty($listeroles)) {
                    //Champ Profils au sein de  *
                    ?>
                    <div class="form-group">
                        <?php
                        //Champ Profils au sein de  *
                        echo $this->Form->input('Role.' . $datas['infos']['id'], [
                            'class' => 'form-control',
                            'label' => [
                                'text' => __d('user', 'user.champProfilEntite') . $datas['infos']['raisonsociale'] . ' <span class="requis">*</span>',
                                'class' => 'col-md-4 control-label'
                            ],
                            'options' => $listeroles,
                            'empty' => true,
                            'between' => '<div class="col-md-8">',
                            'after' => '</div>',
                            'required' => true
                        ]);
                        ?>
                    </div>
                    <?php
                } else {
                    echo "Aucun profil n'a été créé pour cette entité";
                }
                ?>
            </div>
        </div>

        <?php
    }
    ?>
</div>
</div>

<!-- Groupe de bouton -->
<div class="text-center">
    <div class="btn-group send">
        <?php
        //Bouton Annuler
        echo $this->Html->link('<i class="fa fa-arrow-left"></i>' . __d('default', 'default.btnAnnuler'), $referer, [
            'class' => 'btn btn-default-default',
            'escape' => false
        ]);

        //Bouton Entregistrer
        echo $this->Form->button('<i class="fa fa-check"></i>' . __d('default', 'default.btnEnregistrer'), [
            'type' => 'submit',
            'class' => 'btn btn-default-success'
        ]);

        echo '</div>';
        ?>

    </div>
</div>

<?php
echo $this->Html->script('users.js');
?>

<script type="text/javascript">

    $(document).ready(function () {
        $("#deroulant").select2({
            placeholder: "Selectionnez une ou plusieurs entitées",
            allowClear: true
        });

        $("#deroulantService").select2({
            placeholder: "Selectionnez un ou plusieurs service",
            allowClear: true
        });
    });

</script>