<?php
    echo $this->Html->script('pannel.js');
?>

    <!-- Banette des fiches en cours de rédaction -->

<?php
    if($this->Autorisation->authorized(1, $droits)) {
        ?>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="panel-title">Mes fiches en cours de rédaction (<?php echo count($encours); ?>
                                                fiche<?php if(count($encours) > 1) {
                                echo 's';
                            } ?>)</h3>
                    </div>
                </div>
            </div>
            <div class="panel-body panel-body-custom">
                <?php
                    if(!empty($encours)) {
                        ?>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="thleft col-md-1">
                                        Etat
                                    </th>
                                    <th class="thleft col-md-9 col-md-offset-1">
                                        Synthèse
                                    </th>
                                    <th class="thleft col-md-2 col-md-offset-10">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($encours as $donnee) {
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
                                                        <strong>Nom du traitement:
                                                        </strong> <?php echo $donnee['Fiche']['Valeur'][0]['valeur']; ?>
                                                    </div>

                                                </div>
                                                <div class="row top15">
                                                    <div class="col-md-6">
                                                        <strong>Créée
                                                                par:
                                                        </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Dernière modification
                                                                le:
                                                        </strong> <?php echo $this->Time->format($donnee['Fiche']['modified'], '%e-%m-%Y'); ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='tdleft col-md-2 col-md-offset-10'>
                                                <div class="btn-group">
                                                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', [
                                                            'controller' => 'fiches',
                                                            'action'     => 'show',
                                                            $donnee['Fiche']['id']
                                                        ], [
                                                            'class'       => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                                            'escapeTitle' => FALSE,
                                                            'title'       => 'Voir la fiche'
                                                        ]) . $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', [
                                                            'controller' => 'fiches',
                                                            'action'     => 'edit',
                                                            $donnee['Fiche']['id']
                                                        ], [
                                                            'class'       => 'btn btn-default-default boutonEdit btn-sm my-tooltip',
                                                            'escapeTitle' => FALSE,
                                                            'title'       => 'Editer la fiche'
                                                        ]); ?>
                                                    <button type='button'
                                                            class='btn btn-default-default boutonList btn-sm my-tooltip'
                                                            title='Voir le parcours'
                                                            value='<?php echo $donnee['Fiche']['id']; ?>'>
                                                        <span class='glyphicon glyphicon-list-alt'></span>
                                                    </button>

                                                    <button
                                                        class='btn btn-default-default dropdown-toggle boutonSend btn-sm my-tooltip'
                                                        type='button'
                                                        id='dropdownMenu1' data-toggle='dropdown'
                                                        title='Envoyer la fiche'>
                                                        <span class='glyphicon glyphicon-send'></span>
                                                        <span class='caret'></span>
                                                    </button>
                                                    <ul class='dropdown-menu' role='menu'
                                                        aria-labelledby='dropdownMenu1'>
                                                        <li role='presentation'>
                                                            <a role='menuitem' tabindex='-1' href='#'
                                                               class='envoiConsult'
                                                               value='<?php echo $donnee['Fiche']['id']; ?>'>Envoyer
                                                                                                             pour
                                                                                                             consultation
                                                            </a>
                                                        </li>
                                                        <li role='presentation'>
                                                            <a role='menuitem' tabindex='-1' href='#'
                                                               class='envoiValid'
                                                               value='<?php echo $donnee['Fiche']['id']; ?>'>Envoyer
                                                                                                             pour
                                                                                                             validation
                                                            </a>
                                                        </li>
                                                        <li role='presentation'><?php echo $this->Html->link('Envoyer au CIL pour clôture', [
                                                                'controller' => 'etatFiches',
                                                                'action'     => 'cilValid',
                                                                $donnee['Fiche']['id']
                                                            ], [
                                                                'role'     => 'menuitem',
                                                                'tabindex' => '-1'
                                                            ]); ?></li>
                                                    </ul>
                                                </div>
                                                <?php echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', [
                                                    'controller' => 'fiches',
                                                    'action'     => 'delete',
                                                    $donnee['Fiche']['id']
                                                ], [
                                                    'class'       => 'btn btn-default-danger boutonDelete btn-sm my-tooltip',
                                                    'escapeTitle' => FALSE,
                                                    'title'       => 'Supprimer la fiche'
                                                ], 'Voulez vous supprimer la fiche de ' . $donnee['Fiche']['Valeur'][0]['valeur'] . '?'); ?>

                                            </td>
                                        </tr>
                                        <tr class='listeValidation'
                                            id='listeValidation<?php echo $donnee['Fiche']['id']; ?>'>
                                            <td></td>
                                            <td class='tdleft'>
                                                <?php
                                                    $parcours = $this->requestAction([
                                                        'controller' => 'Pannel',
                                                        'action'     => 'parcours',
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
                                                        'action'     => 'getHistorique',
                                                        $donnee['Fiche']['id']
                                                    ]);
                                                    echo $this->element('historique', [
                                                        "historique" => $historique,
                                                        "id"         => $donnee['Fiche']['id']
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
                                                        'class'   => 'usersDeroulant transformSelect form-control bottom5',
                                                        'empty'   => 'Selectionnez un utilisateur',
                                                        'label'   => FALSE
                                                    ]);
                                                    echo $this->Form->hidden('ficheNum', ['value' => $donnee['Fiche']['id']]);
                                                    echo $this->Form->hidden('etatFiche', ['value' => $donnee['EtatFiche']['id']]);
                                                    echo '<div class="btn-group">';
                                                    echo $this->Form->button('<i class="fa fa-arrow-left"></i> Annuler', [
                                                        'type'  => 'button',
                                                        'class' => 'btn btn-default-default sendCancel top5'
                                                    ]);
                                                    echo $this->Form->button('<i class="fa fa-check"></i> Envoyer', [
                                                        'type'  => 'submit',
                                                        'class' => 'btn btn-default-success top5'
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
                                                        'class'   => 'usersDeroulant transformSelect form-control bottom5',
                                                        'empty'   => 'Selectionnez un utilisateur',
                                                        'label'   => FALSE
                                                    ]);
                                                    echo $this->Form->hidden('ficheNum', ['value' => $donnee['Fiche']['id']]);
                                                    echo $this->Form->hidden('etatFiche', ['value' => $donnee['EtatFiche']['id']]);
                                                    echo '<div class="btn-group">';
                                                    echo $this->Form->button('<i class="fa fa-arrow-left"></i> Annuler', [
                                                        'type'  => 'button',
                                                        'class' => 'btn btn-default-default sendCancel top5'
                                                    ]);
                                                    echo $this->Form->button('<i class="fa fa-check"></i> Envoyer', [
                                                        'type'  => 'submit',
                                                        'class' => 'btn btn-default-success top5'
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

                        echo "<div class='text-center'><h3>Vous n'avez aucune fiche</h3></div>";
                    }
                ?>
                <div class="row bottom10">
                    <div class="col-md-12 text-center">
                        <?php echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> Ajouter une fiche', ['#' => '#'], [
                            'escape'      => FALSE,
                            'data-toggle' => 'modal',
                            'data-target' => '#myModal',
                            'class'       => 'btn btn-default-primary'
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>


        <!-- Panel de fiches en attente -->

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="panel-title">Mes fiches en attente (<?php echo count($encoursValidation); ?>
                                                fiche<?php if(count($encoursValidation) > 1) {
                                echo 's';
                            } ?>)</h3>
                    </div>
                </div>
            </div>
            <div class="panel-body panel-body-custom">
                <?php
                    if(!empty($encoursValidation)) {
                        ?>

                        <table class="table  table-bordered">
                            <thead>
                                <tr>
                                    <th class="thleft col-md-1">
                                        Etat
                                    </th>
                                    <th class="thleft col-md-9 col-md-offset-1">
                                        Synthèse
                                    </th>
                                    <th class="thleft col-md-2 col-md-offset-10">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($encoursValidation as $donnee) {
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
                                                        <strong>Nom du traitement:
                                                        </strong> <?php echo $donnee['Fiche']['Valeur'][0]['valeur']; ?>
                                                    </div>

                                                </div>
                                                <div class="row top15">
                                                    <div class="col-md-6">
                                                        <strong>Créée
                                                                par:
                                                        </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Dernière modification
                                                                le:
                                                        </strong> <?php echo $this->Time->format($donnee['Fiche']['modified'], '%e-%m-%Y'); ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='tdcent col-md-2 col-md-offset-10'>
                                                <div class="btn-group">
                                                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', [
                                                        'controller' => 'fiches',
                                                        'action'     => 'show',
                                                        $donnee['Fiche']['id']
                                                    ], [
                                                        'class'       => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                                        'escapeTitle' => FALSE,
                                                        'title'       => 'Voir la fiche'
                                                    ]); ?>
                                                    <button type='button'
                                                            class='btn btn-default-default boutonList btn-sm my-tooltip'
                                                            title='Voir le parcours'
                                                            value='<?php echo $donnee['Fiche']['id']; ?>'>
                                                        <span class='glyphicon glyphicon-list-alt'></span>
                                                    </button>
                                                    <button type='button'
                                                            class='btn btn-default-default boutonReorienter btn-sm my-tooltip'
                                                            title="Réorienter la fiche"
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
                                                        'action'     => 'parcours',
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
                                                        'action'     => 'getHistorique',
                                                        $donnee['Fiche']['id']
                                                    ]);
                                                    echo $this->element('historique', [
                                                        "historique" => $historique,
                                                        "id"         => $donnee['Fiche']['id']
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
                                                        'class'   => 'usersDeroulant transformSelect form-control bottom5',
                                                        'empty'   => 'Selectionnez un utilisateur',
                                                        'label'   => FALSE
                                                    ]);
                                                    echo $this->Form->hidden('ficheNum', ['value' => $donnee['Fiche']['id']]);
                                                    echo $this->Form->hidden('etatFiche', ['value' => $donnee['EtatFiche']['id']]);
                                                    echo '<div class="btn-group">';
                                                    echo $this->Form->button('<i class="fa fa-arrow-left"></i> Annuler', [
                                                        'type'  => 'button',
                                                        'class' => 'btn btn-default-default sendCancel top5'
                                                    ]);
                                                    echo $this->Form->button('<i class="fa fa-check"></i> Envoyer', [
                                                        'type'  => 'submit',
                                                        'class' => 'btn btn-default-success top5'
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

                        echo "<div class='text-center'><h3>Vous n'avez aucune fiche</h3></div>";
                    }
                ?>
            </div>
        </div>


        <!-- Fiches refusées -->

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="panel-title">Mes fiches refusées (<?php echo count($refusees); ?>
                                                fiche<?php if(count($refusees) > 1) {
                                echo 's';
                            } ?>)</h3>
                    </div>
                </div>
            </div>
            <div class="panel-body panel-body-custom">
                <?php
                    if(!empty($refusees)) {
                        ?>

                        <table class="table  table-bordered">
                            <thead>
                                <tr>
                                    <th class="thleft col-md-1">
                                        Etat
                                    </th>
                                    <th class="thleft col-md-9 col-md-offset-1">
                                        Synthèse
                                    </th>
                                    <th class="thleft col-md-2 col-md-offset-10">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($refusees as $donnee) {
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
                                                        <strong>Nom du traitement:
                                                        </strong> <?php echo $donnee['Fiche']['Valeur'][0]['valeur']; ?>
                                                    </div>

                                                </div>
                                                <div class="row top15">
                                                    <div class="col-md-6">
                                                        <strong>Créée
                                                                par:
                                                        </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Dernière modification
                                                                le:
                                                        </strong> <?php echo $this->Time->format($donnee['Fiche']['modified'], '%e-%m-%Y'); ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='tdcent col-md-2 col-md-offset-10'>
                                                <div class="btn-group">
                                                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', [
                                                        'controller' => 'fiches',
                                                        'action'     => 'show',
                                                        $donnee['Fiche']['id']
                                                    ], [
                                                        'class'       => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                                        'escapeTitle' => FALSE,
                                                        'title'       => 'Voir la fiche'
                                                    ]);
                                                        echo $this->Html->link('<span class="glyphicon glyphicon-repeat"></span>', [
                                                            'controller' => 'EtatFiches',
                                                            'action'     => 'relaunch',
                                                            $donnee['Fiche']['id']
                                                        ], [
                                                            'class'       => 'btn btn-default-default boutonRelancer btn-sm my-tooltip',
                                                            'title'       => 'Replacer la fiche en rédaction',
                                                            'escapeTitle' => FALSE
                                                        ]); ?>
                                                    <button type='button'
                                                            class='btn btn-default-default boutonList btn-sm my-tooltip boutonListRefusee'
                                                            title='Voir le parcours'
                                                            value='<?php echo $donnee['Fiche']['id']; ?>'>
                                                        <span class='glyphicon glyphicon-list-alt'></span>
                                                    </button>
                                                    <?php
                                                        echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', [
                                                            'controller' => 'fiches',
                                                            'action'     => 'delete',
                                                            $donnee['Fiche']['id']
                                                        ], [
                                                            'class'       => 'btn btn-default-danger boutonDelete btn-sm my-tooltip',
                                                            'escapeTitle' => FALSE,
                                                            'title'       => 'Supprimer la fiche'
                                                        ], 'Voulez vous supprimer la fiche de ' . $donnee['Fiche']['Valeur'][0]['valeur'] . '?');
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
                                                        'action'     => 'parcours',
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
                                                        'action'     => 'getHistorique',
                                                        $donnee['Fiche']['id']
                                                    ]);
                                                    echo $this->element('historique', [
                                                        "historique" => $historique,
                                                        "id"         => $donnee['Fiche']['id']
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

                        echo "<div class='text-center'><h3>Vous n'avez aucune fiche</h3></div>";
                    }
                ?>
            </div>
        </div>


        <!------------------------------------------------------------------------------------------------------------------------------->


        <?php
    }
?>