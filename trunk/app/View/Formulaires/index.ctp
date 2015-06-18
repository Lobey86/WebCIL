<table class="table  table-bordered">
    <thead>
    <th class="thleft col-md-1">
        Statut
    </th>
    <th class="thleft col-md-9">
        Synthèse
    </th>
    <th class="thleft col-md-2">
        Actions
    </th>
    </thead>
    <tbody>
    <?php
    foreach($formulaires as $data) {
        if($data['Formulaire']['active']) {
            $iconClass = 'fa fa-check-square-o fa-3x fa-success';
            $statut = 'Actif';
            $statutClass = 'fa-success';
        } else {
            $statut = 'Inactif';
            $iconClass = 'fa fa-close fa-3x fa-danger';
            $statutClass = 'fa-danger';

        }
        echo '
    <tr>
        <td class="tdleft col-md-1">
            <div class="etatIcone">
                <i class="' . $iconClass . '"></i>
            </div>
        </td>

        <td class="tdleft col-md-9">
            <div class="row">

                <div class="col-md-5">
                    <div class="row col-md-12">
                        <strong>Nom: </strong>' . $data['Formulaire']['libelle'] . '
                    </div>
                    <div class="row col-md-12">
                        <strong>Statut: </strong><span class="' . $statutClass . '">' . $statut . '</span>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-3">
                        <strong>Description: </strong>
                    </div>
                    <div class="col-md-9">
                            ' . $data['Formulaire']['description'] . '
                    </div>
                </div>
            </div>
        </td>
        <td class="tdleft col-md-2">
        <div class="btn-group">';
        if($valid[$data['Formulaire']['id']]) {
            echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', array(
                'controller' => 'formulaires',
                'action' => 'edit',
                $data['Formulaire']['id']
            ), array(
                'class' => 'btn btn-default-default btn-sm my-tooltip',
                'title' => 'Modifier le formulaire',
                'escape' => false
            ));
        }
        if($data['Formulaire']['active']) {
            $lien = $this->Html->link('<span class="glyphicon glyphicon-remove"></span>', array(
                'controller' => 'formulaires',
                'action' => 'toggle',
                $data['Formulaire']['id'],
                $data['Formulaire']['active']
            ), array(
                'class' => 'btn btn-default-default btn-sm my-tooltip',
                'title' => 'Désactiver le formulaire',
                'escape' => false
            ));
        } else {
            $lien = $this->Html->link('<span class="glyphicon glyphicon-check"></span>', array(
                'controller' => 'formulaires',
                'action' => 'toggle',
                $data['Formulaire']['id'],
                $data['Formulaire']['active']
            ), array(
                'class' => 'btn btn-default-default btn-sm my-tooltip',
                'title' => 'Activer le formulaire',
                'escape' => false
            ));
        }
        echo $lien;
        if($valid[$data['Formulaire']['id']]) {
            echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array(
                'controller' => 'formulaires',
                'action' => 'delete',
                $data['Formulaire']['id']
            ), array(
                'class' => 'btn btn-default-danger btn-sm my-tooltip',
                'title' => 'Supprimer le formulaire',
                'escape' => false
            ));
        }

        echo '
        </div>
        </td>
    </tr>';
    }
    ?>
    </tbody>
</table>
<div class="row bottom10">
    <div class="col-md-12 text-center">
        <button type="button" class="btn btn-default-primary" data-toggle="modal" data-target="#modalAddForm">
            <span class="glyphicon glyphicon-plus"></span>
            Créer un formulaire
        </button>
    </div>
</div>
<div class="modal fade" id="modalAddForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Informations générales du formulaire</h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->create('Formulaire', array('action' => 'addFirst'));
                echo '<div class="row form-group">';
                echo $this->Form->input('libelle', array(
                    'class' => 'form-control',
                    'placeholder' => 'Nom du formulaire',
                    'label' => array(
                        'text' => 'Nom <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'required' => false
                ));
                echo '</div>';
                echo '<div class="row form-group">';
                echo $this->Form->input('description', array(
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'placeholder' => 'Description du formulaire',
                    'label' => array(
                        'text' => 'Description',
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
                            class="fa fa-arrow-left"></i> Annuler
                    </button>
                    <?php
                    echo $this->Form->button("<i class='fa fa-check'></i> Enregistrer", array(
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