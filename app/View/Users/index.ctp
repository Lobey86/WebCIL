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

<?php
if ( $this->Autorisation->isSu() ) {
    echo $this->Form->button('<span class="glyphicon glyphicon-filter"></span> Filtrer les utilisateurs', $options = array(
        'type' => 'button',
        'class' => 'btn btn-primary pull-right',
        'id' => 'filtrageUsers'
    ));

    if ( !empty($this->request->data) ) {
        echo '<div id="filtreUsers">';
    }
    else {
        echo '<div id="filtreUsers" style="display: none;">';
    }


    ?>

        <div class="row">
            <?php
    echo $this->Form->create('users', array('action' => 'index'));
    echo $this->Form->input('organisation', array(
        'empty' => 'Choisissez une organisation',
        'class' => 'usersDeroulant transformSelect form-control',
        'label' => 'Filtrer par organisation',
        'options' => $orgas,
        'before' => '<div class="col-md-4 col-md-offset-1">',
        'after' => '</div>'
    ));
    echo $this->Form->input('nom', array(
        'empty' => 'Chercher par utilisateur',
        'class' => 'usersDeroulant transformSelect form-control',
        'label' => 'Filtrer par nom',
        'options' => $utilisateurs,
        'before' => '<div class="col-md-4 col-md-offset-2">',
        'after' => '</div>'
    ));
    ?>
        </div>
        <div class="row top30">
            <div class="col-md-4 col-md-offset-5 btn-group">
                <?php
    echo $this->Html->link('Réinitialiser', array(
        'controller' => 'users',
        'action' => 'index'
    ), array('class' => 'btn btn-danger'));
    echo $this->Form->button('Appliquer les filtres', array(
        'type' => 'submit',
        'class' => 'btn btn-success'
    ));
    ?>
            </div>
        </div>
    </div>
<?php
}
?>
    <table class="table table-hover">
        <thead>
        <th>Utilisateur</th>
        <th>Créé le</th>
        <th>Organisations</th>
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
            <ul>
                <?php
                if ( $donnees[ 'User' ][ 'id' ] != 1 ) {
                    foreach ( $donnees[ 'Organisations' ] as $key => $value ) {
                        echo '<li>' . $value[ 'Organisation' ][ 'raisonsociale' ] . '</li>';
                    }
                }
                else {
                    echo '<li> Toutes </li>';
                }
                ?>
            </ul>
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