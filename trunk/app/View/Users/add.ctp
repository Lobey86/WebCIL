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
                'username' => ['autocomplete' => 'off', 'required' => true],
                'password' => ['autocomplete' => 'off', 'required' => true],
                'passwd' => ['autocomplete' => 'off', 'required' => true],
                'civilite' => ['options' => $options['User']['civilite'], 'empty' => true, 'required' => true],
                'nom' => ['required' => true],
                'prenom' => ['required' => true],
                'email' => ['required' => true],
                'telephonefixe' => ['required' => true],
                'telephoneportable' => ['required' => true]
            ]);
        ?>
    </div>

    <div class="col-md-6">
        <!-- Champs Entité * -->
        <?php
            echo $this->WebcilForm->input('Organisation.Organisation_id', [
                'options' => $options['Organisation']['Organisation_id'],
                'id' => 'deroulant',
                'multiple' => 'multiple',
                'required' => true
            ]);
        ?>
        
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

            <div class="form-group droitsVille " id="droitsVille<?php echo $key; ?>">
                <div class="titreDiv text-center">
                    <h4><?php echo $datas['infos']['raisonsociale']; ?></h4>
                </div>

                <?php
                    //Si des service existe on affiche le champs de selection d'un service
                    if (!empty($listeservices[$datas['infos']['id']])) {
                        echo $this->WebcilForm->input('Service.' . $datas['infos']['id'], [
                            'label' => [
                                'text' => __d('user', 'user.champService'),
                            ],
                            'options' => $listeservices[$datas['infos']['id']],
                            'id' => 'deroulantservice',
                            'multiple' => 'multiple',
                            'required' => true
                        ]);
                    }
                ?>

                <?php
                if (!empty($listeroles)) {
                    //Champ Profils au sein de  *
                        echo $this->WebcilForm->input('Role.' . $datas['infos']['id'], [
                            'label' => [
                                'text' => __d('user', 'user.champProfilEntite')
                            ],
                            'options' => $listeroles,
                            'empty' => true,
                            'required' => true
                        ]);
                    ?>
                    <?php
                } else {
                    echo "Aucun profil n'a été créé pour cette entité";
                }
                ?>
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
            placeholder: "Sélectionnez une ou plusieurs entitées",
            allowClear: true
        });

        $("#deroulantservice").select2({
            placeholder: "Sélectionnez un ou plusieurs service",
            allowClear: true
        });
    });

</script>