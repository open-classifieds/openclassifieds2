<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Addons_Reviews extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Reviews');

        if($this->request->post())
        {
            Model_Config::set_value('advertisement', 'reviews', Core::post('is_active') ?? 0);
            Model_Config::set_value('advertisement', 'reviews_paid', Core::post('reviews_paid') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'reviews']));
        }

        return $this->template->content = View::factory('oc-panel/pages/addons/reviews/index', [
            'is_active' => (bool) Core::config('advertisement.reviews')
        ]);
    }
}
