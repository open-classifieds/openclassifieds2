<?php foreach ($fieldsets as $fieldset): ?>
<fieldset>
	<legend><?php echo $fieldset['legend']; ?></legend>
	<?php echo View::factory('formmanager/html/fields', array('fields' => $fieldset['fields']))->render(); ?>
</fieldset>
<?php endforeach; ?>
