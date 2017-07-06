<!-- Tableau des formulaires -->
<table class="table">
    <!-- Titre tableau -->
    <thead>
            <!-- Etat -->
            <th class="col-md-1">
                <?php echo __d('formulaire', 'formulaire.titreTableauEtat'); ?>
            </th>

            <!-- Nom -->
            <th class="col-md-3">
                <?php echo __d('user', 'user.titreTableauNomDuFormulaire'); ?>
            </th>

            <!-- Description -->
            <th class="col-md-4">
                <?php echo __d('user', 'user.titreTableauDescription'); ?>
            </th>

            <!-- Date de création -->
            <th class="col-md-2">
                <?php echo __d('user', 'user.titreTableauDateCreation'); ?>
            </th>

            <!-- Actions -->
            <th class="col-md-2">
                <?php echo __d('user', 'user.titreTableauAction'); ?>
            </th>

            <!-- Duplication formulaire autre organisation -->
            <th class="thleft col-md-1">
            </th>
    </thead>
    
    <!-- Info tableau -->
    <tbody>
        <?php
        foreach ($formulaires as $data) {
            if ($data['Formulaire']['active'] == true) {
                $iconClass = 'fa fa-toggle-off fa-3x fa-success';
                $statut = __d('formulaire', 'formulaire.textStatutActif');
                $statutClass = 'fa-success';
            } else {
                $statut = __d('formulaire', 'formulaire.textStatutInactif');
                $iconClass = 'fa fa-toggle-on fa-3x fa-danger';
                $statutClass = 'fa-danger';
            }
            ?>
            <tr>
                <!-- Status du formulaire -->
                <td class="tdleft col-md-1">
                    <div class="etatIcone">
                        <i class= '<?php echo $iconClass; ?>'></i>
                        <span class='<?php echo $statutClass; ?>'>
                            <?php echo $statut; ?>
                        </span>
                    </div>
                </td>
                
                <!-- Nom du formulaire -->
                <td class="tdleft">
                    <?php echo $data['Formulaire']['libelle']; ?>
                </td>
                
                <!-- Description du formulaire -->
                <td class="tdleft">
                    <?php echo $data['Formulaire']['description']; ?>
                </td>
                
                <!-- Date de création -->
                <td class="tdleft">
                    <?php echo $this->Time->format($data['Formulaire']['created'], FORMAT_DATE); ?>
                </td>

                <!-- Action possible d'effectuer en fonction de l'état du formulaire -->
                <td class="tdleft">
                    <div class="btn-group">
                        <?php
                        // Bouton voir le formulaire
                        echo $this->Html->link('<span class="fa fa-eye fa-lg"></span>', array(
                            'controller' => 'formulaires',
                            'action' => 'show',
                            $data['Formulaire']['id']
                                ), array(
                            'class' => 'btn btn-default-default btn-sm my-tooltip',
                            'title' => __d('formulaire', 'formulaire.commentaireVoirFormulaire'),
                            'escape' => false,
                        ));

                        if ($valid[$data['Formulaire']['id']]) {
                            // Bouton édité le formulaire
                            echo $this->Html->link('<span class="fa fa-pencil fa-lg"></span>', array(
                                'controller' => 'formulaires',
                                'action' => 'edit',
                                $data['Formulaire']['id']
                                    ), array(
                                'class' => 'btn btn-default-default btn-sm my-tooltip',
                                'title' => __d('formulaire', 'formulaire.commentaireModifierFormulaire'),
                                'escape' => false
                            ));
                        }

                        if ($data['Formulaire']['active'] == true) {
                            // Bouton désactivé le formulaire
                            $lien = $this->Html->link('<span class="fa fa-toggle-off fa-lg"></span>', array(
                                'controller' => 'formulaires',
                                'action' => 'toggle',
                                $data['Formulaire']['id'],
                                $data['Formulaire']['active']
                                    ), array(
                                'class' => 'btn btn-default-default btn-sm my-tooltip',
                                'escape' => false,
                                'title' => __d('formulaire', 'formulaire.commentaireDesactiverFormulaire')
                            ));
                        } else {
                            // Bouton activé le formulaire
                            $lien = $this->Html->link('<span class="fa fa-toggle-on fa-lg"></span>', array(
                                'controller' => 'formulaires',
                                'action' => 'toggle',
                                $data['Formulaire']['id'],
                                $data['Formulaire']['active']
                                    ), array(
                                'class' => 'btn btn-default-default btn-sm my-tooltip',
                                'title' => __d('formulaire', 'formulaire.commentaireActiverFormulaire'),
                                'escape' => false
                            ));
                        }
                        echo $lien;
                        
                        if ($valid[$data['Formulaire']['id']] == true) {
                            //Bouton supprimé le formulaire
                            echo $this->Html->link('<span class="fa fa-trash fa-lg"></span>', array(
                                'controller' => 'formulaires',
                                'action' => 'delete',
                                $data['Formulaire']['id']
                                    ), array(
                                'class' => 'btn btn-default-danger btn-sm my-tooltip',
                                'title' => __d('formulaire', 'formulaire.commentaireSupprimerFormulaire'),
                                'escape' => false
                                    ), __d('formulaire', 'formulaire.confirmationSupprimerFormulaire')
                            );
                        } else {
                            // Bouton dupliqué le formulaire
                            ?> 
                            <button type="button" class="btn btn-default-default btn-sm my-tooltip btn_duplicate" 
                                    data-toggle="modal" data-target="#modalDupliquer" value="<?php echo $data['Formulaire']['id']; ?>"
                                    title="<?php echo __d('formulaire', 'formulaire.commentaireDupliquerFormulaire'); ?>">
                                <span class="fa fa-files-o fa-lg" ></span>
                            </button>
                            <?php
                        }
                        ?>
                    </div>
                </td>
                
                <td class="tdleft">
                    <button type="button" class="btn btn-default-primary btn-sm my-tooltip btn_duplicateFormulaireOrganisation" 
                            data-toggle="modal" data-target="#modalDupliquerFormulaireOrganisation" value="<?php echo $data['Formulaire']['id']; ?>"
                            title="<?php echo __d('formulaire', 'formulaire.placeholderDupliquerFormulaireOrganisation'); ?>">
                        <span class="fa fa-clipboard fa-lg" ></span>
                    </button>
                </td>
                
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>

<!--Bouton de création d'un formulaire -->
<div class="row bottom10">
    <div class="col-md-12 text-center">
        <button type="button" class="btn btn-default-primary" data-toggle="modal" data-target="#modalAddForm">
            <span class="glyphicon glyphicon-plus"></span>
            <?php echo __d('formulaire', 'formulaire.btnCreerFormulaire'); ?>
        </button>
    </div>
</div>

<!-- Pop-up de création d'un nouveau formulaire -->
<div class="modal fade" id="modalAddForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __d('formulaire', 'formulaire.popupInfoGeneraleFormulaire'); ?></h4>
            </div>
            <div class="modal-body">
                <?php
                //pop-up de création de formulaire
                echo $this->Form->create('Formulaire', array('action' => 'addFirst'));
                echo '<div class="row form-group">';
                //champ nom du formulaire *
                echo $this->Form->input('libelle', array(
                    'class' => 'form-control',
                    'placeholder' => __d('formulaire', 'formulaire.popupPlaceholderNomFormulaire'),
                    'label' => array(
                        'text' => __d('formulaire', 'formulaire.popupNomFormulaire') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'required' => true
                ));
                echo '</div>';

                echo '<div class="row form-group">';
                //Champ Description
                echo $this->Form->input('description', array(
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'placeholder' => __d('formulaire', 'formulaire.popupPlaceholderDescription'),
                    'label' => array(
                        'text' => __d('formulaire', 'formulaire.popupDescription'),
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'required' => false
                ));
                echo '</div>'
                ?>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button type="button" class="btn btn-default-default" data-dismiss="modal"><i
                            class="fa fa-times-circle fa-lg"></i><?php echo __d('default', 'default.btnAnnuler'); ?>
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

<!-- Pop-up de duplication d'un formulaire -->
<div class="modal fade" id="modalDupliquer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="myModalLabel">
                    <?php echo __d('formulaire', 'formulaire.popupInfoGeneraleFormulaireDuplication'); ?>
                </h4>
            </div>
            
            <div class="modal-body">
                <div class="row form-group">
                    <?php
                    echo $this->Form->create('Formulaire', array('action' => 'dupliquer'));

                    echo $this->Form->input('id', array("id" => "FormulaireId", "value" => 0));
                    //echo $this->Form->input('id', array("value" => $data['Formulaire']['id']));

                    // Champ nom du formulaire *
                    echo $this->Form->input('libelle', array(
                        'class' => 'form-control',
                        'placeholder' => __d('formulaire', 'formulaire.popupPlaceholderNomFormulaire'),
                        'label' => array(
                            'text' => __d('formulaire', 'formulaire.popupNomFormulaire') . '<span class="requis">*</span>',
                            'class' => 'col-md-4 control-label'
                        ),
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'required' => true
                    ));
                    ?>
                </div>

                <div class="row form-group">
                    <?php
                    // Champ Description
                    echo $this->Form->input('description', array(
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'placeholder' => __d('formulaire', 'formulaire.popupPlaceholderDescription'),
                        'label' => array(
                            'text' => __d('formulaire', 'formulaire.popupDescription'),
                            'class' => 'col-md-4 control-label'
                        ),
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'required' => false
                    ));
                    ?>
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

<!-- Pop-up de duplication d'un formulaire dans une autre organisation -->
<div class="modal fade" id="modalDupliquerFormulaireOrganisation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="myModalLabel">
                    <?php echo __d('formulaire', 'formulaire.popupInfoGeneraleFormulaireDuplicationOrganisation'); ?>
                </h4>
            </div>
            
            <div class="modal-body">
                <div class="row form-group">
                    <?php
                    echo $this->Form->create('Formulaire', array('action' => 'dupliquerOrganisation'));

                    echo $this->Form->input('id', array("id" => "FormulaireOrganisationId" ,"value" => 0));

                    // Champ nom du formulaire *
                    echo $this->Form->input('libelle', array(
                        'class' => 'form-control',
                        'placeholder' => __d('formulaire', 'formulaire.popupPlaceholderNomFormulaire'),
                        'label' => array(
                            'text' => __d('formulaire', 'formulaire.popupNomFormulaire') . '<span class="requis">*</span>',
                            'class' => 'col-md-4 control-label'
                        ),
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'required' => true
                    ));
                    ?>
                </div>

                <div class="row form-group">
                    <?php
                    // Champ Description
                    echo $this->Form->input('description', array(
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'placeholder' => __d('formulaire', 'formulaire.popupPlaceholderDescription'),
                        'label' => array(
                            'text' => __d('formulaire', 'formulaire.popupDescription'),
                            'class' => 'col-md-4 control-label'
                        ),
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'required' => false
                    ));
                    ?>
                </div>
                
                <div class="row form-group">
                    <?php
                    //Champ Entité cible pour la duplication*
                    echo $this->Form->input('organisationCible', [
                        'class' => 'form-control',
                        'label' => [
                            'text' => __d('formulaire', 'formulaire.popupChampEntiteCible') . '<span class="requis">*</span>',
                            'class' => 'col-md-4 control-label'
                        ],
                        'options' => $listeOrganisations,
                        'empty' => true,
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'required' => true
                    ]);
                    ?>
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

<script type="text/javascript">
    
    $(document).ready(function () {


        $(".btn_duplicate").click(function () {
            var valueId = $(this).val();
            $('#FormulaireId').val(valueId);
        });
        
        $(".btn_duplicateFormulaireOrganisation").click(function () {
          
            var valueId = $(this).val();
          
        $('#FormulaireOrganisationId').val(valueId);
        });

    });
    
</script>