<?php defined('SYSPATH') or die('No direct script access.');?>

<?= View::factory('pricing/index', compact('plans', 'user', 'subscription')) ?>
