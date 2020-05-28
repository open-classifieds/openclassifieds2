<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Addons_Forums extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Forums');

        if($this->request->post())
        {
            Model_Config::set_value('general', 'forums', Core::post('is_active') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'forums']));
        }

        //template header
        $this->template->title  = __('Forums');
        $this->template->styles              = array('css/sortable.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/forums.js';

        list($forums, $order)  = Model_Forum::get_all();

        return $this->template->content = View::factory('oc-panel/pages/addons/forums/index', [
            'is_active' => (bool) Core::config('general.forums'),
            'forums' => $forums,
            'order' => $order,
        ]);
    }
}
