<?php
echo $this->Html->script('users.js');
?>
    <div class="well">
        <?php
        if ( file_exists(IMAGES . DS . 'logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo')) ) {
            echo $this->Html->image('logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'), array('class' => 'pull-right logo-well'));
        }
        ?>
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
        foreach ( $users as $donnees ) {
            ?>
            <tr>
                <td class="tdleft">
                    <?php echo $donnees[ 'User' ][ 'prenom' ] . ' ' . $donnees[ 'User' ][ 'nom' ]; ?>
                </td>
                <td class="tdleft">
                    <?php echo date('d-m-Y', strtotime($donnees[ 'User' ][ 'created' ])); ?>
                </td>
                <td class="tdleft">

                    <?php if ( $this->Autorisation->authorized(9, $droits) ) {
                        echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', array(
                            'controller' => 'users',
                            'action' => 'edit',
                            $donnees[ 'User' ][ 'id' ]
                        ), array(
                            'class' => 'btn btn-default boutonEdit boutonsAction5',
                            'escapeTitle' => false
                        ));
                    }

                    if ( $this->Autorisation->authorized(10, $droits) ) {
                        if ( $donnees[ 'User' ][ 'id' ] != 1 ) {
                            echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array(
                                'controller' => 'users',
                                'action' => 'delete',
                                $donnees[ 'User' ][ 'id' ]
                            ), array(
                                'class' => 'btn btn-danger boutonDelete boutonsAction5',
                                'escapeTitle' => false
                            ), 'Voulez vous vraiment supprimer ' . $donnees[ 'User' ][ 'prenom' ] . ' ' . $donnees[ 'User' ][ 'nom' ]);

                        }
                        else {
                            echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array(
                                'controller' => 'users',
                                'action' => 'delete',
                                $donnees[ 'User' ][ 'id' ]
                            ), array(
                                'class' => 'btn btn-danger boutonDelete boutonsAction5',
                                'escapeTitle' => false,
                                "disabled" => "disabled"
                            ), 'Voulez vous vraiment supprimer ' . $donnees[ 'User' ][ 'prenom' ] . ' ' . $donnees[ 'User' ][ 'nom' ]);

                        }
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
if ( $this->Autorisation->authorized(8, $droits) ) {
    echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> Ajouter un utilisateur', array(
        'controller' => 'users',
        'action' => 'add'
    ), array(
        'class' => 'btn btn-primary pull-right sender',
        'escapeTitle' => false
    ));
}
?>