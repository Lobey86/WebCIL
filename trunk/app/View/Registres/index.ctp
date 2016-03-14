<?php

echo $this->Html->script('registre.js');
echo $this->Form->button('<span class="glyphicon glyphicon-filter"></span>Filtrer la liste', $options = array(
    'type' => 'button',
    'class' => 'btn btn-default-primary btn-sm pull-right',
    'id' => 'filtrage'
));

$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);

?>
<div id="divFiltrage">
    <?php
    echo $this->Form->create('Registre', $options = array('action' => 'index'));

    ?>
    <div class="input-group login">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-user"></span>
        </span>
        <?php
        echo $this->Form->input('user', array(
            'options' => $listeUsers,
            'class' => 'usersDeroulant transformSelect form-control',
            'empty' => 'Selectionnez un utilisateur',
            'label' => false
        ));

        ?>
    </div>
    <div class="input-group login">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-tag"></span>
        </span>
        <?php
        echo $this->Form->input('outil', array(
            'class' => 'form-control',
            'placeholder' => 'Nom du traitement',
            'label' => false
        ));

        ?>
    </div>
    <?php
    if ($this->Autorisation->isCil() || $this->Autorisation->isSu()) {
        echo '<div class = "input-group login">';
        echo $this->Form->input('archive', array(
            'type' => 'checkbox',
            'label' => 'Uniquement les fiches verouillées',
            'id' => 'checkArch'
        ));
        echo $this->Form->input('nonArchive', array(
            'type' => 'checkbox',
            'label' => 'Uniquement les fiches non verouillées',
            'id' => 'checkNonArch'
        ));
        echo '</div>';
    }


    echo $this->Html->link('Supprimer les filtres', array(
        'controller' => 'registres',
        'action' => 'index'
        ), array('class' => 'btn btn-default-danger pull-right'));
    echo $this->Form->submit('Filtrer', array('class' => 'btn btn-default-primary'));
    echo $this->Form->end();

    ?>

</div>
<?php
if (!empty($fichesValid)) {

    ?>
<table class="table ">
    <thead>
    <th class="thleft col-md-2">
        Nom du traitement
    </th>
    <th class="thleft col-md-8">
        Synthèse
    </th>
    <th class="thleft col-md-2">
        Outils
    </th>
</thead>
<tbody>
        <?php
        foreach ($fichesValid as $key => $value) {
            if ($value['Fiche']['numero'] != null) {
                $numero = $value['Fiche']['numero'];
            } else {
                $numero = 'CIL00' . $value['Fiche']['id'];
            }
            
            if ($value['EtatFiche']['etat_id'] != 7){
                $DlOrGenerate = 'genereFusion';
            } else {
                $DlOrGenerate = 'downloadFile';
            }

            if($value['Fiche']['Valeur'] != null){
                echo '<tr>
                        <td class="tdleft">
                            ' . $value['Fiche']['Valeur'][0]['valeur'] . '
                        </td>
                        <td class="tdleft">
                            <div class="row">
                                <div class="col-md-8">
                                    <strong>Date de création: </strong> ' . $value['EtatFiche']['created'] . '
                                </div>
                                <div class="col-md-4">
                                    <strong>Numéro d\'enregistrement </strong>' . $numero . '
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>Finalité principale: </strong> ' . $value['Fiche']['Valeur'][1]['valeur'] . '
                                </div>
                            </div>
                        </td>
                        <td class="tdleft">
                            <div id='.$value['Fiche']['id'].' class="btn-group">' . $this->Html->link('<i class="fa fa-file-pdf-o"></i>', array(
                                'controller' => 'fiches',
                                'action' => $DlOrGenerate,
                                $value['Fiche']['id'],
                                ), array(
                                    'escape' => false,
                                    'class' => 'btn btn-default-default btn-sm my-tooltip',
                                    'title' => 'Télécharger l\'extrait de registre'
                ));

                if ($value['Readable']) {
                    echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array(
                        'controller' => 'fiches',
                        'action' => 'show',
                        $value['Fiche']['id']
                        ), array(
                        'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                        'title' => 'Voir la fiche',
                        'escapeTitle' => false
                    ));
                }
                if (($this->Autorisation->isCil() || $this->Autorisation->isSu()) && $value['EtatFiche']['etat_id'] != 7) {
                    echo $this->Form->button('<span class="glyphicon glyphicon-pencil"></span>', array(
                        'class' => 'btn btn-default-default boutonEdit btn-sm my-tooltip btn-edit-registre',
                        'escapeTitle' => false,
                        'data-toggle' => 'modal',
                        'data-target' => '#modalEditRegistre',
                        'title' => 'Modifier la fiche'
                    ));
                    if ($this->Autorisation->isCil() || $this->Autorisation->isSu()) {
                        echo $this->Html->link('<span class="glyphicon glyphicon-lock"></span>', array(
                            'controller' => 'etatFiches',
                            'action' => 'archive',
                            $value['Fiche']['id']
                            ), array(
                            'class' => 'btn btn-default-danger boutonArchive btn-sm my-tooltip',
                            'title' => 'Vérouiller la fiche',
                            'escapeTitle' => false
                            ), 'Voulez-vous vérouiller cette fiche? Une fois vérouillée, toute modification est impossible.');
                    }
                }
                echo '</div></td>
                                            </tr>';
            }
        }

        ?>
</tbody>
</table>
    <?php
} else {
    if ($search) {
        echo "<div class='text-center'><h3>Il n'y a aucune fiche pour ces filtres <small>";
        echo $this->Html->link('Cliquez ici pour annuler les filtres', array(
            'controller' => 'registres',
            'action' => 'index'
        ));
        echo "</small></h3></div>";
    } else {
        echo "<div class='text-center'><h3>Il n'y a aucune fiche à afficher</h3></div>";
    }
}

?>
<div class="modal fade" id="modalEditRegistre" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Edition d'une fiche du registre</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-warning">
                        <div class="col-md-12 text-center"><i class="fa fa-fw fa-exclamation-triangle"></i></div>
                        <div class="col-md-12">Vous allez modifier une fiche insérée au registre. Merci de préciser le
                            motif de cette
                            modification
                        </div>

                    </div>
                </div>
                <div class="row top17">
                    <div class="col-md-12">
                        <?php
                        echo $this->Form->create('Registre', array(
                            'action' => 'edit',
                            'class' => 'form-horizontal'
                        ));

                        echo $this->Form->input('motif', array(
                            'label' => array(
                                'text' => 'Motif <span class="obligatoire">*</span>',
                                'class' => 'col-md-2 control-label'
                            ),
                            'between' => '<div class="col-md-10">',
                            'after' => '</div>',
                            'class' => 'form-control',
                            'div' => 'form-group',
                            'required' => 'required'
                        ));
                        echo $this->Form->hidden('idEditRegistre', array('id' => 'idEditRegistre'));

                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default-default" data-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-default-success">Modifier la fiche</button>
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