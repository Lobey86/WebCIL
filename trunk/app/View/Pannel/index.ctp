<?php
echo $this->Html->script('pannel.js');
?>
<div class="well">
    <h1>Mon panneau de gestion</h1>
</div>
<div class="page-header" id="headerVosFiches">
    <h2 class="h2Deroulant">Mes fiches <small><span class="glyphicon glyphicon-chevron-up pull-left" id="caretVosFiches"></span></small></h2>
</div>
<div id="vosFiches">
    <ul class="nav nav-tabs" id="tabsVosFiches">
        <li class="active" id="liEnCoursRedaction"><a href="#" id="aEnCoursRedaction" onclick="return false;">En cours de rédaction <span class="badge">4</span></a></li>
        <li id="liEnCoursValidation"><a href="#" id="aEnCoursValidation" onclick="return false;">En cours de validation <span class="badge">4</span></a></li>
        <li id="liSignees"><a href="#" id="aSignees" onclick="return false;">Validées <span class="badge">4</span></a></li>
        <li id="liARevoir"><a href="#" id="aARevoir" onclick="return false;">Refusées <span class="badge">4</span></a></li>
    </ul>
    <div id="listEnCoursRedaction">
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
                        <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?><i> par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?></i>
                    </td>
                    <td class='tdleft'>
                        <?php echo $this->Time->format($donnee['Fiche']['modified'], '%e-%m-%Y'); ?><i> par </i>
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
                <tr class='selectDest<?php echo $donnee['Fiche']['id']; ?>'>
                    <td></td>
                    <td></td>
                    <td colspan='2' class='tdleft'>
                        <select data-placeholder='Choisissez un destinataire' class='usersDeroulant transformSelect'>
                            <option value=''></option>
                            <?php
                            foreach($users as $user){
                            ?>
                                <option value="<?php echo $user['User']['id']; ?>"><?php echo $user['User']['nom'].' '.$user['User']['prenom']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <?php echo $this->Form->input('typeEnvoi', array('type'    =>'hidden', 'value'=>'none', 'id'=>'typeEnvoi'.$donnee['Fiche']['id'].'')); ?>
                        <?php echo $this->Html->link('Annuler', '#', array('class' =>'btn btn-danger pull-right btnDivSend sendCancel')); ?>
                        <?php echo $this->Html->link('Envoyer', array('controller' =>'pannel', 'action'=>'test', $donnee['Fiche']['id']), array('class'=>'btn btn-success pull-right btnDivSend')); ?>
                    </td>
                </tr>
                <tr class='completion'></tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="btn-group pull-right">
            <?php echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> Ajouter une fiche', array('controller'=>'fiches', 'action'=>'add'), array('class'=>'btn btn-primary', 'escapeTitle'=>false)); ?>
        </div>
    </div>

    <div id="listEnCoursValidation">
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
                foreach($encours as $donnee){
                ?>

                <tr id='ligneValidation<?php echo $donnee['Fiche']['id']; ?>'>
                    <td class='tdleft'>
                        <?php echo $donnee['Fiche']['outilnom']; ?>
                    </td>
                    <td class='tdleft'>
                        <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?><i> par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?></i>
                    </td>
                    <td class='tdleft'>
                        En attente de validation<i> par Nom Prénom</i>
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
                        <div class='bg-info tuilesStatuts'>
                            <div class='pull-right'>
                                Rédaction
                            </div>
                            <div class='tuilesStatutsNom'>
                                Créée par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?>
                            </div>
                            <div class='tuilesStatutsDateCrea'>
                                Créée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                            <div class='tuilesStatutsDateSend'>
                                Envoyée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                        </div>
                        <div class='bg-success tuilesStatuts'>
                            <div class='pull-right'>
                                Validation
                            </div>
                            <div class='tuilesStatutsNom'>
                                Validée par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?>
                            </div>
                            <div class='tuilesStatutsDateCrea'>
                                Validée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                            <div class='tuilesStatutsDateSend'>
                                Transférée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                        </div>
                        <div class='bg-warning tuilesStatuts'>
                            <div class='pull-right'>
                                En attente de validation
                            </div>
                            <div class='tuilesStatutsNom'>
                                Reçue par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?>
                            </div>
                            <div class='tuilesStatutsDateCrea'>
                                Reçue le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class='selectDestTrans<?php echo $donnee['Fiche']['id']; ?>'>
                    <td></td>
                    <td></td>
                    <td colspan='2' class='tdleft'>
                        <select data-placeholder='Choisissez un destinataire' class='usersDeroulant transformSelect'>
                            <option value=''></option>
                            <?php
                            foreach($users as $user){
                            ?>
                            <option value="<?php echo $user['User']['id']; ?>"><?php echo $user['User']['nom'].' '.$user['User']['prenom']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <?php echo $this->Form->input('typeEnvoi', array('type'=>'hidden', 'value'=>'trans', 'id'=>'typeEnvoiTrans'.$donnee['Fiche']['id'].'')); ?>
                        <?php echo $this->Html->link('Annuler', '#', array('class'=>'btn btn-danger pull-right btnDivSend sendCancel')); ?>
                        <?php echo $this->Html->link('Envoyer', array('controller'=>'pannel', 'action'=>'test', $donnee['Fiche']['id']), array('class'=>'btn btn-success pull-right btnDivSend')); ?>

                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div id="listSignees">
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
            foreach($encours as $donnee){
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
                        <div class='bg-info tuilesStatuts'>
                            <div class='pull-right'>
                                Rédaction
                            </div>
                            <div class='tuilesStatutsNom'>
                                Créée par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?>
                            </div>
                            <div class='tuilesStatutsDateCrea'>
                                Créée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                            <div class='tuilesStatutsDateSend'>
                                Envoyée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                        </div>
                        <div class='bg-success tuilesStatuts'>
                            <div class='pull-right'>
                                Validation
                            </div>
                            <div class='tuilesStatutsNom'>
                                Validée par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?>
                            </div>
                            <div class='tuilesStatutsDateCrea'>
                                Validée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                            <div class='tuilesStatutsDateSend'>
                                Transférée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class='completion'></tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div id="listARevoir">
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
            foreach($encours as $donnee){
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
                        <?php echo $this->Html->link('<span class="glyphicon glyphicon-repeat"></span>', array('controller'=>'pannel', 'action'=>'relancer', $donnee['Fiche']['id']), array('class'=>'btn btn-default boutonRelancer boutonsAction5', 'escapeTitle'=>false)); ?>
                        <?php echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array('controller'=>'fiches', 'action'=>'delete', $donnee['Fiche']['id']), array('class'=>'btn btn-danger boutonDelete boutonsAction15', 'escapeTitle'=>false), 'Voulez vous supprimer la fiche de '.$donnee['Fiche']['outilnom'].'?'); ?>
                    </td>
                </tr>
                <tr class='listeRefusee' id='listeRefusee<?php echo $donnee['Fiche']['id']; ?>'>
                    <td></td>
                    <td></td>
                    <td class='tdleft'>
                        <div class='bg-info tuilesStatuts'>
                            <div class='pull-right'>
                                Rédaction
                            </div>
                            <div class='tuilesStatutsNom'>
                                Créée par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?>
                            </div>
                            <div class='tuilesStatutsDateCrea'>
                                Créée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                            <div class='tuilesStatutsDateSend'>
                                Envoyée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                        </div>
                        <div class='bg-success tuilesStatuts'>
                            <div class='pull-right'>
                                Validation
                            </div>
                            <div class='tuilesStatutsNom'>
                                Validée par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?>
                            </div>
                            <div class='tuilesStatutsDateCrea'>
                                Validée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                            <div class='tuilesStatutsDateSend'>
                                Transférée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                        </div>
                        <div class='bg-danger tuilesStatuts'>
                            <div class='pull-right'>
                                Refus
                            </div>
                            <div class='tuilesStatutsNom'>
                                Refusée par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?>
                            </div>
                            <div class='tuilesStatutsDateCrea'>
                                Refusée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                            <div class='tuilesStatutsDateSend'>
                                Motif: Champs importants non-remplis.
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class='completion'></tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="page-header" id="headerFichesAControler">
    <h2 class="h2Deroulant">
        Les fiches reçues<small><span class="glyphicon glyphicon-chevron-down pull-left" id="caretFichesAControler"></span></small>
    </h2>
</div>
<div id="fichesAControler">
    <ul class="nav nav-tabs" id="tabsVosFiches">
        <li class="active" id="liDemandeValidation"><a href="#" id="aDemandeValidation" onclick="return false;">Demande de validation <span class="badge">4</span></a></li>
        <li id="liDemandeAvis"><a href="#" id="aDemandeAvis" onclick="return false;">Demande d'avis <span class="badge">1</span></a></li>
    </ul>
    <div id="listDemandeValidation">
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
                foreach($encours as $donnee){
                ?>
                <tr id='ligneAValider<?php echo $donnee['Fiche']['id']; ?>'>
                    <td class='tdleft'>
                        <?php echo $donnee['Fiche']['outilnom']; ?>
                    </td>
                    <td class='tdleft'>
                        <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?><i> par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?></i>
                    </td>
                    <td class='tdleft'>
                        Validation demandée par<i> par Nom Prénom</i>
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
                                <li role='presentation'><a role='menuitem' tabindex=-1' href='#' class='envoiConsultValider' value='<?php echo $donnee['Fiche']['id']; ?>' onclick="return false;">Envoyer pour consultation</a></li>
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
                        <div class='bg-info tuilesStatuts'>
                            <div class='pull-right'>
                                Rédaction
                            </div>
                            <div class='tuilesStatutsNom'>
                                Créée par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?>
                            </div>
                            <div class='tuilesStatutsDateCrea'>
                                Créée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                            <div class='tuilesStatutsDateSend'>
                                Envoyée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                        </div>
                        <div class='bg-success tuilesStatuts'>
                            <div class='pull-right'>
                                Validation
                            </div>
                            <div class='tuilesStatutsNom'>
                                Validée par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?>
                            </div>
                            <div class='tuilesStatutsDateCrea'>
                                Validée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                            <div class='tuilesStatutsDateSend'>
                                Transférée le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                        </div>
                        <div class='bg-warning tuilesStatuts'>
                            <div class='pull-right'>
                                En attente de validation
                            </div>
                            <div class='tuilesStatutsNom'>
                                Reçue par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?>
                            </div>
                            <div class='tuilesStatutsDateCrea'>
                                Reçue le <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class='selectDestValider<?php echo $donnee['Fiche']['id']; ?> selectorDestValider'>
                    <td></td>
                    <td></td>
                    <td colspan='2' class='tdleft'>
                        <select data-placeholder='Choisissez un destinataire' class='usersDeroulant transformSelect'>
                            <option value=''></option>
                            <?php
                            foreach($users as $user){
                            ?>
                            <option value="<?php echo $user['User']['id']; ?>">
                                <?php echo $user['User']['nom'].' '.$user['User']['prenom']; ?>
                            </option>
                            <?php
                            }
                            ?>
                        </select>
                        <?php echo $this->Form->input('typeEnvoi', array('type'=>'hidden', 'value'=>'trans', 'id'=>'typeEnvoiValider'.$donnee['Fiche']['id'].'')); ?>
                        <?php echo $this->Html->link('Annuler', '#', array('class'=>'btn btn-danger pull-right btnDivSend sendCancel', 'onclick'=>'return false')); ?>
                        <?php echo $this->Html->link('Envoyer', array('controller'=>'pannel', 'action'=>'test', $donnee['Fiche']['id']), array('class'=>'btn btn-success pull-right btnDivSend')); ?>
                    </td>
                </tr>
                <tr class='commentaireRefus<?php echo $donnee['Fiche']['id']; ?>'>
                    <td></td>
                    <td></td>
                    <td colspan='2' class='tdleft'>"
                        <?php echo $this->Form->input('commentaireRefus', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Expliquez les raisons de votre refus</span>', 'class'=>'form-control', 'type'=>'textarea')); ?>
                        <?php echo $this->Html->link('Annuler', '#', array('class'=>'btn btn-danger pull-right btnDivSend refusCancel', 'onClick'=> 'return false')); ?>
                        <?php echo $this->Html->link('Envoyer', array('controller'=>'pannel', 'action'=>'test', $donnee['Fiche']['id']), array('class'=>'btn btn-success pull-right btnDivSend')); ?>
                    </td>
                </tr>
                <tr class='completion'></tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
        <div id="listDemandeAvis">
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
                <tr id='ligneAValider<?php echo $donnee['Fiche']['id']; ?>'>
                    <td class='tdleft'>
                        <?php echo $donnee['Fiche']['outilnom']; ?>
                    </td>
                    <td class='tdleft'>
                        <?php echo $this->Time->format($donnee['Fiche']['created'], '%e-%m-%Y'); ?><i> par <?php echo $donnee['User']['prenom']." ".$donnee['User']['nom']; ?></i>
                    </td>
                    <td class='tdleft'>
                        Avis demandé par<i> par Nom Prénom</i>
                    </td>
                    <td class='tdleft'>
                        <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('controller'=>'fiches', 'action'=>'show', $donnee['Fiche']['id']), array('class'=>'btn btn-default boutonShow boutonsAction5', 'escapeTitle'=>false)); ?>
                        <button type='button' class='btn btn-default boutonRepondre boutonsAction5' value='1'><span class='glyphicon glyphicon-share-alt'></span></button>
                    </td>
                </tr>
                <tr class='commentaireRepondre1'><td></td><td></td><td colspan='2' class='tdleft'>
                        <?php echo $this->Form->input('commentaireRepondre', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Donnez votre avis</span>', 'class'=>'form-control', 'type'=>'textarea')); ?>
                        <?php echo $this->Html->link('Annuler', '#', array('class'=>'btn btn-danger pull-right btnDivSend repondreCancel', 'onClick'=>'return false')); ?>
                        <?php echo $this->Html->link('Envoyer', array('controller'=>'pannel', 'action'=>'test', $donnee['Fiche']['id']), array('class'=>'btn btn-success pull-right btnDivSend')); ?>
                </tr>
                <tr class='completion'></tr>

                </tbody>
            </table>
        </div>
</div>