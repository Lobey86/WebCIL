<div class="role form">
    <?php
        echo $this->Form->create('Role', array('autocomplete' => 'off'));
    ?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php
                    echo $this->Form->input('libelle', [
                        'class' => 'form-control',
                        'placeholder' => __d('role','role.placeholderChampNomProfil'),
                        'label' => [
                            'text' => __d('role','role.champNomProfil').'<span class="requis">*</span>',
                            'class' => 'col-md-4 control-label'
                        ],
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>'
                    ]);
                ?>
            </div>
        </div>

        <div class="col-md-6 droitsDroits">
            <?php
                echo $this->Form->input(
                    'ListeDroit.ListeDroit',
                    [
                        'type' => 'select',
                        'multiple' => 'checkbox',
                        'options' => $options['ListeDroit']['ListeDroit'],
                        'label' => false
                    ]
                );
            ?>
        </div>
    </div>
    <div class="row text-center send">
        <div class="btn-group">
            <?php
            echo $this->Html->link('<i class="fa fa-times-circle fa-lg"></i>'.__d('default','default.btnAnnuler'), $referer, array(
                'class' => 'btn btn-default-default',
                'escape' => false
            ));
            echo $this->Form->button('<i class="fa fa-floppy-o fa-lg"></i>'.__d('default','default.btnEnregistrer'), array(
                'type' => 'submit',
                'class' => 'btn btn-default-success'
            ));
            ?>
        </div>
    </div>
</div>