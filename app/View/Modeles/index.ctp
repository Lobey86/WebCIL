<?php
if (!empty($modeles)) {
    ?>
    <table class="table ">
        <thead>
        <th class="thleft col-md-2">
            Formulaire
        </th>
        <th class="thleft col-md-8">
            Fichier de modèle
        </th>
        <th class="thleft col-md-2">
            Outils
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

            if ($value['Modele']['fichier'] != NULL) {
                echo '<i class="fa fa-fw fa-file-o"></i> ' . $value['Modele']['fichier'];

                echo '</td>
                    <td class="tdleft">
                    <div class="btn-group">';

                echo $this->Html->link('<i class="fa fa-download"></i>', array(
                    'controller' => 'modeles',
                    'action' => 'download',
                    $value['Modele']['fichier']
                        ), array(
                    'escape' => false,
                    'class' => 'btn btn-default-default btn-sm my-tooltip',
                    'title' => 'Télécharger le modèle'
                ));

                echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array(
                    'controller' => 'modeles',
                    'action' => 'delete',
                    $value['Modele']['fichier']
                        ), array(
                    'class' => 'btn btn-default-danger btn-sm my-tooltip',
                    'title' => 'Supprimer le model',
                    'escape' => false
                        ), 'Voulez vous supprimer le model ?'
                );
            } else {
                echo 'Aucun modèle pour ce formulaire';

                echo '</td>
                    <td class="tdleft">
                    <div class="btn-group">';

                echo $this->Form->button('<i class="fa fa-upload"></i>', array(
                    'escape' => false,
                    'class' => 'btn btn-default-default btn-sm my-tooltip btn-upload-modele',
                    'title' => 'Importer un modèle',
                    'data-toggle' => 'modal',
                    'data-target' => '#modalUploadModele',
                    'data' => $value['Formulaire']['id']
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
<div class="modal fade" id="modalUploadModele" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Envoie d'un modele</h4>
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
                                'text' => 'Modèle',
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
                <button type="button" class="btn btn-default-default" data-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-default-success">Envoyer ce modèle</button>
                <?php
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
</div>