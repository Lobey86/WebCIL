<div class="role form">
    <?php echo $this->Form->create('Role', array('autocomplete' => 'off')); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $this->Form->input('libelle', array(
                    'class' => 'form-control',
                    'placeholder' => 'Nom du profil',
                    'label' => array(
                        'text' => 'Nom du profil <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
                <?php echo $this->Form->input('organisation_id', array(
                    'class' => 'form-control',
                    'placeholder' => 'Nom du profil',
                    'label' => false,
                    'type' => 'hidden',
                    'value' => $this->Session->read('Organisation.id')
                )); ?>
            </div>
        </div>

        <div class="col-md-6 droitsDroits">

            <?php
            foreach($listedroit as $value) {
                echo $this->Form->input('Droits.' . $value['ListeDroit']['value'], array(
                    'type' => 'checkbox',
                    'label' => $value['ListeDroit']['libelle'],
                    'class' => 'checkDroits'
                ));
            }
            ?>
        </div>
    </div>
    <div class="row text-center send">
        <div class="btn-group">
            <?php
            echo $this->Html->link('<i class="fa fa-arrow-left"></i> Annuler', $referer, array(
                'class' => 'btn btn-default-default',
                'escape' => false
            ));
            echo $this->Form->button('<i class="fa fa-check"></i> Enregistrer', array(
                'type' => 'submit',
                'class' => 'btn btn-default-success'
            ));
            ?>
        </div>
    </div>
</div>