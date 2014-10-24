<?php
echo $this->Html->css("Cakeflow.design.css");
$this->pageTitle = $request . " d'une composition à l'étape du circuit de traitement";

echo $this->Html->tag('h2', $this->pageTitle);

echo $this->Form->create('Composition', array('url' => Router::url(null, true)));
if ($this->action == 'edit')
    echo $this->Html->tag('div', $this->Form->input('Composition.id', array('type' => 'hidden')));

echo $this->Html->tag('div', $this->Form->input('Composition.etape_id', array('type' => 'hidden')));

echo $this->Html->tag('div', null, array('style' => "float: left; margin-bottom:10px;"));
// sélection du type de composition
echo $this->Form->input('Composition.type_composition', array(
    'type' => 'select',
    'empty' => $canAddParapheur,
    'label' => 'Type de composition'));
echo $this->Html->tag('/div');

echo $this->Html->tag('div', null, array('id' => 'userDiv', 'style' => "display: none; float: left;"));
echo $this->Html->tag('div', "<i class='fa fa-arrow-right'></i>", array('style' => "float: left; position: relative; top: 28px; margin-right:20px; margin-left:20px;"));

echo $this->Html->tag('div', null, array('style' => 'float: left;'));
echo $this->Form->input('Composition.trigger_id', array('id' => 'selectUser', 'type' => 'select', 'empty' => true, 'required' => true, 'label' => CAKEFLOW_TRIGGER_TITLE, "style"=>'color:#555;'));
echo $this->Html->tag('/div');
echo $this->Html->tag('/div');

if ($canAddParapheur){
    echo $this->Html->tag('div', null, array('id'=>'soustype', 'style' => "display:none; float: left;")); 
    echo $this->Html->tag('div', "<i class='fa fa-arrow-right'></i>", array('style' => "float: left; position: relative; top: 28px; margin-right:20px; margin-left:20px;"));

    echo $this->Html->tag('div', null, array('style' => 'float: left;'));
    echo $this->Form->input( 'soustype', array('type'=> 'select', 'label' => __( 'Sous-Types de "'.Configure::read('IPARAPHEUR_TYPE').'"', true ), 'empty' => false, 'options' => $soustypes) );
    echo $this->Html->tag('/div');
    echo $this->Html->tag('/div');
} //FIN IF USE_IPARAPHEUR

if(CAKEFLOW_GERE_SIGNATURE){
    echo $this->Html->tag('div', null, array('style' => 'float: left; margin-bottom: 20px; clear: both;', 'id' => 'typeValidation'));
    echo $this->Form->input('Composition.type_validation', array('type' => 'radio', 'label' => 'Type de validation'));
    echo $this->Html->tag('/div');
}else{
    echo $this->Html->tag('div', $this->Form->input('Composition.type_validation', array('type' => 'hidden', 'value' => 'V')));
}
echo $this->Html->tag('div', null, array('class' => 'submit', 'style' => 'clear: both;'));

echo $this->Html->tag("div", null, array("class" => "btn-group"));
echo $this->Html->link('<i class="fa fa-arrow-left"></i> Annuler', array('action' => 'index', $this->data['Composition']['etape_id']), array('class' => 'btn', 'escape' => false, 'title' => 'Annuler'));
echo $this->Form->button('<i class="fa fa-check"></i> Valider', array('type' => 'submit', 'id' => 'boutonValider', 'class' => 'btn btn-primary', 'escape' => false, 'title' => 'Enregistrer la composition'));
echo $this->Html->tag('/div', null);

echo $this->Html->tag('/div', null);
echo $this->Form->end();
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#boutonValider").hide();
        <?php
        if (!$canAddParapheur){
//            echo "$(\"option[value='PARAPHEUR']\").attr('disabled', true)";
            echo "$(\"option[value='PARAPHEUR']\").hide();";
//            echo "$('#userDiv').show();";
        }
        if (CAKEFLOW_GERE_SIGNATURE){ 
            echo '$("#typeValidation").hide();';
            echo '$("#CompositionTypeValidationD").hide();';
            echo '$("label[for=\'CompositionTypeValidationD\']").hide();';
        }
        ?>
        $('#CompositionTypeComposition').on('change', onChangeAction);
        onChangeAction();
    });
  
    
    function onChangeAction() {
        var selectedOption = $('#CompositionTypeComposition').val();
        $('#userDiv').hide();
        $('#soustype').hide();
        $('#tmp_parapheur').remove();
        if (selectedOption == '') {
            $('#boutonValider').hide();
        } else {
            if (selectedOption == 'USER') {
                $('#userDiv').show();
                $("input[name='data[Composition][type_validation]']").val('V');
                <?php
                if (CAKEFLOW_GERE_SIGNATURE) {
                    echo '$("#typeValidation").show();'; 
                }
                ?>
            } else if (selectedOption == 'PARAPHEUR') {
                $('#selectUser').append('<option value="-1" id="tmp_parapheur">Parapheur</option>');
                $('#selectUser').val("-1");
                $("input[name='data[Composition][type_validation]']").val('D');
                $('#soustype').show();
                <?php
                if (CAKEFLOW_GERE_SIGNATURE) {
                    echo '$("#typeValidation").hide();'; 
                }
                ?>
            }
            $('#boutonValider').show();
        }
    }
 
</script>