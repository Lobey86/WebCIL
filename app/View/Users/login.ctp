<div class="form-block">

    <div class="col-md-12 text-center" style="margin-top: 30px; margin-bottom: 30px;">
        <?php
        echo $this->Html->image('logo_connection_WebCil.png', [
            'class' => 'text-center'
        ]);
        ?>
    </div>

    <div class="row buffer-top text-center">
        Veuillez saisir vos identifiants de connexion
    </div>

    <?php 
    echo $this->Form->create('User');
    ?>

    <div class="form-horizontal form-group" style="margin-top: 30px;">
        <label for="username"
               class="col-md-4 control-label normal-left">
            Identifiant *
        </label>

        <div class="col-md-8 input-group">
            <span class="input-group-addon color-inverse"
                  id="sizing-addon-login">
                <i class="fa fa-user"
                   aria-hidden="true"></i>
            </span>

            <?php 
            echo $this->Form->input('username', [
                'class' => 'form-control',
                'id' => 'username',
                'aria-describedby' => 'sizing-addon-login',
                'label' => false
            ]); 
            ?>
        </div>
    </div>

    <div class="form-horizontal form-group" style="margin-top: 15px;">
        <label for="password"
               class="col-md-4 control-label normal-left">
            Mot de passe *
        </label>

        <div class="col-md-8 input-group">
            <span class="input-group-addon color-inverse"
                  id="sizing-addon-password">
                <i class="fa fa-lock"
                   aria-hidden="true"></i>
            </span>

            <?php 
            echo $this->Form->input('password', [
                'class' => 'form-control',
                'aria-describedby' => 'sizing-addon-password',
                'label' => false
            ]); 
            ?>
        </div>
    </div>

    <div class="row form-horizontal form-group buffer-top" style="margin-top: 30px;">
        <?php
        echo $this->Form->button('<i class="fa fa-sign-in fa-fw"></i>' . ' ' . __d('user','user.btnConnexion'), [
            'type' => 'submit',
            'class' => 'pull-right btn btn-primary'
        ]);
        ?>
    </div>

</div>