<?php
echo $this->Html->script('roles.js');
?>
    <div class="well">
        <h1>Gestion des organisations</h1>
    </div>
    <table class="table table-hover">
        <thead>
        <th class="thcent">Organisation</th>
        <?php
        $nbutil = 3;
        if ($nbutil > 1){
            echo "<th class='thcent'>Actions</th>";
        }
        ?>
        </thead>
        <tbody>
        <?php
        foreach($organisations as $donnees){
            ?>
            <tr>
                <td class="tdcent">
                    <?php echo $donnees['Organisation']['raisonsociale']; ?>
                </td>
                <td class="tdcent">
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('controller'=>'organisations', 'action'=>'show', $donnees['Organisation']['id']), array('class'=>'btn btn-default boutonShow boutonsAction5', 'escapeTitle'=>false));
                    echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', array('controller'=>'organisations', 'action'=>'edit', $donnees['Organisation']['id']), array('class'=>'btn btn-default boutonEdit boutonsAction5', 'escapeTitle'=>false));
                    if ($nbutil > 1){
                        echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array('controller'=>'organisations', 'action'=>'delete', $donnees['Organisation']['id']), array('class'=>'btn btn-danger boutonDelete boutonsAction15', 'escapeTitle'=>false), 'Voulez vous vraiment supprimer le rôle '.$donnees['Organisation']['raisonsociale']);
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
echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> Ajouter une organisation', array('controller'=>'organisations', 'action'=>'add'), array('class'=>'btn btn-primary pull-right sender', 'escapeTitle'=>false));
?>