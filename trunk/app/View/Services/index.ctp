<table class="table ">
    <thead>
        <th class="thleft col-md-2">
            <?php echo __d('service', 'service.titreTableauNomService'); ?>
        </th>
        
        <th class="thleft col-md-4">
            <?php echo __d('service', 'service.textTableauOrganisation'); ?>
        </th>
        
        <th class="thleft col-md-4">
            <?php echo __d('service', 'service.titreTableauNbUtilisateur'); ?>
        </th>
        
        <th class="thleft col-md-2">
            <?php echo __d('service', 'service.titreTableauAction'); ?>
        </th>
    </thead>
    <tbody>
        <?php
        foreach ( $serv as $value ) {
            ?>
            <tr>
                <!-- Nom du service -->
                <td class="tdleft">
                    <?php echo $value[ 'Service' ][ 'libelle' ];?>
                </td>

                <!-- Entité -->
                <td class="tdleft">
                    <?php echo $this->Session->read('Organisation.raisonsociale');?>
                </td>
                
                <!-- Nombre d'utilisateurs -->
                <td class="tdleft">
                    <?php echo $value[ 'count' ];?>
                </td>

                <!-- Actions -->
                <td class="tdleft col-md-2">
                    <div class="btn-group">
                        <?php
                        if ( $this->Autorisation->authorized(14, $droits) ) {
                            echo $this->Html->link('<span class="fa fa-pencil fa-lg"></span>', array(
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
                            echo $this->Html->link('<span class="fa fa-trash fa-lg"></span>', array(
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
echo $this->Html->link('<span class="fa fa-plus-circle fa-lg"></span>'. __d('service','service.btnAjouterService'), array(
    'controller' => 'services',
    'action' => 'add'
        ), array(
    'class' => 'btn btn-default-primary sender',
    'escapeTitle' => false
));
echo '</div>';
?>