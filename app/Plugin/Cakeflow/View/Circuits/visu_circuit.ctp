<?php
echo $this->Html->css('/cakeflow/css/circuit');
echo $this->Html->script('/cakeflow/js/init_cakeflow');
?>
<div id='etapes' class='circuit'>
    <?php
    foreach ($etapes as $etape) {
        echo "<div class='etape' id='etape_" . $etape['Etape']['id'] . "'>";
        echo $this->Html->div('nom', '[' . $etape['Etape']['ordre'] . '] - ' . $etape['Etape']['nom']);
        echo $this->Html->div('type', $etape['Etape']['libelleType']);
        echo "<div class='utilisateurs'>";
        foreach ($etape['Composition'] as $composition) {
            $typeValidation = CAKEFLOW_GERE_SIGNATURE ? ', ' . $composition['libelleTypeValidation'] : '';
            echo $this->Html->div('utilisateur', $composition['libelleTrigger'] . $typeValidation);
        }
        echo ('</div>');
        echo ('</div>');
    }
    ?>
</div>
