<?php
echo $this->Html->script('organisations.js');
?>
    <table class="table ">
        <thead>
        <th class="thleft col-md-2">Entité</th>
        <th class="thleft col-md-8">Synthèse</th>
        <th class='thleft col-md-2'>Actions</th>
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
                        <strong>Utilisateurs: </strong> <?php echo $donnees[ 'Count' ]; ?>
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
                            echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', array(
                                'controller' => 'organisations',
                                'action' => 'edit',
                                $donnees[ 'Organisation' ][ 'id' ]
                            ), array(
                                'class' => 'btn btn-default-default boutonEdit btn-sm my-tooltip',
                                'title' => 'Modifier cette organisation',
                                'escapeTitle' => false
                            ));
                        }
                        if ( $this->Autorisation->isSu() ) {
                            echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array(
                                'controller' => 'organisations',
                                'action' => 'delete',
                                $donnees[ 'Organisation' ][ 'id' ]
                            ), array(
                                'class' => 'btn btn-default-danger boutonDelete btn-sm my-tooltip',
                                'title' => 'Supprimer cette organisation',
                                'escapeTitle' => false
                            ), 'Voulez vous vraiment supprimer l\'entité ' . $donnees[ 'Organisation' ][ 'raisonsociale' ]);
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
if ( $this->Autorisation->isSu() ) {
    echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> Ajouter une entité', array(
        'controller' => 'organisations',
        'action' => 'add'
    ), array(
        'class' => 'btn btn-default-primary pull-right sender',
        'escapeTitle' => false
    ));
}
?>