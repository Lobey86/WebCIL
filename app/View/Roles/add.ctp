<div class="well">
    <h2>Veuillez entrer les informations du nouveau rôle</h2>
</div>

<div class="role form">
    <?php echo $this->Form->create('Role');?>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-tag"></span>
            </span>
        <?php echo $this->Form->input('libelle', array('class'=>'form-control', 'placeholder'=>'Nom du rôle', 'label'=>false)); ?>
        <?php echo $this->Form->input('organisation_id', array('class'=>'form-control', 'placeholder'=>'Nom du rôle', 'label'=>false, 'type'=>'hidden', 'value'=>$this->Session->read('Organisation.id'))); ?>
    </div>

    <div class="droitsDroits">
        <fieldset>
            <legend>Droits du rôle</legend>
            <?php
            foreach($listedroit as $value){
                echo $this->Form->input('Droits.'.$value['ListeDroit']['value'], array('type'=>'checkbox', 'label'=>$value['ListeDroit']['libelle'], 'class'=>'checkDroits'));
            }
            ?>
        </fieldset>
    </div>
    <?php
    echo $this->Html->link('Annuler', array('controller'=>'roles', 'action'=>'index'), array('class'=>'btn btn-danger pull-right sender'), 'Voulez-vous vraiment quitter cette page?');
    echo $this->Form->submit('Enregistrer', array('class'=>'btn btn-primary pull-right sender'));    ?>

</div>