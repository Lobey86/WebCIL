<div class="users form">
    <?php
    echo $this->Html->script('users.js');

    if (isset($this->validationErrors['Admin']) && !empty($this->validationErrors['Admin'])) {
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
    
    echo $this->Form->create('Admin', [
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
                'passwd' => ['type' => 'password','autocomplete' => 'off', 'required' => true],
                'civilite' => ['options' => $options, 'empty' => true, 'required' => true],
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