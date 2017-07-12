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
                                <td class='tdleft col-md-1'>
                                    <div class="etatIcone">
                                        <i class="fa fa-pencil-square-o fa-3x"></i>
                                            <?php echo ("Rédaction"); ?>
                                    </div>
                                </td>

                                <td class='tdleft'>
                                    <?php 
                                        echo $donnee['Fiche']['Valeur'][0]['valeur'];
                                    ?>
                                </td>

                                <td class="tdleft">
                                    <?php 
                                        echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], FORMAT_DATE_HEURE);
                                    ?>
                                </td>

                                <td class="tdleft">
                                    <?php 
                                        echo $this->Time->format($donnee['Fiche']['modified'], FORMAT_DATE_HEURE);
                                    ?>
                                </td>

                                <td class='tdleft'>
                                    <div class="btn-group">
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

                            <!-- <tr class='completion'></tr> -->
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
    <?php
}
?>

<!-- Pop-up envoie consultation -->
<div class="modal fade" id="modalEnvoieConsultation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="myModalLabel">
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
                        'autocomplete' => 'off'
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

<!-- Pop-up envoie validation -->
<div class="modal fade" id="modalEnvoieValidation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="myModalLabel">
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
                        'autocomplete' => 'off'
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

        openTarget("<?php echo $idFicheNotification; ?>");

    });

</script>
