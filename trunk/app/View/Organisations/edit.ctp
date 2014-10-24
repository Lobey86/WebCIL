<?php
echo $this->Html->script('organisations.js');
?>
<div class="well">
    <h2>Veuillez entrer les nouvelles informations de l'organisation</h2>
</div>

<div class="users form">
    <?php echo $this->Form->create('Organisation');?>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-tag"></span>
            </span>
        <?php echo $this->Form->input('nom', array('class'=>'form-control', 'placeholder'=>'Nom de l\'organisation', 'label'=>false, 'value'=>'ADULLACT')); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-phone-alt"></span>
            </span>
        <?php echo $this->Form->input('telephone', array('class'=>'form-control', 'placeholder'=>'Téléphone', 'label'=>false, 'value'=>'04 67 65 96 44')); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-home"></span>
            </span>
        <?php echo $this->Form->input('adresse', array('div'=>'input-group inputsForm', 'label'=>false, 'class'=>'form-control', 'type'=>'textarea', 'placeholder'=>'Adresse', 'value'=>str_replace('<br>', "\n", 'Bât Le Tucano <br>836, rue du mas de Verchant<br>34000 Montpellier'))); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-picture"></span>
            </span>
        <?php echo $this->Form->input('upload', array('div'=>'input-group inputsForm', 'type' => 'file', 'multiple'=>'multiple', 'class'=>'form-control', 'label'=>false)); ?>
    </div>
    <?php
    echo $this->Html->link('Annuler', array('controller'=>'organisations', 'action'=>'index'), array('class'=>'btn btn-danger pull-right sender'));
    echo $this->Html->link('Modifier', array('controller'=>'organisations', 'action'=>'index'), array('class'=>'btn btn-primary sender pull-right'));
    ?>
</div>