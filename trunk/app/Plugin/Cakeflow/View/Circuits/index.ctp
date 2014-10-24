<?php
echo $this->Html->css("Cakeflow.design.css");
echo $this->Html->tag('h2', __('Liste des circuits de traitement', true));

echo $this->element('indexPageCourante');
echo $this->Html->tag('table', null, array('class' => 'table table-striped'));
// initialisation de l'entete du tableau
$tableHeaders = array(
    $this->Paginator->sort(__('nom', true), 'Nom'),
    __('Description', true),
    __('Etapes', true),
    $this->Paginator->sort(__('actif', true), 'Actif'));
if (CAKEFLOW_GERE_DEFAUT)
    $tableHeaders[] = __('Défaut', true);
$tableHeaders[] = __('Actions', true);
echo $this->Html->tag('thead', $this->Html->tableHeaders($tableHeaders));
foreach ($this->data as $rownum => $rowElement) {
    echo $this->Html->tag('tr', null);
    echo $this->Html->tag('td', $rowElement['Circuit']['nom']);
    echo $this->Html->tag('td', $rowElement['Circuit']['description']);
    echo $this->Html->tag('td');
    foreach ($rowElement['Etape'] as $etape) {
        echo $etape['Etape']['nom'] . ' (' . $listeType[$etape['Etape']['type']] . ')' . '<br/>';
    }
    echo $this->Html->tag('/td');
    echo $this->Html->tag('td', $rowElement['Circuit']['actifLibelle']);
    if (CAKEFLOW_GERE_DEFAUT)
        echo $this->Html->tag('td', $rowElement['Circuit']['defautLibelle']);
    echo $this->Html->tag('td', null, array('style' => 'text-align:center'));
    echo $this->element('boutonAction', array(
        'class' => 'link_wkf_etapes',
        'plugin' => 'cakeflow',
        'url' => array('controller' => 'etapes', 'action' => 'index', $rowElement['Circuit']['id']),
        'title' => __('étapes', true),
        'iconPath' => '/cakeflow/img/icons/etape.png'));
    if ($rowElement['ListeActions']['view'])
        echo $this->element('boutonAction', array('plugin' => 'cakeflow', 'url' => array('action' => 'view', $rowElement['Circuit']['id'])));
    if ($rowElement['ListeActions']['edit'])
        echo $this->element('boutonAction', array('plugin' => 'cakeflow', 'url' => array('action' => 'edit', $rowElement['Circuit']['id'])));
    if ($rowElement['ListeActions']['delete'])
        echo $this->element('boutonAction', array('plugin' => 'cakeflow', 'url' => array('action' => 'delete', $rowElement['Circuit']['id']),
            'confirmMessage' => __('Voulez-vous supprimer le circuit de traitement : ', true) . $rowElement['Circuit']['nom']));
    if ($rowElement['ListeActions']['visuCircuit'])
        echo $this->element('boutonAction', array(
            'plugin' => 'cakeflow',
            'url' => array('action' => 'visuCircuit', $rowElement['Circuit']['id']),
            'title' => __('Visionner', true),
            'iconPath' => '/cakeflow/img/icons/visionner.png'));
    echo $this->Html->tag('/td', null);
    echo $this->Html->tag('/tr', null);
}
echo $this->Html->tag('/table', null);
echo $this->element('indexPageNavigation');
?>

<div class="actions">
    <?php echo $this->Html->link('<i class="fa fa-plus"></i> Ajouter un circuit de traitement', array('action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false, 'style' => 'margin-top:10px;')); ?>
</div>
