<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Pages extends Auth_Controller {

    public function action_index()
    {
        $locale = Core::get('locale', core::config('i18n.locale'));

        $pages = Model_Content::get_contents('page', $locale);

        $this->template->title = __('Pages');

        $this->template->content = View::factory('oc-panel/pages/pages/index', [
            'pages' => Model_Content::get_contents('page', $locale),
            'locales' => i18n::get_languages(),
            'locale' => $locale,
        ]);
    }

    public function action_create()
    {
        $page = new Model_Content();
        $locale = Core::get('locale', core::config('i18n.locale'));

        if($this->request->post())
        {
            $page->type = 'page';
            $page->locale = Core::post('locale');
            $page->title = Core::post('title');
            $page->seotitle = Core::post('seotitle') ?? $page->gen_seotitle(Core::post('title'));
            $page->description = Kohana::$_POST_ORIG['description'];
            $page->status = Core::post('status') ?? 0;

            try
            {
                $page->save();

                Alert::set(Alert::SUCCESS, __('Page created').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1">'
                    .__('Delete cache').'</a>');
            }
            catch (Exception $e)
            {
                Alert::set(Alert::ERROR, $e->getMessage());
            }

            HTTP::redirect(Route::url('oc-panel', ['controller' => 'pages']) . '?locale=' . $locale);
        }

        $this->template->content = View::factory('oc-panel/pages/pages/create', [
            'page' => $page,
            'locale' => $locale,
            'locales' => i18n::get_languages(),
        ]);
    }

    public function action_update()
    {
        $page = new Model_Content($this->request->param('id'));

        if (! $page->loaded())
        {
            throw HTTP_Exception::factory(404, __('Page not found'));
        }

        if($this->request->post())
        {
            $page->locale = Core::post('locale');
            $page->title = Core::post('title');
            $page->description = Kohana::$_POST_ORIG['description'];
            $page->seotitle = Core::post('seotitle');
            $page->status = Core::post('status') ?? 0;

            try
            {
                $page->save();

                Alert::set(Alert::SUCCESS, __('Page edited'));
            }
            catch (Exception $e)
            {
                Alert::set(Alert::ERROR, $e->getMessage());
            }

            HTTP::redirect(Route::url('oc-panel', [
                'controller' => 'pages',
                'action' => 'update',
                'id' => $page->id_content
            ]));
        }

        $this->template->content = View::factory('oc-panel/pages/pages/update', [
            'page' => $page,
            'locale' => $page->locale,
            'locales' => i18n::get_languages(),
        ]);
    }

    public function action_delete()
    {
        $page = new Model_Content($this->request->param('id'));

        if (! $page->loaded())
        {
            throw HTTP_Exception::factory(404, __('Page not found'));
        }

        try
        {
            $page->delete();
        }
        catch (Exception $e)
        {
            Alert::set(Alert::ERROR, $e->getMessage());
        }

        HTTP::redirect(Route::url('oc-panel', ['controller' => 'pages']) . '?locale=' . $page->locale);
    }

    public function action_duplicate()
    {
        $from_locale = core::get('from_locale', i18n::$locale_default);
        $to_locale = core::get('to_locale');

        if (Model_Content::copy($from_locale, $to_locale, 'page'))
        {
            Alert::set(Alert::SUCCESS, sprintf(
                __('Missing pages copied from %s to %s'),
                $from_locale,
                $to_locale
            ));
        }
        else
        {
            Alert::set(Alert::INFO, sprintf(
                __('We can not copy the pages since the locale %s already has existing %s'),
                $to_locale,
                'page'
            ));
        }

        HTTP::redirect(Route::url('oc-panel', ['controller' => 'pages']).'?locale=' . $to_locale);
    }
}
