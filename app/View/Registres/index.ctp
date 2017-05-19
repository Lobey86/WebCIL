<script src="http://code.jquery.com/jquery-latest.js"></script>

<?php
echo $this->Html->script('pannel.js');
echo $this->Html->script('registre.js');
echo $this->Form->button('<span class="fa fa-filter fa-lg"></span>' . __d('registre', 'registre.btnFiltrerListe'), $options = [
    'type' => 'button',
    'class' => 'btn btn-default-primary',
    'id' => 'filtrage'
]);

$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);
?>

<div id="divFiltrage">
    <?php
    echo $this->Form->create('Registre', $options = ['action' => 'index']);
    ?>
    
    <div class="input-group login">
        <span class="input-group-addon">
            <span class="fa fa-user fa-lg"></span>
        </span>
        <?php
        echo $this->Form->input('user', [
            'options' => $listeUsers,
            'class' => 'usersDeroulant transformSelect form-control',
            'empty' => __d('registre', 'registre.placeholderSelectionnerUser'),
            'label' => false
        ]);
        ?>
    </div>
    
    <div class="input-group login">
        <span class="input-group-addon">
            <span class="fa fa-tag fa-lg"></span>
        </span>
        <?php
        echo $this->Form->input('outil', [
            'class' => 'form-control',
            'placeholder' => __d('registre', 'registre.placeholderNomTraitement'),
            'label' => false
        ]);
        ?>
    </div>
    
    <div class="input-group login">
        <span class="input-group-addon">
            <span class="fa fa-user fa-lg"></span>
        </span>
        <?php
        echo $this->Form->input('service', [
            'options' => $listeServices,
            'class' => 'usersDeroulant transformSelect form-control',
            'empty' => __d('registre', 'registre.placeholderSelectionnerService'),
            'label' => false
        ]);
        ?>
    </div>
    
    <?php
    if ($this->Autorisation->isCil() || $this->Autorisation->isSu()) {
        ?>
        <div class = "input-group login">
            <?php
            echo $this->Form->input('archive', [
                'type' => 'checkbox',
                'label' => __d('registre', 'registre.radioFicheVerouillee'),
                'id' => 'checkArch'
            ]);
            
            echo $this->Form->input('nonArchive', [
                'type' => 'checkbox',
                'label' => __d('registre', 'registre.radioFicheNonVerouillee'),
                'id' => 'checkNonArch'
            ]);
            ?>
        </div>
        <?php
    }

    echo $this->Html->link(__d('registre', 'registre.btnSupprimerFiltre'), [
        'controller' => 'registres',
        'action' => 'index'
        ], [
            'class' => 'btn btn-default-danger pull-right'
        ]
    );
    echo $this->Form->submit(__d('registre', 'registre.btnFiltre'), ['class' => 'btn btn-default-primary']);
    echo $this->Form->end();
    ?>
</div>

<?php
if (!empty($fichesValid)) {
    ?>
    <br/>
    <br/>
    <br/>
    <?php
    if ($this->Autorisation->authorized('7', $this->Session->read('Droit.liste'))) {
        echo $this->Form->button('<span class="fa fa-upload"></span>' . __d('registre', 'registre.btnImprimerExtraitRegistrePDF'), [
            'onclick' => "sendDataExtrait()",
            'class' => 'btn btn-default-primary pull-left'
        ]);
    }
    
    if ($idCil['Organisation']['cil'] == $this->Session->read('Auth.User.id')) {
        echo $this->Form->button(__d('registre', 'registre.btnImprimerTraitementRegistrePDF'), [
            'onclick' => "sendData()",
            'class' => 'btn btn-default-primary pull-right'
        ]);
    }
    ?>
    
    <table class="table">
        <thead>
            <?php
            if ($this->Autorisation->isCil()) {
                ?>
                <!-- checkbox extrait registre -->
                <th class="thleft col-md-1">
                    <input id="extraitRegistreCheckbox" type="checkbox" class = "extraitRegistreCheckbox_checkbox" />
                </th>
            
                <!-- Nom du traitement -->
                <th class="thleft col-md-2">
                    <?php echo __d('registre', 'registre.titreTableauNomTraitement'); ?>
                </th>
                
                <!-- Synthèse -->
                <th class="thleft col-md-6">
                    <?php echo __d('registre', 'registre.titreTableauSynthese'); ?>
                </th>
                
                <!-- Outils -->
                <th class="thleft col-md-2">
                    <?php echo __d('registre', 'registre.titreTableauOutil'); ?>
                </th>
                
                <!-- checkbox traitement -->
                <th class="thleft col-md-1">
                    <input id="masterCheckbox" type="checkbox" class = "masterCheckbox_checkbox" />
                </th>
                
                <?php
            } else {
                if ($this->Autorisation->authorized('7', $this->Session->read('Droit.liste'))) {
                    ?>
                    <!-- checkbox traitement -->
                    <th class="thleft col-md-1">
                        <input id="extraitRegistreCheckbox" type="checkbox" class = "extraitRegistreCheckbox_checkbox" />
                    </th>
                    <?php
                }
                ?>
                
                <!-- Nom du traitement -->
                <th class="thleft col-md-3">
                    <?php echo __d('registre', 'registre.titreTableauNomTraitement'); ?>
                </th>
                
                <!-- Synthèse -->
                <th class="thleft col-md-6">
                    <?php echo __d('registre', 'registre.titreTableauSynthese'); ?>
                </th>
                
                <!-- Outils -->
                <th class="thleft col-md-2">
                    <?php echo __d('registre', 'registre.titreTableauOutil'); ?>
                </th>
                <?php
            }
            ?>
        </thead>
        <tbody>
            <?php
            $idExtrait = [];
            foreach ($fichesValid as $key => $value) {
                $numeroRegistre = $value['Fiche']['numero'];

                if ($value['EtatFiche']['etat_id'] != 7) {
                    $DlOrGenerate = 'genereTraitement';
                    $docExtrait = 'genereExtraitRegistre';
                    $idExtrait = json_encode([$value['Fiche']['id']]);
                    
                } else {
                    $DlOrGenerate = 'downloadFileTraitement';
                    $docExtrait = 'downloadFileExtrait';
                    $idExtrait = $value['Fiche']['id'];
                }

                if ($value['Fiche']['Valeur'] != null) {
                    ?>
                    <tr>
                        <?php
                        if ($this->Autorisation->authorized('7', $this->Session->read('Droit.liste'))) {
                            ?>
                            <!-- Casse à coché pour télécharger les extraits de registre -->
                            <td class="tdleft">
                                <input type="checkbox" class="extraitRegistreCheckbox" id="<?php echo $value['Fiche']['id']; ?>" >
                            </td>
                            <?php
                        }
                        ?>
                        <td class="tdleft">
                            <?php
                            echo $value['Fiche']['Valeur'][1]['valeur']; 
                            ?>
                        </td>

                        <td class="tdleft">
                            <div class="row">
                                <div class="col-md-8">
                                    <strong>
                                        <?php echo __d('registre', 'registre.textTableauDateCreation'); ?>
                                    </strong>
                                    <?php echo $this->Time->format($value['EtatFiche']['created'], FORMAT_DATE); ?>
                                </div>

                                <div class="col-md-4">
                                    <strong>
                                        <?php echo __d('registre', 'registre.textTableauNumeroEnregistrement'); ?>
                                    </strong>
                                    <?php echo $numeroRegistre; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <strong>
                                        <?php echo __d('registre', 'registre.textTableauFinalitePrincipale'); ?>
                                    </strong>
                                        <?php echo $value['Fiche']['Valeur'][2]['valeur']; ?>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>
                                        <?php echo __d('registre', 'registre.textTableauServiceDeclarant'); ?>
                                    </strong>
                                        <?php echo $value['Fiche']['Valeur'][0]['valeur']; ?>
                                </div>
                            </div>
                        </td>

                        <!-- Outils -->
                        <td class="tdleft">
                            <div id='<?php echo $value['Fiche']['id']; ?>' class="btn-group">
                                <?php
                                if ($this->Autorisation->authorized('7', $this->Session->read('Droit.liste'))) {
                                    // Bouton de téléchargement du traitement en PDF
                                    echo $this->Html->link('<i class="fa fa-file-pdf-o fa-lg"></i>', [
                                        'controller' => 'fiches',
                                        'action' => $DlOrGenerate,
                                        $idExtrait
                                        ], [
                                            'escape' => false,
                                            'class' => 'btn btn-default-default btn-sm my-tooltip',
                                            'title' => __d('registre', 'registre.commentaireTelechargeRegistrePDF')
                                        ]
                                    );
                                }

                                // Bouton pour visualiser le traitement
                                echo $this->Html->link('<span class="fa fa-search fa-lg"></span>', [
                                    'controller' => 'fiches',
                                    'action' => 'show',
                                    $value['Fiche']['id']
                                    ], [
                                        'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                        'title' => __d('registre', 'registre.commentaireVoirTraitement'),
                                        'escapeTitle' => false
                                    ]
                                );
                                
                                // Bouton de visualisation de l'historique du traitement
                                ?>
                                <button type='button'
                                    class='btn btn-default-default boutonList btn-sm my-tooltip'
                                    title='<?php echo __d('pannel', 'pannel.commentaireVoirParcours'); ?>'
                                    id='<?php echo $value['Fiche']['id']; ?>'
                                    value='<?php echo $value['Fiche']['id']; ?>'>
                                    <span class='glyphicon glyphicon-list-alt'></span>
                                </button>
                                
                                <?php
                                if ($this->Autorisation->authorized('7', $this->Session->read('Droit.liste'))) {
                                    // Bouton de téléchargement de l'extrait de registre en PDF
                                    echo $this->Html->link('<span class="fa fa-child fa-lg"></span>', [
                                        'controller' => 'fiches',
                                        'action' => $docExtrait,
                                        $idExtrait
                                            ], [
                                            'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                            'title' => 'Télécharger extrait de registre',
                                            'escapeTitle' => false
                                        ]
                                    );
                                }
                                
                                if (($this->Autorisation->isCil() || $this->Autorisation->isSu()) && $value['EtatFiche']['etat_id'] != 7) {
                                    // Bouton de modification du traitement
                                    echo $this->Form->button('<span class="fa fa-pencil fa-lg"></span>', [
                                        'class' => 'btn btn-default-default boutonEdit btn-sm my-tooltip btn-edit-registre modif_traitement',
                                        'id' => $value['Fiche']['id'],
                                        'escapeTitle' => false,
                                        'data-toggle' => 'modal',
                                        'data-target' => '#modalEditRegistre',
                                        'title' => __d('registre', 'registre.commentaireModifierTraitement')
                                    ]);

                                    if ($this->Autorisation->isCil() || $this->Autorisation->isSu()) {
                                        // Bouton de verrouillage du traitement
                                        echo $this->Html->link('<span class="fa fa-lock fa-lg"></span>', [
                                            'controller' => 'fiches',
                                            'action' => 'archive',
                                            $value['Fiche']['id'],
                                                ], [
                                                'class' => 'btn btn-default-danger boutonArchive btn-sm my-tooltip',
                                                'title' => __d('registre', 'registre.commentaireVerouillerTraitement'),
                                                'escapeTitle' => false
                                            ], __d('registre', 'registre.confirmationVerouillerTraitement')
                                        );
                                    }
                                }
                                ?>
                            </div>
                        </td>
                        <?php
                        if ($idCil['Organisation']['cil'] == $this->Session->read('Auth.User.id')) {
                            ?>
                            <td class="tdleft">
                                <?php
                                if (($this->Autorisation->isCil() || $this->Autorisation->isSu()) && $value['EtatFiche']['etat_id'] == 7) {
                                    ?>
                                    <input type="checkbox" class="masterCheckbox" id="<?php echo $value['Fiche']['id']; ?>" >
                                    <?php
                                }
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    
                    <tr class='listeValidation' id='listeValidation<?php echo $value['Fiche']['id']; ?>'>
                        <td></td>
                        <td></td>
                        <td class='tdleft'>
                            <?php
                            $parcours = $this->requestAction([
                                'controller' => 'Pannel',
                                'action' => 'parcours',
                                $value['Fiche']['id']
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
                                $value['Fiche']['id']
                            ]);
                        
                            echo $this->element('historique', [
                                'historique' => $historique,
                                'id' => $value['Fiche']['id']
                            ]);
                            ?>
                        </td>
                        <td></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
    <?php
} else {
    if ($search) {
        ?>
        <div class='text-center'>
            <h3>
                <?php echo __d('registre', 'registre.textAucunTraitementFiltre'); ?>
                <small>
                    <?php
                    echo $this->Html->link(' ' . __d('registre', 'registre.lienAnnulerFiltres'), [
                       'controller' => 'registres',
                        'action' => 'index'
                    ]);
                    ?>
                </small>
            </h3>
        </div>
        <?php
    } else {
        ?>
    <div class='text-center'>
        <h3>
            <?php echo __d('registre', 'registre.textAucunTraitementFiltre'); ?>
        </h3>
    </div>
    <?php
    }
}
?>

<!-- Pop-up modification du traitement enregistré au registre  -->
<div class="modal fade" id="modalEditRegistre" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php echo __d('registre', 'registre.popupTitreEditionTraitementRegistre'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-warning">
                        <div class="col-md-12 text-center">
                            <i class="fa fa-fw fa-exclamation-triangle fa-lg"></i>
                        </div>
                        <div class="col-md-12">
                            <?php echo __d('registre', 'registre.popupText'); ?>
                        </div>
                    </div>
                </div>
                <div class="row top17">
                    <div class="col-md-12">
                        <?php
                        echo $this->Form->create('Registre', [
                            'action' => 'edit',
                            'class' => 'form-horizontal'
                        ]);

                        echo $this->Form->input('motif', [
                            'label' => [
                                'text' => __d('registre', 'registre.popupChampMotif') . '<span class="obligatoire"> *</span>',
                                'class' => 'col-md-2 control-label'
                            ],
                            'between' => '<div class="col-md-10">',
                            'after' => '</div>',
                            'class' => 'form-control',
                            'div' => 'form-group',
                            'required' => 'required'
                        ]);
                        
                        echo $this->Form->hidden('idEditRegistre', [
                            'value' => '', 
                            'id' => "toModif"
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default-default" data-dismiss="modal">
                    <?php echo __d('default', 'default.btnAnnuler'); ?>
                </button>
                <button type="submit" class="btn btn-default-success" id="modif_valid">
                    <?php echo __d('registre', 'registre.popupBtnMofifierTraitement'); ?>
                </button>
                <?php
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var currentTraitementId = 0;

    $(document).ready(function () {

        openTarget("<?php echo $idFicheNotification ?>");

        // Lors d'action sur une checkbox :
        $("#masterCheckbox").change(function () {
            $(".masterCheckbox").not(':disabled').prop('checked', $(this).prop('checked'));
        });

        // Checkbox -> masterCheckbox
        $('input[type="checkbox"]').not("#masterCheckbox").change(function () {
            $('#masterCheckbox').prop('checked', $('input[type="checkbox"]').not('#masterCheckbox').not(':disabled').not(':checked').length === 0);
        });
        
        // Lors d'action sur une checkbox : extraitRegistreCheckbox
        $("#extraitRegistreCheckbox").change(function () {
            $(".extraitRegistreCheckbox").not(':disabled').prop('checked', $(this).prop('checked'));
        });

        // Checkbox -> extraitRegistreCheckbox
        $('input[type="checkbox"]').not("#extraitRegistreCheckbox").change(function () {
            $('#extraitRegistreCheckbox').prop('checked', $('input[type="checkbox"]').not('#extraitRegistreCheckbox').not(':disabled').not(':checked').length === 0);
        });

        $('.modif_traitement').click(function () {
            currentTraitementId = $(this).attr('id');
        });

        $('#RegistreEditForm').submit(function (el) {
            $("#toModif").val(currentTraitementId);
            return true;
        });

    });

    function sendData() {
        var url = "<?php echo Router::url(['controller' => 'registres', 'action' => 'imprimer']); ?>";
        var selectedList = [];

        $(".masterCheckbox").each(function () {
            if (this.checked) {
                selectedList.push(this.id);
            }
        });
        url = url + '/' + JSON.stringify(selectedList);
        window.location.href = url;
    }
    
    function sendDataExtrait() {
        var url = "<?php echo Router::url(['controller' => 'fiches', 'action' => 'genereExtraitRegistre']); ?>";
        var selectedList = [];

        $(".extraitRegistreCheckbox").each(function () {
            if (this.checked) {
                selectedList.push(this.id);
            }
        });
        url = url + '/' + JSON.stringify(selectedList);
        window.location.href = url;
    }

</script>