<?php

echo $this->Form->create('Service', array(
    'autocomplete' => 'off',
    'inputDefaults' => array('div' => false),
    'class' => 'form-horizontal'
));
?>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            echo $this->Form->input('libelle', array(
                'class' => 'form-control',
                'placeholder' => __d('service','service.placeholderChampNomService'),
                'label' => array(
                    'text' => __d('service','service.champNomService').'<span class="requis">*</span>',
                    'class' => 'col-md-4 control-label'
                ),
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'required' => true
            ));
            echo $this->Form->hidden('organisation_id', array('value' => $this->Session->read('Organisation.id')));
            ?>
        </div>
    </div>
<?php
echo '<div class="text-center">';


echo '<div class="btn-group send">';
echo $this->Html->link('<i class="fa fa-arrow-left"></i>'.__d('default','default.btnAnnuler'), $referer, array(
    'class' => 'btn btn-default-default',
    'escape' => false
));
echo $this->Form->button('<i class="fa fa-check"></i>'.__d('default','default.btnEnregistrer'), array(
    'type' => 'submit',
    'class' => 'btn btn-default-success'
));
echo '</div>';
?>