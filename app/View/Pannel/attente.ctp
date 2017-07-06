<?php
echo $this->Html->script('pannel.js');

// balise du scrollTo
$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);

if ($this->Autorisation->authorized(1, $droits)) {
    ?>
    <!-- Banette des fiches en attente -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title"> 
                        <?php
                        echo __d('pannel', 'pannel.traitementEnAttente') . $nbTraitementEnCoursValidation . ')';
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
                                            echo $this->Html->link('<span class="glyphicon glyphicon-transfer"></span>', ['#' => '#'], [
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
    <?php
}
?>  

<!-- Pop-up reorientation du traitement -->
<div class="modal fade" id="modalReorienter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="myModalLabel">
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

<script type="text/javascript">

    $(document).ready(function () {

        openTarget("<?php echo $idFicheNotification ?>");

    });

</script>
