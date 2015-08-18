		<?php 
		$pagin_opt = array();
		if(!empty($options['model'])){
			$pagin_opt['model'] = $options['model'];
		}
		if(!empty($options['provider'])){
			$pagin_opt['url'] = $options['provider'];
		}
		if(!empty($this->params['named']['q'])){
			if(empty($pagin_opt['url'])) $pagin_opt['url'] = array();
			$pagin_opt['url'] = array_merge((array)$pagin_opt['url'],array('q'=>$this->params['named']['q']));
		}
		?>
		<div class="list clearfix">
		<?php
		$i = 0;
		foreach($options['options'] as $option) {
			$class = array();
			if ($i % 2 == $i/2 % 2) {
				$class[] = 'altrow';
			}
			if ($i % 2 == 0) {
				$class[] = 'left';
			}else{
				$class[] = 'right';
			}
			echo $this->element('select_item',array('plugin'=>'spark_form','options'=>$options,'values'=>$option[$options['model']],'class'=>$class,'selected'=>false));
			$i++;
		} 
		?>
		</div>
		<div class="tools clearfix">
			<?php
				echo $form->input('q', array(
							'class' => 'keyword', 
							'label' => false, 
							'div'=>array('class'=>'search input text'),
							'after' => $form->button(__('Search', true), array('div' => false, 'class'=>'submit', 'action'=>$html->url(empty($options['provider'])?'':$options['provider'])))
						));
			?>
			<div class="paging ajax_paging">
				<?php echo $paginator->prev('« '.__('previous', true), $pagin_opt, null, array('class'=>'disabled'));?>
			 | 	<?php echo $paginator->numbers($pagin_opt);?> | 
				<?php echo $paginator->next(__('next', true).' »', $pagin_opt, null, array('class' => 'disabled'));?>
			</div>
		</div>