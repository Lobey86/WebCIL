<div class="users form col-md-6 col-md-offset-3">
    <?php echo $this->Form->create('User'); ?>
    <div class="input-group login">
        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
        <?php echo $this->Form->input('username', [
            'class'       => 'form-control',
            'placeholder' => __d('user','user.placeholderChampLogin'),
            'label'       => false
        ]); ?>
    </div>
    <div class="input-group login">
        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
        <?php
            echo $this->Form->input('password', [
                'class'       => 'form-control',
                'placeholder' => __d('user','user.placeholderChampPassword'),
                'label'       => false
            ]);
        ?>
    </div>
    <?php
        echo $this->Form->submit(__d('user','user.btnConnexion'), ['class' => 'btn btn-lg btn-default-success']);
        echo $this->Form->end();
    ?>
</div>