<?php
    if (!empty($modelesExtrait)) {
        $fileExiste = true;
    } else {
        $fileExiste = false;
    }
    
    ?>
<table class="table ">
    <thead>
    <th class="thleft col-md-2">
        Modèle
    </th>
    <th class="thleft col-md-8">
                <?php echo __d('modele', 'modele.titreTableauFichierModele'); ?>
    </th>
    <th class="thleft col-md-2">
                <?php echo __d('modele', 'modele.titreTableauOutil'); ?>
    </th>
</thead>

<tbody>
            <?php
                echo '<tr>
                        <td class="tdleft">
                            ' . 'Extrait du registre' . '
                        </td>
                        <td class="tdleft">';
                
                if ($fileExiste == true) {
                    foreach ($modelesExtrait as $modeleExtrait){
                        echo '<i class="fa fa-file-text-o fa-lg fa-fw"></i> ' . $modeleExtrait['ModeleExtraitRegistre']['name_modele'];

                        echo '</td>
                            <td class="tdleft">
                            <div class="btn-group">';

                        echo $this->Html->link('<i class="fa fa-download fa-lg"></i>', array(
                            'controller' => 'modeleExtraitRegistres',
                            'action' => 'download',
                            $modeleExtrait['ModeleExtraitRegistre']['fichier'],
                            $modeleExtrait['ModeleExtraitRegistre']['name_modele']
                                ), array(
                            'escape' => false,
                            'class' => 'btn btn-default-default btn-sm my-tooltip',
                            'title' => __d('modele', 'modele.commentaireTelechargerModel')
                        ));

                        echo $this->Html->link('<span class="glyphicon glyphicon-trash fa-lg"></span>', array(
                            'controller' => 'modeleExtraitRegistres',
                            'action' => 'delete',
                            $modeleExtrait['ModeleExtraitRegistre']['fichier']
                                ), array(
                            'class' => 'btn btn-default-danger btn-sm my-tooltip',
                            'title' => __d('modele', 'modele.commentaireSupprimerModel'),
                            'escape' => false
                                ), __d('modele', 'modele.confirmationSupprimerModel')
                        );
                    }
                } else {
                    echo __d('modele', 'modele.textTableauAucunModele');

                    echo '</td>
                        <td class="tdleft">
                        <div class="btn-group">';

                    echo $this->Form->button('<i class="fa fa-upload fa-lg"></i>', array(
                        'escape' => false,
                        'class' => 'btn btn-default-default btn-sm my-tooltip btn-upload-modele',
                        'title' => __d('modele', 'modele.commentaireImporterModel'),
                        'data-toggle' => 'modal',
                        'data-target' => '#modalUploadModele',
                        'data' => ''
                    ));

                    echo $this->Html->link('<span class="fa fa-question-circle fa-lg"></span>', array(
                        'controller' => 'modeles',
                        'action' => 'infoVariable',
                            ), array(
                        'class' => 'btn btn-default-default btn-sm my-tooltip',
                        'title' => __d('modele', 'modele.commentaireVariableModel'),
                        'escape' => false,
                    ));

                    echo '</div>';
                }

                echo '</td></tr>';
            ?>
</tbody>
</table>

<!--Pop-up pour importé un model dans l'application -->
<div class="modal fade" id="modalUploadModele" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __d('modele', 'modele.popupEnvoieModele'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row top17">
                    <div class="col-md-12">
                        <?php
                        echo $this->Form->create('modeleExtraitRegistre', array(
                            'action' => 'add',
                            'class' => 'form-horizontal',
                            'type' => 'file'
                        ));

                        echo $this->Form->input('modeleExtraitRegistre', array(
                            'type' => 'file',
                            'label' => array(
                                'text' => __d('modele', 'modele.popupChampModele'),
                                'class' => 'col-md-4 control-label'
                            ),
                            'between' => '<div class="col-md-8">',
                            'after' => '</div>',
                            'class' => 'filestyle fichiers draggable',
                            'div' => 'form-group'
                        ));
                        echo $this->Form->hidden('idUploadModele', array('id' => 'idUploadModele'));
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button type="button" class="btn btn-default-default" data-dismiss="modal"><i
                            class="fa fa-arrow-left"></i><?php echo __d('default', 'default.btnAnnuler'); ?>
                    </button>
                    <?php
                    echo $this->Form->button("<i class='fa fa-check'></i>" . __d('modele', 'modele.btnEnregistrerModele'), array(
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