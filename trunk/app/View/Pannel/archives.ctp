<?php

echo $this->Html->script('pannel.js');
echo $this->Html->script('registre.js');

$params = ['limit' => false];

// balise du scrollTo
$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);

// Banette mes traitements validés et insérés au registre
echo $this->Banettes->archives($banettes['archives'], $params);