<?php
echo $this->Html->script('organisations.js');
?>
<div class="well">
    <h2>Veuillez entrer les informations de la nouvelle organisation</h2>
</div>

<div class="users form">
    <?php echo $this->Form->create('Organisation');?>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-tag"></span>
            </span>
        <?php echo $this->Form->input('nom', array('class'=>'form-control', 'placeholder'=>'Nom de l\'organisation', 'label'=>false)); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-phone-alt"></span>
            </span>
        <?php echo $this->Form->input('telephone', array('class'=>'form-control', 'placeholder'=>'Téléphone', 'label'=>false)); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-home"></span>
            </span>
        <?php echo $this->Form->input('adresse', array('div'=>'input-group inputsForm', 'label'=>false, 'class'=>'form-control', 'type'=>'textarea', 'placeholder'=>'Adresse')); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-picture"></span>
            </span>
    <?php echo $this->Form->input('upload', array('div'=>'input-group inputsForm', 'type' => 'file', 'multiple'=>'multiple', 'class'=>'form-control', 'label'=>false)); ?>
</div>
    <?php
    echo $this->Html->link('Annuler', array('controller'=>'organisations', 'action'=>'index'), array('class'=>'btn btn-danger pull-right sender'));
    echo $this->Html->link('Ajouter', array('controller'=>'organisations', 'action'=>'index'), array('class'=>'btn btn-primary sender pull-right'));
    ?>
</div>