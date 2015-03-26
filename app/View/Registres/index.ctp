<?php
echo $this->Html->script('registre.js');
?>
<div class="well">
    <h1>Registre de <?php echo $this->Session->read('Organisation.raisonsociale'); ?></h1>
</div>
<form class="" role="search">
    <div class="form-inline pull-right recherche">
        <input type="text" class="form-control input-sm" placeholder="Chercher un outil">
        <button type="submit" class="btn btn-primary btn-sm">
            Rechercher
        </button>
    </div>
</form>
<table class="table table-hover">
    <thead>
        <th class="thleft">
            Nom de l'outil
        </th>
        <th class="thleft">
            Création de la fiche
        </th>
        <th class="thleft">
            Validation de la fiche
        </th>
        <th class="thleft">
            Date de mise en oeuvre
        </th>
        <th class="thleft">
            Outils
        </th>
    </thead>
    <tbody>
        <tr>
            <td class="tdleft">
                Outil de test
            </td>
            <td class="tdleft">
                12/08/2014 <i>par Aurélien Massé</i>
            </td>
            <td class="tdleft">
                15/08/2014 <i>par Aurélien Massé</i>
            </td>
            <td class="tdleft">
                21/10/2014
            </td>
            <td class="tdleft">
                <button type='button' class='btn btn-default boutonDl boutonsAction5' value='1'><img src="img/pdf.png" class="glyph"/></button>
                <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('controller'=>'fiches', 'action'=>'show', '15'), array('class'=>'btn btn-default boutonShow boutonsAction5', 'escapeTitle'=>false)); ?>
                
                <?php if($this->Autorisation->authorized(6, $droits)){ echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', array('controller'=>'fiches', 'action'=>'edit', '15'), array('class'=>'btn btn-default boutonEdit boutonsAction5', 'escapeTitle'=>false)); ?>

                <button type="button" class="btn btn-danger boutonsAction15 boutonArchive">
                    <span class="glyphicon glyphicon-lock"></span>
                </button>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td class="tdleft">
                Outil de test
            </td>
            <td class="tdleft">
                12/08/2014 <i>par Aurélien Massé</i>
            </td>
            <td class="tdleft">
                15/08/2014 <i>par Aurélien Massé</i>
            </td>
            <td class="tdleft">
                21/10/2014
            </td>
            <td class="tdleft">
                <button type='button' class='btn btn-default boutonDl boutonsAction5' value='1'><img src="img/pdf.png" class="glyph"/></button>
                <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('controller'=>'fiches', 'action'=>'show', '14'), array('class'=>'btn btn-default boutonShow boutonsAction5', 'escapeTitle'=>false)); ?>
            </td>
        </tr>
        <tr>
            <td class="tdleft">
                Outil de test
            </td>
            <td class="tdleft">
                12/08/2014 <i>par Aurélien Massé</i>
            </td>
            <td class="tdleft">
                15/08/2014 <i>par Aurélien Massé</i>
            </td>
            <td class="tdleft">
                21/10/2014
            </td>
            <td class="tdleft">
                <button type='button' class='btn btn-default boutonDl boutonsAction5' value='1'><img src="img/pdf.png" class="glyph"/></button>
                <?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('controller'=>'fiches', 'action'=>'show', '13'), array('class'=>'btn btn-default boutonShow boutonsAction5', 'escapeTitle'=>false)); ?>

            </td>
        </tr>
    </tbody>
</table>