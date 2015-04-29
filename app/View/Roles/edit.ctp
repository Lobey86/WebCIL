<div class="well">
    <?php
    if ( file_exists(IMAGES . DS . 'logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo')) ) {
        echo $this->Html->image('logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'), array('class' => 'pull-right logo-well'));
    }
    ?>
    <h1>Veuillez entrer les nouvelles informations du rôle</h1>
</div>

<div class="role form">
    <?php echo $this->Form->create('Role', array('autocomplete' => 'off')); ?>
    <div class="input-group login">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-tag"></span>
        </span>
        <?php echo $this->Form->input('libelle', array(
            'class' => 'form-control',
            'label' => false
        )); ?>
    </div>
    <div class="droitsDroits">
        <fieldset>
            <legend>Droits du rôle</legend>
            <?php
            foreach ( $listedroit as $value ) {
                if ( in_array($value[ 'ListeDroit' ][ 'value' ], $tableDroits) ) {
                    echo $this->Form->input('Droits.' . $value[ 'ListeDroit' ][ 'value' ], array(
                        'type' => 'checkbox',
                        'label' => $value[ 'ListeDroit' ][ 'libelle' ],
                        'class' => 'checkDroits',
                        'checked' => 'checked'
                    ));
                }
                else {
                    echo $this->Form->input('Droits.' . $value[ 'ListeDroit' ][ 'value' ], array(
                        'type' => 'checkbox',
                        'label' => $value[ 'ListeDroit' ][ 'libelle' ],
                        'class' => 'checkDroits'
                    ));
                }
            }
            ?>
        </fieldset>
    </div>
    <?php
    echo $this->Form->input('id', array('type' => 'hidden'));
    echo $this->Html->link('Annuler', array(
        'controller' => 'roles',
        'action' => 'index'
    ), array('class' => 'btn btn-danger pull-right sender'));
    echo $this->Form->submit('Enregistrer', array('class' => 'btn btn-primary pull-right sender'));
    ?>
</div>

