<?php
echo $this->Form->button(__d('pannel','pannel.btnVoirHistoriqueComplet'), array(
    'type' => 'button',
    'class' => 'historique-button btn btn-default-default',
    'data-value' => $id
));

echo '<ul class="list-group list-historique" id="historique-fiche' . $id . '" >';

foreach ($historique as $value) {
    echo '<li class="list-group-item"><strong>' . $this->Time->format($value['Historique']['created'], FORMAT_DATE_HEURE) . ':</strong> ' . $value['Historique']['content'] . '</li>';
}

echo '</ul>';
