<?php
echo $this->Html->script('pannel.js');

// balise du scrollTo
$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);

if ($this->Autorisation->authorized(3, $droits)) {
?>
    <!-- Fiches reçues en consultation -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title"><?php
                        echo __d('pannel', 'pannel.traitementConsultation') . $nbTraitementRecuEnConsultation . ')';
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
?>


<script type="text/javascript">

    $(document).ready(function () {

        openTarget("<?php echo $idFicheNotification ?>");

    });

</script>