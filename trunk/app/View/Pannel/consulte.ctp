<?php
echo $this->Html->script('pannel.js');

if ($this->Autorisation->authorized([2,3], $droits)) {
?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title">
                        <?php
                        echo __d('pannel', 'pannel.titreTableauEtatTraitementPasserPossession');
                        ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="panel-body panel-body-custom">
            <?php
            if (!empty($validees)) {
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
                        foreach ($validees as $donnee) {
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
                                        } elseif ($donnee['EtatFiche']['etat_id'] == 5 || $donnee['EtatFiche']['etat_id'] == 7 || $donnee['EtatFiche']['etat_id'] == 9) {
                                            ?>
                                            <i class="fa fa-check fa-3x fa-success"></i>
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
<?php
}