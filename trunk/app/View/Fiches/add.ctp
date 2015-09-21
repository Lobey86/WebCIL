<?php
    $col = 1;
    $line = 1;
    echo $this->Form->create('Fiche', [
        'action' => 'add',
        'class'  => 'form-horizontal',
        'type'   => 'file'
    ]);
?>
    <div class="row">
        <div class="col-md-6">
            <?php

                echo $this->Form->input('declarantraisonsociale', [
                    'label'    => [
                        'text'  => 'Raison Sociale <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'  => '<div class="col-md-8">',
                    'after'    => '</div>',
                    'class'    => 'form-control',
                    'readonly' => 'readonly',
                    'div'      => 'form-group',
                    'value'    => $this->Session->read('Organisation.raisonsociale'),
                ]);
                echo $this->Form->input('declarantservice', [
                    'label'    => [
                        'text'  => 'Service',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'  => '<div class="col-md-8">',
                    'after'    => '</div>',
                    'class'    => 'form-control',
                    'readonly' => 'readonly',
                    'div'      => 'form-group',
                    'value'    => $this->Session->read('User.service')
                ]);
                echo $this->Form->input('declarantadresse', [
                    'label'    => [
                        'text'  => 'Adresse <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'type'     => 'textarea',
                    'between'  => '<div class="col-md-8">',
                    'after'    => '</div>',
                    'class'    => 'form-control',
                    'readonly' => 'readonly',
                    'div'      => 'form-group',
                    'value'    => $this->Session->read('Organisation.adresse')
                ]);
                echo $this->Form->input('declarantemail', [
                    'label'    => [
                        'text'  => 'E-mail <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'  => '<div class="col-md-8">',
                    'after'    => '</div>',
                    'class'    => 'form-control',
                    'readonly' => 'readonly',
                    'div'      => 'form-group',
                    'value'    => $this->Session->read('Organisation.email')
                ]);
            ?>

        </div>
        <div class='col-md-6'>
            <?php
                echo $this->Form->input('declarantsigle', [
                    'label'    => [
                        'text'  => 'Sigle',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'  => '<div class="col-md-8">',
                    'after'    => '</div>',
                    'class'    => 'form-control',
                    'readonly' => 'readonly',
                    'div'      => 'form-group',
                    'value'    => $this->Session->read('Organisation.sigle')
                ]);
                echo $this->Form->input('declarantsiret', [
                    'label'    => [
                        'text'  => 'N° de SIRET <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'  => '<div class="col-md-8">',
                    'after'    => '</div>',
                    'class'    => 'form-control',
                    'readonly' => 'readonly',
                    'div'      => 'form-group',
                    'value'    => $this->Session->read('Organisation.siret')
                ]);
                echo $this->Form->input('declarantape', [
                    'label'    => [
                        'text'  => 'Code APE <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'  => '<div class="col-md-8">',
                    'after'    => '</div>',
                    'class'    => 'form-control',
                    'readonly' => 'readonly',
                    'div'      => 'form-group',
                    'value'    => $this->Session->read('Organisation.ape')
                ]);
                echo $this->Form->input('declaranttelephone', [
                    'label'    => [
                        'text'  => 'Téléphone <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'  => '<div class="col-md-8">',
                    'after'    => '</div>',
                    'class'    => 'form-control',
                    'readonly' => 'readonly',
                    'div'      => 'form-group',
                    'value'    => $this->Session->read('Organisation.telephone')
                ]);
                echo $this->Form->input('declarantfax', [
                    'label'    => [
                        'text'  => 'Fax',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'  => '<div class="col-md-8">',
                    'after'    => '</div>',
                    'class'    => 'form-control',
                    'readonly' => 'readonly',
                    'div'      => 'form-group',
                    'value'    => $this->Session->read('Organisation.fax')
                ]);
            ?>
        </div>
    </div>
    <div class="row row35"></div>
    <div class="row">
        <div class="col-md-12">
            <span class='labelFormulaire'>Personne à contacter au sein de l'organisme déclarant si un complément doit être demandé et destinataire du récipissé:</span>
            <div class="row row35"></div>
        </div>
        <div class="col-md-6">
            <?php
                echo $this->Form->input('declarantpersonnenom', [
                    'label'    => [
                        'text'  => 'Nom et prénom <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'  => '<div class="col-md-8">',
                    'after'    => '</div>',
                    'class'    => 'form-control',
                    'required' => 'required',
                    'div'      => 'form-group',
                    'value'    => $this->Session->read('Auth.User.prenom') . ' ' . $this->Session->read('Auth.User.nom')
                ]);
            ?>
        </div>
        <div class="col-md-6">
            <?php
                echo $this->Form->input('declarantpersonneemail', [
                    'label'    => [
                        'text'  => 'E-mail <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'  => '<div class="col-md-8">',
                    'after'    => '</div>',
                    'class'    => 'form-control',
                    'required' => 'required',
                    'div'      => 'form-group',
                    'value'    => $this->Session->read('Auth.User.email')
                ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php
                echo $this->Form->input('outilnom', [
                    'label'    => [
                        'text'  => 'Nom du traitement <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'  => '<div class="col-md-8">',
                    'after'    => '</div>',
                    'class'    => 'form-control',
                    'div'      => 'form-group',
                    'required' => 'required'
                ]);
            ?>

        </div>
        <div class="col-md-6">
            <?php
                echo $this->Form->input('finaliteprincipale', [
                    'label'   => [
                        'text'  => 'Finalité <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ],
                    'between' => '<div class="col-md-8">',
                    'after'   => '</div>',
                    'class'   => 'form-control',
                    'div'     => 'form-group',
                    'type'    => 'textarea'
                ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="row row35"></div>
<?php
    echo '
<div class="col-md-6">';
    foreach($champs as $value) {
        if($value['Champ']['colonne'] > $col) {
            echo '
</div>';
            echo '
<div class="col-md-6">';
            $line = 1;
            $col++;
        }
        if($value['Champ']['ligne'] > $line) {
            for($i = $line; $i < $value['Champ']['ligne']; $i++) {
                echo '
    <div class="row row35"></div>
                      ';
            }
            $line = $value['Champ']['ligne'];
        }
        $options = json_decode($value['Champ']['details'], TRUE);
        echo '
    <div class="row row35">
    <div class="col-md-12">';
        switch($value['Champ']['type']) {
            case 'input':
                echo $this->Form->input($options['name'], [
                    'label'       => [
                        'text'  => $options['label'],
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>',
                    'class'       => 'form-control',
                    'div'         => 'form-group',
                    'placeholder' => $options['placeholder'],
                    'required'    => $options['obligatoire']
                ]);

                break;
            case 'textarea':
                echo $this->Form->input($options['name'], [
                    'label'       => [
                        'text'  => $options['label'],
                        'class' => 'col-md-4 control-label'
                    ],
                    'between'     => '<div class="col-md-8">',
                    'after'       => '</div>',
                    'class'       => 'form-control',
                    'div'         => 'form-group',
                    'placeholder' => $options['placeholder'],
                    'required'    => $options['obligatoire'],
                    'type'        => 'textarea'
                ]);
                break;
            case 'date':
                echo '<div class="form-group"><label class="col-md-4 control-label">' . $options['label'] . '</label><div class="col-md-8"><input type="date" class="form-control" placeholder="' . $options['placeholder'] . '" required="' . $options['obligatoire'] . '"/></div></div>';
                break;
            case 'title':
                echo '<div class="col-md-12 text-center"><h1>' . $options['content'] . '</h1></div>';
                break;
            case 'help':
                echo '<div class="col-md-12 alert alert-info text-center">
            <div class="col-md-12"><i class="fa fa-fw fa-info-circle fa-2x"></i></div>
            <div class="col-md-12">' . $options['content'] . '</div>
        </div>';
                break;
            case 'checkboxes':
                if($options['obligatoire']) {
                    $oblig = 'required = "required"';
                } else {
                    $oblig = '';
                }
                echo '
        <div class="form-group">
            <label class="col-md-4 control-label">' . $options['label'] . '</label>
            <div class="col-md-8">';
                $opt = [];
                foreach($options['options'] as $va) {
                    $opt[$va] = $va;
                }
                echo $this->Form->input($options['name'], [
                    'label'    => FALSE,
                    'type'     => 'select',
                    'multiple' => 'checkbox',
                    'options'  => $opt,
                ]);
                echo '
            </div>
        </div>
                           ';
                break;
            case 'radios':
                echo '
        <div class=" col-md-12 form-group">
            <label class="col-md-4 control-label">' . $options['label'] . '</label>
            <div class="col-md-8 radio">';
                echo $this->Form->radio($options['name'], $options['options'], [
                    'label'     => FALSE,
                    'legend'    => FALSE,
                    'separator' => '<br/>'
                ]);
                echo '
            </div>
        </div>
                           ';
                break;
        }
        $line++;
        echo '</div>
</div>';

    }
    echo '
</div>
</div>
<hr/>';


    echo '<div class="col-md-6 form-horizontal top17">' . $this->Form->input('fichiers.', [
            'type'    => 'file',
            'label'   => [
                'text'  => 'Fichiers',
                'class' => 'col-md-4 control-label'
            ],
            'between' => '<div class="col-md-8">',
            'after'   => '</div>',
            'class'   => 'filestyle fichiers draggable',
            'div'     => 'form-group',
            'multiple'
        ]) . '</div>';
    echo '
<div class="row">';
    echo $this->Form->hidden('formulaire_id', ['value' => $formulaireid]);
    echo '<div class="col-md-12 top17 text-center"><div class="btn-group">';

    echo $this->Html->link('<i class="fa fa-fw fa-arrow-left"></i> Annuler', [
        'controller' => 'pannel',
        'action'     => 'index'
    ], [
        'class'  => 'btn btn-default-default',
        'escape' => FALSE
    ]);
    echo $this->Form->button('<i class="fa fa-fw fa-check"></i> Enregistrer', [
        'class'  => 'btn btn-default-success',
        'escape' => FALSE,
        'type'   => 'submit'
    ]);
    echo $this->Form->end();
    echo '
    </div></div>
                 ';
    echo '
</div>';