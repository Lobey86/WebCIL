<div class="role form">
    <?php echo $this->Form->create('Role', array(
        'autocomplete' => 'off',
        'class' => 'form-horizontal'
    )); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $this->Form->input('libelle', array(
                    'class' => 'form-control',
                    'label' => array(
                        'text' => 'Nom du profil <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
            </div>
        </div>

        <div class="col-md-6">
            <?php
            foreach($listedroit as $value) {
                if(in_array($value['ListeDroit']['value'], $tableDroits)) {
                    echo $this->Form->input('Droits.' . $value['ListeDroit']['value'], array(
                        'type' => 'checkbox',
                        'label' => $value['ListeDroit']['libelle'],
                        'class' => 'checkDroits',
                        'checked' => 'checked'
                    ));
                } else {
                    echo $this->Form->input('Droits.' . $value['ListeDroit']['value'], array(
                        'type' => 'checkbox',
                        'label' => $value['ListeDroit']['libelle'],
                        'class' => 'checkDroits'
                    ));
                }
            }
            ?>
        </div>
    </div>
    <?php
    echo $this->Form->input('id', array('type' => 'hidden'));
    echo '<div class="text-center send">';
    echo '<div class="btn-group">';
    echo $this->Html->link('<i class="fa fa-arrow-left"></i> Annuler', $referer, array(
        'class' => 'btn btn-default-default',
        'escape' => false
    ));
    echo $this->Form->button('<i class="fa fa-check"></i> Enregistrer', array(
        'type' => 'submit',
        'class' => 'btn btn-default-success'
    ));
    echo '</div>';
    ?>

</div>
</div>

