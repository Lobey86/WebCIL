<?php
echo $this->Html->script('pannel.js');

$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);
?>
<!-- Fiches reçues en validation -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12">
                <h3 class="panel-title"><?php
                    echo __d('pannel', 'pannel.traitementValidation') . count($dmdValid) . __d('pannel', 'pannel.motTraitement');
                    if (count($dmdValid) > 1) {
                        echo 's';
                    }
                    ?>)</h3>
            </div>
        </div>
    </div>
    <div class="panel-body panel-body-custom">
        <?php
        if (!empty($dmdValid)) {
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
                    foreach ($dmdValid as $donnee) {
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
                                            echo "<li role='presentation'>" . $this->Html->link('Envoyer au CIL pour clôture', [
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
                                                'data' => $donnee['Fiche']['id'],
                                                'class' => 'btn-insert-registre'
                                            ]) . "</li> ";
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <button type='button'
                                        class='btn btn-default-danger boutonRefuser btn-sm my-tooltip'
                                        title="Refuser la fiche"
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
                                echo $this->Form->button('<i class="fa fa-arrow-left"></i>' . __d('pannel', 'pannel.btnAnnuler'), [
                                    'type' => 'button',
                                    'class' => 'btn btn-default-danger pull-right sendCancel',
                                    'onClick' => 'return false'
                                ]);
                                echo $this->Form->button('<i class="fa fa-check"></i>' . __d('pannel', 'pannel.btnEnvoyer'), [
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
                                echo $this->Form->button('<i class="fa fa-arrow-left"></i>' . __d('pannel', 'pannel.btnAnnuler'), [
                                    'type' => 'button',
                                    'class' => 'btn btn-default-danger pull-right sendCancel',
                                    'onClick' => 'return false'
                                ]);
                                echo $this->Form->button('<i class="fa fa-check"></i>' . __d('pannel', 'pannel.btnEnvoyer'), [
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
                                    'before' => '<span class="labelFormulaire">Expliquez les raisons de votre refus</span><span class="obligatoire"> *</span>',
                                    'class' => 'form-control',
                                    'required' => true,
                                    'type' => 'textarea'
                                ]);
                                echo $this->Form->hidden('ficheNum', ['value' => $donnee['Fiche']['id']]);
                                echo $this->Form->hidden('etatFiche', ['value' => $donnee['EtatFiche']['id']]);
                                echo '<div class="btn-group">';
                                echo $this->Form->button('<i class="fa fa-arrow-left"></i>' . __d('pannel', 'pannel.btnAnnuler'), [
                                    'type' => 'button',
                                    'class' => 'btn btn-default-danger pull-right btnDivSend refusCancel',
                                    'onClick' => 'return false'
                                ]);
                                echo $this->Form->button('<i class="fa fa-check"></i>' . __d('pannel', 'pannel.btnEnvoyer'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-default-success pull-right btnDivSend'
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


<!-- Fiches reçues en consultation -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12">
                <h3 class="panel-title"><?php
                    echo __d('pannel', 'pannel.traitementConsultation') . count($dmdAvis) . __d('pannel', 'pannel.motTraitement');
                    if (count($dmdAvis) > 1) {
                        echo 's';
                    }
                    ?>)</h3>
            </div>
        </div>
    </div>
    <div class="panel-body panel-body-custom">
        <?php
        if (!empty($dmdAvis)) {
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
                    foreach ($dmdAvis as $donnee) {
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
                                    'before' => '<span class="labelFormulaire">Donnez votre avis</span><span class="obligatoire">*</span>',
                                    'required' => true,
                                    'class' => 'form-control',
                                    'type' => 'textarea'
                                ]);
                                echo $this->Form->hidden('etatFiche', ['value' => $donnee['EtatFiche']['id']]);
                                echo $this->Form->hidden('previousUserId', ['value' => $donnee['EtatFiche']['previous_user_id']]);
                                echo $this->Form->hidden('ficheNum', ['value' => $donnee['Fiche']['id']]);
                                echo '<div class="btn-group">';
                                echo $this->Form->button('<i class="fa fa-arrow-left"></i>' . __d('pannel', 'pannel.btnAnnuler'), [
                                    'type' => 'button',
                                    'class' => 'btn btn-default-danger pull-right btnDivSend repondreCancel',
                                    'onClick' => 'return false'
                                ]);
                                echo $this->Form->button('<i class="fa fa-check"></i>' . __d('pannel', 'pannel.btnEnvoyer'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-default-success pull-right btnDivSend'
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
                        <div class="col-md-12 text-center"><i class="fa fa-fw fa-exclamation-triangle"></i></div>
                        <div class="col-md-12">Vous allez insérer une fiche au registre. Merci de préciser le numéro
                            d'enregistrement CNIL. Laisser vide pour générer un numéro CIL
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
                        echo $this->Form->hidden('idfiche', ['id' => 'idFiche']);
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default-default" data-dismiss="modal"><?php echo __d('pannel', 'pannel.btnAnnuler'); ?></button>
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
                echo '<a href="/organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/inbox/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-info">Votre avis est demandé sur la fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></a>';
                break;
            case 2:
                echo '<a href="/organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/inbox/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-info">Votre validation est demandée sur la fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></a>';
                break;
            case 3:
                echo '<a href="/organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/registres/index/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-success">La fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong> a été validée</a>';
                break;
            case 4:
                echo '<a id="refus" href="/organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/index/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-danger">La fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong> a été refusée</a>';
                break;
            case 5:
                echo '<a href="/organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/index/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-info">Un commentaire a été ajouté à la fiche du traitement <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></a>';
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