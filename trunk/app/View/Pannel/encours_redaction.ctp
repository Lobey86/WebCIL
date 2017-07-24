<?php
echo $this->Html->script('pannel.js');
echo $this->Html->script('registre.js');

$params = ['limit' => false];

// balise du scrollTo
$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);

// Banette mes déclarations en cours de rédaction
echo $this->Banettes->encoursRedaction($banettes['encours_redaction'], $params);

// Pop-up envoie consultation
echo $this->element(
    'modal',
    [
        'modalId' => 'modalEnvoieConsultation',
        'content' => [
            'title' => __d('pannel', 'pannel.popupEnvoyerTraitementConsultation'),
            'body' => $this->Html->tag(
                'div',
                $this->Form->create('EtatFiche', array('action' => 'askAvis'))
                .$this->Form->input('destinataire', [
                    'class' => 'form-control usersDeroulant transformSelect form-control bottom5',
                    'label' => [
                        'text' => __d('pannel', 'pannel.textSelectUserConsultant') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'options' => $consultants,
                    'empty' => __d('pannel', 'pannel.textSelectUserConsultant'),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'required' => true,
                    'autocomplete' => 'off',
                    'id' => 'destinataireCons'
                ])
                .$this->Form->hidden('ficheNum', ['id' => 'ficheNumCons'])
                .$this->Form->hidden('etatFiche', ['id' => 'etatFicheCons']),
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

// Pop-up envoie validation
echo $this->element(
    'modal',
    [
        'modalId' => 'modalEnvoieValidation',
        'content' => [
            'title' => __d('pannel', 'pannel.popupEnvoyerTraitementValidation'),
            'body' => $this->Html->tag(
                'div',
                $this->Form->create('EtatFiche', array('action' => 'sendValidation'))
                .$this->Form->input('destinataire', [
                    'class' => 'form-control usersDeroulant transformSelect form-control bottom5',
                    'label' => [
                        'text' => __d('pannel', 'pannel.textSelectUserValideur') . '<span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'options' => $validants,
                    'empty' => __d('pannel', 'pannel.textSelectUserValideur'),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'required' => true,
                    'autocomplete' => 'off',
                    'id' => 'destinataireVal'
                ])
                .$this->Form->hidden('ficheNum', ['id' => 'ficheNumVal'])
                .$this->Form->hidden('etatFiche', ['id' => 'etatFicheVal']),
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