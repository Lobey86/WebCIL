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