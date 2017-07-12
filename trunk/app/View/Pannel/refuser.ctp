<?php
echo $this->Html->script('pannel.js');

// balise du scrollTo
$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);

if ($this->Autorisation->authorized(1, $droits)) {
    ?>
    <!-- Banette des fiches refusées -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 id="FichesRefusees" class="panel-title"><?php
                        echo __d('pannel', 'pannel.traitementRefusees') . $nbTraitementRefuser . ')';
                        ?>
                    </h3>
                </div>
            </div>
        </div>
        
        <div class="panel-body panel-body-custom">
            <?php
            if (!empty($traitementRefuser)) {
                ?>
                <!-- Tableau des traitements en cours de rédaction -->
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
                        foreach ($traitementRefuser as $donnee) {
                            ?>
                            <tr>
                                <!-- Etat du traitement -->
                                <td class='tdleft col-md-1'>
                                    <div class="etatIcone">
                                        <i class="fa fa-times fa-3x fa-danger"></i>
                                        </br>
                                        <span class="fa-danger">
                                            <?php echo ("Refusé"); ?>
                                        </span>
                                    </div>
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
                                        // Visualiser le traitement
                                        echo $this->Html->link('<span class="fa fa-eye fa-lg"></span>', [
                                            'controller' => 'fiches',
                                            'action' => 'show',
                                            $donnee['Fiche']['id']
                                                ], [
                                            'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                            'escapeTitle' => false,
                                            'title' => __d('pannel', 'pannel.commentaireVoirTraitement')
                                        ]);
                                        
                                        // Replacer en rédaction
                                        echo $this->Html->link('<span class="fa fa-reply fa-lg"></span>', [
                                            'controller' => 'EtatFiches',
                                            'action' => 'relaunch',
                                            $donnee['Fiche']['id']
                                                ], [
                                            'class' => 'btn btn-default-default boutonRelancer btn-sm my-tooltip',
                                            'title' => __d('pannel', 'pannel.commentaireReplacerTraitementRedaction'),
                                            'escapeTitle' => false
                                        ]);
                                        ?>
                                        
                                        <!-- Historique -->
                                        <button type='button'
                                                class='btn btn-default-default boutonList btn-sm my-tooltip boutonListRefusee'
                                                title='<?php echo __d('pannel', 'pannel.commentaireVoirParcours'); ?>'
                                                id='<?php echo $donnee['Fiche']['id']; ?>'
                                                value='<?php echo $donnee['Fiche']['id']; ?>'>
                                            <span class='fa fa-history fa-lg'></span>
                                        </button>
                                        
                                        <?php
                                        // Supprimer le traitement
                                        echo $this->Html->link('<span class="fa fa-trash fa-lg"></span>', [
                                            'controller' => 'fiches',
                                            'action' => 'delete',
                                            $donnee['Fiche']['id']
                                                ], [
                                            'class' => 'btn btn-default-danger boutonDelete btn-sm my-tooltip',
                                            'escapeTitle' => false,
                                            'title' => __d('pannel', 'pannel.commentaireSupprimerTraitement')
                                                ], __d('pannel', 'pannel.confirmationSupprimerTraitement') . $donnee['Fiche']['Valeur'][0]['valeur'] . '?');
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Liste de l'historique du traitement -->
                            <tr class='listeRefusee' id='listeRefusee<?php echo $donnee['Fiche']['id']; ?>'>
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
                    <h3> 
                        <?php echo __d('pannel', 'pannel.aucunTraitementRefusees'); ?>
                    </h3>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}
?>  

<script type="text/javascript">

    $(document).ready(function () {

        openTarget("<?php echo $idFicheNotification ?>");

    });

</script>