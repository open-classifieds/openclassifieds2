<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Faqs extends Auth_Controller {

    public function action_index()
    {
        $locale = Core::get('locale', core::config('i18n.locale'));

        $faqs = Model_Content::get_contents('help', $locale);

        $this->template->title = __('Faqs');

        $this->template->styles              = array('css/sortable.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/content.js';

        $this->template->content = View::factory('oc-panel/pages/faqs/index', [
            'faqs' => Model_Content::get_contents('help', $locale),
            'locales' => i18n::get_languages(),
            'locale' => $locale,
        ]);
    }

    public function action_create()
    {
        $faq = new Model_Content();
        $locale = Core::get('locale', core::config('i18n.locale'));

        if($this->request->post())
        {
            $faq->type = 'help';
            $faq->locale = Core::post('locale');
            $faq->title = Core::post('title');
            $faq->seotitle = Core::post('seotitle') ?? $faq->gen_seotitle(Core::post('title'));
            $faq->description = Kohana::$_POST_ORIG['description'];
            $faq->status = Core::post('status') ?? 0;

            try
            {
                $faq->save();

                Alert::set(Alert::SUCCESS, __('Faq created').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini" href="'.Route::url('oc-panel',['controller'=>'tools','action'=>'cache']).'?force=1">'
                    .__('Delete cache').'</a>');
            }
            catch (Exception $e)
            {
                Alert::set(Alert::ERROR, $e->getMessage());
            }

            HTTP::redirect(Route::url('oc-panel', ['controller' => 'faqs']) . '?locale=' . $locale);
        }

        $this->template->content = View::factory('oc-panel/pages/faqs/create', [
            'faq' => $faq,
            'locale' => $locale,
            'locales' => i18n::get_languages(),
        ]);
    }

    public function action_update()
    {
        $faq = new Model_Content($this->request->param('id'));

        if (! $faq->loaded())
        {
            throw HTTP_Exception::factory(404, __('Faq not found'));
        }

        if($this->request->post())
        {
            $faq->locale = Core::post('locale');
            $faq->title = Core::post('title');
            $faq->description = Kohana::$_POST_ORIG['description'];
            $faq->seotitle = Core::post('seotitle');
            $faq->status = Core::post('status') ?? 0;

            try
            {
                $faq->save();

                Alert::set(Alert::SUCCESS, __('Faq edited'));
            }
            catch (Exception $e)
            {
                Alert::set(Alert::ERROR, $e->getMessage());
            }

            HTTP::redirect(Route::url('oc-panel', [
                'controller' => 'faqs',
                'action' => 'update',
                'id' => $faq->id_content
            ]));
        }

        $this->template->content = View::factory('oc-panel/pages/faqs/update', [
            'faq' => $faq,
            'locale' => $faq->locale,
            'locales' => i18n::get_languages(),
        ]);
    }

    public function action_delete()
    {
        $faq = new Model_Content($this->request->param('id'));

        if (! $faq->loaded())
        {
            throw HTTP_Exception::factory(404, __('Faq not found'));
        }

        try
        {
            $faq->delete();

            Alert::set(Alert::SUCCESS, __('Faq deleted'));
        }
        catch (Exception $e)
        {
            Alert::set(Alert::ERROR, $e->getMessage());
        }

        HTTP::redirect(Route::url('oc-panel', ['controller' => 'faqs']) . '?locale=' . $faq->locale);
    }

    public function action_duplicate()
    {
        $from_locale = core::get('from_locale', i18n::$locale_default);
        $to_locale = core::get('to_locale');

        if (Model_Content::copy($from_locale, $to_locale, 'help'))
        {
            Alert::set(Alert::SUCCESS, sprintf(
                __('Missing faqs copied from %s to %s'),
                $from_locale,
                $to_locale
            ));
        }
        else
        {
            Alert::set(Alert::INFO, sprintf(
                __('We can not copy the faqs since the locale %s already has existing %s'),
                $to_locale,
                'faqs'
            ));
        }

        HTTP::redirect(Route::url('oc-panel', ['controller' => 'faqs']).'?locale=' . $to_locale);
    }

    public function action_saveorder()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');

        $locale = core::get('locale', core::config('i18n.locale'));

        if (Model_Content::get_contents('help', $locale))
        {
            $order_columns = Core::post('order');

            foreach ($order_columns as $order_column => $faq_id)
            {
                $faq = new Model_Content($faq_id);
                $faq->order = $order_column;
                $faq->save();
            }

            $this->template->content = __('Saved');
        }
        else
        {
            $this->template->content = __('Error');
        }
    }
}
