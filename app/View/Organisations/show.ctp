<?php
echo $this->Html->script('organisations.js');
?>
<div class="well">
    <h2>Veuillez entrer les informations de la nouvelle organisation</h2>
</div>

<div class="users form">
    <?php echo $this->Form->create('Organisation', array('action'=>'add', 'type'=>'file'));?>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-tag"></span>
            </span>
        <?php echo $this->Form->input('raisonsociale', array('class'=>'form-control', 'placeholder'=>'Raison sociale (requis)', 'label'=>false, 'required'=>'required')); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-phone-alt"></span>
            </span>
        <?php echo $this->Form->input('telephone', array('class'=>'form-control', 'placeholder'=>'Téléphone (requis)', 'label'=>false, 'required'=>'required')); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-print"></span>
            </span>
        <?php echo $this->Form->input('fax', array('class'=>'form-control', 'placeholder'=>'Fax (facultatif)', 'label'=>false)); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-home"></span>
            </span>
        <?php echo $this->Form->input('adresse', array('div'=>'input-group inputsForm', 'label'=>false, 'class'=>'form-control', 'type'=>'textarea', 'placeholder'=>'Adresse (requis)', 'required'=>'required')); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-envelope"></span>
            </span>
        <?php echo $this->Form->input('email', array('class'=>'form-control', 'placeholder'=>'E-mail (requis)', 'label'=>false, 'required'=>'required')); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-tag"></span>
            </span>
        <?php echo $this->Form->input('sigle', array('class'=>'form-control', 'placeholder'=>'Sigle (facultatif)', 'label'=>false)); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-barcode"></span>
            </span>
        <?php echo $this->Form->input('siret', array('class'=>'form-control', 'placeholder'=>'N° SIRET (requis)', 'label'=>false, 'required'=>'required')); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-barcode"></span>
            </span>
        <?php echo $this->Form->input('ape', array('class'=>'form-control', 'placeholder'=>'Code APE (requis)', 'label'=>false, 'required'=>'required')); ?>
    </div>
   <?php echo $this->Html->link('Retour', array('controller'=>'organisations', 'action'=>'index'), array('class'=>'btn btn-primary pull-right sender')); ?>

<script type="text/javascript">
    $(":input").prop("disabled", true);
    $(".sender").prop("disabled", false);
</script>