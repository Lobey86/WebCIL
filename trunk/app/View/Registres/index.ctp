<?php
echo $this->Html->script('registre.js');
?>
<div class="well">
    <h1>Registre de <?php echo $this->Session->read('Organisation.raisonsociale'); ?></h1>
</div>
<?php
echo $this->Form->create('Registre', array('action' => 'index', 'role' => 'search'));
echo '<div class="form-inline pull-right recherche">';
echo $this->Form->input('search', array('type' => 'text', 'class' => 'form-control input-sm', 'placeholder' => 'Chercher un outil', 'label' => FALSE, 'div' => FALSE));
echo $this->Form->button('Rechercher', $options = array('type' => 'submit', 'class' => 'btn btn-primary btn-sm'));

echo $this->Form->end();
echo '</div>';
if(!empty($fichesValid)){
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
            foreach ($fichesValid as $key => $value) {
                echo '
                <tr>
                    <td class="tdleft">
                        '.$value['Fiche']['outilnom'].'
                    </td>
                    <td class="tdleft">
                        '.$value['Fiche']['created'].' <i>par '.$value['Fiche']['User']['prenom'].' '.$value['Fiche']['User']['prenom'].'</i>
                    </td>
                    <td class="tdleft">
                        '.$value['EtatFiche']['created'].'
                    </td>
                    <td class="tdleft">
                        <button type="button" class="btn btn-default boutonDl boutonsAction5" value="1">'.$this->Html->image('pdf.png', array('class' => 'glyph')).'</button>'.
                        $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('controller'=>'fiches', 'action'=>'show', $value['Fiche']['id']), array('class'=>'btn btn-default boutonShow boutonsAction5', 'escapeTitle'=>false));
                        if($this->Autorisation->authorized(6, $droits) && $value['EtatFiche']['etat_id']!=7){ echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', array('controller'=>'fiches', 'action'=>'edit', $value['Fiche']['id']), array('class'=>'btn btn-default boutonEdit boutonsAction5', 'escapeTitle'=>false));
                        if($this->Autorisation->isCil()){
                            echo $this->Html->link('<span class="glyphicon glyphicon-lock"></span>', array('controller'=>'etatFiches', 'action' => 'archive', $value['Fiche']['id']), array('class'=>'btn btn-danger boutonArchive boutonsAction15', 'escapeTitle'=>false), 'Voulez-vous archiver cette fiche? Une fois archivée, toute modification est impossible.');
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
else{
    echo "<div class='text-center'><h3>Il n'y a aucune fiche à afficher <small>dans ce registre</small></h3></div>";
}
?>