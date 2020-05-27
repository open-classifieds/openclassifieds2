<ul class="inputs-list">
<?php foreach ($field['options'] as $option): ?>
	<li>
		<label>
			<?php echo Form::radio($field['field_name'], $option, array('checked' => (bool)($option == $field['value']))); ?>
			<span><?php echo $option; ?></span>
		</label>
	</li>
<?php endforeach; ?>
</ul>
