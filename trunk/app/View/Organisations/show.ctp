<?php
echo $this->Html->script('organisations.js');
?>
<div class="well">
    <?php
    if ( file_exists(IMAGES . DS . 'logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo')) ) {
        echo $this->Html->image('logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'), array('class' => 'pull-right logo-well'));
    }
    ?>
    <h1><?php echo $this->request->data[ 'Organisation' ][ 'raisonsociale' ] ?></h1>

</div>

<div class="users form">
    <?php echo $this->Form->create('Organisation', array(
        'action' => 'add',
        'type' => 'file'
    )); ?>

    <?php if ( file_exists(IMAGES . 'logos' . DS . $this->request->data[ 'Organisation' ][ 'id' ] . '.' . $this->request->data[ 'Organisation' ][ 'logo' ]) ) {
        ?>

        <div class="thumbnail">
            <?php echo $this->Html->image('logos/' . $this->request->data[ 'Organisation' ][ 'id' ] . '.' . $this->request->data[ 'Organisation' ][ 'logo' ], array('alt' => 'Logo')); ?>
        </div>
    <?php
    }
    ?>

    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-tag"></span>
            </span>
        <?php echo $this->Form->input('raisonsociale', array(
            'class' => 'form-control',
            'placeholder' => 'Raison sociale (requis)',
            'label' => false,
            'required' => 'required'
        )); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-phone-alt"></span>
            </span>
        <?php echo $this->Form->input('telephone', array(
            'class' => 'form-control',
            'placeholder' => 'Téléphone (requis)',
            'label' => false,
            'required' => 'required'
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
            'placeholder' => 'Adresse (requis)',
            'required' => 'required'
        )); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-envelope"></span>
            </span>
        <?php echo $this->Form->input('email', array(
            'class' => 'form-control',
            'placeholder' => 'E-mail (requis)',
            'label' => false,
            'required' => 'required'
        )); ?>
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
            'label' => false,
            'required' => 'required'
        )); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-barcode"></span>
            </span>
        <?php echo $this->Form->input('ape', array(
            'class' => 'form-control',
            'placeholder' => 'Code APE (requis)',
            'label' => false,
            'required' => 'required'
        )); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-user"></span>
            </span>
        <?php echo $this->Form->input('CIL', array(
            'class' => 'form-control',
            'placeholder' => 'Code APE (requis)',
            'label' => false,
            'required' => 'required',
            'value' => $this->request->data[ 'Cil' ][ 'prenom' ] . ' ' . $this->request->data[ 'Cil' ][ 'nom' ]
        )); ?>
    </div>


    <?php echo $this->Html->link('Retour', array(
        'controller' => 'organisations',
        'action' => 'index'
    ), array('class' => 'btn btn-primary pull-right sender')); ?>

    <script type="text/javascript">
        $(":input").prop("disabled", true);
        $(".sender").prop("disabled", false);
    </script>