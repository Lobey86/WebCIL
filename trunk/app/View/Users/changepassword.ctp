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
                ?>
            </div>
            
            <?php
                echo $this->WebcilForm->inputs([
                    'old_password' => ['autocomplete' => 'off', 'type' => 'password'],
                    'new_password' => ['autocomplete' => 'off', 'type' => 'password'],
                    'new_passwd' => ['autocomplete' => 'off', 'type' => 'password']
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