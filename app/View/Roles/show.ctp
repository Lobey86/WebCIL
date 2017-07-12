<div class="well">
    <?php
    if(file_exists(IMAGES . DS . 'logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'))) {
        echo $this->Html->image('logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'), array('class' => 'pull-right logo-well'));
    }
    ?>
    <h1>Veuillez entrer les nouvelles informations du profil</h1>
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
            <legend>Droits du profil</legend>
            <?php
                echo $this->Form->input(
                    'ListeDroit.ListeDroit',
                    [
                        'type' => 'select',
                        'multiple' => 'checkbox',
                        'options' => $options['ListeDroit']['ListeDroit'],
                        'label' => false
                    ]
                );
            ?>
        </fieldset>
    </div>
    <?php echo $this->Html->link('Retour', array(
        'controller' => 'organisations',
        'action' => 'index'
    ), array('class' => 'btn btn-default-primary pull-right sender')); ?>
</div>
<script type="text/javascript">
    $(":input").prop("disabled", true);
    $(".sender").prop("disabled", false);
</script>