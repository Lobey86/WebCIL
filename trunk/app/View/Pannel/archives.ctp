<?php
echo $this->Html->script('pannel.js');

if ($this->Autorisation->authorized(1, $droits)) {
?>
    <!-- BANNETTE ARCHIVES -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title">
                        <?php
                        echo __d('pannel', 'pannel.traitementValidationInsereeRegistre') . count($validees) . ')';
                        ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="panel-body panel-body-custom">
            <?php
            if (!empty($validees)) {
                ?>
                <table class="table">
                    <!-- Titre tableau -->
                    <thead>
                        <!-- Etat -->
                        <th class="col-md-1">
                            <?php echo __d('pannel', 'pannel.motEtat'); ?>
                        </th>

                        <!-- Nom du traitement -->
                        <th class="col-md-3">
                            <?php echo __d('pannel', 'pannel.motNomTraitement'); ?>
                        </th>

                        <!-- Créé par -->
                        <th class="col-md-3">
                            <?php echo __d('pannel', 'pannel.motCreee'); ?>
                        </th>

                        <!-- Dernière modification le -->
                        <th class="col-md-3">
                            <?php echo __d('pannel', 'pannel.motDerniereModification'); ?>
                        </th>

                        <!-- Actions  -->
                        <th class="col-md-2">
                            <?php echo __d('pannel', 'pannel.motActions'); ?>
                        </th>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($validees as $donnee) {
                            ?>
                            <tr>
                                <!-- Etat du traitement -->
                                <td class='tdleft col-md-1'>
                                    <?php
                                    if ($donnee['EtatFiche']['etat_id'] == 5){
                                        $action = 'genereTraitement';
                                        $icone = '<span class="fa fa-cog fa-lg"></span>';
                                        $titre = __d('pannel', 'pannel.commentaireGenererTraitement');
                                        ?>
                                        <div class="etatIcone">
                                            <i class="fa fa-check fa-3x fa-success"></i>
                                            </br>
                                            <span class="fa-success">
                                                <?php echo ("Validé et inséré au registre"); ?>
                                            </span>
                                        </div>
                                    <?php
                                    } else if ($donnee['EtatFiche']['etat_id'] == 7) {
                                        $action = 'downloadFileTraitement';
                                        $icone = '<span class="fa fa-download fa-lg"></span>';
                                        $titre = __d('pannel', 'pannel.commentaireTelechargerTraitement');
                                        ?>
                                        <div class="etatIcone">
                                            <i class="fa fa-lock fa-3x fa-success"></i>
                                            </br>
                                            <span class="fa-success">
                                                <?php echo ("Archivé au registre"); ?>
                                            </span>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </td>
                                
                                <!-- Nom du traitement -->
                                <td class='tdleft'>
                                    <?php
                                        echo $donnee['Fiche']['Valeur'][0]['valeur'];
                                    ?>
                                </td>
                                
                                <!-- Créé par -->
                                <td class='tdleft'>
                                    <?php 
                                        echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], FORMAT_DATE_HEURE);
                                    ?>
                                </td>
                                
                                <!-- Dernière modification le -->
                                <td class='tdleft'>
                                    <?php 
                                        echo $this->Time->format($donnee['Fiche']['modified'], FORMAT_DATE_HEURE);
                                    ?>
                                </td>
                                
                                <!-- Actions -->
                                <td class='tdleft'>
                                    <div id='<?php echo $donnee['Fiche']['id']; ?>' class="btn-group">
                                        <?php
                                        // Visualiser de traitement
                                        echo $this->Html->link('<span class="fa fa-eye fa-lg"></span>', [
                                            'controller' => 'fiches',
                                            'action' => 'show',
                                            $donnee['Fiche']['id']
                                                ], [
                                            'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                            'title' => __d('pannel', 'pannel.commentaireVoirTraitement'),
                                            'escapeTitle' => false
                                        ]);

                                        // Générer ou télécharger (en fonction de l'état) le traitement
                                        echo $this->Html->link($icone, [
                                            'controller' => 'fiches',
                                            'action' => $action,
                                            $donnee['Fiche']['id']
                                                ], [
                                            'class' => 'btn btn-default-default boutonEdit btn-sm my-tooltip',
                                            'title' => $titre,
                                            'escapeTitle' => false
                                        ]);
                                        ?>
                                        
                                        <!-- Historique -->
                                        <button type='button'
                                                class='btn btn-default-default boutonList btn-sm my-tooltip'
                                                title='<?php echo __d('pannel', 'pannel.commentaireVoirParcours'); ?>'
                                                id='<?php echo $donnee['Fiche']['id']; ?>'
                                                value='<?php echo $donnee['Fiche']['id']; ?>'>
                                            <span class='fa fa-history fa-lg'></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Liste de l'historique du traitement -->
                            <tr class='listeValidation' id='listeValidation<?php echo $donnee['Fiche']['id']; ?>'>
                                <td></td>
                                
                                <td class='tdleft' colspan='3'>
                                    <?php
                                    $parcours = $this->requestAction([
                                        'controller' => 'Pannel',
                                        'action' => 'parcours',
                                        $donnee['Fiche']['id']
                                    ]);

                                    echo $this->element('parcours', [
                                        'parcours' => $parcours
                                    ]);
                                    ?>
                                </td>

                                <td class="tdleft">
                                    <?php
                                    $historique = $this->requestAction([
                                        'controller' => 'Pannel',
                                        'action' => 'getHistorique',
                                        $donnee['Fiche']['id']
                                    ]);

                                    echo $this->element('historique', [
                                        'historique' => $historique,
                                        'id' => $donnee['Fiche']['id']
                                    ]);
                                    ?>
                                </td>
                                <td></td>
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
                    <h3><?php echo __d('pannel', 'pannel.aucunTraitementValidationInsereeRegistre'); ?></h3>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}