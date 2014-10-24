<?php
echo $this->Html->script('roles.js');
?>
<div class="well">
    <h1>Gestion des roles</h1>
</div>
    <table class="table table-hover">
        <thead>
        <th class="thcent">Rôle</th>
        <?php
        $nbutil = 3;
        if ($nbutil > 1){
            echo "<th class='thcent'>Actions</th>";
        }
        ?>
        </thead>
        <tbody>
        <?php
        foreach($roles as $donnees){
            ?>
            <tr>
                <td class="tdcent">
                    <?php echo $donnees['Role']['libelle']; ?>
                </td>
                <td class="tdcent">
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('controller'=>'roles', 'action'=>'show', $donnees['Role']['id']), array('class'=>'btn btn-default boutonShow boutonsAction5', 'escapeTitle'=>false));
                    echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', array('controller'=>'roles', 'action'=>'edit', $donnees['Role']['id']), array('class'=>'btn btn-default boutonEdit boutonsAction5', 'escapeTitle'=>false));
                    if ($nbutil > 1){
                        echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array('controller'=>'roles', 'action'=>'index', $donnees['Role']['id']), array('class'=>'btn btn-danger boutonDelete boutonsAction15', 'escapeTitle'=>false), 'Voulez vous vraiment supprimer le rôle '.$donnees['Role']['libelle']);
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
echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> Ajouter un rôle', array('controller'=>'roles', 'action'=>'add'), array('class'=>'btn btn-primary pull-right sender', 'escapeTitle'=>false));
?>