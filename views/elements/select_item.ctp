			<div class="item <?php echo 'item_'.$values['id'].' '. implode(' ',$class); ?>">
				<?php 
				if($selected){
				echo $form->input($options['fieldName'].'_'.$values['id'],array(
							'type'=>'hidden',
							'name'=>$options['name'].'[]',
							'value'=>$values['id'],
						));
				}else{
					echo $form->input('item_'.$values['id'],array(
							'type'=>'hidden',
							'name'=>'',
							'value'=>$values['id'],
							'disabled'=>true
						));
				}
				?>
				<?php
					if(isset($values['photo'][0]['id']) && is_numeric($values['photo'][0]['id'])) {
						?>
							<img src="<?php echo $multimedia->path($values['photo'], array('size' => '50x50')); ?>" />
						<?php
					}
				?>
				<p><?php echo $values['numref'] . ' / ' . $values['desc']; ?></p>
				<a class="add" href="#">Ajouter</a>
				<a class="remove" href="#">Remove</a>
			</div>