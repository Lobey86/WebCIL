<?php
echo $this->Html->css("Cakeflow.design.css");
$this->pageTitle = sprintf("Étapes du circuit : '%s'", $circuit);
echo $this->Html->tag('h2', $this->pageTitle);

if (!empty($etapes)) {
    $cells = '';
    foreach ($etapes as $rownum => $etape) {
        $row = Set::extract($etape, 'Etape');

        // Liens pour changer la position de l'étape
        $ordre = $etape['Etape']['ordre'];
        $moveUp = $ordre > 1 ? $this->Html->link('&#9650;', array('action' => 'moveUp', $row['id']), array('escape' => false), false) : '&#9650;';
        $moveDown = $ordre < $nbrEtapes ? $this->Html->link('&#9660;', array('action' => 'moveDown', $row['id']), array('escape' => false)) : '&#9660;';

        // Mise en forme de la liste des déclencheurs
        $triggers = array();
        foreach ($etape['Composition'] as $composition) {
            $triggers[] = $composition['libelleTrigger'];
        }

        $cells .= '<tr>
					<td>' . "$moveUp $ordre $moveDown" . '</td>
					<td>' . $row['nom'] . '</td>
					<td>' . $row['description'] . '</td>
					<td>' . h($row['libelleType']) . '</td>
					<td>' . implode(', ', $triggers) . '</td>';
        if (!empty($row['cpt_retard']))
			$cells .= '<td><i class="fa fa-clock-o"></i> ' . $row['cpt_retard'] . ' jours avant la séance</td>';
        elseif (!isset($row['cpt_retard']))
            $cells .= '<td><i class="fa fa-ban"></i> <em>Pas d\'alerte de retard programmée<em></td>';
        else
            $cells .= '<td><i class="fa fa-clock-o"></i> Le jour de la séance</td>';

        $cells .= '<td style="text-align:center">';
        $cells .= $this->Myhtml->bouton(array('controller' => 'compositions', 'action' => 'index', $etape['Etape']['id']), __("Composition de l'étape", true), false, '/cakeflow/img/icons/composition.png');
        $cells .= $this->Myhtml->bouton(array('action' => 'view', $etape['Etape']['id']), 'Voir') . '
					' . $this->Myhtml->bouton(array('action' => 'edit', $etape['Etape']['id']), 'Modifier') . '
					' . (($etape['ListeActions']['delete']) ? $this->Myhtml->bouton(array('action' => 'delete', $etape['Etape']['id']), 'Supprimer', 'Voulez vous réellement supprimer l&acute;étape '.$etape['Etape']['nom'].' ?') : '') . '
				</td>
			</tr>';
    }

    $headers = $this->Html->tableHeaders(
        array(
            __('Ordre', true),
            __('Nom', true),
            __('Description', true),
            __('Type', true),
            __('Utilisateur(s)', true),
            __('Délais avant retard', true),
            __('Actions', true)
        )
    );

    echo $this->element('indexPageCourante');
    echo $this->Html->tag('table', $this->Html->tag('thead', $headers) . $this->Html->tag('tbody', $cells), array('class' => 'table table-striped')
    );
    echo $this->element('indexPageNavigation');
}
?>
<div class="actions">
    <?php
    echo $this->Html->tag('div', null, array('class' => 'btn-group'));
    echo $this->Html->link('<i class="fa fa-arrow-left"></i> Retour à la liste des circuits', array('controller' => 'circuits', 'action' => 'index'), array('class' => 'btn', 'escape' => false, 'title' => 'Revenir en arrière'));
    echo $this->Html->link('<i class="fa fa-plus"></i> Ajouter une étape', array('action' => 'add', $this->params['pass'][0]), array('class' => 'btn btn-primary', 'escape' => false, 'title' => 'Ajouter une étape au circuit'));
    echo $this->Html->tag('/div', null);
    ?>
</div>
