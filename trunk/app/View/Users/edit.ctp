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
    
    <div class="col-md-6">
        <?php
            echo $this->WebcilForm->inputs([
                'username' => ['autocomplete' => 'off', 'required' => true]
            ]);
        ?>
        
        <!-- Encadré bleu -->
        <div class="alert alert-info">
            <?php 
                echo __d('user', 'user.textInfoMotDePasse'); 
            
                // Champ caché pour éviter l'autocomplete du navigateur pour le mot de passe
                echo $this->Form->input('password1', [
                    'style' => 'display: none;',
                    'type' => 'password',
                    'label' => false,
                    'id' => false
                ]);
                
                echo $this->WebcilForm->inputs([
                    'new_password' => ['type' => 'password', 'autocomplete' => 'off', 'required' => true],
                    'new_passwd' => ['type' => 'password', 'autocomplete' => 'off', 'required' => true],
                ]);
            ?>
        </div>

        <?php
            echo $this->WebcilForm->inputs([
                'civilite' => ['options' => $options['User']['civilite'], 'empty' => false, 'required' => true],
                'nom' => ['required' => true],
                'prenom' => ['required' => true],
                'email' => ['required' => true],
                'telephonefixe' => [],
                'telephoneportable' => []
            ]);
        ?>
    </div>
    
    <div class="col-md-6">
        <!-- Champs Entité * -->
        <div class="form-group">
            <?php
            $listeOrganisations = [];
            foreach ($tableau['Organisation'] as $key => $datas) {
                $listeOrganisations[$datas['infos']['id']] = $datas['infos']['raisonsociale'];
            }

            echo $this->Form->input('Organisation.Organisation_id', [
                'options' => $listeOrganisations,
                'selected' => $tableau['Orgas'],
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

                <!-- Champs Service -->
                <div class="form-group">
                    <?php
                    //Si des service existe on affiche le champs de selection d'un service
                    if (!empty($listeservices[$datas['infos']['id']])) {
                        //Si l'utilisateur est présent dans un service on le pré-selectionne sinon on affiche juste le champs avec tout les services
                        if (!empty($tableau['UserService'])) {
                            // On pré-selectionne
                            echo $this->Form->input('Service.' . $datas['infos']['id'], [
                                'options' => $listeservices[$datas['infos']['id']],
                                'selected' => $tableau['UserService'],
                                'class' => 'form-control',
                                'id' => 'deroulantservice',
                                'label' => [
                                    'text' => __d('user', 'user.champService'),
                                    'class' => 'col-md-4 control-label'
                                ],
                                'between' => '<div class="col-md-8">',
                                'after' => '</div>',
                                'multiple' => 'multiple'
                            ]);
                        } else {
                            echo $this->Form->input('Service.' . $datas['infos']['id'], [
                                'options' => $listeservices[$datas['infos']['id']],
                                'class' => 'form-control',
                                'id' => 'deroulantservice',
                                'label' => [
                                    'text' => __d('user', 'user.champService'),
                                    'class' => 'col-md-4 control-label'
                                ],
                                'between' => '<div class="col-md-8">',
                                'after' => '</div>',
                                'multiple' => 'multiple',
                            ]);
                        }
                    }
                    ?>
                </div>

                <!-- Champs Profils au sein de * -->
                <div class="form-group">
                    <?php
                    //Si il existe des profils de droit on les affiches
                    if (!empty($listeroles)) {
                        //Si l'utilisateur en question a déjà un type de droit on le pré-selectionne sinon on affiche juste la liste
                        if (!empty($tableau['UserRoles'])) {
                                echo $this->Form->input('Role.role_ida.' . $datas['infos']['id'], [
                                'options' => $listeroles,
                                'class' => 'form-control deroulantRoles' . $key,
                                'selected' => $tableau['UserRoles'],
                                'id' => $key,
                                    'label' => [
                                    'text' => __d('user', 'user.champProfilEntite') . $datas['infos']['raisonsociale'] . '<span class="requis"> *</span>',
                                        'class' => 'col-md-4 control-label'
                                    ],
                                    'between' => '<div class="col-md-8">',
                                    'after' => '</div>',
                                    //'multiple' => 'multiple',
                                ]);
                        } else {
                            echo $this->Form->input('Role.role_ida.' . $datas['infos']['id'], [
                                'options' => $listeroles,
                                'class' => 'form-control deroulantRoles' . $key,
                                'id' => $key,
                                    'label' => [
                                        'text' => __d('user', 'user.champProfilEntite') . $datas['infos']['raisonsociale'] . ' <span class="requis">*</span>',
                                        'class' => 'col-md-4 control-label'
                                    ],
                                    'between' => '<div class="col-md-8">',
                                    'after' => '</div>',
                                    //'multiple' => 'multiple',
                                ]);
                        }
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

</div>

<?php
    // Groupe de boutons
    echo $this->WebcilForm->buttons( array( 'Cancel', 'Save' ) );

    echo $this->Form->end();
?>

<script type="text/javascript">

    $(document).ready(function () {
        $("#deroulant").select2({
            placeholder: "Sélectionnez une ou plusieurs entités",
            allowClear: true
        });

        $("#deroulantservice").select2({
            placeholder: "Sélectionnez un ou plusieurs service",
            allowClear: true
        });
    });

</script>