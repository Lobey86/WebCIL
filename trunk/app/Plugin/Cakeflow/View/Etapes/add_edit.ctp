<?php
$this->pageTitle = $this->action == 'add' ? __('Nouvelle étape', true) : __('Modification de l\'étape', true) . ' : ' .$this->data['Etape']['nom'];
echo $this->Html->tag('h2', $this->pageTitle);

echo $this->Form->create('Etape');
if ($this->action == 'edit') {
    echo $this->Form->input('Etape.id', array('type' => 'hidden'));
    echo $this->Form->input('Etape.ordre', array('type' => 'hidden'));
    echo $this->Form->input('retardInf', array('type' => 'hidden'));
}
echo $this->Form->input('Etape.circuit_id', array('type' => 'hidden'));

echo $this->Form->input('Etape.nom', array('label' => __('Nom', true)));
echo $this->Form->input('Etape.description', array('label' => __('Description', true), 'cols' => 100, 'rows' => 5));
echo $this->Form->input('Etape.type', array(
    'empty' => false,
    'label' => __('Type', true),
    'options' => $types,
    'title' => "- Type d'étape - \nSimple: accord requis \nConcurrent: l'accord d'un seul suffit \nCollaboratif: accord de tous requis"
));
echo $this->Form->input('Etape.cpt_retard', array(
    'type' => 'number',
    'label' => array(
        'text' => __('Nombre de jours avant retard *', true),
        'title' => 'Nombre de jours avant la date de la séance pour déclencher l\'alerte de retard'),
    'min' => '0',
    'max' => $retard_max,
    'title' => 'Nombre de jours avant la date de la séance pour déclencher l\'alerte de retard'
));
if (!empty($retard_max)){
    echo $this->Html->tag('p',"* Maximum : $retard_max jours", array('title'=>'La valeur pour cette étape ne peut pas dépasser celle des étapes précédentes'));
}
?>
    <div class="spacer"></div>
<?php
echo $this->Html->tag('div', null, array('class' => 'submit btn-group', 'style'=>'margin-left: 110px;'));
echo $this->Html->link('<i class="fa fa-ban"></i> Annuler', array('action' => 'index', $this->data['Etape']['circuit_id']), array('class' => 'btn', 'escape' => false));
echo $this->Form->button(__('Valider <i class="fa fa-check"></i>', true), array('type' => 'submit', 'class' => 'btn btn-primary'));
echo $this->Html->tag('/div', null);
echo $this->Form->end();
?>
<em>* La date de retard est calculée par rapport à la date de la séance délibérante du projet</em>
<?php
echo $this->Html->script('Cakeflow.etapes');
?>