<ul class="list-group list-parcours">
    <?php
    foreach ($parcours as $value) {
        switch ($value['EtatFiche']['etat_id']) {
            //Rectangle bleu Rédaction
            case 1:
                ?>
                <div class='bg-info tuilesStatuts col-md-10 col-md-offset-1'>
                    <div class='text-center'>
                        <h3>
                            <b>
                                <?php echo __d('element', 'element.Redaction'); ?>
                            </b>
                        </h3>
                    </div>
                    <div class='tuilesStatutsNom'>
                        <?php echo __d('element', 'element.CreePar'); ?>
                        <b>
                            <?php echo $value['User']['prenom'] . " " . $value['User']['nom']; ?>
                        </b> 
                        <?php echo __d('element', 'element.Le'); ?> 
                        <b>
                            <?php echo $this->Time->format($value['Fiche']['created'], FORMAT_DATE_HEURE); ?>
                        </b>

                    </div>
                    <?php
                    if (!empty($value['Commentaire'])) {
                        ?>
                        <div>
                            <br/>
                            <hr class='hrComms'/>
                            <div class='text-center'>
                                <h4>
                                    <?php echo __d('element', 'element.Commentaire'); ?>
                                </h4>
                            </div>
                            <?php
                            foreach ($value['Commentaire'] as $val) {
                                ?>
                                <div  style="margin-left: 60px; ">
                                    <p>
                                        <?php echo $val['content']; ?>
                                    </p>
                                    <footer>
                                            <?php echo __d('element', 'element.CommenterPar'); ?>
                                            <b>
                                                <?php echo $val['User']['prenom'] . " " . $val['User']['nom']; ?>
                                            </b>
                                            <?php echo __d('element', 'element.Le'); ?> 
                                            <b>
                                                <?php echo $this->Time->format($value['Fiche']['created'], FORMAT_DATE_HEURE); ?>
                                            </b>
                                    </footer>
                                </div>
                                <br/>
                                <hr class='hrComms'/>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                break;

            //Rectangle orange En attente de validation
            case 2:
                ?>
                <div class='bg-warning tuilesStatuts col-md-10 col-md-offset-1'>
                    <div class='text-center'>
                        <h3>
                            <b>
                                <?php echo __d('element', 'element.AttenteValidation'); ?>
                            </b>
                        </h3>
                    </div>
                    <div class='tuilesStatutsNom'>
                        <?php echo __d('element', 'element.RecuePar'); ?> 
                        <b>
                            <?php echo $value['User']['prenom'] . " " . $value['User']['nom']; ?>
                        </b>
                        <?php echo __d('element', 'element.Le'); ?> 
                        <b>
                            <?php echo $this->Time->format($value['EtatFiche']['created'], FORMAT_DATE_HEURE); ?>
                        </b>
                    </div>
                    <?php
                    if (!empty($value['Commentaire'])) {
                        $idUserCommentaire = [];
                        ?>
                        <div>
                            <br/>
                            <hr class='hrComms'/>
                            <div class='text-center'>
                                <h4>
                                    <?php echo __d('element', 'element.Commentaire'); ?>
                                </h4>
                            </div>
                            <?php
                            foreach ($value['Commentaire'] as $val) {
                                if ($value['User']['id'] != $val['user_id'] && !in_array($val['user_id'], $idUserCommentaire)) {
                                    $idUserCommentaire[$val['user_id']] = $val['user_id'];
                                }
                                ?>
                                <div>
                                    <p>
                                        <?php echo $val['content']; ?>
                                    </p>
                                    <footer>
                                        <?php echo __d('element', 'element.CommenterPar'); ?>
                                        <b>
                                            <?php echo $val['User']['prenom'] . " " . $val['User']['nom']; ?>
                                        </b>
                                        <?php echo __d('element', 'element.Le'); ?> 
                                        <b>
                                            <?php echo $this->Time->format($val['created'], FORMAT_DATE_HEURE); ?>
                                        </b>
                                    </footer>
                                </div>
                                <br/>
                                <hr class='hrComms'/>
                                <?php
                            }
                            ?>     
                            
                            <!--Bouton de réponse à un commentaire-->
                            <div class="row bottom10">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-default-primary" data-toggle="modal" data-target="#AddCommentaire">
                                        <?php echo("Répondre au commentaire");/*echo __d('formulaire', 'formulaire.btnCreerFormulaire');*/ ?>
                                    </button>
                                </div>
                            </div>

                            <div class="modal fade" id="AddCommentaire" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title" id="myModalLabel">
                                                <?php echo("Ajouter un commentaire");/*echo __d('formulaire', 'formulaire.popupInfoGeneraleFormulaire');*/ ?>
                                            </h4>
                                        </div>

                                        <div class="modal-body">
                                            <?php
                                            //pop-up de création de formulaire
                                            echo $this->Form->create('EtatFiche', array(
                                                'action' => 'repondreCommentaire'
                                            ));
                                            
                                            echo '<div class="row form-group">';
                                            //Champ Description
                                            echo $this->Form->input('commentaire', array(
                                                'type' => 'textarea',
                                                'class' => 'form-control',
                                                'placeholder' => __d('formulaire', 'formulaire.popupPlaceholderDescription'),
                                                'label' => array(
                                                    'text' => /*__d('formulaire', 'formulaire.popupDescription')*/ "Commentaire" .' '. '<span class="requis">*</span>',
                                                    'class' => 'col-md-4 control-label'
                                                ),
                                                'between' => '<div class="col-md-8">',
                                                'after' => '</div>',
                                                'required' => true
                                            ));
                                            
                                            echo $this->Form->input('idUserCommentaire', array(
                                                'type' => 'hidden',
                                                'value' => json_encode($idUserCommentaire)
                                            ));
                                            
                                            echo $this->Form->input('etat_fiche_id', array(
                                                'type' => 'hidden',
                                                'value' => $value['EtatFiche']['id']
                                            ));
                                            
                                            echo $this->Form->input('fiche_id', array(
                                                'type' => 'hidden',
                                                'value' => $value['EtatFiche']['fiche_id']
                                            ));
                                            
                                            echo '</div>'
                                            ?>
                                        </div>

                                        <div class="modal-footer">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default-default" data-dismiss="modal"><i
                                                        class="fa fa-arrow-left"></i><?php echo __d('default', 'default.btnAnnuler'); ?>
                                                </button>
                                                <?php
                                                echo $this->Form->button("<i class='fa fa-check'></i>" . __d('default', 'default.btnEnregistrer'), array(
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





                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                break;

            //Rectangle vert Validée    
            case 3:
                ?>
                <div class='bg-success tuilesStatuts col-md-10 col-md-offset-1'>
                    <div class='text-center'>
                        <h3>
                            <b>
                                <?php echo __d('element', 'element.Validee'); ?>
                            </b>
                        </h3>
                    </div>
                    <div class='tuilesStatutsNom'>
                        <?php echo __d('element', 'element.ValideePar'); ?>   
                        <b>
                            <?php echo $value['User']['prenom'] . " " . $value['User']['nom']; ?>
                        </b> 
                        <?php echo __d('element', 'element.Le'); ?>  
                        <b>
                            <?php echo $this->Time->format($value['EtatFiche']['modified'], FORMAT_DATE_HEURE); ?>
                        </b>
                    </div>
                    <?php
                    if (!empty($value['Commentaire'])) {
                        ?>
                        <div>
                            <br/>
                            <hr class='hrComms'/>
                            <div class='text-center'>
                                <h4>
                                    <?php echo __d('element', 'element.Commentaire'); ?>
                                </h4>
                            </div>
                            <?php
                            foreach ($value['Commentaire'] as $val) {
                                ?>
                                <div>
                                    <p><?php echo $val['content']; ?></p>
                                    <footer>
                                        <?php echo __d('element', 'element.CommenterPar'); ?>
                                        <b>
                                            <?php echo $val['User']['prenom'] . " " . $val['User']['nom']; ?>
                                        </b>
                                    </footer>
                                </div>
                                <br/>
                                <hr class='hrComms'/>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                break;

            //Rectangle rouge Refusée    
            case 4:
                ?>
                <div class='bg-danger tuilesStatuts col-md-10 col-md-offset-1'>
                    <div class='text-center'>
                        <h3>
                            <b>
                                <?php echo __d('element', 'element.Refusee'); ?>
                            </b>
                        </h3>
                    </div>
                    <div class='tuilesStatutsNom'>
                        <?php echo __d('element', 'element.RefuseePar'); ?>
                        <b>
                            <?php echo $value['User']['prenom'] . " " . $value['User']['nom']; ?>
                        </b> 
                        <?php echo __d('element', 'element.Le'); ?>   
                        <b>
                            <?php echo $this->Time->format($value['Fiche']['created'], FORMAT_DATE_HEURE); ?>
                        </b>
                    </div>
                    <?php
                    if (!empty($value['Commentaire'])) {
                        ?>
                        <div>
                            <br/>
                            <hr class='hrComms'/>
                            <div class='text-center'>
                                <h4>
                                    <?php echo __d('element', 'element.Commentaire'); ?>
                                </h4>
                            </div>
                            <?php
                            foreach ($value['Commentaire'] as $val) {
                                ?>
                                <div>
                                    <p>
                                        <?php echo $val['content']; ?>
                                    </p>
                                    <footer>
                                        <?php echo __d('element', 'element.CommenterPar'); ?>
                                        <b>
                                            <?php echo $val['User']['prenom'] . " " . $val['User']['nom']; ?>
                                        </b>
                                    </footer>
                                </div>
                                <br/>
                                <hr class='hrComms'/>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                break;

            //Rectangle Validéé Inseréé dans le registre    
            case 5:
                ?>
                <div class='bg-success tuilesStatuts col-md-10 col-md-offset-1'>
                    <div class='text-center'>
                        <h3>
                            <b>
                                <?php echo __d('element', 'element.ValideeInsereeRegistre'); ?>
                            </b>
                        </h3>
                    </div>
                    <div class='tuilesStatutsNom'>
                        <?php echo __d('element', 'element.ValideePar'); ?>
                        <b>
                            <?php echo $value['User']['prenom'] . " " . $value['User']['nom']; ?>
                        </b> 
                        <?php echo __d('element', 'element.Le'); ?>   
                        <b>
                            <?php echo $this->Time->format($value['EtatFiche']['modified'], FORMAT_DATE_HEURE); ?>
                        </b>
                    </div>
                    <?php
                    if (!empty($value['Commentaire'])) {
                        ?>
                        <div>
                            <br/>
                            <hr class='hrComms'/>
                            <div class='text-center'>
                                <h4>
                                    <?php echo __d('element', 'element.Commentaire'); ?>
                                </h4>
                            </div>
                            <?php
                            foreach ($value['Commentaire'] as $val) {
                                ?>
                                <div>
                                    <p>
                                        <?php echo $val['content']; ?>
                                    </p>
                                    <footer>
                                        <?php echo __d('element', 'element.CommenterPar'); ?>
                                        <b>
                                            <?php echo $val['User']['prenom'] . " " . $val['User']['nom']; ?>
                                        </b>
                                    </footer>
                                </div>
                                <br/>
                                <hr class='hrComms'/>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                break;

            //Rectangle orange En attente de consultation
            case 6:
                ?>
                <div class='bg-warning tuilesStatuts col-md-10 col-md-offset-1'>
                    <div class='text-center'>
                        <h3>
                            <b>
                                <?php echo __d('element', 'element.AttenteConsultation'); ?>
                            </b>
                        </h3>
                    </div>
                    <div class='tuilesStatutsNom'>
                        <?php echo __d('element', 'element.RecuePar'); ?> 
                        <b>
                            <?php echo $value['User']['prenom'] . " " . $value['User']['nom']; ?>
                        </b>
                        <?php echo __d('element', 'element.Le'); ?> 
                        <b>
                            <?php echo $this->Time->format($value['EtatFiche']['created'], FORMAT_DATE_HEURE); ?>
                        </b>
                    </div>
                    <?php
                    if (!empty($value['Commentaire'])) {
                        ?>
                        <div>
                            <br/>
                            <hr class='hrComms'/>
                            <div class='text-center'>
                                <h4>
                                    <?php echo __d('element', 'element.Commentaire'); ?>
                                </h4>
                            </div>
                            <?php
                            foreach ($value['Commentaire'] as $val) {
                                ?>
                                <div>
                                    <p>
                                        <?php echo $val['content']; ?>
                                    </p>
                                    <footer>
                                        <?php echo __d('element', 'element.CommenterPar'); ?>
                                        <b>
                                            <?php echo $val['User']['prenom'] . " " . $val['User']['nom']; ?>
                                        </b>
                                    </footer>
                                </div>
                                <br/>
                                <hr class='hrComms'/>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                break;
                
            //Rectangle vert Archivée 
            case 7:
                ?>
                <div class='bg-success tuilesStatuts col-md-10 col-md-offset-1'>
                    <div class='text-center'>
                        <h3>
                            <b>
                                <?php echo __d('element', 'element.Archivee'); ?>
                            </b>
                        </h3>
                    </div>
                    <div class='tuilesStatutsNom'>
                        <?php echo __d('element', 'element.ArchiveePar'); ?>   
                        <b>
                            <?php echo $value['User']['prenom'] . " " . $value['User']['nom']; ?>
                        </b> 
                        <?php echo __d('element', 'element.Le'); ?>  
                        <b>
                            <?php echo $this->Time->format($value['EtatFiche']['modified'], FORMAT_DATE_HEURE); ?>
                        </b>
                    </div>
                    <?php
                    if (!empty($value['Commentaire'])) {
                        ?>
                        <div>
                            <br/>
                            <hr class='hrComms'/>
                            <div class='text-center'>
                                <h4>
                                    <?php echo __d('element', 'element.Commentaire'); ?>
                                </h4>
                            </div>
                            <?php
                            foreach ($value['Commentaire'] as $val) {
                                ?>
                                <div>
                                    <p><?php echo $val['content']; ?></p>
                                    <footer>
                                        <?php echo __d('element', 'element.CommenterPar'); ?>
                                        <b>
                                            <?php echo $val['User']['prenom'] . " " . $val['User']['nom']; ?>
                                        </b>
                                    </footer>
                                </div>
                                <br/>
                                <hr class='hrComms'/>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                break;    

            //Rectangle bleu Replacer en rédaction
            case 8:
                ?>
                <div class='bg-info tuilesStatuts col-md-10 col-md-offset-1'>
                    <div class='text-center'>
                        <h3>
                            <b>
                                <?php echo __d('element', 'element.ReplacerRedaction'); ?>
                            </b>
                        </h3>
                    </div>
                    <div class='tuilesStatutsNom'>
                        <?php echo __d('element', 'element.ReplacerRedactionPar') ?>
                        <b>
                            <?php echo $value['User']['prenom'] . " " . $value['User']['nom']; ?>
                        </b> 
                        <?php echo __d('element', 'element.Le'); ?> 
                        <b>
                            <?php echo $this->Time->format($value['Fiche']['created'], FORMAT_DATE_HEURE); ?>
                        </b>

                    </div>
                    <?php
                    if (!empty($value['Commentaire'])) {
                        ?>
                        <div>
                            <br/>
                            <hr class='hrComms'/>
                            <div class='text-center'>
                                <h4>
                                    <?php echo __d('element', 'element.Commentaire'); ?>
                                </h4>
                            </div>
                            <?php
                            foreach ($value['Commentaire'] as $val) {
                                ?>
                                <div>
                                    <p>
                                        <?php echo $val['content']; ?>
                                    </p>
                                    <footer>
                                        <?php echo __d('element', 'element.CommenterPar'); ?>
                                        <b>
                                            <?php echo $val['User']['prenom'] . " " . $val['User']['nom']; ?>
                                        </b>
                                        <?php echo __d('element', 'element.Le'); ?> 
                                        <b>
                                            <?php echo $this->Time->format($value['EtatFiche']['created'], FORMAT_DATE_HEURE); ?>
                                        </b>
                                    </footer>
                                </div>
                                <br/>
                                <hr class='hrComms'/>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                break;
                
             //Rectangle orange Modification du traitement inséré au registre
            case 9:
                ?>
                <div class='bg-warning tuilesStatuts col-md-10 col-md-offset-1'>
                    <div class='text-center'>
                        <h3>
                            <b>
                                <?php echo __d('element', 'element.ModificationTraitementRegistre'); ?>
                            </b>
                        </h3>
                    </div>
                    <div class='tuilesStatutsNom'>
                        <?php echo __d('element', 'element.ModificationTraitementRegistrePar'); ?> 
                        <b>
                            <?php echo $value['User']['prenom'] . " " . $value['User']['nom']; ?>
                        </b>
                        <?php echo __d('element', 'element.Le'); ?> 
                        <b>
                            <?php echo $this->Time->format($value['EtatFiche']['created'], FORMAT_DATE_HEURE); ?>
                        </b>
                    </div>
                    <?php
                    
                    if (!empty($value['Modification']['id'])) {
                        ?>
                        <div>
                            <br/>
                            <hr class='hrComms'/>
                            <div class='text-center'>
                                <h4>
                                    <?php echo __d('element', 'element.MotifModification'); ?>
                                </h4>
                            </div>
                            
                            <div>
                                <p>
                                    <?php echo $value['Modification']['modif']; ?>
                                </p>
                            </div>
                            
                            <br/>
                            <hr class='hrComms'/>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                break;

            default:
                break;
        }
    }
    ?>
</ul>