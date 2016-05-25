<?php
if (!empty($modeles)) {
    ?>
    <table class="table ">
        <thead>
        <th class="thleft col-md-2">
            <?php echo __d('modele', 'modele.titreTableauFormulaire'); ?>
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
        foreach ($modeles as $key => $value) {
            echo '<tr>
                    <td class="tdleft">
			' . $value['Formulaire']['libelle'] . '
                    </td>
                    <td class="tdleft">';

            if ($value['Modele']['fichier'] != null) {
                echo '<i class="fa fa-file-text-o fa-lg fa-fw"></i> ' . $value['Modele']['name_modele'];

                echo '</td>
                    <td class="tdleft">
                    <div class="btn-group">';

                echo $this->Html->link('<i class="fa fa-download fa-lg"></i>', array(
                    'controller' => 'modeles',
                    'action' => 'download',
                    $value['Modele']['fichier'],
                    $value['Modele']['name_fichier']
                        ), array(
                    'escape' => false,
                    'class' => 'btn btn-default-default btn-sm my-tooltip',
                    'title' => __d('modele', 'modele.commentaireTelechargerModel')
                ));

                echo $this->Html->link('<span class="glyphicon glyphicon-trash fa-lg"></span>', array(
                    'controller' => 'modeles',
                    'action' => 'delete',
                    $value['Modele']['fichier']
                        ), array(
                    'class' => 'btn btn-default-danger btn-sm my-tooltip',
                    'title' => __d('modele', 'modele.commentaireSupprimerModel'),
                    'escape' => false
                        ), __d('modele', 'modele.confirmationSupprimerModel')
                );
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
                    'data' => $value['Formulaire']['id']
                ));

                echo $this->Html->link('<span class="fa fa-question-circle fa-lg"></span>', array(
                    'controller' => 'modeles',
                    'action' => 'infoVariable',
                    $value['Formulaire']['id']
                        ), array(
                    'class' => 'btn btn-default-default btn-sm my-tooltip',
                    'title' => __d('modele', 'modele.commentaireVariableModel'),
                    'escape' => false,
                ));

                echo '</div>';
            }

            echo '</td></tr>';
        }
        ?>
    </tbody>
    </table>
    <?php
}
?>

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
                        echo $this->Form->create('Modele', array(
                            'action' => 'add',
                            'class' => 'form-horizontal',
                            'type' => 'file'
                        ));

                        echo $this->Form->input('modele', array(
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