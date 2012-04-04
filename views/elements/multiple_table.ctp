<?php if($elemsAttr['div'] !== false){ ?>
<div<?php echo $elemsAttr['div'] ?>>
<?php } ?>
	<?php echo $this->O2form->labelFor($fieldName, $options); ?>
	<table<?php echo $elemsAttr['table'] ?>>
		<tr>
		<?php foreach($labels as $label){ ?>
			<th><?php echo $label ?></th>
		<?php } ?>
			<th><?php echo __('Delete') ?></th>
		</tr>
		<?php foreach($lines as $line){ ?>
			<tr<?php echo $line['tr'] ?>>
			<?php foreach($line['inputs'] as $key => $input){ ?>
				<td<?php echo $elemsAttr['td'] ?>>
					<?php echo $this->O2form->input($key,$input); ?>
				</td>
			<?php } ?>
				<td<?php echo $elemsAttr['tdAction'] ?>>
				<?php foreach($line['hidden'] as $key => $input){ ?>
					<?php echo $this->O2form->input($key,$input); ?>
				<?php } ?>
					<a href="#" class="btDelete">-</a>
				</td>
			</tr>
		<?php } ?>
		<tr<?php echo $elemsAttr['trAction'] ?>>
			<td colspan="<?php echo count($labels)+1; ?>"><a href="#" class="btAdd">+</a></td>
		</tr>
	</table>
<?php if($elemsAttr['div'] !== false){ ?>
</div>
<?php } ?>