<?php

echo $this->Html->script('pannel.js');
echo $this->Html->script('registre.js');

$params = ['limit' => false];

// balise du scrollTo
$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);

// Banette traitements reçus pour consultation
echo $this->Banettes->recuConsultation($banettes['recuConsultation'], $params);