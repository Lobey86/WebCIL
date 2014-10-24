<div class="well">
    <h2>Veuillez vous identifier</h2>
</div>

<div class="users form">
    <?php echo $this->Form->create('User');?>
    <div class="input-group login">
        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
        <?php echo $this->Form->input('username', array('class'=>'form-control', 'placeholder'=>'Utilisateur', 'label'=>false)); ?>
    </div>
    <div class="input-group login">
        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
        <?php
        echo $this->Form->input('password', array('class'=>'form-control', 'placeholder'=>'Mot de passe', 'label'=>false));
        ?>
    </div>
    <?php
    echo $this->Form->submit('Connexion', array('class'=>'btn btn-lg btn-success'));
    echo $this->Form->end();
    ?>
</div>