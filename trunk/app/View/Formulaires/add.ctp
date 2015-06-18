<?php
echo $this->Html->script(array(
    'FormGenerator.jquery-ui.min',
    'FormGenerator.createForm'
));
echo $this->Html->css(array(
    'FormGenerator.jquery-ui.min',
    'jquery-ui.structure.min'
));
?>
    <div class="col-md-8 form-oblig form-horizontal">
        <div class="row35"></div>
        <div class="row">
            <div class="col-md-6">
                <?php

                echo $this->Form->input('declarantraisonsociale', array(
                    'label' => array(
                        'text' => 'Raison Sociale <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => 'Remplissage automatique',
                ));
                echo $this->Form->input('declarantservice', array(
                    'label' => array(
                        'text' => 'Service',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => 'Remplissage automatique'
                ));
                echo $this->Form->input('declarantadresse', array(
                    'label' => array(
                        'text' => 'Adresse <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'type' => 'textarea',
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => 'Remplissage automatique'
                ));
                echo $this->Form->input('declarantemail', array(
                    'label' => array(
                        'text' => 'E-mail <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => 'Remplissage automatique'
                ));
                ?>

            </div>
            <div class='col-md-6'>
                <?php
                echo $this->Form->input('declarantsigle', array(
                    'label' => array(
                        'text' => 'Sigle',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => 'Remplissage automatique'
                ));
                echo $this->Form->input('declarantsiret', array(
                    'label' => array(
                        'text' => 'N° de SIRET <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => 'Remplissage automatique'
                ));
                echo $this->Form->input('declarantape', array(
                    'label' => array(
                        'text' => 'Code APE <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => 'Remplissage automatique'
                ));
                echo $this->Form->input('declaranttelephone', array(
                    'label' => array(
                        'text' => 'Téléphone <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => 'Remplissage automatique'
                ));
                echo $this->Form->input('declarantfax', array(
                    'label' => array(
                        'text' => 'Fax',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'div' => 'form-group',
                    'value' => 'Remplissage automatique'
                ));
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
                echo $this->Form->input('declarantpersonnenom', array(
                    'label' => array(
                        'text' => 'Nom <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'required' => 'required',
                    'div' => 'form-group',
                    'value' => 'Remplissage automatique',
                    'readonly' => 'readonly'
                ));
                ?>
            </div>
            <div class="col-md-6">
                <?php
                echo $this->Form->input('declarantpersonneemail', array(
                    'label' => array(
                        'text' => 'E-mail <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'required' => 'required',
                    'div' => 'form-group',
                    'value' => 'Remplissage automatique',
                    'readonly' => 'readonly'
                ));
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?php
                echo $this->Form->input('outilnom', array(
                    'label' => array(
                        'text' => 'Nom du traitement <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'readonly' => 'readonly'
                ));
                ?>
            </div>
            <div class="col-md-6">
                <?php
                echo $this->Form->input('finaliteprincipale', array(
                    'label' => array(
                        'text' => 'Finalité <span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'readonly' => 'readonly',
                    'type' => 'textarea'
                ));
                ?>
            </div>
        </div>
    </div>
    <div id="form-container" class="col-md-8">

    </div>
    <div class="col-md-offset-4" id="field-affix">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default panel-affix">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Options du champ </h3>
                    </div>
                    <div class="panel-body" id="field-options">
                    </div>
                </div>
            </div>
            <div class="btn-group-vertical col-md-6">
                <button class="btn btn-default-default btn-sm btn-input" id="btn-small-text"><i
                        class="fa fa-font fa-fw"></i>
                    Petit
                    champ
                    texte
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-long-text"><i
                        class="fa fa-text-height fa-fw"></i> Grand
                                                             champ
                                                             texte
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-date"><i
                        class="fa fa-calendar fa-fw"></i>
                    Champ
                    date
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-checkbox"><i
                        class="fa fa-check-square-o fa-fw"></i> Cases
                                                                à
                                                                cocher
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-radio"><i
                        class="fa fa-check-circle-o fa-fw"></i> Choix
                                                                unique
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-title"><i class="fa fa-tag fa-fw"></i>
                    Titre de
                    catégorie
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-help"><i
                        class="fa fa-info-circle fa-fw"></i>
                    Champ
                    d'information
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-8 form-oblig form-horizontal">
        <?php
        echo $this->Form->input('file', array(
            'type' => 'file',
            'label' => 'Fichiers',
            'multiple',
            'class' => 'filestyle fichiers draggable',
            'data-buttonText' => 'Parcourir',
            'data-buttonName' => "btn-primary",
            'data-buttonBefore' => "true"
        ));
        ?>
    </div>
    <div class="top30 btn-group col-md-12">
        <button class="btn btn-default-success" id="successForm"><i class="fa fa-fw fa-check"></i> Enregistrer ce
                                                                                                   formulaire
        </button>
    </div>
<?php

echo $this->Form->create('Formulaire', array(
    'action' => 'add',
    'id' => 'addForm'
));
echo $this->Form->hidden('id', array('value' => $id));
echo $this->Form->hidden('json', array('id' => 'hiddenForm'));
echo $this->Form->end();