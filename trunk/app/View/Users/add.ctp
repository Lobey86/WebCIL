<?php
echo $this->Html->script('users.js');
?>
<div class="well">
    <h2>Veuillez entrer les informations du nouvel utilisateur</h2>
</div>

<div class="users form">
    <?php echo $this->Form->create('User', array('action'=>'add'));?>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-user"></span>
            </span>
        <?php echo $this->Form->input('username', array('class'=>'form-control', 'placeholder'=>'Nom d\'utilisateur', 'label'=>false)); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-lock"></span>
            </span>
        <?php echo $this->Form->input('password', array('class'=>'form-control', 'placeholder'=>'Mot de passe', 'label'=>false)); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-lock"></span>
            </span>
        <?php echo $this->Form->input('passwd', array('class'=>'form-control', 'placeholder'=>'Mot de passe (verification)', 'label'=>false)); ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-user"></span>
            </span>
        <?php
        echo $this->Form->input('nom', array('class'=>'form-control', 'placeholder'=>'Nom', 'label'=>false));
        ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-user"></span>
            </span>
        <?php
        echo $this->Form->input('prenom', array('class'=>'form-control', 'placeholder'=>'Prenom', 'label'=>false));
        ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-envelope"></span>
            </span>
        <?php
        echo $this->Form->input('email', array('class'=>'form-control', 'placeholder'=>'E-mail', 'label'=>false));
        ?>
    </div>
    <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-home"></span>
            </span>
        <?php
        echo $this->Form->input('Organisation_ida', array('options' => $listeOrganisations, 'class'=>'form-control', 'id'=>'deroulant', 'label'=>false, 'multiple' => 'multiple')); ?>
    </div>
    <?php
    echo $this->Form->hidden('Organisation_id', array('value'=>'1'));
    echo $this->Form->hidden('role_id', array('value'=>'1'));
    //echo $this->Form->hidden('createdBy', array($idUser));
    foreach($listeOrganisations as $key => $datas){
        ?>
        <div class="panel panel-default inputsForm droitsVille" id="droitsVille<?php echo $key; ?>">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $datas; ?></h3>
            </div>
            <div class="panel-body">
                <div class="input-group login">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-tag"></span>
            </span>
                    <?php echo $this->Form->input('role_ida', array('options' => $listeroles, 'class'=>'form-control deroulantRoles'.$key, 'label'=>false, 'multiple' => 'multiple', 'id'=>$key)); ?>
                </div>
                <button type="button" class="btn btn-default btnDroitsParticuliers" value="<?php echo $key; ?>">Droits particuliers</button>
                <div class="role form droitsParticuliers" id="droitsParticuliers<?php echo $key; ?>">
                    <div class="droitsFiche">
                        <fieldset>
                            <legend>Droits sur les fiches</legend>
                            <?php
                            echo '<div class="inputsFormRight">';
                            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Donner un avis sur une fiche', 'class'=>'checkDroits'.$key.' 1 2 3 4'));
                            echo $this->Form->input('droitsAjoutFiche', array('type'=>'checkbox', 'label'=>'Voir les fiches d\'autres utilisateurs', 'class'=>'checkDroits'.$key.' 1 4'));
                            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Supprimer les fiches d\'autres utilisateurs', 'class'=>'checkDroits'.$key.' 1 4'));
                            echo "</div>";
                            echo '<div class="inputsFormLeft">';
                            echo $this->Form->input('droitsAjoutFiche', array('type'=>'checkbox', 'label'=>'Ajouter une fiche', 'class'=>'checkDroits'.$key.' 2'));
                            echo $this->Form->input('droitsValidFiche', array('type'=>'checkbox', 'label'=>'Valider une fiche', 'class'=>'checkDroits'.$key.' 3'));
                            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Réorienter les fiches d\'autres utilisateurs', 'class'=>'checkDroits'.$key.' 1 4'));
                            echo $this->Form->input('droitsValidFiche', array('type'=>'checkbox', 'label'=>'Modifier les fiches d\'autres utilisateurs', 'class'=>'checkDroits'.$key.' 1'));
                            echo "</div>";
                            ?>
                        </fieldset>
                    </div>
                    <div class="droitsOrganisation">
                        <fieldset>
                            <legend>Droits sur les organisations</legend>
                            <?php
                            echo '<div class="inputsFormRight">';
                            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Modifier une organisation', 'class'=>'checkDroits'.$key.' 4'));
                            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Supprimer une organisation', 'class'=>'checkDroits'.$key.' 4'));
                            echo "</div>";
                            echo '<div class="inputsFormLeft">';
                            echo $this->Form->input('droitsAjoutFiche', array('type'=>'checkbox', 'label'=>'Ajouter une organisation', 'class'=>'checkDroits'.$key.''));
                            echo $this->Form->input('droitsValidFiche', array('type'=>'checkbox', 'label'=>'Changer le CIL d\'une organisation', 'class'=>'checkDroits'.$key.' 4'));
                            echo "</div>";
                            ?>
                        </fieldset>
                    </div>
                    <div class="droitsRoles">
                        <fieldset>
                            <legend>Droits sur les rôles</legend>
                            <?php
                            echo '<div class="inputsFormRight">';
                            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Supprimer un rôle', 'class'=>'checkDroits'.$key.' 4'));
                            echo "</div>";
                            echo '<div class="inputsFormLeft">';
                            echo $this->Form->input('droitsAjoutFiche', array('type'=>'checkbox', 'label'=>'Ajouter un rôle', 'class'=>'checkDroits'.$key.' 4'));
                            echo $this->Form->input('droitsValidFiche', array('type'=>'checkbox', 'label'=>'Modifier un rôle', 'class'=>'checkDroits'.$key.' 4'));
                            echo "</div>";
                            ?>
                        </fieldset>
                    </div>
                    <div class="droitsUtilisateurs">
                        <fieldset>
                            <legend>Droits sur les utilisateurs</legend>
                            <?php
                            echo '<div class="inputsFormRight">';
                            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Supprimer un utilisateur', 'class'=>'checkDroits'.$key.' 1 4'));
                            echo "</div>";
                            echo '<div class="inputsFormLeft">';
                            echo $this->Form->input('droitsAjoutFiche', array('type'=>'checkbox', 'label'=>'Ajouter un utilisateur', 'class'=>'checkDroits'.$key.' 1 4'));
                            echo $this->Form->input('droitsValidFiche', array('type'=>'checkbox', 'label'=>'Modifier un utilisateur', 'class'=>'checkDroits'.$key.' 1 4'));
                            echo "</div>";
                            ?>
                        </fieldset>
                    </div>
                    <div class="droitsDroits">
                        <fieldset>
                            <legend>Droits sur les droits</legend>
                            <?php
                            echo '<div class="inputsFormRight">';
                            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Accorder ou supprimer les droits administrateur', 'class'=>'checkDroits 4'));
                            echo $this->Form->input('droitsDonnerAvis', array('type'=>'checkbox', 'label'=>'Accorder ou supprimer les droits super-administrateur', 'class'=>'checkDroits'));
                            echo "</div>";
                            echo '<div class="inputsFormLeft">';
                            echo $this->Form->input('droitsAjoutFiche', array('type'=>'checkbox', 'label'=>'Accorder ou supprimer les mêmes droits', 'class'=>'checkDroits 4'));
                            echo $this->Form->input('droitsValidFiche', array('type'=>'checkbox', 'label'=>'Accorder ou supprimer les droits CIL', 'class'=>'checkDroits 4'));
                            echo "</div>";
                            ?>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    echo $this->Html->link('Annuler', array('controller'=>'users', 'action'=>'index'), array('class'=>'btn btn-danger pull-right sender'), 'Voulez-vous vraiment quitter cette page?');
    echo $this->Form->submit('Enregistrer', array('class'=>'btn btn-primary pull-right sender'));    ?>
</div>