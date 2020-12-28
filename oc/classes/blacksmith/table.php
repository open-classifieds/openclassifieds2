<?php defined('SYSPATH') or die('No direct script access.');

class Blacksmith_Table extends Blacksmith_Table_Core {

    public function json($column_name)
	{
		return $this->_column($column_name)->type("JSON");
	}
}
