<?php foreach ($fields as $field): ?>
	<?php
	$data = array('field' => $field);

	if ($field['display_as'] != 'hidden') {
		echo View::factory('formmanager/html/field_open', $data)->render();
	}

	echo View::factory('formmanager/html/' . $field['display_as'], $data)->render();
	
	if ($field['display_as'] != 'hidden') {
		echo View::factory('formmanager/html/field_close', $data)->render();
	}
	?>
<?php endforeach; ?>