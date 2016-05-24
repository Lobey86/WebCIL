<table class=" table">
    <thead>
    <th class="thleft col-md-3">Utilisateur</th>
    <th class="thleft col-md-8">Synthèse</th>
    <th class="thleft col-md-1">Actions</th>
    </thead>
    <tbody>
    <?php
    foreach($admins as $key => $value) {
        echo ' <tr>
        <td class="tdleft">
   ' . $value['User']['prenom'] . ' ' . $value['User']['nom'] . '
        </td>
        <td></td>
        <td class="tdleft">
        <div class="form-group">
        ' . $this->Html->link('<span class="glyphicon glyphicon-remove"></span>', array(
                'controller' => 'admins',
                'action' => 'delete',
                $value['Admin']['id']
            ), array(
                'class' => 'btn btn-default-danger btn my-tooltip',
                'title' => __d('admin','admin.commentaireRetirerPrivilege'),
                'escape' => false
                ), array (__d('admin', 'admin.confirmationSupprimerPrivilegeUser') . ' ' . $value['User']['prenom'] . ' ' . $value['User']['nom'] . ' ?'
            )) . '
        </div>
        </td>
    </tr>';

    }
    ?>
    </tbody>
</table>
<div class="row bottom10">
    <div class="col-md-12 text-center">
        <?php echo $this->Form->button('<span class="glyphicon glyphicon-plus"></span>'.__d('admin','admin.btnAjouterPrivilege'), array(
            'class' => 'btn btn-default-primary',
            'data-toggle' => 'modal',
            'data-target' => '#modalAddPrivilege'
        )); ?>
    </div>
</div>

<div class="modal fade" id="modalAddPrivilege" tabindex="-1" role="dialog" aria-labelledby="modalAddPrivilegeLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __d('admin','admin.popupTitreAjouterPrivilegeSuperAdmin');?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <?php echo $this->Form->create('Admin', array('action' => 'add')) ?>
                        <?php echo $this->Form->input('user', array(
                            'options' => $listeusers,
                            'class' => 'usersDeroulant transformSelect form-control',
                            'empty' => __d('admin','admin.textEmptyChampSelectionnerUser'),
                            'label' => array(
                                'text' => __d('admin','admin.textChampChoisirUser').'<span class="requis">*</span>',
                                'class' => 'col-md-4 control-label'
                            ),
                            'between' => '<div class="col-md-8">',
                            'after' => '</div>'
                        )); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <div class="row text-center">
                    <div class="btn-group text-center">
                        <button type="button" class="btn btn-default-default" data-dismiss="modal">
                            <i class="fa fa-arrow-left"></i>
                                <?php echo __d('default','default.btnAnnuler');?>
                        </button>
                        <button type="submit" class="btn btn-default-success">
                            <i class="fa fa-check"></i> 
                                <?php echo __d('admin','admin.btnAjouter');?>
                        </button>
                        <?php echo $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>