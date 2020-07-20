<?php defined('SYSPATH') or die('No direct script access.');?>

<?php

Response::factory()->headers('Content-Type',  'application/javascript');

echo $content;
