<?php
echo $this->Html->script('pannel.js');
echo $this->Html->script('registre.js');
// balise du scrollTo
$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);

if ($this->Autorisation->authorized(1, $droits)) {
    ?>
    <!-- Banette des fiches en cours de rédaction -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title">
                        <?php
                        echo __d('pannel', 'pannel.traitementEnCours') . $nbTraitementEnCoursRedaction . ")";
                        
                        if ($nbTraitementEnCoursRedaction != 0) {
                        ?>
                            <span class="pull-right">
                                <?php
                                echo $this->Html->link('<span class="fa fa-eye fa-fw"></span>' . __d('pannel', 'pannel.btnVoirTraitementEnCoursRedaction'), [
                                        'controller' => 'pannel',
                                        'action' => 'encours_redaction',
                                            ], [
                                        'class' => 'btn btn-default-primary',
                                        'escapeTitle' => false,
                                ]);
                                ?>
                            </span>
                        <?php
                        }
                        ?>
                    </h3>
                </div>
            </div>
        </div>
        
        <div class="panel-body panel-body-custom">
            <?php
            if (!empty($traitementEnCoursRedaction)) {
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
                        foreach ($traitementEnCoursRedaction as $donnee) {
                            ?>
                            <tr>
                                <!-- Status du traitement -->
                                <td class='tdleft col-md-1'> <!-- style="border-right: 1px solid black;"-->
                                    <div class="etatIcone">
                                        <i class="fa fa-pencil-square-o fa-3x"></i>
                                            <?php echo ("Rédaction"); ?>
                                    </div>
                                </td>

                                <!-- Nom du traitement -->
                                <td class='tdleft'>
                                    <?php 
                                        echo $donnee['Fiche']['Valeur'][0]['valeur'];
                                    ?>
                                </td>

                                <!-- Créé par -->
                                <td class="tdleft">
                                    <?php 
                                        echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], FORMAT_DATE_HEURE);
                                    ?>
                                </td>

                                <!-- Dernière modification le -->
                                <td class="tdleft">
                                    <?php 
                                        echo $this->Time->format($donnee['Fiche']['modified'], FORMAT_DATE_HEURE);
                                    ?>
                                </td>

                                <!-- Actions -->
                                <td class='tdleft'>
                                    <div id='<?php echo $donnee['Fiche']['id']; ?>' class="btn-group">
                                        <?php
                                        echo $this->Html->link('<span class="fa fa-eye fa-lg"></span>', [
                                            'controller' => 'fiches',
                                            'action' => 'show',
                                            $donnee['Fiche']['id']
                                                ], [
                                            'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                            'escapeTitle' => false,
                                            'title' => __d('pannel', 'pannel.commentaireVoirTraitement'),
                                            'id' => 'btnShow'. $donnee['Fiche']['id']
                                        ]);

                                        echo $this->Html->link('<span class="fa fa-pencil fa-lg"></span>', [
                                            'controller' => 'fiches',
                                            'action' => 'edit',
                                            $donnee['Fiche']['id']
                                                ], [
                                            'class' => 'btn btn-default-default boutonEdit btn-sm my-tooltip',
                                            'escapeTitle' => false,
                                            'title' => __d('pannel', 'pannel.commentaireModifierTraitement'),
                                            'id' => 'btnEdit'. $donnee['Fiche']['id']
                                        ]);
                                        ?>
                                        <button type='button'
                                                class='btn btn-default-default boutonList btn-sm my-tooltip'
                                                title='<?php echo __d('pannel', 'pannel.commentaireVoirParcours'); ?>'
                                                id='<?php echo $donnee['Fiche']['id']; ?>'
                                                value='<?php echo $donnee['Fiche']['id']; ?>'>
                                            <span class='fa fa-history fa-lg'></span>
                                        </button>
                                        
                                        <button
                                            class='btn btn-default-default dropdown-toggle boutonSend btn-sm my-tooltip'
                                            type='button'
                                            id='dropdownMenu1' data-toggle='dropdown'
                                            title='<?php echo __d('pannel', 'pannel.commentaireEnvoyerTraitement'); ?>'>
                                            <span class='fa fa-paper-plane fa-lg'></span>
                                            <span class='caret'></span>
                                        </button>

                                        <ul class='dropdown-menu' role='menu'
                                            aria-labelledby='dropdownMenu1'>
                                            <?php
                                            if (!empty($consultants)){
                                                ?>
                                                <!-- Envoyer pour consultation -->
                                                <li role='presentation'>
                                                    <?php
                                                        echo $this->Html->link(__d('pannel', 'pannel.textEnvoyerConsultation'), ['#' => '#'], [
                                                            'role' => 'menuitem',
                                                            'tabindex' => -1,
                                                            'escape' => false,
                                                            'data-toggle' => 'modal',
                                                            'data-target' => '#modalEnvoieConsultation',
                                                            'data-id' => $donnee['Fiche']['id'],
                                                            'data-fiche' => $donnee['EtatFiche']['id'],
                                                            'class' => 'btn_envoyerConsultation'
                                                        ]);
                                                    ?>
                                                </li>
                                            <?php
                                            }

                                            if (!empty($validants)){
                                                ?>
                                                <!-- Envoyer pour validation -->
                                                <li role='presentation'>
                                                    <?php
                                                        echo $this->Html->link(__d('pannel', 'pannel.textEnvoyerValidation'), ['#' => '#'], [
                                                            'role' => 'menuitem',
                                                            'tabindex' => -1,
                                                            'escape' => false,
                                                            'data-toggle' => 'modal',
                                                            'data-target' => '#modalEnvoieValidation',
                                                            'data-id' => $donnee['Fiche']['id'],
                                                            'data-fiche' => $donnee['EtatFiche']['id'],
                                                            'class' => 'btn_envoyerValideur'
                                                        ]);
                                                    ?>
                                                </li>
                                                <?php
                                            }   
                                            ?>

                                            <li role='presentation'>
                                                <?php
                                                    echo $this->Html->link(__d('pannel', 'pannel.textEnvoyerCIL'), [
                                                        'controller' => 'etatFiches',
                                                        'action' => 'cilValid',
                                                        $donnee['Fiche']['id']
                                                            ], [
                                                        'role' => 'menuitem',
                                                        'tabindex' => '-1'
                                                    ]);
                                                ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php
                                    echo $this->Html->link('<span class="fa fa-trash fa-lg"></span>', [
                                        'controller' => 'fiches',
                                        'action' => 'delete',
                                        $donnee['Fiche']['id']
                                            ], [
                                        'class' => 'btn btn-default-danger boutonDelete btn-sm my-tooltip',
                                        'escapeTitle' => false,
                                        'title' => __d('pannel', 'pannel.commentaireSupprimerTraitement')
                                            ], __d('pannel', 'pannel.confirmationSupprimerTraitement') . $donnee['Fiche']['Valeur'][0]['valeur'] . ' " ?');
                                    ?>
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
                            
                            <tr class='completion'></tr>
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
                        <?php echo __d('pannel', 'pannel.aucunTraitementEnCours'); ?>
                    </h3>
                </div>
                <?php
            }
            ?>

            <div class="row bottom10">
                <div class="col-md-12 text-center">
                    <?php
                    echo $this->Html->link('<span class="fa fa-plus fa-lg"></span>' . __d('pannel', 'pannel.btnCreerTraitement'), ['#' => '#'], [
                        'escape' => false,
                        'data-toggle' => 'modal',
                        'data-target' => '#myModal',
                        'class' => 'btn btn-default-primary'
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Banette des fiches en attente -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title"> 
                        <?php
                        echo __d('pannel', 'pannel.traitementEnAttente') . $nbTraitementEnCoursValidation . ')';

                        if ($nbTraitementEnCoursValidation != 0) {
                        ?>
                            <span class="pull-right">
                                <?php
                                echo $this->Html->link('<span class="fa fa-eye fa-fw"></span>' . __d('pannel', 'pannel.btnVoirTraitementEnAttente'), [
                                    'controller' => 'pannel',
                                    'action' => 'attente',
                                        ], [
                                    'class' => 'btn btn-default-primary',
                                    'escapeTitle' => false,
                                ]);
                                ?>
                            </span>
                        <?php
                        }
                        ?>
                    </h3>
                </div>
            </div>
        </div>
        
        <div class="panel-body panel-body-custom">
            <?php
            if (!empty($traitementEnCoursValidation)) {
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
                        foreach ($traitementEnCoursValidation as $donnee) {
                            ?>
                            <tr>
                                <!-- Etat du traitement -->
                                <td class='tdleft col-md-1'> <!-- style="border-right: 1px solid black;"-->
                                    <div class="etatIcone">
                                        <i class="fa fa-clock-o fa-3x"></i>
                                        </br>
                                        <?php echo ("En attente"); ?>
                                    </div>
                                </td>
                                
                                <!-- Nom du traitement -->
                                <td class='tdleft'>
                                    <?php 
                                        echo $donnee['Fiche']['Valeur'][0]['valeur'];
                                    ?>
                                </td>
                                
                                <!-- Créé par -->
                                <td class="tdleft">
                                    <?php
                                        echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], FORMAT_DATE_HEURE);
                                    ?>
                                </td>
                                
                                <!-- Dernière modification le -->
                                <td class="tdleft">
                                    <?php 
                                        echo $this->Time->format($donnee['Fiche']['modified'], FORMAT_DATE_HEURE); 
                                    ?>
                                </td>
                                
                                <!-- Actions -->
                                <td class='tdleft'>
                                    <div id='<?php echo $donnee['Fiche']['id']; ?>' class="btn-group">
                                        <?php
                                        echo $this->Html->link('<span class="fa fa-eye fa-lg"></span>', [
                                            'controller' => 'fiches',
                                            'action' => 'show',
                                            $donnee['Fiche']['id']
                                                ], [
                                            'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                            'escapeTitle' => false,
                                            'title' => __d('pannel', 'pannel.commentaireVoirTraitement')
                                        ]);
                                        ?>
                                        <button type='button'
                                                class='btn btn-default-default boutonList btn-sm my-tooltip'
                                                title='<?php echo __d('pannel', 'pannel.commentaireVoirParcours'); ?>'
                                                id='<?php echo $donnee['Fiche']['id']; ?>'
                                                value='<?php echo $donnee['Fiche']['id']; ?>'>
                                            <span class='fa fa-history fa-lg'></span>
                                        </button>

                                        <?php
                                        echo $this->Html->link('<span class="fa fa-exchange fa-lg"></span>', ['#' => '#'], [
                                            'data-id' => $donnee['Fiche']['id'],
                                            'data-fiche' => $donnee['EtatFiche']['id'],
                                            'escape' => false,
                                            'data-toggle' => 'modal',
                                            'data-target' => '#modalReorienter',
                                            'class' => 'btn btn-default-default btn_ReorienterTraitement  btn-sm my-tooltip',
                                            'title' => __d('pannel', 'pannel.commentaireReorienterTraitement')
                                        ]);
                                        ?>
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
                    <h3> 
                        <?php echo __d('pannel', 'pannel.aucunTraitementEnAttente'); ?>
                    </h3>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    
    <!-- Banette des fiches refusées -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 id="FichesRefusees" class="panel-title"><?php
                        echo __d('pannel', 'pannel.traitementRefusees') . $nbTraitementRefuser . ')';
                    
                        if ($nbTraitementRefuser != 0) {
                        ?>
                            <span class="pull-right">
                                <?php
                                echo $this->Html->link('<span class="fa fa-eye fa-fw"></span>' . __d('pannel', 'pannel.btnVoirTraitementRefuser'), [
                                    'controller' => 'pannel',
                                    'action' => 'refuser',
                                        ], [
                                    'class' => 'btn btn-default-primary',
                                    'escapeTitle' => false,
                                ]);
                                ?>
                            </span>
                        <?php
                        }
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

if ($this->Autorisation->authorized(2, $droits)) {
?>
<!-- Banette reçues en validation -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12">
                <h3 class="panel-title">
                    <?php
                    echo __d('pannel', 'pannel.traitementValidation') . $nbTaitementRecuEnValidation . ')';

                    if ($nbTaitementRecuEnValidation != 0) {
                        ?>
                        <span class="pull-right">
                            <?php
                            echo $this->Html->link('<span class="fa fa-eye fa-fw"></span>' . __d('pannel', 'pannel.btnVoirTraitementRecuValidation'), [
                                'controller' => 'pannel',
                                'action' => 'recuValidation',
                                    ], [
                                'class' => 'btn btn-default-primary',
                                'escapeTitle' => false,
                            ]);
                            ?>
                        </span>
                    <?php
                    }
                    ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="panel-body panel-body-custom">
        <?php
        if (!empty($traitementRecuEnValidation)) {
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
                    foreach ($traitementRecuEnValidation as $donnee) {
                        ?>
                        <tr>
                            <!-- Etat du traitement -->
                            <td class='tdleft col-md-1'>
                                <div class="etatIcone">
                                    <i class="fa fa-check-square-o fa-3x"></i>
                                    </br>
                                    <?php echo ("En attente de validation"); ?>
                                </div>
                            </td>

                            <!-- Nom du traitement -->
                            <td class='tdleft'>
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong><?php echo __d('pannel', 'pannel.motNomTraitement'); ?>
                                        </strong> <?php echo $donnee['Fiche']['Valeur'][0]['valeur']; ?>
                                    </div>

                                </div>
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
                                        'title' => __d('pannel', 'pannel.commentaireVoirTraitement'),
                                    ]);
                                    
                                    // Modifier le traitement
                                    echo $this->Html->link('<span class="fa fa-pencil fa-lg"></span>', [
                                        'controller' => 'fiches',
                                        'action' => 'edit',
                                        $donnee['Fiche']['id']
                                            ], [
                                        'class' => 'btn btn-default-default boutonEdit btn-sm my-tooltip',
                                        'escapeTitle' => false,
                                        'title' => __d('pannel', 'pannel.commentaireModifierTraitement')
                                    ]);
                                    ?>
                                    <button type='button'
                                            class='btn btn-default-default boutonListAValider btn-sm my-tooltip'
                                            title= '<?php echo __d('pannel', 'pannel.commentaireVoirParcours'); ?>'
                                            id='<?php echo $donnee['Fiche']['id']; ?>'
                                            value='<?php echo $donnee['Fiche']['id']; ?>'>
                                        <span class='fa fa-history fa-lg'></span>
                                    </button>
                                    
                                    <button
                                        class='btn btn-default-success dropdown-toggle boutonValider btn-sm my-tooltip'
                                        type='button'
                                        title= '<?php echo __d('pannel', 'pannel.commentaireValiderTraitement'); ?>'
                                        id='dropdownMenuValider' data-toggle='dropdown'>
                                        <span class='fa fa-check fa-lg'></span>
                                        <span class='caret'></span>
                                    </button>
                                    
                                    <ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenuValider'>
                                        <?php
                                        if (!empty($consultants)){
                                            ?>
                                            <!-- Envoyer pour consultation -->
                                            <li role='presentation'>
                                                <?php
                                                    echo $this->Html->link(__d('pannel', 'pannel.textEnvoyerConsultation'), ['#' => '#'], [
                                                        'role' => 'menuitem',
                                                        'tabindex' => -1,
                                                        'escape' => false,
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#modalEnvoieConsultation',
                                                        'data-id' => $donnee['Fiche']['id'],
                                                        'data-fiche' => $donnee['EtatFiche']['id'],
                                                        'class' => 'btn_envoyerConsultation'
                                                    ]);
                                                ?>
                                            </li>
                                        <?php
                                        }

                                        if (!empty($validants)){
                                            ?>
                                            <!-- Envoyer pour validation -->
                                            <li role='presentation'>
                                                <?php
                                                    echo $this->Html->link(__d('pannel', 'pannel.textValiderEnvoyerValidation'), ['#' => '#'], [
                                                        'role' => 'menuitem',
                                                        'tabindex' => -1,
                                                        'escape' => false,
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#modalEnvoieValidation',
                                                        'data-id' => $donnee['Fiche']['id'],
                                                        'data-fiche' => $donnee['EtatFiche']['id'],
                                                        'class' => 'btn_envoyerValideur'
                                                    ]);
                                                ?>
                                            </li>
                                            <?php
                                        }    
                                        
                                        if (!$this->Autorisation->isCil()) {
                                            echo "<li role='presentation'>" . $this->Html->link(__d('pannel', 'pannel.textValiderEnvoyerCIL'), [
                                                'controller' => 'etatFiches',
                                                'action' => 'cilValid',
                                                $donnee['Fiche']['id']
                                                    ], [
                                                'role' => 'menuitem',
                                                'tabindex' => '-1'
                                            ]) . "</li>";
                                        } else {
                                            echo "
                                                <li role='presentation'>" . $this->Html->link(__d('pannel', 'pannel.textValiderInsererRegistre'), ['#' => '#'], [
                                                'role' => 'menuitem',
                                                'tabindex' => '-1',
                                                'data-toggle' => 'modal',
                                                'data-target' => '#modalValidCil',
                                                'data-type' => $donnee['Fiche']['typedeclaration'],
                                                'data-id' => $donnee['Fiche']['id'],
                                                'class' => 'btn-insert-registre'
                                            ]) . "</li> ";
                                        }
                                        ?>
                                    </ul>
                                </div>
                                
                                <!-- Refuser le traitement -->
                                <button type='button'
                                        class='btn btn-default-danger boutonRefuser btn-sm my-tooltip'
                                        title='<?php echo __d('pannel','pannel.commentaireRefuserTraitement'); ?>'
                                        value='<?php echo $donnee['Fiche']['id']; ?>'>
                                    <span class='fa fa-times fa-lg'></span>
                                </button>

                            </td>
                        </tr>
                        
                        <!-- Liste de l'historique du traitement -->
                        <tr class='listeAValider' id='listeAValider<?php echo $donnee['Fiche']['id']; ?>'>
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
                        
                        <tr class='commentaireRefus<?php echo $donnee['Fiche']['id']; ?>'>
                            <td></td>
                            <td colspan='2' class='tdleft'>
                                <?php
                                echo $this->Form->create('EtatFiche', $options = ['action' => 'refuse']);
                                echo $this->Form->input('content', [
                                    'div' => 'input-group inputsForm',
                                    'label' => false,
                                    'before' => '<span class="labelFormulaire">'.__d('pannel','pannel.textExpliquezRaisonRefus').'</span><span class="obligatoire"> *</span>',
                                    'class' => 'form-control',
                                    'required' => true,
                                    'type' => 'textarea'
                                ]);
                                echo $this->Form->hidden('ficheNum', ['value' => $donnee['Fiche']['id']]);
                                echo $this->Form->hidden('etatFiche', ['value' => $donnee['EtatFiche']['id']]);
                                echo '<div class="btn-group">';
                                echo $this->Form->button('<i class="fa fa-times-circle fa-lg"></i>' . __d('default', 'default.btnAnnuler'), array(
                                    'type' => 'button',
                                    'class' => 'btn btn-default-default refusCancel top5'
                                ));
                                echo $this->Form->button('<i class="fa fa-check"></i>' . __d('default', 'default.btnEnvoyer'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-default-success pull-right top5'
                                ]);
                                echo '</div>';
                                echo $this->Form->end();
                                ?>
                            </td>
                        </tr>
                        
                        <tr class='completion'></tr>
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
                    <?php echo __d('pannel', 'pannel.aucunTraitementValidation'); ?>
                </h3>
            </div>
            <?php
        }
        ?>
    </div>
</div>    
<?php
}

if ($this->Autorisation->authorized(3, $droits)) {
?>
    <!-- Fiches reçues en consultation -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title">
                        <?php
                        echo __d('pannel', 'pannel.traitementConsultation') . $nbTraitementRecuEnConsultation . ')';

                        if ($nbTraitementRecuEnConsultation != 0) {
                        ?>
                            <span class="pull-right">
                                    <?php
                                    echo $this->Html->link('<span class="fa fa-eye fa-fw"></span>' . __d('pannel', 'pannel.btnVoirTraitementRecuConsultation'), [
                                            'controller' => 'pannel',
                                            'action' => 'recuConsultation',
                                                ], [
                                            'class' => 'btn btn-default-primary',
                                            'escapeTitle' => false,
                                    ]);
                                    ?>
                            </span>
                        <?php
                        }
                        ?>
                    </h3>
                </div>
            </div>
        </div>
        
        <div class="panel-body panel-body-custom">
            <?php
            if (!empty($traitementRecuEnConsultation)) {
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
                        foreach ($traitementRecuEnConsultation as $donnee) {
                            ?>
                            <tr>
                                <!-- Etat du traitement -->
                                <td class='tdleft col-md-1'>
                                    <div class="etatIcone">
                                        <i class="fa fa-eye fa-3x"></i>
                                        </br>
                                        <?php echo ("En attente de consultation"); ?>
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
                                            'title' => __d('pannel', 'pannel.commentaireVoirTraitement'),
                                            'escapeTitle' => false
                                        ]);
                                        ?>

                                        <!-- Répondre au traitement -->
                                        <button type='button'
                                                class='btn btn-default-default boutonRepondre boutonsAction5 btn-sm my-tooltip'
                                                title= '<?php echo __d('pannel', 'pannel.commentaireRepondre'); ?>'
                                                id="<?php echo $donnee['Fiche']['id']; ?>"
                                                value='<?php echo $donnee['Fiche']['id']; ?>'>
                                            <span class='fa fa-reply fa-lg'></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class='commentaireRepondre<?php echo $donnee['Fiche']['id']; ?>'>
                                <td></td>
                                
                                <td class='tdleft' colspan='3'>
                                    <?php
                                    echo $this->Form->create('EtatFiche', $options = ['action' => 'answerAvis']);
                                    echo $this->Form->input('commentaireRepondre', [
                                        'div' => 'input-group inputsForm',
                                        'label' => false,
                                        'before' => '<span class="labelFormulaire">'. __d('pannel','pannel.textDonnerAvis').'</span><span class="obligatoire">*</span>',
                                        'required' => true,
                                        'class' => 'form-control',
                                        'type' => 'textarea'
                                    ]);
                                    echo $this->Form->hidden('etatFiche', ['value' => $donnee['EtatFiche']['id']]);
                                    echo $this->Form->hidden('previousUserId', ['value' => $donnee['EtatFiche']['previous_user_id']]);
                                    echo $this->Form->hidden('ficheNum', ['value' => $donnee['Fiche']['id']]);
                                    echo '<div class="btn-group">';
                                    echo $this->Form->button('<i class="fa fa-times-circle fa-lg"></i>' . __d('default', 'default.btnAnnuler'), array(
                                        'type' => 'button',
                                        'class' => 'btn btn-default-default repondreCancel',
                                    ));
                                    echo $this->Form->button('<i class="fa fa-check"></i>' . __d('default', 'default.btnEnvoyer'), [
                                        'type' => 'submit',
                                        'class' => 'btn btn-default-success pull-right'
                                    ]);
                                    echo '</div>';
                                    echo $this->Form->end();
                                    ?>
                                </td>
                                
                                <td></td>
                            </tr>
                            
                            <tr class='completion'></tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php
            } else {
                ?>
                <div class='text-center'>
                    <h3><?php echo __d('pannel', 'pannel.aucunTraitementConsultation'); ?></h3>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
<?php
}

if ($this->Autorisation->authorized(1, $droits)) {
?>
    <!-- BANNETTE ARCHIVES -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title">
                        <?php
                        echo __d('pannel', 'pannel.traitementValidationInsereeRegistre') . $nbTraitementArchives . ')';

                        if ($nbTraitementArchives != 0) {
                        ?>
                            <span class="pull-right">
                               <?php
                                echo $this->Html->link('<span class="fa fa-eye fa-fw"></span>' . __d('pannel', 'pannel.btnVoirTraitementArchiver'), [
                                    'controller' => 'pannel',
                                    'action' => 'archives',
                                        ], [
                                    'class' => 'btn btn-default-primary',
                                    'escapeTitle' => false,
                                ]);
                                ?>
                            </span>
                        <?php
                        }
                        ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="panel-body panel-body-custom">
            <?php
            if (!empty($traitementArchives)) {
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
                        foreach ($traitementArchives as $donnee) {
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

if ($this->Autorisation->authorized([2,3], $droits)) {
?>
    <!-- Bannette traitement passés en ma possession -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title">
                        <?php
                        echo __d('pannel', 'pannel.titreTableauEtatTraitementPasserPossession') . ' (' . count($traitementConnaissance) . ')';

                        if (count($traitementConnaissance) != 0) {
                        ?>
                            <span class="pull-right">
                                   <?php
                                    echo $this->Html->link('<span class="fa fa-eye fa-fw"></span>' . __d('pannel', 'pannel.btnVoirTraitementPasserPossession'), [
                                        'controller' => 'pannel',
                                        'action' => 'consulte',
                                            ], [
                                        'class' => 'btn btn-default-primary',
                                        'escapeTitle' => false,
                                    ]);
                                    ?>
                            </span>
                        <?php
                        }
                        ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="panel-body panel-body-custom">
            <?php
            if (!empty($traitementConnaissance)) {
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
                        foreach ($traitementConnaissance as $donnee) {
                            ?>
                            <tr>
                                <!-- Etat du traitement -->
                                <td class='tdleft col-md-1'>
                                    <div class="etatIcone">
                                        <?php
                                        //Adapter l'icone affiché en fonction de sont état
                                        if ($donnee['EtatFiche']['etat_id'] == 1 || $donnee['EtatFiche']['etat_id'] == 2 || $donnee['EtatFiche']['etat_id'] == 6 || $donnee['EtatFiche']['etat_id'] == 8) {
                                            ?>
                                            <i class="fa fa-clock-o fa-3x"></i>
                                            </br>
                                            <?php 
                                                echo ("En attente");
                                        } elseif ($donnee['EtatFiche']['etat_id'] == 3 || $donnee['EtatFiche']['etat_id'] == 5 || $donnee['EtatFiche']['etat_id'] == 7 || $donnee['EtatFiche']['etat_id'] == 9) {
                                            ?>
                                            <i class="fa fa-check fa-3x fa-success"></i>
                                            </br>
                                            <span class="fa-success">
                                                <?php echo ("Validé"); ?>
                                            </span>
                                            <?php
                                        } elseif ($donnee['EtatFiche']['etat_id'] == 4) {
                                            ?>
                                            <i class="fa fa-times fa-3x fa-danger"></i>
                                            </br>
                                            <span class="fa-danger">
                                                <?php echo ("Refusé"); ?>
                                            </span>
                                            <?php
                                        }
                                        ?>
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
                                        ?>

                                        <!-- Historique -->
                                        <button type='button'
                                                class='btn btn-default-default boutonList btn-sm my-tooltip'
                                                title='<?php echo __d('pannel', 'pannel.commentaireVoirParcours'); ?>'
                                                id='<?php echo $donnee['Fiche']['id']; ?>'
                                                value='<?php echo $donnee['Fiche']['id']; ?>'>
                                            <span class='fa fa-history fa-lg'></span>
                                        </button>

                                        <?php
                                        if ($donnee['EtatFiche']['etat_id'] == 2 && $donnee['EtatFiche']['user_id_actuel'] != $donnee['EtatFiche']['user_id']) {
                                            ?>
                                            <button type='button'
                                                    class='btn btn-default-default boutonReorienter btn-sm my-tooltip'
                                                    title='<?php echo __d('pannel', 'pannel.commentaireReorienterTraitement'); ?>'
                                                    value='<?php echo $donnee['Fiche']['id']; ?>'>
                                                <span class='fa fa-exchange fa-lg'></span>
                                            </button>
                                            <?php
                                        } elseif ($this->Autorisation->authorized(['5'], $this->Session->read('Droit.liste'))) {
                                            if ($donnee['EtatFiche']['etat_id'] == 4) {
                                                echo $this->Html->link('<span class="fa fa-repeat"></span>', [
                                                    'controller' => 'EtatFiches',
                                                    'action' => 'relaunch',
                                                    $donnee['Fiche']['id']
                                                        ], [
                                                    'escapeTitle' => false,
                                                    'class' => 'btn btn-default-default boutonRelancer btn-sm my-tooltip',
                                                    'title' => __d('pannel', 'pannel.commentaireReplacerTraitementRedactionDansEspace'),
                                                        ], __d('pannel', 'pannel.confirmationReapproprierTraitement') . $donnee['Fiche']['Valeur'][0]['valeur'] . '" ?'
                                                );
                                            }
                                        }
                                        ?>
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
                            
                            <tr class='selectDestTrans<?php echo $donnee['Fiche']['id']; ?>'>
                                <td></td>
                                <td></td>
                                <td colspan='2' class='tdleft'>
                                    <?php
                                    echo $this->Form->create('EtatFiche', $options = ['action' => 'reorientation']);
                                    echo $this->Form->input('destinataire', [
                                        'options' => $validants,
                                        'class' => 'usersDeroulant transformSelect form-control bottom5',
                                        'empty' => __d('pannel', 'pannel.textSelectUser'),
                                        'required' => true,
                                        'label' => false
                                    ]);
                                    echo $this->Form->hidden('ficheNum', ['value' => $donnee['Fiche']['id']]);
                                    echo $this->Form->hidden('etatFiche', ['value' => $donnee['EtatFiche']['id']]);
                                    echo '<div class="btn-group">';
                                    echo $this->Form->button('<i class="fa fa-times-circle fa-lg"></i>' . __d('default', 'default.btnAnnuler'), array(
                                        'type' => 'button',
                                        'class' => 'btn btn-default-default sendCancel',
                                    ));
                                    echo $this->Form->button('<i class="fa fa-check"></i>' . __d('default', 'default.btnEnvoyer'), [
                                        'type' => 'submit',
                                        'class' => 'btn btn-default-success pull-right'
                                    ]);
                                    echo '</div>';
                                    echo $this->Form->end();
                                    ?>
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
                    <h3><?php echo __d('pannel', 'pannel.aucunTraitementPasserPossession'); ?></h3>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}
?>

<div class="modal fade" id="modalValidCil" tabindex="-1" role="dialog" aria-labelledby="myModalLabelValidCil">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabelValidCil">Insertion au registre</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-warning">
                        <div class="col-md-12 text-center">
                            <i class="fa fa-fw fa-exclamation-triangle"></i>
                        </div>
                        <div class="col-md-12">
                            <?php echo __d('pannel','pannel.confirmationInsererRegistre');?>
                        </div>
                    </div>
                </div>
                <div class="row top17">
                    <div class="col-md-12">
                        <?php
                        echo $this->Form->create('Registre', [
                            'action' => 'add',
                            'class' => 'form-horizontal'
                        ]);

                        echo $this->Form->input('numero', [
                            'label' => [
                                'text' => 'Numéro d\'enregistrement',
                                'class' => 'col-md-4 control-label'
                            ],
                            'between' => '<div class="col-md-8">',
                            'after' => '</div>',
                            'class' => 'form-control',
                            'div' => 'form-group',
                            'id' => 'numero'
                        ]);
                        
                        echo $this->Form->input('typedeclaration', [
                            'label' => [
                                'text' => 'Type de déclaration',
                                'class' => 'col-md-4 control-label'
                            ],
                            'between' => '<div class="col-md-8">',
                            'after' => '</div>',
                            'class' => 'form-control',
                            'div' => 'form-group',
                            'id' => 'typedeclaration'
                        ]);

                        echo $this->Form->hidden('idfiche', ['id' => 'idFiche']);
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button type="button" class="btn btn-default-default" data-dismiss="modal">
                        <i class="fa fa-times-circle fa-lg"></i>
                        <?php echo __d('default', 'default.btnAnnuler'); ?>
                    </button>
                    <?php
                    echo $this->Form->button("<i class='fa fa-floppy-o fa-lg'></i>" . __d('default', 'default.btnEnregistrer'), array(
                        'type' => 'submit',
                        'class' => 'btn btn-default-success',
                        'escape' => false
                    ));
                    ?>
                </div>
                <?php
                echo $this->Form->end();
                ?>
            </div> 
        </div>
    </div>
</div>

<!-- Modal de notification -->
<?php
$arrayNotificationNotVuNotAfficher = [];

foreach ($notifications as $key => $value) {
    if ($value['Notification']['vu'] == false && $value['Notification']['afficher'] == false) {
        array_push($arrayNotificationNotVuNotAfficher, $value['Notification']['fiche_id']);
    }
}

if (!empty($notifications) && !empty($arrayNotificationNotVuNotAfficher)) {
    $this->Organisation = new Organisation();

    echo '<div class="modal fade" id="modalNotif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Nouvelles notifications</h4>
            </div>
        <div class="modal-body">';

    $oldmairie = '';

    foreach ($notifications as $key => $value) {
        $mairie = $nameOrganisation[$key]['Organisation']['raisonsociale'];

        if ($oldmairie != $mairie) {
            echo '<div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">' . $mairie . '</h5>
                    </div>';
        }

        switch ($value['Notification']['content']) {
            case 1:
                // Demande d'avis
                echo '<a href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/recuConsultation/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-info">'.__d('default','default.notificationAvisDemandeTraitement').' <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></a>';
                break;
            case 2:
                // Validation demandée
                echo '<a href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/recuValidation/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-info">'.__d('default','default.notificationValidationDemandeTraitement').' <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></a>';
                break;
            case 3:
                // Traitement validé
                echo '<a href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/registres/index/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-success">'.__d('default','default.notificationLeTraitement').' <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong>'.__d('default','default.notificationTraitementValidee').'</a>';
                break;
            case 4:
                // Traitement refusé
                echo '<a id="refus" href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/refuser/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-danger">'.__d('default','default.notificationLeTraitement').' <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong>'.__d('default','default.notificationTraitementRefusee').'</a>';
                break;
            case 5:
                // Commentaire ajouté sur le traitement
                echo '<a href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/consulte/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-info">'.__d('default','default.notificationCommentaireAjouterTraitement').' <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></a>';
                break;
        }

        $oldmairie = $mairie;

        $this->requestAction(array(
            'controller' => 'pannel',
            'action' => 'notifAfficher',
            $arrayNotificationNotVuNotAfficher[$key]
        ));
    }

    echo '</div>
                <div class="modal-footer">';

    echo $this->Html->link("<i class='fa fa-times fa-lg'></i>" . ' Fermer', [
        'controller' => 'pannel',
        'action' => 'validNotif'
            ], [
        'class' => 'btn btn-default-primary',
        'escapeTitle' => false
    ]);

    echo '</div>
                </div>
                </div>
               </div>
            </div>';
}
?>

<!-- Pop-up envoie consultation -->
<div class="modal fade" id="modalEnvoieConsultation" tabindex="-1" role="dialog" aria-labelledby="myModalLabelEnvoieConsultation" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="myModalLabelEnvoieConsultation">
                    <?php echo __d('pannel', 'pannel.popupEnvoyerTraitementConsultation'); ?>
                </h4>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <?php 

                    echo $this->Form->create('EtatFiche', array('action' => 'askAvis'));

                    echo $this->Form->input('destinataire', [
                        'class' => 'form-control usersDeroulant transformSelect form-control bottom5',
                        'label' => [
                            'text' => __d('pannel', 'pannel.textSelectUserConsultant') . '<span class="requis">*</span>',
                            'class' => 'col-md-4 control-label'
                        ],
                        'options' => $consultants,
                        'empty' => __d('pannel', 'pannel.textSelectUserConsultant'),
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'required' => true,
                        'autocomplete' => 'off',
                        'id' => 'destinataireCons'
                    ]);

                    echo $this->Form->hidden('ficheNum', ['id' => 'ficheNumCons']);
                    echo $this->Form->hidden('etatFiche', ['id' => 'etatFicheCons']);
                    ?>
                </div>
            </div>
            
            </hr>
            
            <div class="modal-footer">
                <div class="btn-group">
                    <button type="button" class="btn btn-default-default" data-dismiss="modal">
                        <i class="fa fa-times-circle fa-lg"></i>
                        <?php echo __d('default', 'default.btnAnnuler'); ?>
                    </button>
                    <?php
                    echo $this->Form->button("<i class='fa fa-send fa-lg'></i>" . __d('default', 'default.btnEnvoyer'), array(
                        'type' => 'submit',
                        'class' => 'btn btn-default-success',
                        'escape' => false
                    ));
                    ?>
                </div>
                <?php
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Pop-up reorientation du traitement -->
<div class="modal fade" id="modalReorienter" tabindex="-1" role="dialog" aria-labelledby="myModalLabelReorienter" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="myModalLabelReorienter">
                    <?php echo __d('pannel', 'pannel.popupReorienterTraitement'); ?>
                </h4>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <?php 

                    echo $this->Form->create('EtatFiche', array('action' => 'reorientation'));

                    echo $this->Form->input('destinataire', [
                        'class' => 'form-control usersDeroulant transformSelect form-control bottom5',
                        'label' => [
                            'text' => __d('pannel', 'pannel.textSelectReorienterValideur') . '<span class="requis">*</span>',
                            'class' => 'col-md-4 control-label'
                        ],
                        'options' => $validants,
                        'empty' => __d('pannel', 'pannel.textSelectReorienterValideur'),
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'required' => true,
                        'autocomplete' => 'off',
                        'id' => 'destinataireReo'
                    ]);

                    echo $this->Form->hidden('ficheNum', ['id' => 'ficheNumReo']);
                    echo $this->Form->hidden('etatFiche', ['id' => 'etatFicheReo']);
                    ?>
                </div>
            </div>
            
            </hr>
            
            <div class="modal-footer">
                <div class="btn-group">
                    <button type="button" class="btn btn-default-default" data-dismiss="modal">
                        <i class="fa fa-times-circle fa-lg"></i>
                        <?php echo __d('default', 'default.btnAnnuler'); ?>
                    </button>
                    <?php
                    echo $this->Form->button("<i class='fa fa-send fa-lg'></i>" . __d('default', 'default.btnEnvoyer'), array(
                        'type' => 'submit',
                        'class' => 'btn btn-default-success',
                        'escape' => false
                    ));
                    ?>
                </div>
                <?php
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Pop-up envoie validation -->
<div class="modal fade" id="modalEnvoieValidation" tabindex="-1" role="dialog" aria-labelledby="myModalLabelEnvoieValidation" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="myModalLabelEnvoieValidation">
                    <?php echo __d('pannel', 'pannel.popupEnvoyerTraitementValidation'); ?>
                </h4>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <?php 
                    echo $this->Form->create('EtatFiche', array('action' => 'sendValidation'));

                    echo $this->Form->input('destinataire', [
                        'class' => 'form-control usersDeroulant transformSelect form-control bottom5',
                        'label' => [
                            'text' => __d('pannel', 'pannel.textSelectUserValideur') . '<span class="requis">*</span>',
                            'class' => 'col-md-4 control-label'
                        ],
                        'options' => $validants,
                        'empty' => __d('pannel', 'pannel.textSelectUserValideur'),
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'required' => true,
                        'autocomplete' => 'off',
                        'id' => 'destinataireVal'
                    ]);
                    
                    echo $this->Form->hidden('ficheNum', ['id' => 'ficheNumVal']);
                    echo $this->Form->hidden('etatFiche', ['id' => 'etatFicheVal']);
                    ?>
                </div>
            </div>
            
            </hr>
            
            <div class="modal-footer">
                <div class="btn-group">
                    <button type="button" class="btn btn-default-default" data-dismiss="modal">
                        <i class="fa fa-times-circle fa-lg"></i>
                        <?php echo __d('default', 'default.btnAnnuler'); ?>
                    </button>
                    <?php
                    echo $this->Form->button("<i class='fa fa-send fa-lg'></i>" . __d('default', 'default.btnEnvoyer'), array(
                        'type' => 'submit',
                        'class' => 'btn btn-default-success',
                        'escape' => false
                    ));
                    ?>
                </div>
                <?php
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
</div>      

<script type="text/javascript">

    $(document).ready(function () {

        openTarget("<?php echo $idFicheNotification ?>");
        
    });

</script>