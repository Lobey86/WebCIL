<div class="well">
    <?php
    if ( file_exists(IMAGES . DS . 'logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo')) ) {
        echo $this->Html->image('logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'), array('class' => 'pull-right logo-well'));
    }
    ?>
    <h1>Veuillez entrer les nouvelles informations du rôle</h1>
</div>

<div class="role form">
    <?php echo $this->Form->create('Role'); ?>
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
    <?php echo $this->Html->link('Retour', array(
        'controller' => 'organisations',
        'action' => 'index'
    ), array('class' => 'btn btn-primary pull-right sender')); ?>
</div>
<script type="text/javascript">
    $(":input").prop("disabled", true);
    $(".sender").prop("disabled", false);
</script>