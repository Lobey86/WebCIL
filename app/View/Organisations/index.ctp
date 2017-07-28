<?php
echo $this->Html->script('organisations.js');
?>
    <table class="table ">
        <thead>
            <!-- Entité -->
            <th class="thleft col-md-2">
                <?php echo __d('organisation', 'organisation.titreTableauEntite'); ?>
            </th>
            
            <!-- Organisation -->
            <th class="thleft col-md-8">
                <?php echo __d('organisation', 'organisation.titreTableauNbUtilisateur'); ?>
            </th>
            
            <!-- Actions -->
            <th class="thleft col-md-2">
                <?php echo __d('organisation', 'organisation.titreTableauActions'); ?>
            </th>
        </thead>
        <tbody>
        <?php
        foreach ( $organisations as $donnees ) {
            ?>
            <tr>
                <td class="tdleft">
                    <?php echo $donnees[ 'Organisation' ][ 'raisonsociale' ]; ?>
                </td>
                
                <td class="tdleft">
                    <div class="col-md-6">
                        <?php echo $donnees[ 'Count' ]; ?>
                    </div>
                </td>
                
                <td class="tdleft">
                    <div class="btn-group">
                        <?php echo $this->Html->link('<span class="fa fa-eye fa-lg"></span>', array(
                            'controller' => 'organisations',
                            'action' => 'show',
                            $donnees[ 'Organisation' ][ 'id' ]
                        ), array(
                            'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                            'title' => __d('organisation','organisation.commentaireVIsualiserEntite'),
                            'escapeTitle' => false
                        ));
                        if ( $this->Autorisation->authorized(12, $droits) ) {
                            echo $this->Html->link('<span class="fa fa-pencil fa-lg"></span>', array(
                                'controller' => 'organisations',
                                'action' => 'edit',
                                $donnees[ 'Organisation' ][ 'id' ]
                            ), array(
                                'class' => 'btn btn-default-default boutonEdit btn-sm my-tooltip',
                                'title' => 'Modifier cette organisation',
                                'escapeTitle' => false
                            ));
                        }

                        if ($donnees[ 'Count' ] == 0) {
                            if ( $this->Autorisation->isSu() ) {
                                echo $this->Html->link('<span class="fa fa-trash fa-lg"></span>', array(
                                    'controller' => 'organisations',
                                    'action' => 'delete',
                                    $donnees[ 'Organisation' ][ 'id' ]
                                ), array(
                                    'class' => 'btn btn-default-danger boutonDelete btn-sm my-tooltip',
                                    'title' => 'Supprimer cette organisation',
                                    'escapeTitle' => false
                                ), 'Voulez vous vraiment supprimer l\'entité ' . $donnees[ 'Organisation' ][ 'raisonsociale' ]);
                            }
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

<div class="text-center">
    <?php
        if ( $this->Autorisation->isSu() ) {
            echo $this->Html->link('<span class="fa fa-plus-circle fa-lg"></span> Ajouter une entité', array(
                'controller' => 'organisations',
                'action' => 'add'
            ), array(
                'class' => 'btn btn-default-primary sender',
                'escapeTitle' => false
            ));
        }
    ?>
</div>