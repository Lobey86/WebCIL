<?php
echo $this->Html->script('pannel.js');

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
                    <h3 class="panel-title"> <?php
                        echo __d('pannel', 'pannel.traitementEnCours') . count($encours) . __d('pannel', 'pannel.motTraitement');
                        if (count($encours) > 1) {
                            echo 's';
                        }
                        ?>)</h3>
                </div>
            </div>
        </div>
        <div class="panel-body panel-body-custom">
            <?php
            if (!empty($encours)) {
                ?>

                <table class="table  table-bordered">
                    <thead>
                        <tr>
                            <th class="thleft col-md-1">
                                <?php echo __d('pannel', 'pannel.motEtat'); ?>
                            </th>
                            <th class="thleft col-md-9 col-md-offset-1">
                                <?php echo __d('pannel', 'pannel.motSynthese'); ?>
                            </th>
                            <th class="thleft col-md-2 col-md-offset-10">
                                <?php echo __d('pannel', 'pannel.motActions'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($encours as $donnee) {
                            ?>
                            <tr>
                                <td class='tdleft col-md-1'>
                                    <div class="etatIcone">
                                        <i class="fa fa-pencil-square-o fa-3x"></i>
                                    </div>
                                </td>
                                <td class='tdleft col-md-9 col-md-offset-1'>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong><?php echo __d('pannel', 'pannel.motNomTraitement'); ?>
                                            </strong> <?php echo $donnee['Fiche']['Valeur'][0]['valeur']; ?>
                                        </div>
                                    </div>
                                    <div class="row top15">
                                        <div class="col-md-6">
                                            <strong><?php echo __d('pannel', 'pannel.motCreee'); ?>
                                            </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><?php echo __d('pannel', 'pannel.motDerniereModification'); ?>
                                            </strong> <?php echo $this->Time->format($donnee['Fiche']['modified'], '%e-%m-%Y'); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class='tdleft col-md-2 col-md-offset-10'>
                                    <div class="btn-group">
                                        <?php
                                        echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', [
                                            'controller' => 'fiches',
                                            'action' => 'show',
                                            $donnee['Fiche']['id']
                                                ], [
                                            'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                            'escapeTitle' => false,
                                            'title' => __d('pannel', 'pannel.commentaireVoirTraitement')
                                        ]) . $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', [
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
                                                class='btn btn-default-default boutonList btn-sm my-tooltip'
                                                title='<?php echo __d('pannel', 'pannel.commentaireVoirParcours'); ?>'
                                                id='<?php echo $donnee['Fiche']['id']; ?>'
                                                value='<?php echo $donnee['Fiche']['id']; ?>'>
                                            <span class='glyphicon glyphicon-list-alt'></span>
                                        </button>

                                        <button
                                            class='btn btn-default-default dropdown-toggle boutonSend btn-sm my-tooltip'
                                            type='button'
                                            id='dropdownMenu1' data-toggle='dropdown'
                                            title='<?php echo __d('pannel', 'pannel.commentaireEnvoyerTraitement'); ?>'>
                                            <span class='glyphicon glyphicon-send'></span>
                                            <span class='caret'></span>
                                        </button>
                                        <ul class='dropdown-menu' role='menu'
                                            aria-labelledby='dropdownMenu1'>
                                            <li role='presentation'>
                                                <a role='menuitem' tabindex='-1' href='#'
                                                   class='envoiConsult'
                                                   value='<?php echo $donnee['Fiche']['id']; ?>'><?php echo __d('pannel', 'pannel.textEnvoyerConsultation'); ?>
                                                </a>
                                            </li>
                                            <li role='presentation'>
                                                <a role='menuitem' tabindex='-1' href='#'
                                                   class='envoiValid'
                                                   value='<?php echo $donnee['Fiche']['id']; ?>'><?php echo __d('pannel', 'pannel.textEnvoyerValidation'); ?>
                                                </a>
                                            </li>
                                            <li role='presentation'><?php
                                                echo $this->Html->link(__d('pannel', 'pannel.textEnvoyerCIL'), [
                                                    'controller' => 'etatFiches',
                                                    'action' => 'cilValid',
                                                    $donnee['Fiche']['id']
                                                        ], [
                                                    'role' => 'menuitem',
                                                    'tabindex' => '-1'
                                                ]);
                                                ?></li>
                                        </ul>
                                    </div>
                                    <?php
                                    echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', [
                                        'controller' => 'fiches',
                                        'action' => 'delete',
                                        $donnee['Fiche']['id']
                                            ], [
                                        'class' => 'btn btn-default-danger boutonDelete btn-sm my-tooltip',
                                        'escapeTitle' => false,
                                        'title' => __d('pannel', 'pannel.commentaireSupprimer')
                                            ], __d('pannel', 'pannel.confirmationSupprimer') . $donnee['Fiche']['Valeur'][0]['valeur'] . '?');
                                    ?>

                                </td>
                            </tr>
                            <tr class='listeValidation'
                                id='listeValidation<?php echo $donnee['Fiche']['id']; ?>'>
                                <td></td>
                                <td class='tdleft'>
                                    <?php
                                    $parcours = $this->requestAction([
                                        'controller' => 'Pannel',
                                        'action' => 'parcours',
                                        $donnee['Fiche']['id']
                                    ]);
                                    echo $this->element('parcours', [
                                        "parcours" => $parcours
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
                                        "historique" => $historique,
                                        "id" => $donnee['Fiche']['id']
                                    ]);
                                    ?>
                                </td>
                            </tr>
                            <tr class='selectConsultDest<?php echo $donnee['Fiche']['id']; ?>'>
                                <td></td>
                                <td></td>
                                <td colspan='2' class='tdleft'>
                                    <?php
                                    echo $this->Form->create('EtatFiche', $options = ['action' => 'askAvis']);
                                    echo $this->Form->input('destinataire', [
                                        'options' => $consultants,
                                        'class' => 'usersDeroulant transformSelect form-control bottom5',
                                        'empty' => __d('pannel', 'pannel.textSelectUser'),
                                        'label' => false,
                                        'required' => true
                                    ]);
                                    echo $this->Form->hidden('ficheNum', ['value' => $donnee['Fiche']['id']]);
                                    echo $this->Form->hidden('etatFiche', ['value' => $donnee['EtatFiche']['id']]);
                                    echo '<div class="btn-group">';
                                    echo $this->Form->button('<i class="fa fa-fw fa-arrow-left"></i>'. __d('default', 'default.btnAnnuler'), array(
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
                            <tr class='selectValidDest<?php echo $donnee['Fiche']['id']; ?>'>
                                <td></td>
                                <td></td>
                                <td colspan='2' class='tdleft'>
                                    <?php
                                    echo $this->Form->create('EtatFiche', $options = ['action' => 'sendValidation']);
                                    echo $this->Form->input('destinataire', [
                                        'options' => $validants,
                                        'class' => 'usersDeroulant transformSelect form-control bottom5',
                                        'empty' => __d('pannel', 'pannel.textSelectUser'),
                                        'label' => false,
                                        'required' => true
                                    ]);
                                    echo $this->Form->hidden('ficheNum', ['value' => $donnee['Fiche']['id']]);
                                    echo $this->Form->hidden('etatFiche', ['value' => $donnee['EtatFiche']['id']]);
                                    echo '<div class="btn-group">';
                                    echo $this->Form->button('<i class="fa fa-fw fa-arrow-left"></i>'. __d('default', 'default.btnAnnuler'), array(
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
                    <h3> <?php echo __d('pannel', 'pannel.aucunTraitementEnCours'); ?></h3>
                </div>
                <?php
            }
            ?>
            <div class="row bottom10">
                <div class="col-md-12 text-center">
                    <?php
                    echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span>' . __d('pannel', 'pannel.btnCreerTraitement'), ['#' => '#'], [
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
                    <h3 class="panel-title"> <?php
                        echo __d('pannel', 'pannel.traitementEnAttente') . count($encoursValidation) . __d('pannel', 'pannel.motTraitement');
                        if (count($encoursValidation) > 1) {
                            echo 's';
                        }
                        ?>)</h3>
                </div>
            </div>
        </div>
        <div class="panel-body panel-body-custom">
            <?php
            if (!empty($encoursValidation)) {
                ?>
                <table class="table  table-bordered">
                    <thead>
                        <tr>
                            <th class="thleft col-md-1">
                                <?php echo __d('pannel', 'pannel.motEtat'); ?>
                            </th>
                            <th class="thleft col-md-9 col-md-offset-1">
                                <?php echo __d('pannel', 'pannel.motSynthese'); ?>
                            </th>
                            <th class="thleft col-md-2 col-md-offset-10">
                                <?php echo __d('pannel', 'pannel.motActions'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($encoursValidation as $donnee) {
                            ?>
                            <tr>
                                <td class='tdleft col-md-1'>
                                    <div class="etatIcone">
                                        <i class="fa fa-clock-o fa-3x"></i>
                                    </div>
                                </td>
                                <td class='tdleft col-md-9 col-md-offset-1'>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong><?php echo __d('pannel', 'pannel.motNomTraitement'); ?>
                                            </strong> <?php echo $donnee['Fiche']['Valeur'][0]['valeur']; ?>
                                        </div>

                                    </div>
                                    <div class="row top15">
                                        <div class="col-md-6">
                                            <strong><?php echo __d('pannel', 'pannel.motCreee'); ?>
                                            </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><?php echo __d('pannel', 'pannel.motDerniereModification'); ?>
                                            </strong> <?php echo $this->Time->format($donnee['Fiche']['modified'], '%e-%m-%Y'); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class='tdcent col-md-2 col-md-offset-10'>
                                    <div class="btn-group">
                                        <?php
                                        echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', [
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
                                            <span class='glyphicon glyphicon-list-alt'></span>
                                        </button>
                                        <button type='button'
                                                class='btn btn-default-default boutonReorienter btn-sm my-tooltip'
                                                title='<?php echo __d('pannel', 'pannel.commentaireReorienterTraitement'); ?>'
                                                value='<?php echo $donnee['Fiche']['id']; ?>'>
                                            <span class='glyphicon glyphicon-transfer'></span>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                            <tr class='listeValidation'
                                id='listeValidation<?php echo $donnee['Fiche']['id']; ?>'>
                                <td></td>
                                <td class='tdleft'>
                                    <?php
                                    $parcours = $this->requestAction([
                                        'controller' => 'Pannel',
                                        'action' => 'parcours',
                                        $donnee['Fiche']['id']
                                    ]);
                                    echo $this->element('parcours', [
                                        "parcours" => $parcours
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
                                        "historique" => $historique,
                                        "id" => $donnee['Fiche']['id']
                                    ]);
                                    ?>
                                </td>
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
                                    echo $this->Form->button('<i class="fa fa-fw fa-arrow-left"></i>'. __d('default', 'default.btnAnnuler'), array(
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
                            <?php
                        }
                        ?>
                    </tbody>
                </table>


                <?php
            } else {
                ?>
                <div class='text-center'>
                    <h3><?php echo __d('pannel', 'pannel.aucunTraitementEnAttente'); ?></h3>
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
                        echo __d('pannel', 'pannel.traitementRefusees') . count($refusees) . __d('pannel', 'pannel.motTraitement');
                        if (count($refusees) > 1) {
                            echo 's';
                        }
                        ?>)</h3>
                </div>
            </div>
        </div>
        <div class="panel-body panel-body-custom">
            <?php
            if (!empty($refusees)) {
                ?>

                <table class="table  table-bordered">
                    <thead>
                        <tr>
                            <th class="thleft col-md-1">
                                <?php echo __d('pannel', 'pannel.motEtat'); ?>
                            </th>
                            <th class="thleft col-md-9 col-md-offset-1">
                                <?php echo __d('pannel', 'pannel.motSynthese'); ?>
                            </th>
                            <th class="thleft col-md-2 col-md-offset-10">
                                <?php echo __d('pannel', 'pannel.motActions'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($refusees as $donnee) {
                            ?>
                            <tr>
                                <td class='tdleft col-md-1'>
                                    <div class="etatIcone">
                                        <i class="fa fa-times fa-3x fa-danger"></i>
                                    </div>
                                </td>
                                <td class='tdleft col-md-9 col-md-offset-1'>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong><?php echo __d('pannel', 'pannel.motNomTraitement'); ?>
                                            </strong> <?php echo $donnee['Fiche']['Valeur'][0]['valeur']; ?>
                                        </div>

                                    </div>
                                    <div class="row top15">
                                        <div class="col-md-6">
                                            <strong><?php echo __d('pannel', 'pannel.motCreee'); ?>
                                            </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><?php echo __d('pannel', 'pannel.motDerniereModification'); ?>
                                            </strong> <?php echo $this->Time->format($donnee['Fiche']['modified'], '%e-%m-%Y'); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class='tdcent col-md-2 col-md-offset-10'>
                                    <div class="btn-group">
                                        <?php
                                        echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', [
                                            'controller' => 'fiches',
                                            'action' => 'show',
                                            $donnee['Fiche']['id']
                                                ], [
                                            'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                            'escapeTitle' => false,
                                            'title' => __d('pannel', 'pannel.commentaireVoirTraitement')
                                        ]);
                                        echo $this->Html->link('<span class="glyphicon glyphicon-repeat"></span>', [
                                            'controller' => 'EtatFiches',
                                            'action' => 'relaunch',
                                            $donnee['Fiche']['id']
                                                ], [
                                            'class' => 'btn btn-default-default boutonRelancer btn-sm my-tooltip',
                                            'title' => __d('pannel', 'pannel.commentaireReplacerTraitementRedaction'),
                                            'escapeTitle' => false
                                        ]);
                                        ?>
                                        <button type='button'
                                                class='btn btn-default-default boutonList btn-sm my-tooltip boutonListRefusee'
                                                title='<?php echo __d('pannel', 'pannel.commentaireVoirParcours'); ?>'
                                                id='<?php echo $donnee['Fiche']['id']; ?>'
                                                value='<?php echo $donnee['Fiche']['id']; ?>'>
                                            <span class='glyphicon glyphicon-list-alt'></span>
                                        </button>
                                        <?php
                                        echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', [
                                            'controller' => 'fiches',
                                            'action' => 'delete',
                                            $donnee['Fiche']['id']
                                                ], [
                                            'class' => 'btn btn-default-danger boutonDelete btn-sm my-tooltip',
                                            'escapeTitle' => false,
                                            'title' => __d('pannel', 'pannel.commentaireSupprimer')
                                                ], __d('pannel', 'pannel.confirmationSupprimer') . $donnee['Fiche']['Valeur'][0]['valeur'] . '?');
                                        ?>

                                    </div>
                                </td>
                            </tr>
                            <tr class='listeRefusee' id='listeRefusee<?php echo $donnee['Fiche']['id']; ?>'>
                                <td></td>
                                <td class='tdleft'>
                                    <?php
                                    $parcours = $this->requestAction([
                                        'controller' => 'Pannel',
                                        'action' => 'parcours',
                                        $donnee['Fiche']['id']
                                    ]);
                                    echo $this->element('parcours', [
                                        "parcours" => $parcours
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
                                        "historique" => $historique,
                                        "id" => $donnee['Fiche']['id']
                                    ]);
                                    ?>
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
                    <h3><?php echo __d('pannel', 'pannel.aucunTraitementRefusees'); ?></h3>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <?php
}
?>

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
                echo '<a href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/inbox/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-info">Votre avis est demandé sur la fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></a>';
                break;
            case 2:
                echo '<a href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/inbox/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-info">Votre validation est demandée sur la fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></a>';
                break;
            case 3:
                echo '<a href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/registres/index/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-success">La fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong> a été validée</a>';
                break;
            case 4:
                echo '<a id="refus" href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/index/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-danger">La fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong> a été refusée</a>';
                break;
            case 5:
                echo '<a href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/index/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-info">Un commentaire a été ajouté à la fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></a>';
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

    echo $this->Html->link('Fermer', [
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

<script type="text/javascript">

    $(document).ready(function () {

        openTarget("<?php echo $idFicheNotification ?>");

    });

</script>