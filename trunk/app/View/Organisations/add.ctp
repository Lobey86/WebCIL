<?php
echo $this->Html->script('organisations.js');
?>
<div class="well">
    <?php
    if ( file_exists(IMAGES . DS . 'logos/' . $this->Session->read('Organisation.id')) ) {
        echo $this->Html->image('logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'), array('class' => 'pull-right logo-well'));
    }
    ?>
    <h1>Veuillez entrer les informations de la nouvelle organisation</h1>
</div>

<div class="users form">
    <?php echo $this->Form->create('Organisation', array(
        'action' => 'add',
        'type' => 'file',
        'autocomplete' => 'off'
    )); ?>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-tag"></span>
            </span>
        <?php echo $this->Form->input('raisonsociale', array(
            'class' => 'form-control',
            'placeholder' => 'Raison sociale (requis)',
            'label' => false
        )); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-phone-alt"></span>
            </span>
        <?php echo $this->Form->input('telephone', array(
            'class' => 'form-control',
            'placeholder' => 'Téléphone (requis)',
            'label' => false
        )); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-print"></span>
            </span>
        <?php echo $this->Form->input('fax', array(
            'class' => 'form-control',
            'placeholder' => 'Fax (facultatif)',
            'label' => false
        )); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-home"></span>
            </span>
        <?php echo $this->Form->input('adresse', array(
            'div' => 'input-group inputsForm',
            'label' => false,
            'class' => 'form-control',
            'type' => 'textarea',
            'placeholder' => 'Adresse (requis)'
        )); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-envelope"></span>
            </span>
        <?php echo $this->Form->input('email', array(
            'class' => 'form-control',
            'placeholder' => 'E-mail (requis)',
            'label' => false
        ));
        ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-tag"></span>
            </span>
        <?php echo $this->Form->input('sigle', array(
            'class' => 'form-control',
            'placeholder' => 'Sigle (facultatif)',
            'label' => false
        )); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-barcode"></span>
            </span>
        <?php echo $this->Form->input('siret', array(
            'class' => 'form-control',
            'placeholder' => 'N° SIRET (requis)',
            'label' => false
        )); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-barcode"></span>
            </span>
        <?php echo $this->Form->input('ape', array(
            'class' => 'form-control',
            'placeholder' => 'Code APE (requis)',
            'label' => false
        )); ?>
    </div>
    <div class="login">
        <?php echo $this->Form->input('model_file', array(
            'div' => 'input-group inputsForm',
            'type' => 'file',
            'class' => 'filestyle',
            'data-buttonText' => ' Changer le modèle',
            'data-buttonName' => "btn-primary",
            'data-buttonBefore' => "true",
            'label' => false
        )); ?>
    </div>
    <div class="login">
        <?php echo $this->Form->input('logo_file', array(
            'div' => 'input-group inputsForm',
            'type' => 'file',
            'class' => 'filestyle',
            'data-buttonText' => 'Ajouter un logo',
            'data-buttonName' => "btn-primary",
            'data-buttonBefore' => "true",
            'label' => false
        )); ?>
    </div>
    <?php
    echo $this->Html->link('Annuler', array(
        'controller' => 'organisations',
        'action' => 'index'
    ), array('class' => 'btn btn-danger pull-right sender'));
    echo $this->Form->submit('Enregistrer', array('class' => 'btn btn-primary pull-right sender'));
    ?>
</div>