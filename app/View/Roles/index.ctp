<?php
echo $this->Html->script('roles.js');
?>

<?php
if (!empty($roles)) {
    ?>
    <table class="table ">
        <!-- Titre tableau -->
        <thead>
            <!-- Profil -->
            <th class="thleft col-md-2">
                <?php echo __d('role', 'role.titreTableauProfil'); ?>
            </th>

            <!-- Droits -->
            <th class="thleft col-md-8">
                <?php echo __d('role', 'role.titreTableauDroit'); ?>
            </th>

            <!-- Actions -->
            <th class='thleft col-md-2'>
                <?php echo __d('role', 'role.titreTableauAction'); ?>
            </th>
        </thead>

        <tbody>
            <?php
            foreach ($roles as $donnees) {
                ?>
                <tr>
                    <!-- Libelle profil -->
                    <td class="tdleft col-md-2">
                        <?php echo $donnees['Role']['libelle']; ?>
                    </td>

                    <!-- Droits accordé au profil -->
                    <td class="tdleft col-md-8">
                        <ul>
                            <?php
                            foreach (Hash::extract($donnees, 'ListeDroit.{n}.libelle') as $key => $droit) {
                                echo '<li>' . h($droit) . '</li>';
                            }
                            ?>
                        </ul>
                    </td>

                    <!-- Bouton -->
                    <td class="tdleft">
                        <div class="btn-group">
                            <?php
                            if ($this->Autorisation->authorized(14, $droits)) {
                                //Bouton de modification (stylo)
                                echo $this->Html->link('<span class="fa fa-pencil fa-lg"></span>', array(
                                    'controller' => 'roles',
                                    'action' => 'edit',
                                    $donnees['Role']['id']
                                        ), array(
                                    'class' => 'btn btn-default-default btn-sm my-tooltip',
                                    'title' => __d('role', 'role.commentaitreModifierProfil'),
                                    'escapeTitle' => false
                                ));
                            }

                            if ($this->Autorisation->authorized(15, $droits)) {
                                if ($donnees['Role']['linked_user'] == false) {
                                    //Bouton de suppression
                                    echo $this->Html->link('<span class="fa fa-trash fa-lg"></span>', array(
                                        'controller' => 'roles',
                                        'action' => 'delete',
                                        $donnees['Role']['id']
                                            ), array(
                                        'class' => 'btn btn-default-danger btn-sm my-tooltip',
                                        'title' => __d('role', 'role.commentaireSupprimerProfil'),
                                        'escape' => false
                                            ), __d('role', 'role.confirmationSupprimerProfil') . $donnees['Role']['libelle'] . ' ?'
                                    );
                                }

                                //Bouton permettant de recharger les droits sur tous les utilisateurs de l'entité concernée
                                echo $this->Html->link('<span class="fa fa-refresh fa-lg"></span>', array(
                                    'controller' => 'roles',
                                    'action' => 'reattributionRoles',
                                    $donnees['Role']['id']
                                        ), array(
                                    'class' => 'btn btn-default-default btn-sm my-tooltip',
                                    'title' => __d('role','role.commentaireActualiserDroitsUtilisateurs'),
                                    'escapeTitle' => false
                                ));
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
    echo "<div class='text-center'><h3>Il n'existe aucun profil <small>pour cette entité</small></h3></div>";
}

//Si les droits de l'utilisateur le permet, affichage du bouton "+ Ajouter un profil"
if ($this->Autorisation->authorized(13, $droits)) {
    echo '<div class="text-center">';
    echo $this->Html->link('<span class="fa fa-plus-circle fa-lg"></span>' . __d('role', 'role.btnAjouterProfil'), array(
        'controller' => 'roles',
        'action' => 'add'
            ), array(
        'class' => 'btn btn-default-primary sender',
        'escapeTitle' => false
    ));
    echo '</div>';
}