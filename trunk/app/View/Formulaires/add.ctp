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
        <!--<div class="row35"></div>-->
        <!--<div class="row">-->
            <!--<div class="col-md-6">-->
                <?php
//                echo $this->Form->input('declarantraisonsociale', array(
//                    'label' => array(
//                        'text' => 'Raison Sociale <span class="obligatoire">*</span>',
//                        'class' => 'col-md-4 control-label'
//                    ),
//                    'between' => '<div class="col-md-8">',
//                    'after' => '</div>',
//                    'class' => 'form-control',
//                    'readonly' => 'readonly',
//                    'div' => 'form-group',
//                    'value' => $organisation['Organisation']['raisonsociale']
//                ));
//                echo $this->Form->input('declarantservice', array(
//                    'label' => array(
//                        'text' => 'Service',
//                        'class' => 'col-md-4 control-label'
//                    ),
//                    'between' => '<div class="col-md-8">',
//                    'after' => '</div>',
//                    'class' => 'form-control',
//                    'readonly' => 'readonly',
//                    'div' => 'form-group',
//                    'value' => $organisation['Organisation']['service']
//                ));
//                echo $this->Form->input('declarantadresse', array(
//                    'label' => array(
//                        'text' => 'Adresse <span class="obligatoire">*</span>',
//                        'class' => 'col-md-4 control-label'
//                    ),
//                    'type' => 'textarea',
//                    'between' => '<div class="col-md-8">',
//                    'after' => '</div>',
//                    'class' => 'form-control',
//                    'readonly' => 'readonly',
//                    'div' => 'form-group',
//                    'value' => $organisation['Organisation']['adresse']
//                ));
//                echo $this->Form->input('declarantemail', array(
//                    'label' => array(
//                        'text' => 'E-mail <span class="obligatoire">*</span>',
//                        'class' => 'col-md-4 control-label'
//                    ),
//                    'between' => '<div class="col-md-8">',
//                    'after' => '</div>',
//                    'class' => 'form-control',
//                    'readonly' => 'readonly',
//                    'div' => 'form-group',
//                    'value' => $organisation['Organisation']['email']
//                ));
               ?>
            <!--</div>-->
            <!--<div class='col-md-6'>-->
                <?php
//                echo $this->Form->input('declarantsigle', array(
//                    'label' => array(
//                        'text' => 'Sigle',
//                        'class' => 'col-md-4 control-label'
//                    ),
//                    'between' => '<div class="col-md-8">',
//                    'after' => '</div>',
//                    'class' => 'form-control',
//                    'readonly' => 'readonly',
//                    'div' => 'form-group',
//                    'value' => $organisation['Organisation']['sigle']
//                ));
//                echo $this->Form->input('declarantsiret', array(
//                    'label' => array(
//                        'text' => 'N° de SIRET <span class="obligatoire">*</span>',
//                        'class' => 'col-md-4 control-label'
//                    ),
//                    'between' => '<div class="col-md-8">',
//                    'after' => '</div>',
//                    'class' => 'form-control',
//                    'readonly' => 'readonly',
//                    'div' => 'form-group',
//                    'value' => $organisation['Organisation']['siret']
//                ));
//                echo $this->Form->input('declarantape', array(
//                    'label' => array(
//                        'text' => 'Code APE <span class="obligatoire">*</span>',
//                        'class' => 'col-md-4 control-label'
//                    ),
//                    'between' => '<div class="col-md-8">',
//                    'after' => '</div>',
//                    'class' => 'form-control',
//                    'readonly' => 'readonly',
//                    'div' => 'form-group',
//                    'value' => $organisation['Organisation']['ape']
//                ));
//                echo $this->Form->input('declaranttelephone', array(
//                    'label' => array(
//                        'text' => 'Téléphone <span class="obligatoire">*</span>',
//                        'class' => 'col-md-4 control-label'
//                    ),
//                    'between' => '<div class="col-md-8">',
//                    'after' => '</div>',
//                    'class' => 'form-control',
//                    'readonly' => 'readonly',
//                    'div' => 'form-group',
//                    'value' => $organisation['Organisation']['telephone']
//                ));
//                echo $this->Form->input('declarantfax', array(
//                    'label' => array(
//                        'text' => 'Fax',
//                        'class' => 'col-md-4 control-label'
//                    ),
//                    'between' => '<div class="col-md-8">',
//                    'after' => '</div>',
//                    'class' => 'form-control',
//                    'readonly' => 'readonly',
//                    'div' => 'form-group',
//                    'value' => $organisation['Organisation']['fax']
//                ));
                ?>
            <!--</div>-->
        <!--</div>-->
        <div class="row row35"></div>
        <div class="row">
            <div class="col-md-12">
                <span class='labelFormulaire'><?php echo __d('formulaire','formulaire.textInfo');?></span>
                <div class="row row35"></div>
            </div>
            <div class="col-md-6">
                <?php
                echo $this->Form->input('declarantpersonnenom', array(
                    'label' => array(
                        'text' => __d('default','default.champNom').'<span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'required' => 'required',
                    'div' => 'form-group',
                    'value' => __d('default','default.valueChampRemplissageAuto'),
                    'readonly' => 'readonly'
                ));
                ?>
            </div>
            <div class="col-md-6">
                <?php
                echo $this->Form->input('declarantpersonneemail', array(
                    'label' => array(
                        'text' => __d('default','default.champE-mail').'<span class="obligatoire">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'required' => 'required',
                    'div' => 'form-group',
                    'value' => __d('default','default.valueChampRemplissageAuto'),
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
                        'text' => __d('default','default.champNomTraitement').'<span class="obligatoire">*</span>',
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
                        'text' => __d('default','default.champFinalite').'<span class="obligatoire">*</span>',
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
                        <h3 class="panel-title"><?php echo __d('formulaire','formulaire.textOptionChamp');?></h3>
                    </div>
                    <div class="panel-body" id="field-options">
                    </div>
                </div>
            </div>
            <div class="btn-group-vertical col-md-6">
                <button class="btn btn-default-default btn-sm btn-input" id="btn-small-text">
                    <i class="fa fa-font fa-lg fa-fw"></i>
                    <?php echo __d ('formulaire','formulaire.btnPetitChamp');?>
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-long-text">
                    <i class="fa fa-text-height fa-lg fa-fw"></i> 
                    <?php echo __d ('formulaire','formulaire.btnGrandChamp');?>
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-date">
                    <i class="fa fa-calendar fa-lg fa-fw"></i>
                    <?php echo __d ('formulaire','formulaire.btnChampDate');?>
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-checkbox">
                    <i class="fa fa-check-square-o fa-lg fa-fw"></i> 
                    <?php echo __d ('formulaire','formulaire.btnCheckbox');?>
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-radio">
                    <i class="fa fa-check-circle-o fa-lg fa-fw"></i> 
                    <?php echo __d ('formulaire','formulaire.btnRadio');?>
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-deroulant">
                    <i class="fa fa-list-alt fa-lg fa-fw"></i>
                    <?php echo __d ('formulaire','formulaire.btnDeroulant');?>
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-title">
                    <i class="fa fa-tag fa-lg fa-fw"></i>
                    <?php echo __d ('formulaire','formulaire.btnTitreCategorie');?>
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-help">
                    <i class="fa fa-info-circle fa-lg fa-fw"></i>
                    <?php echo __d ('formulaire','formulaire.btnChampInfo');?>
                </button>
                <button class="btn btn-default-default btn-sm btn-input" id="btn-texte">
                    <i class="fa fa-pencil fa-lg fa-fw"></i>
                    <?php echo __d ('formulaire','formulaire.btnLabel');?>
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-8 form-oblig form-horizontal">
        <?php
        echo $this->Form->input('file', array(
            'type' => 'file',
            'label' => __d('formulaire','formulaire.champFichier'),
            'multiple',
            'class' => 'filestyle fichiers draggable',
            'data-buttonText' => __d('default','default.btnParcourir'),
            'data-buttonName' => "btn-primary",
            'data-buttonBefore' => "true"
        ));
        ?>
    </div>

    <div class="top30 btn-group col-md-12">
        <button class="btn btn-default-success" id="successForm"><i class="fa fa-fw fa-check"></i>
            <?php echo __d('formulaire','formulaire.btnEnregistreFormulaire');?>
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