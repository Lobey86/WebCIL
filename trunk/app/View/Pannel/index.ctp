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
                    <h3 class="panel-title">
                        <?php
                        echo __d('pannel', 'pannel.traitementEnCours') . $nbTraitementEnCoursRedaction . ")";
                        
                        if ($nbTraitementEnCoursRedaction != 0) {
                        ?>
                            <span class="pull-right">
                                <?php
                                echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span>' . __d('pannel', 'pannel.btnVoirTraitementEnCoursRedaction'), [
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
                        foreach ($traitementEnCoursRedaction as $donnee) {
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
                                            </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], FORMAT_DATE_HEURE); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><?php echo __d('pannel', 'pannel.motDerniereModification'); ?>
                                            </strong> <?php echo $this->Time->format($donnee['Fiche']['modified'], FORMAT_DATE_HEURE); ?>
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
                                            <?php 
                                            if($donnee['EtatFiche']['user_id'] != $donnee['User']['id']){
                                                debug($donnee);
                                            }
                                            ?>
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
                                        'title' => __d('pannel', 'pannel.commentaireSupprimerTraitement')
                                            ], __d('pannel', 'pannel.confirmationSupprimerTraitement') . $donnee['Fiche']['Valeur'][0]['valeur'] . ' " ?');
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
                    <h3 class="panel-title"> 
                        <?php
                        echo __d('pannel', 'pannel.traitementEnAttente') . $nbTraitementEnCoursValidation . ')';

                        if ($nbTraitementEnCoursValidation != 0) {
                        ?>
                            <span class="pull-right">
                                <?php
                                echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span>' . __d('pannel', 'pannel.btnVoirTraitementEnAttente'), [
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
                        foreach ($traitementEnCoursValidation as $donnee) {
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
                                            </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], FORMAT_DATE_HEURE); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><?php echo __d('pannel', 'pannel.motDerniereModification'); ?>
                                            </strong> <?php echo $this->Time->format($donnee['Fiche']['modified'], FORMAT_DATE_HEURE); ?>
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
                    <h3>
                        <?php 
                        echo __d('pannel', 'pannel.aucunTraitementEnAttente'); 
                        ?>
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
                                echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span>' . __d('pannel', 'pannel.btnVoirTraitementRefuser'), [
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
                        foreach ($traitementRefuser as $donnee) {
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
                                            </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], FORMAT_DATE_HEURE); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><?php echo __d('pannel', 'pannel.motDerniereModification'); ?>
                                            </strong> <?php echo $this->Time->format($donnee['Fiche']['modified'], FORMAT_DATE_HEURE); ?>
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
                                            'title' => __d('pannel', 'pannel.commentaireSupprimerTraitement')
                                                ], __d('pannel', 'pannel.confirmationSupprimerTraitement') . $donnee['Fiche']['Valeur'][0]['valeur'] . '?');
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

if ($this->Autorisation->authorized(2, $droits)) {
?>    
<!-- Fiches reçues en validation -->
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
                            echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span>' . __d('pannel', 'pannel.btnVoirTraitementRecuValidation'), [
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
            <table class="table table-bordered">
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
                    foreach ($traitementRecuEnValidation as $donnee) {
                        ?>
                        <tr>
                            <td class='tdleft col-md-1'>
                                <div class="etatIcone">
                                    <i class="fa fa-check-square-o fa-3x"></i>
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
                                        </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], FORMAT_DATE_HEURE); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><?php echo __d('pannel', 'pannel.motDerniereModification'); ?>
                                        </strong> <?php echo $this->Time->format($donnee['Fiche']['modified'], FORMAT_DATE_HEURE); ?>
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
                                        'title' => __d('pannel', 'pannel.commentaireVoirTraitement'),
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
                                            class='btn btn-default-default boutonListAValider btn-sm my-tooltip'
                                            title= '<?php echo __d('pannel', 'pannel.commentaireVoirParcours'); ?>'
                                            id='<?php echo $donnee['Fiche']['id']; ?>'
                                            value='<?php echo $donnee['Fiche']['id']; ?>'>
                                        <span class='glyphicon glyphicon-list-alt'></span>
                                    </button>
                                    <button
                                        class='btn btn-default-success dropdown-toggle boutonValider btn-sm my-tooltip'
                                        type='button'
                                        title= '<?php echo __d('pannel', 'pannel.commentaireValiderTraitement'); ?>'
                                        id='dropdownMenuValider' data-toggle='dropdown'>
                                        <span class='glyphicon glyphicon-ok'></span>
                                        <span class='caret'></span>
                                    </button>
                                    <ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenuValider'>
                                        <li role='presentation'>
                                            <a role='menuitem' tabindex='-1' href='#'
                                               class='envoiConsultValider'
                                               value='<?php echo $donnee['Fiche']['id']; ?>'><?php echo __d('pannel', 'pannel.textEnvoyerConsultation'); ?>
                                            </a>
                                        </li>
                                        <li role='presentation'>
                                            <a role='menuitem' tabindex='-1' href='#'
                                               class='envoiValidValider'
                                               value='<?php echo $donnee['Fiche']['id']; ?>'><?php echo __d('pannel', 'pannel.textEnvoyerValidation'); ?>
                                            </a>
                                        </li>
                                        <?php
                                        if (!$this->Autorisation->isCil()) {
                                            echo "<li role='presentation'>" . $this->Html->link(__d('pannel', 'pannel.textEnvoyerCIL'), [
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
                                <button type='button'
                                        class='btn btn-default-danger boutonRefuser btn-sm my-tooltip'
                                        title='<?php echo __d('pannel','pannel.commentaireRefuserTraitement'); ?>'
                                        value='<?php echo $donnee['Fiche']['id']; ?>'>
                                    <span class='glyphicon glyphicon-remove'></span>
                                </button>

                            </td>
                        </tr>
                        <tr class='listeAValider' id='listeAValider<?php echo $donnee['Fiche']['id']; ?>'>
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
                        <tr class='selectDestConsultValider<?php echo $donnee['Fiche']['id']; ?> selectorDestConsultValider'>
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
                                echo $this->Form->button('<i class="fa fa-fw fa-arrow-left"></i>' . __d('default', 'default.btnAnnuler'), array(
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
                        <tr class='selectDestValidValider<?php echo $donnee['Fiche']['id']; ?> selectorDestValidValider'>
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
                                echo $this->Form->button('<i class="fa fa-fw fa-arrow-left"></i>' . __d('default', 'default.btnAnnuler'), array(
                                    'type' => 'button',
                                    'class' => 'btn btn-default-default sendCancel'
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
                                echo $this->Form->button('<i class="fa fa-fw fa-arrow-left"></i>' . __d('default', 'default.btnAnnuler'), array(
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
                <h3><?php echo __d('pannel', 'pannel.aucunTraitementValidation'); ?></h3>
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
                                echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span>' . __d('pannel', 'pannel.btnVoirTraitementRecuConsultation'), [
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
            <table class="table table-bordered">
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
                    foreach ($traitementRecuEnConsultation as $donnee) {
                        ?>
                        <tr>
                            <td class='tdleft col-md-1'>
                                <div class="etatIcone">
                                    <i class="fa fa-eye fa-3x"></i>
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
                                        </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], FORMAT_DATE_HEURE); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><?php echo __d('pannel', 'pannel.motDerniereModification'); ?>
                                        </strong> <?php echo $this->Time->format($donnee['Fiche']['modified'], FORMAT_DATE_HEURE); ?>
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
                                        'title' => __d('pannel', 'pannel.commentaireVoirTraitement'),
                                        'escapeTitle' => false
                                    ]);
                                    ?>
                                    <button type='button'
                                            class='btn btn-default-default boutonRepondre boutonsAction5 btn-sm my-tooltip'
                                            title= '<?php echo __d('pannel', 'pannel.commentaireRepondre'); ?>'
                                            id="<?php echo $donnee['Fiche']['id']; ?>"
                                            value='<?php echo $donnee['Fiche']['id']; ?>'>
                                        <span class='glyphicon glyphicon-share-alt'></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class='commentaireRepondre<?php echo $donnee['Fiche']['id']; ?>'>
                            <td></td>
                            <td class='tdleft'>
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
                                echo $this->Form->button('<i class="fa fa-fw fa-arrow-left"></i>' . __d('default', 'default.btnAnnuler'), array(
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
                            <td class="tdleft">
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
                            echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span>' . __d('pannel', 'pannel.btnVoirTraitementArchiver'), [
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
                    foreach ($traitementArchives as $donnee) {
                        ?>
                        <tr>
                            <td class='tdleft col-md-1'>
                                <div class="etatIcone">
                                    <i class="fa fa-check fa-3x fa-success"></i>
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
                                        </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], FORMAT_DATE_HEURE); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><?php echo __d('pannel', 'pannel.motDerniereModification'); ?>
                                        </strong> <?php echo $this->Time->format($donnee['Fiche']['modified'], FORMAT_DATE_HEURE); ?>
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
                                        'title' => __d('pannel', 'pannel.commentaireVoirTraitement'),
                                        'escapeTitle' => false
                                    ]) . $this->Html->link('<span class="glyphicon glyphicon-file"></span>', [
                                        'controller' => 'fiches',
                                        'action' => 'genereFusion',
                                        $donnee['Fiche']['id']
                                            ], [
                                        'class' => 'btn btn-default-default boutonEdit btn-sm my-tooltip',
                                        'title' => __d('pannel', 'pannel.commentaireTelechargeRegistre'),
                                        'escapeTitle' => false
                                    ]);
                                    ?>
                                    <button type='button'
                                            class='btn btn-default-default boutonList btn-sm my-tooltip'
                                            title='<?php echo __d('pannel', 'pannel.commentaireVoirParcours'); ?>'
                                            id='<?php echo $donnee['Fiche']['id']; ?>'
                                            value='<?php echo $donnee['Fiche']['id']; ?>'>
                                        <span class='glyphicon glyphicon-list-alt'></span>
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
?>

<!-- Bannette consulte -->
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
                                echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span>' . __d('pannel', 'pannel.btnVoirTraitementArchiver'), [
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
                    foreach ($traitementConnaissance as $donnee) {
                        ?>
                        <tr>
                            <td class='tdleft col-md-1'>
                                <div class="etatIcone">
                                    <?php
                                    //Adapter l'icone affiché en fonction de sont état
                                    if ($donnee['EtatFiche']['etat_id'] == 1 || $donnee['EtatFiche']['etat_id'] == 8) {
                                        ?>
                                        <i class="fa fa-pencil-square-o fa-3x"></i>
                                        <?php
                                    } elseif ($donnee['EtatFiche']['etat_id'] == 2 || $donnee['EtatFiche']['etat_id'] == 6) {
                                        ?>
                                        <i class="fa fa-clock-o fa-3x"></i>
                                        <?php
                                    } elseif ($donnee['EtatFiche']['etat_id'] == 3) {
                                        ?>
                                        <i class="fa fa-check fa-3x fa-success"></i>
                                        <?php
                                    } elseif ($donnee['EtatFiche']['etat_id'] == 4) {
                                        ?>
                                        <i class="fa fa-times fa-3x fa-danger"></i>
                                        <?php
                                    }
                                    ?>
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
                                        </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], FORMAT_DATE_HEURE); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><?php echo __d('pannel', 'pannel.motDerniereModification'); ?>
                                        </strong> <?php echo $this->Time->format($donnee['Fiche']['modified'], FORMAT_DATE_HEURE); ?>
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
                                    <?php
                                    if ($donnee['EtatFiche']['etat_id'] == 2 && $donnee['EtatFiche']['user_id_actuel'] != $donnee['EtatFiche']['user_id']) {
                                        ?>
                                        <button type='button'
                                                class='btn btn-default-default boutonReorienter btn-sm my-tooltip'
                                                title='<?php echo __d('pannel', 'pannel.commentaireReorienterTraitement'); ?>'
                                                value='<?php echo $donnee['Fiche']['id']; ?>'>
                                            <span class='glyphicon glyphicon-transfer'></span>
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
                                echo $this->Form->button('<i class="fa fa-fw fa-arrow-left"></i>' . __d('default', 'default.btnAnnuler'), array(
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
                        <tr class='listeRefusee' 
                            id='listeRefusee<?php echo $donnee['Fiche']['id']; ?>'>
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
                <h3><?php echo __d('pannel', 'pannel.aucunTraitementPasserPossession'); ?></h3>
            </div>
            <?php
        }
        ?>
    </div>
</div>



<div class="modal fade" id="modalValidCil" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Insertion au registre</h4>
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
                <button type="button" class="btn btn-default-default" data-dismiss="modal"><?php echo __d('default', 'default.btnAnnuler'); ?></button>
                <button type="submit" class="btn btn-default-success">Valider</button>
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