<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Design extends Auth_Controller {

    public function action_index()
    {
        $this->template->panel_title = __('Design');

        $this->template->content = View::factory('oc-panel/pages/design/index');
    }
}
