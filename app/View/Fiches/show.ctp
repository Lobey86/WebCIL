<?php
$col = 1;
$line = 1;

$nameController = $this->Session->read('nameController');
$nameView = $this->Session->read('nameView');
unset($_SESSION['nameController']);
unset($_SESSION['nameView']);

if( $nameController == null){
    $nameController = 'pannel';
}

if( $nameView == null){
    $nameView = 'index';
}

echo $this->Form->create('Fiche', array(
    'action' => 'edit',
    'class' => 'form-horizontal'
));
?>
<div class="form-horizontal">
    <!--    <div class="row">
            <div class="col-md-6">-->
    <?php
//            echo $this->Form->input('declarantraisonsociale', array(
//                'label' => array(
//                    'text' => 'Raison Sociale <span class="obligatoire">*</span>',
//                    'class' => 'col-md-4 control-label'
//                ),
//                'between' => '<div class="col-md-8">',
//                'after' => '</div>',
//                'class' => 'form-control',
//                'readonly' => 'readonly',
//                'div' => 'form-group',
//            ));
//            echo $this->Form->input('declarantservice', array(
//                'label' => array(
//                    'text' => 'Service',
//                    'class' => 'col-md-4 control-label'
//                ),
//                'between' => '<div class="col-md-8">',
//                'after' => '</div>',
//                'class' => 'form-control',
//                'readonly' => 'readonly',
//                'div' => 'form-group',
//            ));
//            echo $this->Form->input('declarantadresse', array(
//                'label' => array(
//                    'text' => 'Adresse <span class="obligatoire">*</span>',
//                    'class' => 'col-md-4 control-label'
//                ),
//                'type' => 'textarea',
//                'between' => '<div class="col-md-8">',
//                'after' => '</div>',
//                'class' => 'form-control',
//                'readonly' => 'readonly',
//                'div' => 'form-group',
//            ));
//            echo $this->Form->input('declarantemail', array(
//                'label' => array(
//                    'text' => 'E-mail <span class="obligatoire">*</span>',
//                    'class' => 'col-md-4 control-label'
//                ),
//                'between' => '<div class="col-md-8">',
//                'after' => '</div>',
//                'class' => 'form-control',
//                'readonly' => 'readonly',
//                'div' => 'form-group',
//            ));
    ?>
    <!--        </div>
            <div class='col-md-6'>-->
    <?php
//            echo $this->Form->input('declarantsigle', array(
//                'label' => array(
//                    'text' => 'Sigle',
//                    'class' => 'col-md-4 control-label'
//                ),
//                'between' => '<div class="col-md-8">',
//                'after' => '</div>',
//                'class' => 'form-control',
//                'readonly' => 'readonly',
//                'div' => 'form-group',
//            ));
//            echo $this->Form->input('declarantsiret', array(
//                'label' => array(
//                    'text' => 'N° de SIRET <span class="obligatoire">*</span>',
//                    'class' => 'col-md-4 control-label'
//                ),
//                'between' => '<div class="col-md-8">',
//                'after' => '</div>',
//                'class' => 'form-control',
//                'readonly' => 'readonly',
//                'div' => 'form-group',
//            ));
//            echo $this->Form->input('declarantape', array(
//                'label' => array(
//                    'text' => 'Code APE <span class="obligatoire">*</span>',
//                    'class' => 'col-md-4 control-label'
//                ),
//                'between' => '<div class="col-md-8">',
//                'after' => '</div>',
//                'class' => 'form-control',
//                'readonly' => 'readonly',
//                'div' => 'form-group',
//            ));
//            echo $this->Form->input('declaranttelephone', array(
//                'label' => array(
//                    'text' => 'Téléphone <span class="obligatoire">*</span>',
//                    'class' => 'col-md-4 control-label'
//                ),
//                'between' => '<div class="col-md-8">',
//                'after' => '</div>',
//                'class' => 'form-control',
//                'readonly' => 'readonly',
//                'div' => 'form-group',
//            ));
//            echo $this->Form->input('declarantfax', array(
//                'label' => array(
//                    'text' => 'Fax',
//                    'class' => 'col-md-4 control-label'
//                ),
//                'between' => '<div class="col-md-8">',
//                'after' => '</div>',
//                'class' => 'form-control',
//                'readonly' => 'readonly',
//                'div' => 'form-group',
//            ));
//            
    ?>
    <!--        </div>
        </div>-->
    <div class="row row35"></div>
    <div class="row">
        <div class="col-md-12">
            <span class='labelFormulaire'>
                <?php echo __d('fiche', 'fiche.textInfoContact');?>
            </span>
            <div class="row row35"></div>
        </div>
        <div class="col-md-6">
            <?php
            echo $this->Form->input('declarantpersonnenom', array(
                'label' => array(
                    'text' => __d('fiche', 'fiche.champNomPrenom') . '<span class="obligatoire">*</span>',
                    'class' => 'col-md-4 control-label'
                ),
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'required' => 'required',
                'div' => 'form-group',
            ));
            ?>
        </div>
        <div class="col-md-6">
            <?php
            echo $this->Form->input('declarantpersonneemail', array(
                'label' => array(
                    'text' => __d('default', 'default.champE-mail') . '<span class="obligatoire">*</span>',
                    'class' => 'col-md-4 control-label'
                ),
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'required' => 'required',
                'div' => 'form-group',
            ));
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php
            echo $this->Form->input('outilnom', array(
                'label' => array(
                    'text' => __d('default', 'default.champNomTraitement') . '<span class="obligatoire">*</span>',
                    'class' => 'col-md-4 control-label'
                ),
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'div' => 'form-group',
                'required' => 'required'
            ));
            ?>

        </div>
        <div class="col-md-6">
            <?php
            echo $this->Form->input('finaliteprincipale', array(
                'label' => array(
                    'text' => __d('default', 'default.champFinalite') . '<span class="obligatoire">*</span>',
                    'class' => 'col-md-4 control-label'
                ),
                'between' => '<div class="col-md-8">',
                'after' => '</div>',
                'class' => 'form-control',
                'div' => 'form-group',
                'type' => 'textarea'
            ));
            ?>
        </div>
    </div>
    <div class="row">
        <div class="row row35"></div>
        <?php
        echo '<hr/>';

        $incrementation_id = 0;

        echo '<div class="col-md-6">';
        foreach ($champs as $value) {
            if ($value['Champ']['colonne'] > $col) {
                echo '</div>';
                echo '<div class="col-md-6">';
                $line = 1;
                $col++;
            }

            if ($value['Champ']['ligne'] > $line) {
                for ($i = $line; $i < $value['Champ']['ligne']; $i++) {
                    echo '<div class="row row35"></div>';
                }

                $line = $value['Champ']['ligne'];
            }

            $options = json_decode($value['Champ']['details'], true);

            $afficherObligation = "";

            if ($options['obligatoire'] == true) {
                $afficherObligation = '<span class="obligatoire"> *</span>';
            }

            echo '<div class="row row35"><div class="col-md-12">';

            switch ($value['Champ']['type']) {
                case 'input':
                    echo $this->Form->input($options['name'], [
                        'label' => [
                            'text' => $options['label'] . $afficherObligation,
                            'class' => 'col-md-4 control-label'
                        ],
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => $options['obligatoire']
                    ]);
                    break;

                case 'textarea':
                    echo $this->Form->input($options['name'], [
                        'label' => [
                            'text' => $options['label'] . $afficherObligation,
                            'class' => 'col-md-4 control-label'
                        ],
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => $options['obligatoire'],
                        'type' => 'textarea'
                    ]);
                    break;

                case 'date':
                    echo $this->Form->input($options['name'], [
                        'label' => [
                            'text' => $options['label'] . $afficherObligation,
                            'class' => 'col-md-4 control-label'
                        ],
                        'id' => 'datetimepicker' . $incrementation_id,
                        'between' => '<div class="col-md-8">',
                        'after' => '</div>',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => $options['obligatoire'],
                    ]);
                    $incrementation_id ++;
                    break;

                case 'title':
                    echo '<div class="col-md-12 text-center"><h1>' . $options['content'] . '</h1></div>';
                    break;

                case 'texte':
                    echo '<div class="form-group"><div class="container"><h5 class="col-md-4 control-label">' . $options['content'] . '</h5></div></div>';
                    break;

                case 'help':
                    echo '<div class="col-md-12 alert alert-info text-center">
                            <div class="col-md-12"><i class="fa fa-fw fa-info-circle fa-2x"></i></div>
                            <div class="col-md-12">' . $options['content'] . '</div>
                        </div>';
                    break;

                case 'checkboxes':
                    echo '<div class="form-group">
                            <label class="col-md-4 control-label">' . $options['label'] . '</label>
                        <div class="col-md-8">';

                    echo $this->Form->input($options['name'], [
                        'label' => false,
                        'type' => 'select',
                        'multiple' => 'checkbox',
                        'options' => $options['options']
                    ]);
                    echo '</div></div>';
                    break;

                case 'deroulant':
                    echo '<div class="form-group">
                            <label class="col-md-4 control-label">' . $options['label'] . $afficherObligation . '</label>
                        <div class="col-md-8">';

                    echo $this->Form->select($options['label'], $options['options'], [
                        'required' => $options['obligatoire'],
                        'empty' => true,
                    ]);

                    echo '</div></div>';
                    break;

                case 'radios':
                    echo '<div class="form-group">
                            <label class="col-md-4 control-label">' . $options['label'] . '</label>
                        <div class="col-md-8">';

                    echo $this->Form->radio($options['name'], $options['options'], [
                        'label' => false,
                        'legend' => false,
                        'separator' => '<br/>'
                    ]);

                    echo '</div></div>';
                    break;
            }
            $line++;
            echo '</div></div>';
        }
        echo '</div></div>';

        if ($files == null) {
            ?>
            <div class="col-md-12 top30">
                <h4><?php echo __d('fiche', 'fiche.textInfoAucunePieceJointe'); ?></h4>
            </div>
            <?php
        } else {
            ?>
            <div class="col-md-12 top30">
                <h4><?php echo __d('fiche', 'fiche.textInfoPieceJointe'); ?></h4>
                <table>
                    <tbody>
                        <tr>
                            <?php
                            foreach ($files as $val) {
                                $ext = explode('.', $val['Fichier']['url']);
                                $ext = strtolower(end($ext));
                                switch ($ext) {
                                    case 'pdf':
                                        $icone = 'file-pdf-o';
                                        break;
                                    case 'xls':
                                    case 'xlsx':
                                        $icone = 'file-excel-o';
                                        break;
                                    case 'doc':
                                    case 'docx':
                                        $icone = 'file-word-o';
                                        break;
                                    case 'zip':
                                    case 'rar':
                                    case 'tar':
                                    case'gz':
                                        $icone = 'file-archive-o';
                                        break;
                                    case 'avi':
                                    case 'mpg':
                                    case 'mpeg':
                                    case 'mkv':
                                    case 'mp4':
                                    case 'mov':
                                        $icone = 'file-video-o';
                                        break;
                                    case 'jpg':
                                    case 'jpeg':
                                    case 'gif':
                                    case 'png':
                                    case 'bmp':
                                        $icone = 'file-image-o';
                                        break;
                                    case 'ppt':
                                    case 'pptx':
                                        $icone = 'file-powerpoint-o';
                                        break;
                                    case 'odt':
                                    case 'ods':
                                    case 'txt':
                                        $icone = 'file-text-o';
                                        break;
                                    default:
                                        $icone = 'file-o';
                                        break;
                                }
                                ?>
                                <tr>
                                <td class="col-md-1">
                                    <i class="fa fa-fw fa-"<?php echo $icone;?>></i>
                                </td>
                                <td class="col-md-9 tdleft">
                                     <?php echo $val['Fichier']['nom'];?>
                                </td>
                                <td class="col-md-2">
                                    <?php 
                                    echo $this->Html->link('<span class="glyphicon glyphicon-download-alt"></span>', array(
                                            'controller' => 'fiches',
                                            'action' => 'download',
                                            $val['Fichier']['url'],
                                            $val['Fichier']['nom']
                                                ), array(
                                            'class' => 'btn btn-default-default boutonShow btn-sm my-tooltip',
                                            'title' => 'Télécharger le fichier',
                                            'escapeTitle' => false
                                        ));?>

                                </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php
        }
        ?>
        <div class="row">
            <div class="col-md-12 top17 text-center">
                <div class="btn-group">
                    <?php
                    echo $this->Html->link('<i class="fa fa-fw fa-arrow-left"></i>'. __d('fiche','fiche.btnRevenir'), array(
                        'controller' => $nameController,
                        'action' => $nameView
                        ), array(
                            'class' => 'btn btn-default-default',
                            'escape' => false
                        ));
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>

<script type="text/javascript">
    $(":input").prop("disabled", true);
    $(".boutonDl").prop("disabled", false);
</script>