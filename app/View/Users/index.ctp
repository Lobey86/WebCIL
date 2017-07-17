<?php
echo $this->Html->script('users.js');

// Filtrer les utilisateur que pour le Superadmin
if ($this->Autorisation->isSu()) {
    // Bouton du filtre des utilisateurs
    echo $this->Form->button('<span class="fa fa-filter fa-lg"></span> Filtrer les utilisateurs', $options = [
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

        // Filtrer par nom complet
        echo $this->Form->input('nom', [
            'empty' => 'Chercher par utilisateur',
            'class' => 'usersDeroulant transformSelect form-control',
            'label' => 'Nom complet',
            'options' => $utilisateurs,
            'before' => '<div class="col-md-4 col-md-offset-2">',
            'after' => '</div>'
        ]);

        // Filtrer par profil
        echo $this->Form->input('profil', [
            'empty' => 'Chercher par profil',
            'class' => 'usersDeroulant transformSelect form-control',
            'label' => 'Profil',
            'options' => $roles,
            'before' => '<div class="col-md-4 col-md-offset-2">',
            'after' => '</div>'
        ]);
        ?>
    </div>

    <!-- Groupe de bouton -->
    <div class="row top30">
        <div class="col-md-4 col-md-offset-5 btn-group">
            <?php
            // Bouton Réinitialiser le filtre
            echo $this->Html->link('Réinitialiser', [
                'controller' => 'users',
                'action' => 'index'
                    ], ['class' => 'btn btn-default-danger']
            );

            // Bouton Appliquer les filtres
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
if (!empty($users)) {
    ?>

    <!-- Tableau -->
    <table class="table">
        <!-- Titre tableau -->
        <thead>
            <?php
                if ($servicesExiste != 0) {
                ?>
                <!-- Utilisateur -->
                <th class="col-md-2" colspan="2">
                    <?php echo __d('user', 'user.titreTableauUtilisateur'); ?>
                </th>

                <!-- Entité -->
                <th class="col-md-2">
                    <?php echo __d('user', 'user.titreTableauEntite'); ?>
                </th>

                <!-- Identifiant -->
                <th class="col-md-2">
                    <?php echo __d('user', 'user.titreTableauIdentifiant'); ?>
                </th>

                <!-- Profil -->
                <th class="col-md-2">
                    <?php echo __d('user', 'user.titreTableauProfil'); ?>
                </th>

                <!-- Service -->
                <th class="col-md-2">
                    <?php echo __d('user', 'user.titreTableauService'); ?>
                </th>

                <!-- Actions -->
                <th class="col-md-2">
                    <?php echo __d('user', 'user.titreTableauAction'); ?>
                </th>
                <?php
            } else {
                 ?>
                <!-- Utilisateur -->
                <th class="col-md-3" colspan="2">
                    <?php echo __d('user', 'user.titreTableauUtilisateur'); ?>
                </th>

                <!-- Entité -->
                <th class="col-md-2">
                    <?php echo __d('user', 'user.titreTableauEntite'); ?>
                </th>

                <!-- Identifiant -->
                <th class="col-md-2">
                    <?php echo __d('user', 'user.titreTableauIdentifiant'); ?>
                </th>

                <!-- Profil -->
                <th class="col-md-2">
                    <?php echo __d('user', 'user.titreTableauProfil'); ?>
                </th>

                <!-- Actions -->
                <th class="col-md-1">
                    <?php echo __d('user', 'user.titreTableauAction'); ?>
                </th>
                <?php
            }
            ?>
        </thead>

        <!-- Info tableau -->
        <tbody>
            <?php
            foreach ($users as $donnees) {
                ?>
                <tr>
                    <!-- Logo du CiL le cas échéant -->
                    <td class="tdleft">
                        <?php
                        // Si l'utilisateur est CIL on affiche le logo du CIL
                        if ($donnees['User']['id'] == $cil){
                            if (file_exists(IMAGES . DS . 'logos' . DS . 'logo_cil.jpg')) {
                                echo $this->Html->image('logos' . DS . 'logo_cil.jpg', [
                                    'class' => 'logo-well',
                                ]);
                            }
                        }
                        ?>
                    </td>

                    <!-- Nom + prénom utilisateur -->
                    <td class="tdleft">
                        <?php
                            echo $donnees['User']['civilite'] . ' ' .  $donnees['User']['prenom'] . ' ' . $donnees['User']['nom'];
                        ?>
                    </td>

                    <!-- Entitée(s) de l'utilisateur -->
                    <td class="tdleft">
                        <ul>
                            <?php
                            //Nom de la ou des entitée(s) de l'utilisateur
                            foreach ($donnees['Organisations'] as $key => $value) {
                                echo '<li>' . $value['Organisation']['raisonsociale'] . '</li>';
                            }
                            ?>
                        </ul>
                    </td>

                    <td class="tdleft">
                        <!-- Login de l'utilisateur -->
                        <?php
                            //Libelle du login de l'utilisateur
                            echo $donnees['User']['username'];
                        ?>
                    </td>

                    <td class="tdleft">
                        <!-- Profil de l'utilisateur -->
                        <?php
                            $libelleRole = Hash::get($donnees, 'OrganisationUserRole.0.Role.libelle');
                            echo $libelleRole;
                        ?>
                    </td>

                    <?php
                    if ($servicesExiste != 0) {
                        ?>
                        <td class="tdleft">
                            <!-- Service(s) de l'utilisateur -->
                            <?php
                            if (!empty($services)) {
                                ?>
                                <div class="col-md-3">
                                    <ul>
                                        <?php
                                        if (!empty($donnees['OrganisationUserService'])) {
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
                                <?php
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>

                    <!-- Action possible d'effectuer en fonction des droits de l'utilisateur -->
                    <td class="tdleft">
                        <div class="btn-group">
                            <?php
                            if ($this->Autorisation->authorized(9, $droits)) {
                                //Bouton de modification
                                echo $this->Html->link('<span class="fa fa-pencil fa-lg"></span>', [
                                    'controller' => 'users',
                                    'action' => 'edit',
                                    $donnees['User']['id']
                                        ], [
                                    'class' => 'btn btn-default-default btn-sm my-tooltip',
                                    'title' => __d('user', 'user.commentaireModifierUser'),
                                    'escapeTitle' => false
                                ]);
                            }

                            if ($this->Session->read('Auth.User.id') != $donnees['User']['id']){
                                if ($this->Autorisation->authorized(10, $droits)) {
                                    if ($donnees['User']['id'] != 1) {
                                        //Bouton de suppression
                                        echo $this->Html->link('<span class="fa fa-trash fa-lg"></span>', [
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
} else {
    ?>
    <div class='text-center'>
        <h3>
            <?php echo __d('user', 'user.textAucunUserCollectiviter'); ?>
        </h3>
    </div>
    <?php
}

// Ajout d'un nouveau utilisateur en fonction des droits de l'utilisateur connecté pour la création
if ($this->Autorisation->authorized(8, $droits)) {
    ?>
    <div class="row text-center">
        <?php
        //Bouton " + Ajouter un utilisateur
        echo $this->Html->link('<span class="fa fa-plus-circle fa-lg"></span>' . __d('user', 'user.btnAjouterUser'), [
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