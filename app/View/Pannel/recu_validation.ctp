<?php
echo $this->Html->script('pannel.js');

// balise du scrollTo
$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);

if ($this->Autorisation->authorized(2, $droits)) {
?>    
<!-- Fiches reçues en validation -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12">
                <h3 class="panel-title"><?php
                    echo __d('pannel', 'pannel.traitementValidation') . $nbTaitementRecuEnValidation . ')';
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
                                    echo $this->Html->link('<span class="fa fa-eye fa-lg"></span>', [
                                        'controller' => 'fiches',
                                        'action' => 'show',
                                        $donnee['Fiche']['id']
                                            ], [
                                        'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                        'escapeTitle' => false,
                                        'title' => __d('pannel', 'pannel.commentaireVoirTraitement'),
                                    ]) . $this->Html->link('<span class="fa fa-pencil fa-lg"></span>', [
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
                                <button type='button'
                                        class='btn btn-default-danger boutonRefuser btn-sm my-tooltip'
                                        title='<?php echo __d('pannel','pannel.commentaireRefuserTraitement'); ?>'
                                        value='<?php echo $donnee['Fiche']['id']; ?>'>
                                    <span class='fa fa-times fa-lg'></span>
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
                <h3><?php echo __d('pannel', 'pannel.aucunTraitementValidation'); ?></h3>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php
}
?>

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

        openTarget("<?php echo $idFicheNotification ?>");
        
    });

</script>