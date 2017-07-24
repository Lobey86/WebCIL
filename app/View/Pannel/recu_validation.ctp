<?php

echo $this->Html->script('pannel.js');
echo $this->Html->script('registre.js');

$params = ['limit' => false];

// balise du scrollTo
$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);

// Banette traitements reçus pour validation
echo $this->Banettes->recuValidation($banettes['recuValidation'], $params);

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

// Pop-up inséré traitement registre
echo $this->element(
    'modal',
    [
        'modalId' => 'modalValidCil',
        'content' => [
            'title' => 'Insertion au registre',            
            'body' => $this->Html->tag(
                'div',
                $this->Html->tag(
                    'div',
                    $this->Html->tag(
                        'div',
                        '<i class="fa fa-fw fa-exclamation-triangle"></i>',
                        ['class' => 'col-md-12 text-center']
                    )
                    .$this->Html->tag(
                        'div',
                        __d('pannel','pannel.confirmationInsererRegistre'),
                        ['class' => 'col-md-12']
                    ),
                    ['class' => 'col-md-12 text-warning']
                ),
                ['class' => 'row']
            )
            .$this->Html->tag(
                'div',
                $this->Html->tag(
                    'div',
                    $this->Form->create('Registre', array('action' => 'add'))
                    .$this->Form->input('numero', [
                        'label' => [
                            'text' => 'Numéro d\'enregistrement',
                            'class' => 'col-md-4 control-label'
                        ],
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'id' => 'numero'
                    ])
                    .$this->Form->input('typedeclaration', [
                        'label' => [
                            'text' => 'Type de déclaration',
                            'class' => 'col-md-4 control-label'
                        ],
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'id' => 'typedeclaration'
                    ])
                    .$this->Form->hidden('idfiche', ['id' => 'idFiche']),
                    ['class' => 'col-md-12 form-horizontal']
                ),
                ['class' => 'row top17']
            ),
            'footer' => $this->Html->tag(
                'div',
                $this->Form->button(
                    '<i class="fa fa-times-circle fa-lg"></i>'
                    .__d('default', 'default.btnAnnuler'),
                    ['class' => 'btn btn-default-default', 'data-dismiss' => 'modal']
                )
                .$this->Form->button("<i class='fa fa-floppy-o fa-lg'></i>" . __d('default', 'default.btnEnregistrer'), array(
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