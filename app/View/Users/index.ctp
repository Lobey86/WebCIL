<?php
echo $this->Html->script('users.js');
?>
<div class="well">
    <h1>Gestion des utilisateurs</h1>
</div>
<table class="table table-hover">
    <thead>
        <th>Utilisateur</th>
        <th>Ajouté le</th>
        <th>Actions</th>

    </thead>
    <tbody>
        <?php
        foreach($users as $donnees){
        ?>
            <tr>
                <td class="tdleft">
                    <?php echo $donnees['User']['prenom'].' '. $donnees['User']['nom']; ?>
                </td>
                <td class="tdleft">
                    <?php echo date('d-m-Y', strtotime($donnees['User']['created'])); ?>
                </td>
                <td class="tdleft">
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', array('controller'=>'users', 'action'=>'edit', $donnees['User']['id']), array('class'=>'btn btn-default boutonEdit boutonsAction5', 'escapeTitle'=>false));
                    if ($donnees['User']['id'] != 1){
                     echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array('controller'=>'users', 'action'=>'index'), array('class'=>'btn btn-danger boutonDelete boutonsAction5', 'escapeTitle'=>false), 'Voulez vous vraiment supprimer '.$donnees['User']['prenom'].' '.$donnees['User']['nom']);
                    }
                    else{
                        echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array('controller'=>'users', 'action'=>'index'), array('class'=>'btn btn-danger boutonDelete boutonsAction5', 'escapeTitle'=>false, "disabled"=>"disabled"), 'Voulez vous vraiment supprimer '.$donnees['User']['prenom'].' '.$donnees['User']['nom']);

                    }
                    ?>
                </td>
            </tr>
            <?php
            }
            ?>
    </tbody>
</table>
<?php
echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> Ajouter un utilisateur', array('controller'=>'users', 'action'=>'add'), array('class'=>'btn btn-primary pull-right sender', 'escapeTitle'=>false));
?>