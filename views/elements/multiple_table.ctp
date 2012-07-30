<?php if($elemsAttr['div'] !== false){ ?>
<div<?php echo $elemsAttr['div'] ?>>
<?php } ?>
	<?php echo $this->O2form->labelFor($fieldName, $options); ?>
	<table<?php echo $elemsAttr['table'] ?>>
		<tr>
		<?php foreach($labels as $label){ ?>
			<th><?php echo $label ?></th>
		<?php } ?>
			<?php if($options['delete'] !== false) { ?>
			<th><?php echo $options['delete']['colLabel']; ?></th>
			<?php }?>
		</tr>
		<?php foreach($lines as $line){ ?>
			<tr<?php echo $line['tr'] ?>>
			<?php $i = 0; foreach($line['inputs'] as $key => $input){ ?>
				<td<?php echo $elemsAttr['td'] ?>>
					<?php if( $i == 0  && !empty($line['hidden'])) foreach($line['hidden'] as $key => $input){ ?>
						<?php echo $this->O2form->input($key,$input); ?>
					<?php } ?>
					<?php echo $this->O2form->input($key,$input); ?>
				</td>
			<?php  $i++; } ?>
			<?php if($options['delete'] !== false) { ?>
				<td<?php echo $elemsAttr['tdAction'] ?>>
					<a href="#" class="btDelete"><?php echo $options['delete']['label']; ?></a>
				</td>
			<?php }?>
			</tr>
		<?php } ?>
		<?php if($options['add'] !== false) { ?>
		<tr<?php echo $elemsAttr['trAction'] ?>>
			<td colspan="<?php echo count($labels)+($options['delete'] !== false?1:0); ?>"><a href="#" class="btAdd"><?php echo $options['add']['label']; ?></a></td>
		</tr>
		<?php } ?>
	</table>
<?php if($elemsAttr['div'] !== false){ ?>
</div>
<?php } ?>