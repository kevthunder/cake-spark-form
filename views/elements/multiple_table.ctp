<?php if($elemsAttr['div'] !== false){ ?>
<div<?php echo $elemsAttr['div'] ?>>
<?php } ?>
	<?php if( !empty($options['before']) ) echo $options['before']; ?>
	<?php echo $this->O2form->labelFor($fieldName, $options); ?>
	<table<?php echo $elemsAttr['table'] ?> depth="<?php echo $depth ?>">
		<?php $rows = 0; ?>
		<tr>
		<?php if( !empty($options['subline']['collapse']) ) { ?>
			<th>&nbsp;</th>
			<?php $rows++; ?>
		<?php } ?>
		<?php foreach($labels as $label){ ?>
			<th><?php echo $label ?></th>
			<?php $rows++; ?>
		<?php } ?>
		<?php if($options['delete'] !== false) { ?>
			<th><?php echo $options['delete']['colLabel']; ?></th>
			<?php $rows++; ?>
		<?php }?>
		</tr>
		<?php foreach($lines as $pos => $line){ ?>
			<tr<?php echo $line['tr'] ?> rel="line<?php echo $line['index'] ?>">
			<?php if( !empty($line['subline']['collapse']) ) { ?>
				<td><a href="#" class="btMore">more</a></td>
			<?php } ?>
			<?php $i = 0; foreach($line['inputs'] as $key => $input){ ?>
				<td<?php echo $elemsAttr['td'] ?>>
					<?php if( $i == 0  && !empty($line['hidden'])) foreach($line['hidden'] as $hkey => $hidden){ ?>
						<?php echo $this->O2form->input($hkey,$hidden); ?>
					<?php } ?>
					<?php echo $this->O2form->input($key,$input); ?>
				</td>
			<?php  $i++; } ?>
			<?php if($options['delete'] !== false) { ?>
				<td<?php echo $elemsAttr['tdAction'] ?>>
					<a href="#" class="btDelete"><?php echo $options['delete']['label']; ?></a>
				</td>
			<?php } ?>
			</tr>
			<?php if( !empty($line['subline']) ) { ?>
			<tr<?php echo $line['subline']['tr'] ?> rel="line<?php echo $line['index'] ?>">
				<td<?php echo $line['subline']['td'] ?>>
					<?php $i = 0; foreach($line['subline']['inputs'] as $key => $input){ ?>
						<?php echo $this->O2form->input($key,$input); ?>
					<?php } ?>
				</td>
			</tr>
			<?php }?>
		<?php } ?>
		<?php if($options['add'] !== false) { ?>
		<tr<?php echo $elemsAttr['trAction'] ?>>
			<td colspan="<?php echo $rows; ?>"><a href="#" class="btAdd"><?php echo $options['add']['label']; ?></a></td>
		</tr>
		<?php } ?>
	</table>
	<?php if( !empty($options['after']) ) echo $options['after']; ?>
<?php if($elemsAttr['div'] !== false){ ?>
</div>
<?php } ?>