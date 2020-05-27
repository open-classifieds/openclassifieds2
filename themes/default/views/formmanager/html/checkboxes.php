<?php foreach ($field['options'] as $option_key => $option): ?>
	<input
        class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
		type="checkbox"
		name="<?php echo $field['field_name']; ?>[]"
		value="<?php echo $option_key; ?>"
		<?php if (in_array($option_key, $field['value'])): ?>checked="checked"<?php endif; ?>
	/>
	<?php echo $option; ?>
<?php endforeach; ?>

