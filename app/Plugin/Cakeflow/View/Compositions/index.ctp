<?php
echo $this->Html->css("Cakeflow.design.css");
if (!empty($etape)) {
    $this->pageTitle = sprintf("Compositions de l'étape '%s' du circuit '%s'", $etape['Etape']['nom'], $etape['Circuit']['nom']);
} else {
    $this->pageTitle = __('Compositions', true);
}

echo $this->Html->tag('h2', $this->pageTitle);

if (!empty($compositions)) {
    $this->Paginator->options(array('url' => $this->passedArgs));
    $cells = '';

    foreach ($compositions as $rownum => $composition) {
        $rows = Set::extract($composition, 'Composition');
        $triggerLibelle = '<td>' . $rows['triggerLibelle'] . '</td>';
        if (!Configure::read('USE_PARAPHEUR') && $composition['Composition']['trigger_id'] == -1){
            $triggerLibelle = "<td><span style='cursor: help; border-bottom-color: #999; border-bottom-style: dotted; border-bottom-width: 1px;' title='Attention : Cette délégation peut poser problème. \nSolution : Activer le parapheur dans les connecteurs ou modifier/supprimer la composition'>
                <i class='fa fa-warning'></i> " . $rows['triggerLibelle'] . "</span></td>";
        }
        $cells .= '<tr>'
					. $triggerLibelle .
					'<td>' . $rows['typeValidationLibelle'] . '</td>
				<td style="text-align:center">
					' . $this->Myhtml->bouton(array('action' => 'view', $composition['Composition']['id']), 'Visualiser', false) . '
					' . $this->Myhtml->bouton(array('action' => 'edit', $composition['Composition']['id']), 'Modifier', false) . '
					' . $this->Myhtml->bouton(array('action' => 'delete', $composition['Composition']['id']), 'Supprimer', sprintf('Réellement supprimer la composition %s ?', $rows['triggerLibelle'])) . '
				</td>
			</tr>';
    }

    $headers = $this->Html->tableHeaders(
            array(
                CAKEFLOW_TRIGGER_TITLE,
                __('Type de validation', true),
                __('Actions', true)
            )
    );

    echo $this->element('indexPageCourante');
    echo $this->Html->tag(
            'table', $this->Html->tag('thead', $headers) . $this->Html->tag('tbody', $cells), array('class' => 'table table-striped')
    );
    echo $this->element('indexPageNavigation');
}
?>

<div class="actions">
<?php
echo $this->Html->tag("div", null, array("class" => "btn-group"));
echo $this->Html->link('<i class="fa fa-arrow-left"></i> Retour à la liste des étapes', array('controller' => 'etapes', 'action' => 'index', $etape['Circuit']['id']), array('class' => 'btn', 'escape' => false, 'title' => 'Revenir en arrière'));
if ($canAdd) {
    echo $this->Html->link('<i class="fa fa-plus"></i> Ajouter une composition', array('action' => 'add', $this->params['pass'][0]), array('class' => 'btn btn-primary', 'escape' => false, 'title' => 'Créer une composition pour cette étape'));
}
echo $this->Html->tag('/div', null);
?>
</div>