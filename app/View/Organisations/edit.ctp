<?php
echo $this->Html->script('organisations.js');
?>
<div class="well">
    <h2>Veuillez entrer les informations de l'organisation</h2>
</div>
<?php
    if(isset($this->validationErrors['Organisation']) && !empty($this->validationErrors['Organisation'])) {
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
?>
<div class="users form">
    <?php echo $this->Form->create('Organisation', array('action'=>'edit', 'type'=>'file'));?>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-tag"></span>
            </span>
        <?php echo $this->Form->input('raisonsociale', array('class'=>'form-control', 'placeholder'=>'Raison sociale (requis)', 'label'=>false)); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-phone-alt"></span>
            </span>
        <?php echo $this->Form->input('telephone', array('class'=>'form-control', 'placeholder'=>'Téléphone (requis)', 'label'=>false)); ?>
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
        <?php echo $this->Form->input('adresse', array('div'=>'input-group inputsForm', 'label'=>false, 'class'=>'form-control', 'type'=>'textarea', 'placeholder'=>'Adresse (requis)')); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-envelope"></span>
            </span>
        <?php echo $this->Form->input('email', array('class'=>'form-control', 'placeholder'=>'E-mail (requis)', 'label'=>false)); ?>
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
        <?php echo $this->Form->input('siret', array('class'=>'form-control', 'placeholder'=>'N° SIRET (requis)', 'label'=>false)); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-barcode"></span>
            </span>
        <?php echo $this->Form->input('ape', array('class'=>'form-control', 'placeholder'=>'Code APE (requis)', 'label'=>false)); ?>
    </div>
    <?php if (file_exists(IMAGES . 'logos' . DS . $this->request->data['Organisation']['id'] . '.' . $this->request->data['Organisation']['logo'])) {
        ?>

        <div class="thumbnail">
            <?php echo $this->Html->image('logos/'.$this->request->data['Organisation']['id'].'.'.$this->request->data['Organisation']['logo'], array('alt' => 'Logo')); ?>
        </div>
    <?php
    }
    ?>



    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-picture"></span>
            </span>
        <?php echo $this->Form->input('logo_file', array('div'=>'input-group inputsForm', 'type' => 'file', 'class'=>'form-control', 'label'=>false)); ?>
    </div>
    <?php

    echo $this->Html->link('Annuler', array('controller'=>'organisations', 'action'=>'index'), array('class'=>'btn btn-danger pull-right sender'));
    echo $this->Form->submit('Enregistrer', array('class'=>'btn btn-primary pull-right sender'));
    ?>
</div>
