<?php

echo $this->Html->script('pannel.js');
echo $this->Html->script('registre.js');

$params = ['limit' => false];

// balise du scrollTo
$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);

// Banette mes déclarations en attente 
echo $this->Banettes->attente($banettes['attente'], $params);

// Pop-up reorientation du traitement
echo $this->element(
    'modal',
    [
        'modalId' => 'modalReorienter',
        'content' => [
            'title' => __d('pannel', 'pannel.popupReorienterTraitement'),
            'body' => $this->Html->tag(
                'div',
                $this->Form->create('EtatFiche', array('action' => 'reorientation'))
                .$this->Form->input('destinataire', [
                    'class' => 'form-control usersDeroulant transformSelect form-control bottom5',
                    'label' => [
                        'text' => __d('pannel', 'pannel.textSelectReorienterValideur') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'options' => $validants,
                    'empty' => __d('pannel', 'pannel.textSelectReorienterValideur'),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'required' => true,
                    'autocomplete' => 'off',
                    'id' => 'destinataireReo'
                ])
                .$this->Form->hidden('ficheNum', ['id' => 'ficheNumReo'])
                .$this->Form->hidden('etatFiche', ['id' => 'etatFicheReo']),
                ['class' => 'form-group']
            ),
            'footer' => $this->Html->tag(
                'div',
                $this->Form->button(
                    '<i class="fa fa-times-circle fa-lg"></i>'
                    .__d('default', 'default.btnAnnuler'),
                    ['class' => 'btn btn-default-default', 'data-dismiss' => 'modal']
                )
                .$this->Form->button("<i class='fa fa-send fa-lg'></i>" . __d('default', 'default.btnEnvoyer'), array(
                    'type' => 'submit',
                    'class' => 'btn btn-default-success',
                    'escape' => false
                )),
                ['class' => 'btn-group']
            )
            .$this->Form->end()
        ]
    ]
);