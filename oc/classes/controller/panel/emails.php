<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Emails extends Auth_Controller {

    public function action_index()
    {
        $locale = Core::get('locale', core::config('i18n.locale'));

        $emails = Model_Content::get_contents('email', $locale);

        $this->template->title = __('Emails');

        $this->template->content = View::factory('oc-panel/pages/emails/index', [
            'emails' => Model_Content::get_contents('email', $locale),
            'locales' => i18n::get_languages(),
            'locale' => $locale,
        ]);
    }

    public function action_create()
    {
        $email = new Model_Content();
        $locale = Core::get('locale', core::config('i18n.locale'));

        if($this->request->post())
        {
            $email->type = 'email';
            $email->locale = Core::post('locale');
            $email->title = Core::post('title');
            $email->seotitle = Core::post('seotitle') ?? $email->gen_seotitle(Core::post('title'));
            $email->from_email = Core::post('from_email');
            $email->status = Core::post('status') ?? 0;

            try
            {
                $email->save();

                Alert::set(Alert::SUCCESS, __('Email created').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1">'
                    .__('Delete cache').'</a>');
            }
            catch (Exception $e)
            {
                Alert::set(Alert::ERROR, $e->getMessage());
            }

            HTTP::redirect(Route::url('oc-panel', ['controller' => 'emails']) . '?locale=' . $locale);
        }

        $this->template->content = View::factory('oc-panel/pages/emails/create', [
            'email' => $email,
            'locale' => $locale,
            'locales' => i18n::get_languages(),
        ]);
    }

    public function action_update()
    {
        $email = new Model_Content($this->request->param('id'));

        if (! $email->loaded())
        {
            throw HTTP_Exception::factory(404, __('Email not found'));
        }

        if($this->request->post())
        {
            $email->locale = Core::post('locale');
            $email->title = Core::post('title');
            $email->description = Kohana::$_POST_ORIG['description'];
            $email->from_email = Core::post('from_email');
            $email->status = Core::post('status') ?? 0;

            try
            {
                $email->save();

                Alert::set(Alert::SUCCESS, __('Email edited'));
            }
            catch (Exception $e)
            {
                Alert::set(Alert::ERROR, $e->getMessage());
            }

            HTTP::redirect(Route::url('oc-panel', [
                'controller' => 'emails',
                'action' => 'update',
                'id' => $email->id_content
            ]));
        }

        $this->template->content = View::factory('oc-panel/pages/emails/update', [
            'email' => $email,
            'locale' => $email->locale,
            'locales' => i18n::get_languages(),
        ]);
    }

    public function action_delete()
    {
        $email = new Model_Content($this->request->param('id'));

        if (! $email->loaded())
        {
            throw HTTP_Exception::factory(404, __('Email not found'));
        }

        if ($content->locale == i18n::$locale_default)
        {
            Alert::set(Alert::INFO, sprintf(__('Sorry, deleting %s locale emails is not allowed'), i18n::$locale_default));

            HTTP::redirect(Route::url('oc-panel', ['controller' => 'emails']).'?locale='.$content->locale);
        }

        try
        {
            $email->delete();
        }
        catch (Exception $e)
        {
            Alert::set(Alert::ERROR, $e->getMessage());
        }

        HTTP::redirect(Route::url('oc-panel', ['controller' => 'emails']) . '?locale=' . $email->locale);
    }

    public function action_duplicate()
    {
        $from_locale = core::get('from_locale', i18n::$locale_default);
        $to_locale = core::get('to_locale');

        if (Model_Content::copy($from_locale, $to_locale, 'email'))
        {
            Alert::set(Alert::SUCCESS, sprintf(
                __('Missing emails copied from %s to %s'),
                $from_locale,
                $to_locale
            ));
        }
        else
        {
            Alert::set(Alert::INFO, sprintf(
                __('We can not copy the emails since the locale %s already has existing %s'),
                $to_locale,
                $type
            ));
        }

        HTTP::redirect(Route::url('oc-panel', ['controller' => 'emails']).'?locale=' . $to_locale);
    }

    public function action_set_from_email()
    {
        $validation = Validation::factory($this->request->post())
            ->rule('from_email', 'not_empty')
            ->rule('from_email', 'email');

        if (! $validation->check())
        {
            $errors = $validation->errors('config');

            foreach ($errors as $error)
            {
                Alert::set(Alert::ALERT, $error);
            }

            HTTP::redirect(Route::url('oc-panel', ['controller'=>'emails', 'action'=>'index']));
        }

        $from_email = $this->request->post('from_email');

        $query = DB::update('content')
            ->set(['from_email' => $from_email])
            ->where('type', '=', 'email')->execute();

        Alert::set(Alert::SUCCESS, __('From Email has been changed to :email on all emails.', [':email' => $from_email]));

        HTTP::redirect(Route::url('oc-panel', ['controller'=>'emails', 'action'=>'index']));
    }
}
