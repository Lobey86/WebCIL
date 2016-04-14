<?php
    echo $this->Html->script('pannel.js');
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12">
                <h3 class="panel-title"><?php echo __d('pannel', 'pannel.traitementValidationInsereeRegistre') . count($validees) . __d('pannel', 'pannel.motTraitement');
                    if(count($validees) > 1) {
                        echo 's';
                    } ?>)</h3>
            </div>
        </div>
    </div>
    <div class="panel-body panel-body-custom">
        <?php
            if(!empty($validees)) {
                ?>
                <table class="table  table-bordered">
                    <thead>
                        <tr>
                            <th class="thleft col-md-1">
                                <?php echo __d('pannel', 'pannel.motEtat') ;?>
                            </th>
                            <th class="thleft col-md-9 col-md-offset-1">
                                <?php echo __d('pannel', 'pannel.motSynthese') ;?>
                            </th>
                            <th class="thleft col-md-2 col-md-offset-10">
                                <?php echo __d('pannel', 'pannel.motActions') ;?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($validees as $donnee) {
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
                                                <strong><?php echo __d('pannel','pannel.motNomTraitement');?>
                                                </strong> <?php echo $donnee['Fiche']['Valeur'][0]['valeur']; ?>
                                            </div>

                                        </div>
                                        <div class="row top15">
                                            <div class="col-md-6">
                                                <strong><?php echo __d('pannel','pannel.motCreee');?>
                                                </strong> <?php echo $donnee['Fiche']['User']['prenom'] . ' ' . $donnee['Fiche']['User']['nom'] . ' le ' . $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <strong><?php echo __d('pannel','pannel.motDerniereModification');?>
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
                                                    'title'       => __d('pannel', 'pannel.commentaireVoirTraitement'),
                                                    'escapeTitle' => false
                                                ]) . $this->Html->link('<span class="glyphicon glyphicon-file"></span>', [
                                                    'controller' => 'fiches',
                                                    'action'     => 'genereFusion',
                                                    $donnee['Fiche']['id']
                                                ], [
                                                    'class'       => 'btn btn-default-default boutonEdit btn-sm my-tooltip',
                                                    'title'       => __d('pannel', 'pannel.commentaireTelechargeRegistre'),
                                                    'escapeTitle' => false
                                                ]);
                                            ?>
                                        </div>
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
                    <h3><?php echo __d('pannel', 'pannel.aucunTraitementValidationInsereeRegistre') ;?></h3>
                </div>";
            <?php
            }
        ?>
    </div>
</div>