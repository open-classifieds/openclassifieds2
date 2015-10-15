<?php
// Configuration for koharness - builds a standalone skeleton Kohana app for running unit tests
return array(
	'modules' => array(
		'minion'   => __DIR__,
		'unittest' => __DIR__ . '/vendor/kohana/unittest'
	),
);
