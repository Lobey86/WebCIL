<?php

echo $this->Html->script('pannel.js');
echo $this->Html->script('registre.js');

$params = ['limit' => 5];

// balise du scrollTo
$idFicheNotification = $this->Session->read('idFicheNotification');
unset($_SESSION['idFicheNotification']);

// Banette mes déclarations en cours de rédaction
if(true === isset($banettes['encours_redaction'])) {
    echo $this->Banettes->encoursRedaction($banettes['encours_redaction'], $params);
}

// Banette mes déclarations en attente 
if(true === isset($banettes['attente'])) {
    echo $this->Banettes->attente($banettes['attente'], $params);
}

// Banette mes déclarations refusées 
if(true === isset($banettes['refuser'])) {
    echo $this->Banettes->refuser($banettes['refuser'], $params);
}

// Banette traitements reçus pour validation
if(true === isset($banettes['recuValidation'])) {
    echo $this->Banettes->recuValidation($banettes['recuValidation'], $params);
}

// Banette traitements reçus pour consultation
if(true === isset($banettes['recuConsultation'])) {
    echo $this->Banettes->recuConsultation($banettes['recuConsultation'], $params);
}

// Banette mes traitements validés et insérés au registre
if(true === isset($banettes['archives'])) {
    echo $this->Banettes->archives($banettes['archives'], $params);
}

// Banette etat des traitements passés en ma possession
if(true === isset($banettes['consulte'])) {
    echo $this->Banettes->consulte($banettes['consulte'], $params);
}

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

/*
<div class="modal fade" id="modalValidCil" tabindex="-1" role="dialog" aria-labelledby="myModalLabelValidCil">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabelValidCil">Insertion au registre</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-warning">
                        <div class="col-md-12 text-center">
                            <i class="fa fa-fw fa-exclamation-triangle"></i>
                        </div>
                        <div class="col-md-12">
                            <?php echo __d('pannel','pannel.confirmationInsererRegistre');?>
                        </div>
                    </div>
                </div>
                <div class="row top17">
                    <div class="col-md-12">
                        <?php
                        echo $this->Form->create('Registre', [
                            'action' => 'add',
                            'class' => 'form-horizontal'
                        ]);

                        echo $this->Form->input('numero', [
                            'label' => [
                                'text' => 'Numéro d\'enregistrement',
                                'class' => 'col-md-4 control-label'
                            ],
                            'between' => '<div class="col-md-8">',
                            'after' => '</div>',
                            'class' => 'form-control',
                            'div' => 'form-group',
                            'id' => 'numero'
                        ]);
                        
                        echo $this->Form->input('typedeclaration', [
                            'label' => [
                                'text' => 'Type de déclaration',
                                'class' => 'col-md-4 control-label'
                            ],
                            'between' => '<div class="col-md-8">',
                            'after' => '</div>',
                            'class' => 'form-control',
                            'div' => 'form-group',
                            'id' => 'typedeclaration'
                        ]);

                        echo $this->Form->hidden('idfiche', ['id' => 'idFiche']);
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button type="button" class="btn btn-default-default" data-dismiss="modal">
                        <i class="fa fa-times-circle fa-lg"></i>
                        <?php echo __d('default', 'default.btnAnnuler'); ?>
                    </button>
                    <?php
                    echo $this->Form->button("<i class='fa fa-floppy-o fa-lg'></i>" . __d('default', 'default.btnEnregistrer'), array(
                        'type' => 'submit',
                        'class' => 'btn btn-default-success',
                        'escape' => false
                    ));
                    ?>
                </div>
                <?php
                echo $this->Form->end();
                ?>
            </div> 
        </div>
    </div>
</div>
*/
?>

<!-- Modal de notification -->
<?php
$arrayNotificationNotVuNotAfficher = [];

foreach ($notifications as $key => $value) {
    if ($value['Notification']['vu'] == false && $value['Notification']['afficher'] == false) {
        array_push($arrayNotificationNotVuNotAfficher, $value['Notification']['fiche_id']);
    }
}

if (!empty($notifications) && !empty($arrayNotificationNotVuNotAfficher)) {
    $this->Organisation = new Organisation();

    echo '<div class="modal fade" id="modalNotif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Nouvelles notifications</h4>
            </div>
        <div class="modal-body">';

    $oldmairie = '';

    foreach ($notifications as $key => $value) {
        $mairie = $nameOrganisation[$key]['Organisation']['raisonsociale'];

        if ($oldmairie != $mairie) {
            echo '<div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">' . $mairie . '</h5>
                    </div>';
        }

        switch ($value['Notification']['content']) {
            case 1:
                // Demande d'avis
                echo '<a href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/recuConsultation/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-info">'.__d('default','default.notificationAvisDemandeTraitement').' <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></a>';
                break;
            case 2:
                // Validation demandée
                echo '<a href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/recuValidation/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-info">'.__d('default','default.notificationValidationDemandeTraitement').' <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></a>';
                break;
            case 3:
                // Traitement validé
                echo '<a href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/registres/index/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-success">'.__d('default','default.notificationLeTraitement').' <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong>'.__d('default','default.notificationTraitementValidee').'</a>';
                break;
            case 4:
                // Traitement refusé
                echo '<a id="refus" href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/refuser/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-danger">'.__d('default','default.notificationLeTraitement').' <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong>'.__d('default','default.notificationTraitementRefusee').'</a>';
                break;
            case 5:
                // Commentaire ajouté sur le traitement
                echo '<a href="organisations/changenotification/' . $value['Fiche']['organisation_id'] . '/pannel/consulte/' . $value['Fiche']['id'] . '" class="list-group-item list-group-item-info">'.__d('default','default.notificationCommentaireAjouterTraitement').' <strong>"' . $value['Fiche']['Valeur'][0]['valeur'] . '"</strong></a>';
                break;
        }

        $oldmairie = $mairie;

        $this->requestAction(array(
            'controller' => 'pannel',
            'action' => 'notifAfficher',
            $arrayNotificationNotVuNotAfficher[$key]
        ));
    }

    echo '</div>
                <div class="modal-footer">';

    echo $this->Html->link("<i class='fa fa-times fa-lg'></i>" . ' Fermer', [
        'controller' => 'pannel',
        'action' => 'validNotif'
            ], [
        'class' => 'btn btn-default-primary',
        'escapeTitle' => false
    ]);

    echo '</div>
                </div>
                </div>
               </div>
            </div>';
}
?>

<script type="text/javascript">

    $(document).ready(function () {

        openTarget("<?php echo $idFicheNotification ?>");
        
    });

</script>