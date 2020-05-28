<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Disqus extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Disqus');

        if($this->request->post())
        {
            Model_Config::set_value('general', 'blog_disqus', Core::post('blog_disqus'));
            Model_Config::set_value('general', 'faq_disqus', Core::post('faq_disqus'));
            Model_Config::set_value('advertisement', 'disqus', Core::post('advertisement_disqus'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'disqus']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/disqus', [
            'is_active' => (bool) Core::config('general.blog_disqus')
        ]);
    }
}
