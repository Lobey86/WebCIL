<?
$modelName = $this->Paginator->defaultModel();
$nbPages = $this->params['paging'][$modelName]['pageCount'];
if ($nbPages > 1) :?>
	<div class="paging">
		<? echo $this->Paginator->prev('<< '.__('Pr&eacute;c&eacute;dent', true), array('escape'=>false), null, array('escape'=>false, 'class'=>'disabled')); ?>
		|
		<? echo $this->Paginator->numbers(); ?>
		<? echo $this->Paginator->next(__('Suivant', true).' >>', array(), null, array('class'=>'disabled')); ?>
	</div>
<? endif; ?>