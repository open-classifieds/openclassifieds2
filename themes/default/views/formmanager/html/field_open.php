<div class="col-span-3 sm:col-span-2">
	<?php echo View::factory('formmanager/html/label', array('field' => $field))->render(); ?>
	<?php if (isset($field['prefix'])) echo $field['prefix']; ?>
