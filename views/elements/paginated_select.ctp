<?php $html->css('/spark_form/css/spark_form',null,null,false); ?>
<?php $javascript->link('/spark_form/js/ajax_item_list',null,array('inline'=>false)); ?>
<div class="ajax_item_list clearfix" inputname="<?php echo $options['name'] ?>"> 
	<?php echo $label ?>
	<div class="selected clearfix">
		<p class="title"><?php __('Selected'); ?><p>
	<?php
	$i = 0;
	foreach((array)$options['selected'] as $selected) {
		$class = array();
		if ($i % 2 == 0) {
			$class[] = 'altrow';
		}
		$class[] = 'selected_item';
		echo $this->element('select_item',array('plugin'=>'spark_form','options'=>$options,'values'=>$selected,'class'=>$class,'selected'=>true));
		$i++;
	} ?>
	</div>
	<div class="select_page clearfix">
		<?php echo $this->element('select_page',array('plugin'=>'spark_form','options'=>$options)); ?>
	</div>
	<div class="loader"></div>
</div>
