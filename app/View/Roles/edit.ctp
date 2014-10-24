<div class="well">
    <h2>Veuillez entrer les nouvelles informations du rôle</h2>
</div>

<div class="role form">
    <?php echo $this->Form->create('Role');?>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-tag"></span>
            </span>
        <?php echo $this->Form->input('username', array('class'=>'form-control', 'value'=>'Validateur', 'label'=>false)); ?>
    </div>
    <div class="droitsFiche">
        <fieldset>
            <legend>Droits sur les fiches</legend>
            <?php
            echo '<div class="inputsFormRight">';
            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Donner un avis sur une fiche', 'checked'=>'checked'));
            echo $this->Form->input('droitsAjoutFiche', array('type'=>'checkbox', 'label'=>'Voir les fiches d\'autres utilisateurs'));
            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Supprimer les fiches d\'autres utilisateurs'));
            echo "</div>";
            echo '<div class="inputsFormLeft">';
            echo $this->Form->input('droitsAjoutFiche', array('type'=>'checkbox', 'label'=>'Ajouter une fiche'));
            echo $this->Form->input('droitsValidFiche', array('type'=>'checkbox', 'label'=>'Valider une fiche', 'checked'=>'checked'));
            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Réorienter les fiches d\'autres utilisateurs'));
            echo $this->Form->input('droitsValidFiche', array('type'=>'checkbox', 'label'=>'Modifier les fiches d\'autres utilisateurs'));
            echo "</div>";
            ?>
        </fieldset>
    </div>
    <div class="droitsOrganisation">
        <fieldset>
            <legend>Droits sur les organisations</legend>
            <?php
            echo '<div class="inputsFormRight">';
            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Modifier une organisation'));
            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Supprimer une organisation'));
            echo "</div>";
            echo '<div class="inputsFormLeft">';
            echo $this->Form->input('droitsAjoutFiche', array('type'=>'checkbox', 'label'=>'Ajouter une organisation'));
            echo $this->Form->input('droitsValidFiche', array('type'=>'checkbox', 'label'=>'Changer le CIL d\'une organisation'));
            echo "</div>";
            ?>
        </fieldset>
    </div>
    <div class="droitsRoles">
        <fieldset>
            <legend>Droits sur les rôles</legend>
            <?php
            echo '<div class="inputsFormRight">';
            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Supprimer un rôle'));
            echo "</div>";
            echo '<div class="inputsFormLeft">';
            echo $this->Form->input('droitsAjoutFiche', array('type'=>'checkbox', 'label'=>'Ajouter un rôle'));
            echo $this->Form->input('droitsValidFiche', array('type'=>'checkbox', 'label'=>'Modifier un rôle'));
            echo "</div>";
            ?>
        </fieldset>
    </div>
    <div class="droitsUtilisateurs">
        <fieldset>
            <legend>Droits sur les utilisateurs</legend>
            <?php
            echo '<div class="inputsFormRight">';
            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Supprimer un utilisateur'));
            echo "</div>";
            echo '<div class="inputsFormLeft">';
            echo $this->Form->input('droitsAjoutFiche', array('type'=>'checkbox', 'label'=>'Ajouter un utilisateur'));
            echo $this->Form->input('droitsValidFiche', array('type'=>'checkbox', 'label'=>'Modifier un utilisateur'));
            echo "</div>";
            ?>
        </fieldset>
    </div>

    <div class="droitsDroits">
        <fieldset>
            <legend>Droits sur les droits</legend>
            <?php
            echo '<div class="inputsFormRight">';
            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Accorder ou supprimer les droits administrateur'));
            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Accorder ou supprimer les droits super-administrateur'));
            echo "</div>";
            echo '<div class="inputsFormLeft">';
            echo $this->Form->input('droitsAjoutFiche', array('type'=>'checkbox', 'label'=>'Accorder ou supprimer les mêmes droits'));
            echo $this->Form->input('droitsValidFiche', array('type'=>'checkbox', 'label'=>'Accorder ou supprimer les droits CIL'));
            echo "</div>";
            ?>
        </fieldset>
    </div>
    <?php
    echo $this->Html->link('Annuler', array('controller'=>'roles', 'action'=>'index'), array('class'=>'btn btn-danger pull-right sender'));
    echo $this->Html->link('Modifier', array('controller'=>'roles', 'action'=>'index'), array('class'=>'btn btn-primary sender pull-right'));
    ?>
</div>
