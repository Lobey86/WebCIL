<div class="pagination pagination-large">
	<p class="counter text-muted">
		<?php
			$format = 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}';
			echo $this->Paginator->counter( array( 'format' => __( $format ) ) );
		?>
	</p>
	<?php if( true === $this->Paginator->hasPage(null, 2) ): ?>
		<ul class="pagination">
			<?php
			echo $this->Paginator->prev( __( '<<' ), array( 'tag' => 'li' ), null, array( 'tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a' ) );
			echo $this->Paginator->numbers( array( 'separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1 ) );
			echo $this->Paginator->next( __( '>>' ), array( 'tag' => 'li', 'currentClass' => 'disabled' ), null, array( 'tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a' ) );
			?>
		</ul>
	<?php endif; ?>
</div>