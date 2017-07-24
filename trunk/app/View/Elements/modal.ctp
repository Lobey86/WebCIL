<?php
    // Attributs
    $modalId = true === isset($modalId) ? $modalId : 'myModal';
    $titleTag = true === isset($titleTag) ? $titleTag : 'h4';
    $titleId =  true === isset($titleId) ? $titleId : "aria-labelledby-{$modalId}";

    $content = true === isset($content)
        ? $content
        : [];
    $content += [
        'title' => null,
        'body' => null,
        'footer' => null
    ];
?>
<div class="modal fade" id="<?php echo $modalId;?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $titleId;?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $this->Html->tag($titleTag, $content['title'], ['id' => $titleId, 'class' => 'modal-title']);?>
            </div>
            
            <div class="modal-body">
                <?php echo $content['body'];?>
            </div>
            
            </hr>
            
            <div class="modal-footer">
                <?php echo $content['footer'];?>
            </div>
        </div>
    </div>
</div>