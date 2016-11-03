<table class="table ">
    <thead>
    <th class="thleft col-md-2">
        <?php echo __d('service', 'service.titreTableauNomService'); ?>
    </th>
    <th class="thleft col-md-8">
        <?php echo __d('service', 'service.titreTableauSynthèse'); ?>
    </th>
    <th class="thleft col-md-2">
        <?php echo __d('service', 'service.titreTableauAction'); ?>
    </th>
</thead>
<tbody>
    <?php
    foreach ( $serv as $value ) {
        if($value[ 'count' ] > 1){
            $plurielle = "s : ";
        } else {
            $plurielle = " : ";
        }
    ?>
        <tr>
            <td class="tdleft col-md-2">
                <?php echo $value[ 'Service' ][ 'libelle' ];?>
            </td>
            <td class="tdleft col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <strong>
                            <?php echo __d('service','service.textTableauOrganisation');?>
                        </strong>
                            <?php echo $this->Session->read('Organisation.raisonsociale');?>
                    </div>
                    <div class="col-md-6">
                        <strong>
                            <?php echo __d('service','service.textTableauMembre').$plurielle;?>
                        </strong>
                            <?php echo $value[ 'count' ];?>
                    </div>
                </div>
            </td>

            <td class="tdleft col-md-2">
                <div class="btn-group">
                    <?php
                    if ( $this->Autorisation->authorized(14, $droits) ) {
                        echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', array(
                            'controller' => 'services',
                            'action' => 'edit',
                            $value['Service']['id']
                                ), array(
                            'class' => 'btn btn-default-default boutonEdit btn-sm',
                            'escapeTitle' => false,
                            'title' => __d('service', 'service.commentaireModifierService')
                        ));
                    }
                    if ($this->Autorisation->authorized(15, $droits) && $value['count'] == 0) {
                        echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array(
                            'controller' => 'services',
                            'action' => 'delete',
                            $value['Service']['id']
                                ), array(
                            'class' => 'btn btn-default-danger boutonDelete btn-sm',
                            'escapeTitle' => false,
                            'title' => __d('service', 'service.commentaireSupprimerService')
                                ), __d('service', 'service.confirmationSupprimerService') . $value['Service']['libelle'] . ' ?');
                    }
                    ?>
                </div>
            </td>
        </tr>
        <?php
    }
    ?>
</tbody>
</table>
<?php
echo '<div class="text-center">';
echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span>'. __d('service','service.btnAjouterService'), array(
    'controller' => 'services',
    'action' => 'add'
        ), array(
    'class' => 'btn btn-default-primary sender',
    'escapeTitle' => false
));
echo '</div>';
?>