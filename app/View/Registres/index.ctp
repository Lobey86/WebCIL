<?php
echo $this->Html->script('registre.js');
?>
    <div class="well">
        <?php
        if ( file_exists(IMAGES . DS . 'logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo')) ) {
            echo $this->Html->image('logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'), array('class' => 'pull-right logo-well'));
        }
        ?>
        <h1>Registre de <?php echo $this->Session->read('Organisation.raisonsociale'); ?></h1>
    </div>
<?php
echo $this->Form->button('<span class="glyphicon glyphicon-filter"></span>Filtrer la liste', $options = array(
    'type' => 'button',
    'class' => 'btn btn-primary btn-sm pull-right',
    'id' => 'filtrage'
));
?>
    <div id="divFiltrage">
        <?php
        echo $this->Form->create('Registre', $options = array('action' => 'index'));
        ?>
        <div class="input-group login">
		<span class="input-group-addon">
			<span class="glyphicon glyphicon-user"></span>
		</span>
            <?php
            echo $this->Form->input('user', array(
                'options' => $listeUsers,
                'class' => 'usersDeroulant transformSelect form-control',
                'empty' => 'Selectionnez un utilisateur',
                'label' => false
            ));
            ?>
        </div>
        <div class="input-group login">
		<span class="input-group-addon">
			<span class="glyphicon glyphicon-tag"></span>
		</span>
            <?php
            echo $this->Form->input('outil', array(
                'class' => 'form-control',
                'placeholder' => 'Nom d\'outil',
                'label' => false
            ));
            ?>
        </div>
        <?php
        if ( $this->Autorisation->isCil() || $this->Autorisation->isSu() ) {
            echo '<div class = "input-group login">';
            echo $this->Form->input('archive', array(
                'type' => 'checkbox',
                'label' => 'Uniquement les fiches archivées',
                'id' => 'checkArch'
            ));
            echo $this->Form->input('nonArchive', array(
                'type' => 'checkbox',
                'label' => 'Uniquement les fiches non archivées',
                'id' => 'checkNonArch'
            ));
            echo '</div>';
        }


        echo $this->Html->link('Supprimer les filtres', array(
            'controller' => 'registres',
            'action' => 'index'
        ), array('class' => 'btn btn-danger pull-right'));
        echo $this->Form->submit('Filtrer', array('class' => 'btn btn-primary'));
        echo $this->Form->end();
        ?>

    </div>
<?php
if ( !empty($fichesValid) ) {
    ?>
    <table class="table table-hover">
        <thead>
        <th class="thleft">
            Nom de l'outil
        </th>
        <th class="thleft">
            Création de la fiche
        </th>
        <th class="thleft">
            Validée le
        </th>
        <th class="thleft">
            Outils
        </th>
        </thead>
        <tbody>
        <?php
        foreach ( $fichesValid as $key => $value ) {
            echo '
				<tr>
					<td class="tdleft">
						' . $value[ 'Fiche' ][ 'outilnom' ] . '
					</td>
					<td class="tdleft">
						' . $value[ 'Fiche' ][ 'created' ] . ' <i>par ' . $value[ 'Fiche' ][ 'User' ][ 'prenom' ] . ' ' . $value[ 'Fiche' ][ 'User' ][ 'nom' ] . '</i>
					</td>
					<td class="tdleft">
						' . $value[ 'EtatFiche' ][ 'created' ] . '
					</td>
					<td class="tdleft">' . $this->Html->link('<button type="button" class="btn btn-default boutonDl boutonsAction5" value="1">' . $this->Html->image('pdf.png', array('class' => 'glyph')) . '</button>', array(
                    'controller' => 'fiches',
                    'action' => 'genereFusion',
                    $value[ 'Fiche' ][ 'id' ]
                ), array('escape' => false));
            if ( $value[ 'Readable' ] ) {
                echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array(
                    'controller' => 'fiches',
                    'action' => 'show',
                    $value[ 'Fiche' ][ 'id' ]
                ), array(
                    'class' => 'btn btn-default boutonShow boutonsAction5',
                    'escapeTitle' => false
                ));
            }
            if ( ($this->Autorisation->isCil() || $this->Autorisation->isSu()) && $value[ 'EtatFiche' ][ 'etat_id' ] != 7 ) {
                echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', array(
                    'controller' => 'fiches',
                    'action' => 'edit',
                    $value[ 'Fiche' ][ 'id' ]
                ), array(
                    'class' => 'btn btn-default boutonEdit boutonsAction5',
                    'escapeTitle' => false
                ));
                if ( $this->Autorisation->isCil() || $this->Autorisation->isSu() ) {
                    echo $this->Html->link('<span class="glyphicon glyphicon-lock"></span>', array(
                        'controller' => 'etatFiches',
                        'action' => 'archive',
                        $value[ 'Fiche' ][ 'id' ]
                    ), array(
                        'class' => 'btn btn-danger boutonArchive boutonsAction15',
                        'escapeTitle' => false
                    ), 'Voulez-vous archiver cette fiche? Une fois archivée, toute modification est impossible.');
                }
            }
            echo '</td>
					</tr>';

        }
        ?>
        </tbody>
    </table>
<?php
}
else {
    if ( $search ) {
        echo "<div class='text-center'><h3>Il n'y a aucune fiche pour ces filtres <small>";
        echo $this->Html->link('Cliquez ici pour annuler les filtres', array(
            'controller' => 'registres',
            'action' => 'index'
        ));
        echo "</small></h3></div>";
    }
    else {
        echo "<div class='text-center'><h3>Il n'y a aucune fiche à afficher <small>dans ce registre</small></h3></div>";
    }
}
?>