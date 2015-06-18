<table class="table ">
    <thead>
    <th class="thleft col-md-2">
        Nom du service
    </th>
    <th class="thleft col-md-8">
        Synthèse
    </th>
    <th class="thleft col-md-2">
        Actions
    </th>
    </thead>
    <tbody>
    <?php
    foreach ( $serv as $value ) {
        echo '<tr>';
        echo '<td class="tdleft col-md-2">';
        echo $value[ 'Service' ][ 'libelle' ];
        echo '</td>';
        echo '<td class="tdleft col-md-8"><div class="row">';
        echo '<div class="col-md-6"><strong>Organisation: </strong>' . $this->Session->read('Organisation.raisonsociale') . '</div>';
        echo '<div class="col-md-6"><strong>Membres: </strong>' . $value[ 'count' ] . '</div>';
        echo '</div></td>';
        echo '<td class="tdleft col-md-2">';
        echo '<div class="btn-group">';
        if ( $this->Autorisation->authorized(14, $droits) ) {
            echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', array(
                'controller' => 'services',
                'action' => 'edit',
                $value[ 'Service' ][ 'id' ]
            ), array(
                'class' => 'btn btn-default-default boutonEdit btn-sm',
                'escapeTitle' => false
            ));
        }
        if ( $this->Autorisation->authorized(15, $droits) ) {
            echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', array(
                'controller' => 'services',
                'action' => 'delete',
                $value[ 'Service' ][ 'id' ]
            ), array(
                'class' => 'btn btn-default-danger boutonDelete btn-sm',
                'escapeTitle' => false
            ), 'Voulez vous vraiment supprimer le service "' . $value[ 'Service' ][ 'libelle' ] . '"');
        }
        echo '</div>';
        echo '</td>';
        echo '</tr>';
    }
    ?>
    </tbody>
</table>
<?php
echo '<div class="text-center">';
echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> Ajouter un service', array(
    'controller' => 'services',
    'action' => 'add'
), array(
    'class' => 'btn btn-default-primary sender',
    'escapeTitle' => false
));
echo '</div>';
?>