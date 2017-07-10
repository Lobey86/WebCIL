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
    
    echo $this->Form->create('users', [
        'action' => 'admin_index'
    ]);
    ?>

    <div class="row">
        <div class="form-group">
            <?php
            // Filtrer par organisation
            echo $this->Form->input('organisation', [
                'empty' => 'Choisissez une entité',
                'class' => 'usersDeroulant transformSelect form-control',
                'label' => 'Filtrer par organisation',
                'options' => $orgas,
                'before' => '<div class="col-md-4 col-md-offset-1">',
                'after' => '</div>'
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
            ?>
        </div>
    </div>

    <!--Groupe de bouton--> 
    <div class="row top30">
        <div class="col-md-4 col-md-offset-5 btn-group">
            <?php
            // Bouton Réinitialiser le filtre
            echo $this->Html->link('<i class="fa fa-undo fa-lg"></i>' . __d('user','user.btnReinitialiserFiltre'), [
                'controller' => 'users',
                'action' => 'admin_index'
                    ], [
                'class' => 'btn btn-default-danger',
                'escape' => false,
            ]);

            // Bouton Appliquer les filtres
            echo $this->Form->button('<i class="fa fa-filter fa-lg"></i>' . __d('user','user.btnFiltrer'), [
                'type' => 'submit',
                'class' => 'btn btn-default-success'
            ]);
            
            echo $this->Form->end();
            ?>
        </div>
    </div>
    </div>
    <?php
}

$paginationBlock = $this->element('pagination');
echo $paginationBlock;
?>

<!-- Tableau -->
<table class="table">
    <!-- Titre tableau -->
    <thead>
        <!-- Utilisateur -->
        <th class="col-md-2">
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
    </thead>

    <!-- Info tableau -->
    <tbody>
        <?php
        foreach ($users as $donnees) {
            ?>
            <tr>
                <!-- Nom + prénom utilisateur -->
                <td class="tdleft">
                    <?php 
                        echo $donnees['User']['civilite'] . ' ' . $donnees['User']['prenom'] . ' ' . $donnees['User']['nom']; 
                    ?>
                </td>

                <!-- Entitée(s) de l'utilisateur -->
                <td class="tdleft">
                    <strong>
                        <?php 
                            // Entités
                            if (empty($donnees['Organisation'])) {
                                echo __d('user', 'user.textTableauAucuneEntite');
                            }
                        ?>
                    </strong>
                    <ul>
                        <?php
                            // Nom de la ou des entitée(s) de l'utilisateur
                            foreach ($donnees['Organisation'] as $key => $value) {
                                echo '<li>' . $value['raisonsociale'] . '</li>';
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
                        $libelleRole = Hash::get($donnees, 'OrganisationUserRole.Role.libelle');

                        if (!empty($libelleRole)){
                            echo $libelleRole;
                        } else {
                            echo "Super-administrateur";
                        }
                    ?>
                </td>

                <!-- Action possible d'effectuer en fonction des droits de l'utilisateur -->
                <td class="tdleft">
                    <div class="btn-group">
                            <?php
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
    echo $paginationBlock;

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