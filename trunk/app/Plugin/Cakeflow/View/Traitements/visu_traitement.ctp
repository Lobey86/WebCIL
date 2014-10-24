<?php
    echo $this->Html->css('/cakeflow/css/circuit');
    echo $this->Html->script('/cakeflow/js/init_cakeflow');
?>

<div id='etapes' class='circuit'>
<?php
foreach ($etapes as $i=>$etape) {
    echo $this->Html->tag('div', null, array('class'=>'etape', 'id'=>"etape_$i"));
        $classname = $etape['Etape']['courante'] ? 'courante' : '';
		echo $this->Html->div('nom'.$classname, '['.$i.'] - '.$etape['Etape']['nom']);
		echo $this->Html->div('type', $etape['Etape']['libelleType']);
		echo $this->Html->tag('div', null, array('class'=>'utilisateurs'));
			foreach ($etape['Visa'] as $visa) {
				$icon = null;
				$iconTrigger = '';
                switch ($visa['action']) {
                    case 'OK':
                        $icon = 'coche.png';
                        break;
                    case 'KO':
                        $icon = 'croixrouge.png';
                        break;
                    case 'IL':
                        $icon = 'lacet.png';
                        break;
                    case 'IP':
                        $icon = 'envoyer.png';
                        break;
                    case 'JP':
                        $icon = 'sautprec.png';
                        break;
                    case 'JS':
                        $icon = 'sautsuiv.png';
                        break;
                    case 'ST':
                        $icon = 'arreter.png';
                        break;
                    case 'IN':
                        $icon = 'inserer.png';
                        break;
                    case 'VF':
                        $icon = 'checkered-flag.png';
                        break;
                }
                if ($icon)
					$iconTrigger = $this->Html->image('/cakeflow/img/icons/'.$icon, array('border'=>'0', 'style'=>'height:16px;width:16px;'));
				$typeValidation = CAKEFLOW_GERE_SIGNATURE ? ', '.$visa['libelleTypeValidation'] : '';
				$title = ($visa['action'] == 'RI' ? '' : $visa['libelleAction'].' le '.$this->Time->format("d-m-Y à H:i", $visa['date']));
				echo $this->Html->div('utilisateur' ,$iconTrigger."&nbsp;".$visa['libelleTrigger'].$typeValidation, array('title'=>$title) );
			}
        echo $this->Html->tag('/div', null);
    echo $this->Html->tag('/div', null);
}
?>
</div>
