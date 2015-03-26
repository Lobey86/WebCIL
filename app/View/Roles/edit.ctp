<div class="well">
    <h2>Veuillez entrer les nouvelles informations du rôle</h2>
</div>

<div class="role form">
    <?php echo $this->Form->create('Role');?>
    <div class="input-group login">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-tag"></span>
        </span>
        <?php echo $this->Form->input('libelle', array('class'=>'form-control', 'label'=>false)); ?>
    </div>
    <div class="droitsDroits">
        <fieldset>
            <legend>Droits du rôle</legend>
            <?php
            foreach($listedroit as $value){
                if(in_array($value['ListeDroit']['value'], $tableDroits)){
                    echo $this->Form->input('Droits.'.$value['ListeDroit']['value'], array('type'=>'checkbox', 'label'=>$value['ListeDroit']['libelle'], 'class'=>'checkDroits', 'checked'=>'checked'));
                }
                else{
                    echo $this->Form->input('Droits.'.$value['ListeDroit']['value'], array('type'=>'checkbox', 'label'=>$value['ListeDroit']['libelle'], 'class'=>'checkDroits'));
                }
            }
            ?>
        </fieldset>
    </div>
    <?php
    echo $this->Form->input('id', array('type' => 'hidden'));
    echo $this->Html->link('Annuler', array('controller'=>'roles', 'action'=>'index'), array('class'=>'btn btn-danger pull-right sender'));
    echo $this->Form->submit('Enregistrer', array('class'=>'btn btn-primary pull-right sender'));
    ?>
</div>
