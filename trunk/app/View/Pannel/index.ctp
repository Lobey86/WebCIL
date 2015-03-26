<?php
echo $this->Html->script('pannel.js');
?>
<div class="well">
    <h1>Mon panneau de gestion</h1>
</div>
<?php
if($this->Autorisation->authorized(1, $droits)){
    ?>
    <div class="page-header" id="headerVosFiches">
        <h2 class="h2Deroulant">Mes fiches <small><span class="glyphicon glyphicon-chevron-up pull-left" id="caretVosFiches"></span></small></h2>
    </div>
    <div id="vosFiches">
        <ul class="nav nav-tabs" id="tabsVosFiches">
            <li class="active" id="liEnCoursRedaction"><a href="#" id="aEnCoursRedaction" onclick="return false;">En cours de rédaction <span class="badge"><?php echo count($encours); ?></span></a></li>
            <li id="liEnCoursValidation"><a href="#" id="aEnCoursValidation" onclick="return false;">En cours de validation <span class="badge"><?php echo count($encoursValidation); ?></span></a></li>
            <li id="liSignees"><a href="#" id="aSignees" onclick="return false;">Validées <span class="badge"><?php echo count($validees); ?></span></a></li>
            <li id="liARevoir"><a href="#" id="aARevoir" onclick="return false;">Refusées <span class="badge"><?php echo count($refusees); ?></span></a></li>
        </ul>
        <div id="listEnCoursRedaction">
            <?php
            if(!empty($encours)){
                ?>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="thleft">
                                Nom de l'outil
                            </th>
                            <th class="thleft">
                                Création
                            </th>
                            <th class="thleft">
                                Dernière modification
                            </th>
                            <th class="thleft">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($encours as $donnee){
                            ?>
                            <tr>
                                <td class='tdleft'>
                                    <?php echo $donnee['Fiche']['outilnom']; ?>
                                </td>
                                <td class='tdleft'>
                                    <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?></i>
                                </td>
                                <td class='tdleft'>
                                    <?php echo $this->Time->format($donnee['Fiche']['modified'], '%e-%m-%Y'); ?>
                                </td>
                                <td class='tdleft'>
                                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('controller'=>'fiches', 'action'=>'show', $donnee['Fiche']['id']), array('class'=>'btn btn-default boutonShow boutonsAction5', 'escapeTitle'=>false)).$this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', array('controller'=>'fiches', 'action'=>'edit', $donnee['Fiche']['id']), array('class'=>'btn btn-default boutonEdit boutonsAction5', 'escapeTitle'=>false)); ?>
                                    <span class='dropdown'>
                                        <button class='btn btn-default dropdown-toggle boutonSend' type='button' id='dropdownMenu1' data-toggle='dropdown'>
                                            <span class='glyphicon glyphicon-send'></span>
                                            <span class='caret'></span>
                                        </button>
                                        <ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu1'>
                                            <li role='presentation'><a role='menuitem' tabindex='-1' href='#' class='envoiConsult' value='<?php echo $donnee['Fiche']['id']; ?>'>Envoyer pour consultation</a></li>
                                            <li role='presentation'><a role='menuitem' tabindex='-1' href='#' class='envoiValid'  value='<?php echo $donnee['Fiche']['id']; ?>'>Envoyer pour validation</a></li>
                                            <li role='presentation'><?php echo $this->Html->link('Envoyer au CIL pour clôture', array('controller'=>'pannel', 'action'=>'test'), array('role'=>'menuitem', 'tabindex'=>'-1')); ?></li>
                                        </ul>
                                    </span>
                                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array('controller'=>'fiches', 'action'=>'delete', $donnee['Fiche']['id']), array('class'=>'btn btn-danger boutonDelete boutonsAction15', 'escapeTitle'=>false), 'Voulez vous supprimer la fiche de '.$donnee['Fiche']['outilnom'].'?'); ?>
                                </td>
                            </tr>
                            <tr class='selectConsultDest<?php echo $donnee['Fiche']['id']; ?>'>
                                <td></td>
                                <td></td>
                                <td colspan='2' class='tdleft'>
                                   <?php
                                   echo $this->Form->create('EtatFiche', $options = array('action'=>'askAvis'));
                                   echo $this->Form->input('destinataire', array('options' => $validants, 'class'=>'usersDeroulant transformSelect', 'empty'=>'Selectionnez un utilisateur', 'label'=>false));
                                   echo $this->Html->link('Annuler', '#', array('class' =>'btn btn-danger pull-right btnDivSend sendCancel'));
                                   echo $this->Form->hidden('ficheNum', array('value'=>$donnee['Fiche']['id'])); 
                                   echo $this->Form->hidden('etatFiche', array('value'=>$donnee['EtatFiche']['id']));
                                   echo $this->Form->buton('Envoyer', array('type'=>'submit', 'class'=>'btn btn-success pull-right btnDivSend')); 
                                   echo $this->Form->end();
                                   ?>
                               </td>
                           </tr>
                           <tr class='selectValidDest<?php echo $donnee['Fiche']['id']; ?>'>
                            <td></td>
                            <td></td>
                            <td colspan='2' class='tdleft'>
                                <?php

                                echo $this->Form->create('EtatFiche', $options = array('action'=>'sendValidation'));
                                echo $this->Form->input('destinataire', array('options' => $validants, 'class'=>'usersDeroulant transformSelect', 'empty'=>'Selectionnez un utilisateur', 'label'=>false));
                                echo $this->Html->link('Annuler', '#', array('class' =>'btn btn-danger pull-right btnDivSend sendCancel'));
                                echo $this->Form->hidden('ficheNum', array('value'=>$donnee['Fiche']['id'])); 
                                echo $this->Form->hidden('etatFiche', array('value'=>$donnee['EtatFiche']['id']));
                                echo $this->Form->buton('Envoyer', array('type'=>'submit', 'class'=>'btn btn-success pull-right btnDivSend')); 
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
        }
        else{

            echo "<div class='text-center'><h3>Vous n'avez aucune fiche <small>en cours de rédaction</small></h3></div>";
        }
        ?>
        <div class="btn-group pull-right">
            <?php echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> Ajouter une fiche', array('controller'=>'fiches', 'action'=>'add'), array('class'=>'btn btn-primary', 'escapeTitle'=>false)); ?>
        </div>
    </div>
    <div id="listEnCoursValidation">
        <?php
        if(!empty($encoursValidation)){
            ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="thleft">
                            Nom de l'outil
                        </th>
                        <th class="thleft">
                            Création
                        </th>
                        <th class="thleft">
                            Statut
                        </th>
                        <th class="thleft">
                            Actions
                        </th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($encoursValidation as $donnee){
                        ?>

                        <tr id='ligneValidation<?php echo $donnee['Fiche']['id']; ?>'>
                            <td class='tdleft'>
                                <?php echo $donnee['Fiche']['outilnom']; ?>
                            </td>
                            <td class='tdleft'>
                                <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?><i> par <?php echo $donnee['Fiche']['User']['prenom']." ".$donnee['Fiche']['User']['nom']; ?></i>
                            </td>
                            <td class='tdleft'>
                                En attente de validation<i> par <?php echo $donnee['User']['prenom'].' '.$donnee['User']['nom']; ?></i>
                            </td>
                            <td class='tdleft'>
                                <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('controller'=>'fiches', 'action'=>'show', $donnee['Fiche']['id']), array('class'=>'btn btn-default boutonShow boutonsAction5', 'escapeTitle'=>false)); ?>
                                <button type='button' class='btn btn-default boutonList boutonsAction5' value='<?php echo $donnee['Fiche']['id']; ?>'>
                                    <span class='glyphicon glyphicon-list-alt'></span>
                                </button>
                                <button type='button' class='btn btn-default boutonReorienter boutonsAction5' value='<?php echo $donnee['Fiche']['id']; ?>'>
                                    <span class='glyphicon glyphicon-transfer'></span>
                                </button>
                                <?php echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array('controller'=>'fiches', 'action'=>'delete', $donnee['Fiche']['id']), array('class'=>'btn btn-danger boutonDelete boutonsAction15', 'escapeTitle'=>false), 'Voulez vous supprimer la fiche de '.$donnee['Fiche']['outilnom'].'?'); ?>
                            </td>
                        </tr>


                        <tr class='listeValidation' id='listeValidation<?php echo $donnee['Fiche']['id']; ?>'>
                            <td></td>
                            <td></td>
                            <td class='tdleft'>
                                <?php 
                                $parcours = $this->requestAction(array('controller' => 'Pannel', 'action'=>'parcours', $donnee['Fiche']['id']));
                                echo $this->element('parcours', ["parcours" => $parcours]); 
                                ?>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr class='selectDestTrans<?php echo $donnee['Fiche']['id']; ?>'>
                            <td></td>
                            <td></td>
                            <td colspan='2' class='tdleft'>
                                <?php
                                echo $this->Form->create('EtatFiche', $options = array('action'=>'reorientation'));
                                echo $this->Form->input('destinataire', array('options' => $validants, 'class'=>'usersDeroulant transformSelect', 'empty'=>'Selectionnez un utilisateur', 'label'=>false));
                                echo $this->Html->link('Annuler', '#', array('class' =>'btn btn-danger pull-right btnDivSend sendCancel'));
                                echo $this->Form->hidden('ficheNum', array('value'=>$donnee['Fiche']['id'])); 
                                echo $this->Form->hidden('etatFiche', array('value'=>$donnee['EtatFiche']['id']));
                                echo $this->Form->buton('Envoyer', array('type'=>'submit', 'class'=>'btn btn-success pull-right btnDivSend')); 
                                echo $this->Form->end();
                                ?>           
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        else{

            echo "<div class='text-center'><h3>Vous n'avez aucune fiche <small>en cours de validation</small></h3></div>";
        }
        ?>
    </div>
    <div id="listSignees">
        <?php
        if(!empty($validees)){
            ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="thleft">
                            Nom de l'outil
                        </th>
                        <th class="thleft">
                            Création
                        </th>
                        <th class="thleft">
                            Validée le
                        </th>
                        <th class="thleft">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($validees as $donnee){
                        ?>

                        <tr id='ligneValidation<?php echo $donnee['Fiche']['id']; ?>'>
                            <td class='tdleft'>
                                <?php echo $donnee['Fiche']['outilnom']; ?>
                            </td>
                            <td class='tdleft'>
                                <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?><i> par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?></i>
                            </td>
                            <td class='tdleft'>
                                <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?><i> par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?></i>
                            </td>
                            <td class='tdleft'>
                                <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('controller'=>'fiches', 'action'=>'show', $donnee['Fiche']['id']), array('class'=>'btn btn-default boutonShow boutonsAction5', 'escapeTitle'=>false)); ?>
                                <button type='button' class='btn btn-default boutonListValidee boutonsAction5' value='<?php echo $donnee['Fiche']['id']; ?>'>
                                    <span class='glyphicon glyphicon-list-alt'></span>
                                </button>
                                <button type='button' class='btn btn-default boutonDl boutonsAction5' value='<?php echo $donnee['Fiche']['id']; ?>'>
                                    <img src="img/pdf.png" class="glyph"/>
                                </button>
                                <button type="button" class="btn btn-danger boutonsAction15 boutonArchive">
                                    <span class="glyphicon glyphicon-lock"></span>
                                </button>
                            </td>
                        </tr>
                        <tr class='listeValidee' id='listeValidee<?php echo $donnee['Fiche']['id']; ?>'>
                            <td></td>
                            <td></td>
                            <td class='tdleft'>
                                <?php 
                                $parcours = $this->requestAction(array('controller' => 'Pannel', 'action'=>'parcours', $donnee['Fiche']['id']));
                                echo $this->element('parcours', ["parcours" => $parcours]); 
                                ?>

                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr class='completion'></tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        else{
            echo "<div class='text-center'><h3>Vous n'avez aucune fiche <small>validée</small></h3></div>";
        }
        ?>
    </div>
    <div id="listARevoir">
        <?php
        if(!empty($refusees)){
            ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="thleft">
                            Nom de l'outil
                        </th>
                        <th class="thleft">
                            Création
                        </th>
                        <th class="thleft">
                            Refusée le
                        </th>
                        <th class="thleft">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($refusees as $donnee){
                        ?>
                        <tr id='ligneValidation<?php echo $donnee['Fiche']['id']; ?>'>
                            <td class='tdleft'>
                                <?php echo $donnee['Fiche']['outilnom']; ?>
                            </td>
                            <td class='tdleft'>
                                <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?><i> par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?></i>
                            </td>
                            <td class='tdleft'>
                                <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?><i> par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?></i>
                            </td>
                            <td class='tdleft'>
                                <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('controller'=>'fiches', 'action'=>'show', $donnee['Fiche']['id']), array('class'=>'btn btn-default boutonShow boutonsAction5', 'escapeTitle'=>false)); ?>
                                <button type='button' class='btn btn-default boutonListRefusee boutonsAction5' value='<?php echo $donnee['Fiche']['id']; ?>'><span class='glyphicon glyphicon-list-alt'></span></button>
                                <?php echo $this->Html->link('<span class="glyphicon glyphicon-repeat"></span>', array('controller'=>'EtatFiches', 'action'=>'relaunch', $donnee['Fiche']['id']), array('class'=>'btn btn-default boutonRelancer boutonsAction5', 'escapeTitle'=>false)); ?>
                                <?php echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array('controller'=>'fiches', 'action'=>'delete', $donnee['Fiche']['id']), array('class'=>'btn btn-danger boutonDelete boutonsAction15', 'escapeTitle'=>false), 'Voulez vous supprimer la fiche de '.$donnee['Fiche']['outilnom'].'?'); ?>
                            </td>
                        </tr>
                        <tr class='listeRefusee' id='listeRefusee<?php echo $donnee['Fiche']['id']; ?>'>
                            <td></td>
                            <td></td>
                            <td class='tdleft'>
                                <?php 
                                $parcours = $this->requestAction(array('controller' => 'Pannel', 'action'=>'parcours', $donnee['Fiche']['id']));
                                echo $this->element('parcours', ["parcours" => $parcours]); 
                                ?>

                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr class='completion'></tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        else{

            echo "<div class='text-center'><h3>Vous n'avez aucune fiche <small>refusée</small></h3></div>";
        }
        ?>
    </div>
</div>
<?php
}
if($this->Autorisation->authorized(array('2', '3'), $droits)){
    $compte=count($dmdAvis) + count($dmdValid);
    ?>
    <div class="page-header" id="headerFichesAControler">
        <h2 class="h2Deroulant">
            Les fiches reçues <?php if($compte >0){echo'<span class="badge">'.$compte.'</span>';} ?><small><span class="glyphicon glyphicon-chevron-down pull-left" id="caretFichesAControler"></span></small>
        </h2>
    </div>
    <div id="fichesAControler">
        <ul class="nav nav-tabs" id="tabsVosFiches">
            <?php if($this->Autorisation->authorized(2, $droits)){ ?>
            <li class="active" id="liDemandeValidation"><a href="#" id="aDemandeValidation" onclick="return false;">Demande de validation <span class="badge"><?php echo count($dmdValid); ?></span></a></li><?php } ?>
            <?php if($this->Autorisation->authorized(3, $droits)){ ?>
            <li id="liDemandeAvis"><a href="#" id="aDemandeAvis" onclick="return false;">Demande d'avis <span class="badge"><?php echo count($dmdAvis); ?></span></a></li>
            <?php } ?>
        </ul>
        <?php if($this->Autorisation->authorized(2, $droits)){ ?>
        <div id="listDemandeValidation">
            <?php
            if(!empty($dmdValid)){
                ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="thleft">
                                Nom de l'outil
                            </th>
                            <th class="thleft">
                                Création
                            </th>
                            <th class="thleft">
                                Statut
                            </th>
                            <th class="thleft">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($dmdValid as $donnee){
                            ?>
                            <tr id='ligneAValider<?php echo $donnee['Fiche']['id']; ?>'>
                                <td class='tdleft'>
                                    <?php echo $donnee['Fiche']['outilnom']; ?>
                                </td>
                                <td class='tdleft'>
                                    <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?><i> par <?php echo $donnee['Fiche']['User']['prenom']." ".$donnee['Fiche']['User']['nom']; ?></i>
                                </td>
                                <td class='tdleft'>
                                    Validation demandée <i> par <?php echo $donnee['PreviousUser']['prenom'].' '.$donnee['PreviousUser']['nom']; ?></i>
                                </td>
                                <td class='tdleft'>
                                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('controller'=>'fiches', 'action'=>'show', $donnee['Fiche']['id']), array('class'=>'btn btn-default boutonShow boutonsAction5', 'escapeTitle'=>false)); ?>
                                    <button type='button' class='btn btn-default boutonListAValider boutonsAction5' value='<?php echo $donnee['Fiche']['id']; ?>'>
                                        <span class='glyphicon glyphicon-list-alt'></span>
                                    </button>
                                    <span class='dropdown'>
                                        <button class='btn btn-success dropdown-toggle boutonValider boutonsAction15' type='button' id='dropdownMenuValider' data-toggle='dropdown'>
                                            <span class='glyphicon glyphicon-ok'></span><span class='caret'></span>
                                        </button>
                                        <ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenuValider'>
                                            <li role='presentation'><a role='menuitem' tabindex=-1 href='#' class='envoiConsultValider' value='<?php echo $donnee['Fiche']['id']; ?>' onclick="return false;">Envoyer pour consultation</a></li>
                                            <li role='presentation'><a role='menuitem' tabindex='-1' href='#' class='envoiValidValider'  value='<?php echo $donnee['Fiche']['id']; ?>' onclick="return false;">Envoyer pour validation</a></li>
                                            <li role='presentation'><?php echo $this->Html->link('Envoyer au CIL pour clôture', array('controller'=>'pannel', 'action'=>'test'), array('role'=>'menuitem', 'tabindex'=>'-1')); ?></li>
                                        </ul>
                                    </span>
                                    <button type='button' class='btn btn-danger boutonRefuser boutonsAction5' value='<?php echo $donnee['Fiche']['id']; ?>'>
                                        <span class='glyphicon glyphicon-remove'></span>
                                    </button>
                                </td>
                            </tr>
                            <tr class='listeAValider' id='listeAValider<?php echo $donnee['Fiche']['id']; ?>'>
                                <td></td>
                                <td></td>
                                <td class='tdleft'>
                                    <?php 
                                    $parcours = $this->requestAction(array('controller' => 'Pannel', 'action'=>'parcours', $donnee['Fiche']['id']));
                                    echo $this->element('parcours', ["parcours" => $parcours]); 
                                    ?>

                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class='selectDestValidValider<?php echo $donnee['Fiche']['id']; ?> selectorDestValidValider'>
                                <td></td>
                                <td></td>
                                <td colspan='2' class='tdleft'>
                                    <?php
                                    echo $this->Form->create('EtatFiche', $options = array('action'=>'sendValidation'));
                                    echo $this->Form->input('destinataire', array('options' => $validants, 'class'=>'usersDeroulant transformSelect', 'empty'=>'Selectionnez un utilisateur', 'label'=>false));
                                    echo $this->Html->link('Annuler', '#', array('class' =>'btn btn-danger pull-right btnDivSend sendCancel'));
                                    echo $this->Form->hidden('ficheNum', array('value'=>$donnee['Fiche']['id'])); 
                                    echo $this->Form->hidden('etatFiche', array('value'=>$donnee['EtatFiche']['id']));
                                    echo $this->Form->buton('Envoyer', array('type'=>'submit', 'class'=>'btn btn-success pull-right btnDivSend')); 
                                    echo $this->Form->end();
                                    ?>                           
                                </td>
                            </tr>
                            <tr class='selectDestConsultValider<?php echo $donnee['Fiche']['id']; ?> selectorDestConsultValider'>
                                <td></td>
                                <td></td>
                                <td colspan='2' class='tdleft'>
                                   <?php

                                   echo $this->Form->create('EtatFiche', $options = array('action'=>'askAvis'));
                                   echo $this->Form->input('destinataire', array('options' => $validants, 'class'=>'usersDeroulant transformSelect', 'empty'=>'Selectionnez un utilisateur', 'label'=>false));
                                   echo $this->Html->link('Annuler', '#', array('class' =>'btn btn-danger pull-right btnDivSend sendCancel'));
                                   echo $this->Form->hidden('ficheNum', array('value'=>$donnee['Fiche']['id'])); 
                                   echo $this->Form->hidden('etatFiche', array('value'=>$donnee['EtatFiche']['id']));
                                   echo $this->Form->buton('Envoyer', array('type'=>'submit', 'class'=>'btn btn-success pull-right btnDivSend')); 
                                   echo $this->Form->end();
                                   ?>                      
                               </td>
                           </tr>
                           <tr class='commentaireRefus<?php echo $donnee['Fiche']['id']; ?>'>
                            <td></td>
                            <td></td>
                            <td colspan='2' class='tdleft'>
                                <?php 
                                echo $this->Form->create('EtatFiche', $options = array('action'=>'refuse'));
                                echo $this->Form->input('content', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Expliquez les raisons de votre refus</span>', 'class'=>'form-control', 'type'=>'textarea')); 
                                echo $this->Html->link('Annuler', '#', array('class' =>'btn btn-danger pull-right btnDivSend sendCancel'));
                                echo $this->Form->hidden('ficheNum', array('value'=>$donnee['Fiche']['id'])); 
                                echo $this->Form->hidden('etatFiche', array('value'=>$donnee['EtatFiche']['id']));
                                echo $this->Form->buton('Envoyer', array('type'=>'submit', 'class'=>'btn btn-success pull-right btnDivSend')); 
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
        }
        else{

            echo "<div class='text-center'><h3>Vous n'avez aucune fiche <small>à valider</small></h3></div>";
        }
        ?>
    </div>
    <?php }
    if($this->Autorisation->authorized(3, $droits)){ ?>
    <div id="listDemandeAvis">
        <?php
        if(!empty($dmdAvis)){
            ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="thleft">
                            Nom de l'outil
                        </th>
                        <th class="thleft">
                            Création
                        </th>
                        <th class="thleft">
                            Statut
                        </th>
                        <th class="thleft">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($dmdAvis as $key => $donnee) {
                        ?>
                        <tr id='ligneAValider<?php echo $donnee['Fiche']['id']; ?>'>
                            <td class='tdleft'>
                                <?php echo $donnee['Fiche']['outilnom']; ?>
                            </td>
                            <td class='tdleft'>
                                <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?><i> par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?></i>
                            </td>
                            <td class='tdleft'>
                                Avis demandé par<i> par <?php echo $donnee['PreviousUser']['prenom']." ".$donnee['PreviousUser']['nom']; ?></i>
                            </td>
                            <td class='tdleft'>
                                <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('controller'=>'fiches', 'action'=>'show', $donnee['Fiche']['id']), array('class'=>'btn btn-default boutonShow boutonsAction5', 'escapeTitle'=>false)); ?>
                                <button type='button' class='btn btn-default boutonRepondre boutonsAction5' value='<?php echo $donnee['Fiche']['id']; ?>'><span class='glyphicon glyphicon-share-alt'></span></button>
                            </td>
                        </tr>
                        <tr class='commentaireRepondre<?php echo $donnee['Fiche']['id']; ?>'><td></td><td></td><td colspan='2' class='tdleft'>
                            <?php
                            echo $this->Form->create('EtatFiche', $options = array('action'=>'answerAvis')); 
                            echo $this->Form->input('commentaireRepondre', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Donnez votre avis</span>', 'class'=>'form-control', 'type'=>'textarea')); 
                            echo $this->Form->hidden('etatFiche', array('value'=>$donnee['EtatFiche']['id']));
                            echo $this->Form->hidden('previousUserId', array('value'=>$donnee['EtatFiche']['previous_user_id']));
                            echo $this->Form->hidden('ficheNum', array('value'=>$donnee['Fiche']['id']));
                            echo $this->Html->link('Annuler', '#', array('class'=>'btn btn-danger pull-right btnDivSend repondreCancel', 'onClick'=>'return false')); 
                            echo $this->Form->buton('Envoyer', array('type'=>'submit', 'class'=>'btn btn-success pull-right btnDivSend')); 
                            echo $this->Form->end();?>
                        </tr>
                        <tr class='completion'></tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        else{

            echo "<div class='text-center'><h3>Vous n'avez aucune demande <small>d'avis</small></h3></div>";
        }
        ?>
    </div>
    <?php } ?>
</div>
<?php
}
?>