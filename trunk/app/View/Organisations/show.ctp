<?php
echo $this->Html->script('organisations.js');
if ( isset($this->validationErrors[ 'Organisation' ]) && !empty($this->validationErrors[ 'Organisation' ]) ) {
    ?>

    <div class="alert alert-danger" role="alert">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        <span class="sr-only">Error:</span>
        Ces erreurs se sont produites:
        <ul>
            <?php
            foreach ( $this->validationErrors as $donnees ) {
                foreach ( $donnees as $champ ) {
                    foreach ( $champ as $error ) {
                        echo '<li>' . $error . '</li>';
                    }
                }
            }
            ?>
        </ul>
    </div>
<?php
}
?>
<div class="users form">
    <?php echo $this->Form->create('Organisation', array(
        'action' => 'edit',
        'type' => 'file',
        'autocomplete' => 'off',
        'class' => 'form-horizontal'
    )); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $this->Form->input('raisonsociale', array(
                    'class' => 'form-control',
                    'placeholder' => 'Raison sociale (requis)',
                    'label' => array(
                        'text' => 'Raison sociale <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'escape' => true
                )); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('telephone', array(
                    'class' => 'form-control',
                    'placeholder' => 'Téléphone (requis)',
                    'label' => array(
                        'text' => 'Téléphone <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('fax', array(
                    'class' => 'form-control',
                    'placeholder' => 'Fax (facultatif)',
                    'label' => array(
                        'text' => 'Fax',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('adresse', array(
                    'div' => 'input-group inputsForm',
                    'label' => array(
                        'text' => 'Adresse <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>',
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'placeholder' => 'Adresse (requis)'
                )); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('email', array(
                    'class' => 'form-control',
                    'placeholder' => 'E-mail (requis)',
                    'label' => array(
                        'text' => 'E-mail <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $this->Form->input('sigle', array(
                    'class' => 'form-control',
                    'placeholder' => 'Sigle (facultatif)',
                    'label' => array(
                        'text' => 'Sigle',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('siret', array(
                    'class' => 'form-control',
                    'placeholder' => 'N° SIRET (requis)',
                    'label' => array(
                        'text' => 'N° Siret <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('ape', array(
                    'class' => 'form-control',
                    'placeholder' => 'Code APE (requis)',
                    'label' => array(
                        'text' => 'Code APE <span class="requis">*</span>',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                )); ?>
            </div>
            <div class="form-group">
                <?php
                echo $this->Form->input('cil', array(
                    'options' => $users,
                    'div' => 'input-group inputsForm',
                    'class' => 'form-control usersDeroulant',
                    'empty' => 'Selectionnez un nouveau CIL',
                    'label' => array(
                        'text' => 'Cil',
                        'class' => 'col-md-4 control-label'
                    ),
                    'between' => '<div class="col-md-8">',
                    'after' => '</div>'
                ));
                ?>
            </div>

            <div>
            </div>
            <div class="col-md-8 col-md-offset-4">
                <?php if ( file_exists(IMAGES . 'logos' . DS . $this->request->data[ 'Organisation' ][ 'id' ] . '.' . $this->request->data[ 'Organisation' ][ 'logo' ]) ) {
                    ?>

                    <div class="thumbnail">
                        <?php echo $this->Html->image('logos/' . $this->request->data[ 'Organisation' ][ 'id' ] . '.' . $this->request->data[ 'Organisation' ][ 'logo' ], array(
                            'alt' => 'Logo',
                            'width' => '300px'
                        )); ?>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="text-center">

        <?php
        echo '<div class="btn-group send">';
        echo $this->Html->link('<i class="fa fa-arrow-left"></i> Retour', $referer, array(
            'class' => 'btn btn-default-default',
            'escape' => false
        ));
        echo '</div>';
        ?>
    </div>
</div>
<script type="text/javascript">
    $(":input").prop("disabled", true);
    $(".boutonDl").prop("disabled", false);
</script>