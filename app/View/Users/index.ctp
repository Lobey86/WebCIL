<?php
echo $this->Html->script('users.js');

if ($this->Autorisation->isSu()) {
    echo $this->Form->button('<span class="glyphicon glyphicon-filter"></span> Filtrer les utilisateurs', $options = [
        'type' => 'button',
        'class' => 'btn btn-default-default pull-right',
        'id' => 'filtrageUsers'
    ]);

    if (!empty($this->request->data)) {
        echo '<div id="filtreUsers">';
    } else {
        echo '<div id="filtreUsers" style="display: none;">';
    }
    ?>

    <div class="row">
        <?php
        echo $this->Form->create('users', [
            'action' => 'index'
        ]);

        echo $this->Form->input('organisation', [
            'empty' => 'Choisissez une entité',
            'class' => 'usersDeroulant transformSelect form-control',
            'label' => 'Filtrer par organisation',
            'options' => $orgas,
            'before' => '<div class="col-md-4 col-md-offset-1">',
            'after' => '</div>'
        ]);

        echo $this->Form->input('nom', [
            'empty' => 'Chercher par utilisateur',
            'class' => 'usersDeroulant transformSelect form-control',
            'label' => 'Filtrer par nom',
            'options' => $utilisateurs,
            'before' => '<div class="col-md-4 col-md-offset-2">',
            'after' => '</div>'
        ]);
        ?>
    </div>

    <div class="row top30">
        <div class="col-md-4 col-md-offset-5 btn-group">
            <?php
            echo $this->Html->link('Réinitialiser', [
                'controller' => 'users',
                'action' => 'index'
                    ], ['class' => 'btn btn-default-danger']
            );

            echo $this->Form->button('Appliquer les filtres', [
                'type' => 'submit',
                'class' => 'btn btn-default-success'
            ]);
            ?>
        </div>
    </div>
    </div>
    <?php
}
?>
<table class="table">
    <thead>
    <th class="col-md-2">
        <?php echo __d('user', 'user.titreTableauUtilisateur'); ?>
    </th>
    <th class="col-md-8">
        <?php echo __d('user', 'user.titreTableauSynthese'); ?>
    </th>
    <th class="col-md-1">
        <?php echo __d('user', 'user.titreTableauAction'); ?>
    </th>
</thead>

<tbody>
    <?php
//    debug($users);die;
    foreach ($users as $donnees) {
        ?>
        <tr>
            <td class="tdleft">
                <?php echo $donnees['User']['prenom'] . ' ' . $donnees['User']['nom']; ?>
            </td>

            <td class="tdleft">
                <div class="col-md-3">
                    <strong>
                        <?php echo __d('user', 'user.textTableauEntite'); ?>
                    </strong>
                    <ul>
                        <?php
                        if ($donnees['User']['id'] != 1) {
                            foreach ($donnees['Organisations'] as $key => $value) {
                                echo '<li>' . $value['Organisation']['raisonsociale'] . '</li>';
                            }
                        } else {
                            echo '<li> Toutes </li>';
                        }
                        ?>
                    </ul>
                </div>

                <div class="col-md-3">
                    <strong>
                        <?php echo __d('user', 'user.textTableauLogin'); ?>
                    </strong>
                    <?php echo $donnees['User']['username']; ?>
                </div>

                <div class="col-md-3">
                    <strong>
                        <?php echo __d('user', 'user.champService'); ?>
                    </strong>
                    <ul>
                        <?php
                        if (!empty($donnees['OrganisationUserService'])){
                            foreach ($donnees['OrganisationUserService'] as $value) {
                                $libelleService = Hash::get($value, 'Service.libelle');
                                echo '<li>' . $libelleService . '</li>';
                            }
                        } else {
                            echo '<li> Aucun service</li>';
                        }
                        ?>
                    </ul>
                </div>

                <div class="col-md-3">
                    <strong>
                        <?php echo __d('user', 'user.champProfil'); ?>
                    </strong>
                    <?php
                    $libelleRole = Hash::get($donnees, 'OrganisationUserRole.0.Role.libelle');
                    echo $libelleRole;
                    ?>
                </div>
            </td>

            <td class="tdleft">
                <div class="btn-group">
                    <?php
                    if ($this->Autorisation->authorized(9, $droits)) {
                        echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', [
                            'controller' => 'users',
                            'action' => 'edit',
                            $donnees['User']['id']
                                ], [
                            'class' => 'btn btn-default-default btn-sm my-tooltip',
                            'title' => __d('user', 'user.commentaireModifierUser'),
                            'escapeTitle' => false
                        ]);
                    }

                    if ($this->Autorisation->authorized(10, $droits)) {
                        if ($donnees['User']['id'] != 1) {
                            echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', [
                                'controller' => 'users',
                                'action' => 'delete',
                                $donnees['User']['id']
                                    ], [
                                'class' => 'btn btn-default-danger btn-sm my-tooltip',
                                'title' => __d('user', 'user.commentaireSupprimerUser'),
                                'escapeTitle' => false
                                    ], __d('user', 'user.confirmationSupprimerUser') . $donnees['User']['prenom'] . ' ' . $donnees['User']['nom'] . ' ?');
                        } else {
                            echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', [
                                'controller' => 'users',
                                'action' => 'delete',
                                $donnees['User']['id']
                                    ], [
                                'class' => 'btn btn-default-danger btn-sm my-tooltip',
                                'escapeTitle' => false,
                                'title' => __d('user', 'user.commentaireSupprimerUser'),
                                "disabled" => "disabled"
                                    ], __d('user', 'user.confirmationSupprimerUser') . $donnees['User']['prenom'] . ' ' . $donnees['User']['nom'] . ' ?');
                        }
                    }
                    ?>
                </div>
            </td>
        </tr>
        <?php
    }
    ?>
</tbody>
</table>

<?php
if ($this->Autorisation->authorized(8, $droits)) {
    ?>
    <div class="row text-center">
        <?php
        echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span>' . __d('user', 'user.btnAjouterUser'), [
            'controller' => 'users',
            'action' => 'add'
                ], [
            'class' => 'btn btn-default-primary sender',
            'escapeTitle' => false
        ]);
        ?>
    </div>
    <?php
}
?>