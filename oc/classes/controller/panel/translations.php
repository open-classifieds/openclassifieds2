<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller Translations
 */


class Controller_Panel_Translations extends Auth_Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Translations'))->set_url(Route::url('oc-panel',array('controller'  => 'translations'))));

    }

    public function action_index()
    {

        // validation active
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('List')));
        $this->template->title = __('Translations');

        //scan project files and generate .po
        if( core::get('parse')!==NULL )
        {
            //scan script
            require_once Kohana::find_file('vendor', 'POTCreator/POTCreator','php');

            $obj = new POTCreator;
            $obj->set_root(DOCROOT);
            $obj->set_exts('php');
            $obj->set_regular('/_[_|e]\([\"|\']([^\"|\']+)[\"|\']\)/i');
            $obj->set_base_path('..');
            $obj->set_read_subdir(true);

            $obj->write_pot(i18n::get_language_path());
            Alert::set(Alert::SUCCESS, 'File regenerated');
        }

        //change default site language
        if( ($locale=$this->request->param('id'))!=NULL AND array_key_exists($locale,i18n::get_languages()))
        {
            //save language
            Model_Config::set_value('i18n','locale',$locale);

            //change the cookie if not he will not see the changes
            if (Core::config('i18n.allow_query_language')==1)
                Cookie::set('user_language',$locale, Core::config('auth.lifetime'));

            Alert::set(Alert::SUCCESS,__('Language').' '. $locale);
            HTTP::redirect(Route::url('oc-panel',array('controller'  => 'translations')));
        }

        //create language
        if(Core::post('locale'))
        {
            $language   = $this->request->post('locale');
            $folder     = DOCROOT.'languages/'.$language.'/LC_MESSAGES/';

            // if folder does not exist, try to make it
            if ( !file_exists($folder) AND ! @mkdir($folder, 0775, true))
            { // mkdir not successful ?
                Alert::set(Alert::ERROR, __('Language folder cannot be created with mkdir. Please correct to be able to create new translation.'));
                HTTP::redirect(Route::url('oc-panel',array('controller'  => 'translations')));
            };

            // write an empty .po file for $language
            $out = 'msgid ""'.PHP_EOL.'msgstr ""'.PHP_EOL;
            File::write($folder.'messages.po', $out);
            File::write($folder.'apps.po', $out);

            Alert::set(Alert::SUCCESS, $this->request->param('id').' '.__('Language saved'));
        }

        $this->template->content = View::factory('oc-panel/pages/translations/index',array('languages' => i18n::get_languages(),
                                                                                            'current_language' => core::config('i18n.locale')
                                                                                            ));

    }

    public function action_edit()
    {
        $language   = $this->language_fix($this->request->param('id'));
        $translation_file = core::request('translation_file','messages');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit Translation').' "'.$language.'" "'.$translation_file.'"'));
        $this->template->title = __('Edit Translation').' "'.$language.'" "'.$translation_file.'"';
        $this->template->bind('content', $content);
        $content = View::factory('oc-panel/pages/translations/edit');


        //get the translated ad not translated.
        list($translation_array,$untranslated_array) = $this->get_translation($language,$translation_file);

        //watch out at any standard php installation there's a limit of 1000 posts....edit php.ini max_input_vars = 10000 to amend it.
        if($this->request->post() AND is_array(Core::post('translations')) )
        {
            $data_translated = Core::post('translations');

            if ($this->save_translation($language,$translation_array,$data_translated,$translation_file))
            {
                Model_Config::set_value('translations','lastupdate-'.($translation_file=='apps'?'apps-':'').$language, time());
                Alert::set(Alert::SUCCESS, $language.' '.__('Language saved'));
            }
            else
                Alert::set(Alert::ALERT, $language);

            $this->redirect(URL::current());
        }

        //add filters to search
        $translation_array_filtered = $translation_array;

        //only display not translated
        if (core::get('translated')==1)
        {
            $translation_array_filtered_aux = array();
            foreach ($untranslated_array as $key=>$value )
            {
                $translation_array_filtered_aux[] =  $translation_array_filtered[$value];
            }

            $translation_array_filtered = $translation_array_filtered_aux;
        }
        elseif (core::get('search')!==NULL)
        {
            $translation_array_filtered_aux = array();
            foreach ($translation_array as $key=>$value )
            {
                if (strpos($value['original'],core::get('search'))!==FALSE OR
                    strpos($value['translated'],core::get('search'))!==FALSE )
                        $translation_array_filtered_aux[] =  $value;
            }

            $translation_array_filtered = $translation_array_filtered_aux;
        }

        //how many translated items we have?
        $total_items = core::count($translation_array_filtered);

        //get elements for current page
        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $total_items,
                    'items_per_page' => 20,
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
                    'id'         => $language,
        ));

        $trans_array_paginated = array();
        $from = $pagination->offset;
        $to   = $from + $pagination->items_per_page;

        for ($key=$from; $key <$to ; $key++)
        {
            if (isset($translation_array_filtered[$key]))
                $trans_array_paginated[$key] = $translation_array_filtered[$key];
        }

        $content->edit_language     = $language;
        $content->translation_array = $trans_array_paginated;
        $content->cont_untranslated = core::count($untranslated_array);
        $content->total_items       = core::count($translation_array);
        $content->pagination        = $pagination->render();
        $content->translation_file  = $translation_file;

    }


    public function action_replace()
    {
        $search     = Core::request('search', Core::request('name'));
        $replace    = Core::request('replace', Core::request('value'));
        $where      = Core::request('where','original');
        $exact      = (bool) Core::request('exact','0');
        $translation_file = core::request('translation_file','messages');

        //d([$search, $replace, $where, $exact]);

        $language   = $this->language_fix($this->request->param('id'));

        //read original mo file to get the full array
        //read translated mo
        //get the translated ad not translated.
        //merge original with translated
        list($translation_array,$untranslated_array) = $this->get_translation($language,$translation_file);

        //array of new translations
        $data_translated = array();

        //for each item search
        foreach ($translation_array as $key => $values)
        {
            //replace if theres a match
            list($id,$original,$translated) = array_values($values);

            switch ($where) {
                case 'translation':
                    //found exact in the translated
                    if ($exact AND $translated == $search)
                    {
                        $data_translated[$id] = $replace;
                    }
                    //found in the translated
                    elseif (strpos($translated,$search)!==FALSE)
                    {
                        //add it to the new translations
                        $data_translated[$id] = str_replace($search,$replace,$translated);
                    }
                    break;

                case 'original':
                    //found exact in the original
                    if ($exact AND $original == $search)
                    {
                        $data_translated[$id] = $replace;
                    }
                    //found in the original
                    elseif(strpos($original,$search)!==FALSE)
                    {
                        //add it to the new translations
                        $data_translated[$id] = str_replace($search,$replace,$original);
                    }
                    break;
            }
        }

        if ($this->save_translation($language,$translation_array,$data_translated,$translation_file))
        {
            Model_Config::set_value('translations','lastupdate-'.($translation_file=='apps'?'apps.':'').$language, time());
            Alert::set(Alert::SUCCESS, $language.' '.__('Language saved'));
        }
        else
            Alert::set(Alert::ALERT, $language);

        $this->redirect(Route::url('oc-panel',array('controller'  => 'translations','action'=>'edit','id'=>$language)).'?translation_file='.$translation_file);

    }

    /**
     * gets the translation as array form a language
     * @param  string $language
     * @return array
     */
    public function get_translation($language,$translation_file = 'messages')
    {
        $mo_translation = i18n::get_language_path($language,$translation_file);

        // write an empty .po file for $language
        if(!file_exists($mo_translation))
        {
            File::write($mo_translation, 'msgid ""'.PHP_EOL.'msgstr ""'.PHP_EOL);

            //Alert::set(Alert::ERROR, $language);
            //$this->redirect(Route::url('oc-panel',array('controller'  => 'translations')));
        }

        $base_translation = i18n::get_language_path(NULL,$translation_file);

        //pear gettext scripts
        require_once Kohana::find_file('vendor', 'GT/Gettext','php');
        require_once Kohana::find_file('vendor', 'GT/Gettext/PO','php');
        require_once Kohana::find_file('vendor', 'GT/Gettext/MO','php');

        //load the .po files
        //original en translation
        $pocreator_en = new File_Gettext_PO();
        $pocreator_en->load($base_translation);
        //the translation file
        $pocreator_translated = new File_Gettext_PO();
        $pocreator_translated->load($mo_translation);

        //get an array with all the strings
        $en_array_order = $pocreator_en->strings;

        //sort alphabetical using locale
        ksort($en_array_order,SORT_LOCALE_STRING);

        //array with translated language may contain missing from EN
        $origin_translation = $pocreator_translated->strings;

        //lets get the array with translated values and sorted, will include everything even if was not previously saved
        $translation_array  = array();
        $untranslated_array = array();//keep track of words not translated stores ID

        $i = 0;
        foreach ($en_array_order as $origin => $value)
        {
            //do we have the translation?
            if (isset($origin_translation[$origin]) AND !empty($origin_translation[$origin]))
            {
                $translated = $origin_translation[$origin];
            }
            else
            {
                $untranslated_array[] = $i;
                $translated = '';
            }

            $translation_array[] = array( 'id' => $i,
                                          'original' => $origin,
                                          'translated' => $translated);

            $i++;
        }

        return array($translation_array,$untranslated_array);
    }


    /**
     * saves a translation
     * @param  string $language
     * @param  array $translation_array
     * @param  array $data_translated
     * @return bool
     */
    public function save_translation($language,$translation_array, $data_translated,$translation_file = 'messages')
    {
        //.po to .mo script
        require_once Kohana::find_file('vendor', 'php-mo/php-mo','php');

        //we save always in the custom file
        $mo_translation = i18n::get_language_custom_path($language,$translation_file);

        //changing the translation_array with the posted values
        foreach($data_translated as $key => $value)
        {
            if (isset($translation_array[$key]['translated']))
            {
                $value = str_replace(array("\r", "\n"), '', $value);
                $translation_array[$key]['translated'] = addslashes($value);
            }
        }

        //let's generate a proper .po file for the mo converter
        $out = '';

        foreach($translation_array as $key => $values)
        {
            list($id,$original,$translated) = array_values($values);
            if ($translated!='')
            {
                //only adding translated items
                $out .= '#: String '.$key.PHP_EOL;
                $out .= 'msgid "'.$original.'"'.PHP_EOL;
                $out .= 'msgstr "'.$translated.'"'.PHP_EOL;
                $out .= PHP_EOL;
            }
        }

        //write the generated .po to file
        if (File::write($mo_translation,$out)===FALSE)
            return FALSE;

        //generate the .mo from the .po file
        phpmo_convert($mo_translation);

        //we regenerate the file again to be poedit friendly
        $out = 'msgid ""
msgstr ""
"Project-Id-Version: '.Core::VERSION.'\n"
"POT-Creation-Date: '.Date::unix2mysql().'\n"
"PO-Revision-Date: '.Date::unix2mysql().'\n"
"Last-Translator: '.$this->user->name.' <'.$this->user->email.'>\n"
"Language-Team: en\n"
"Language: '.strtolower(substr($language,0,2)).'\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset='.i18n::$charset.'\n"
"Content-Transfer-Encoding: 8bit\n"
"X-Generator: Yclas '.Core::VERSION.'\n"'.PHP_EOL.PHP_EOL;

        foreach($translation_array as $key => $values)
        {
            list($id,$original,$translated) = array_values($values);
            //only adding translated items
            $out .= '#: String '.$key.PHP_EOL;
            $out .= 'msgid "'.$original.'"'.PHP_EOL;
            $out .= 'msgstr "'.$translated.'"'.PHP_EOL;
            $out .= PHP_EOL;
        }

        //write the generated .po to file
        file_put_contents($mo_translation, $out, LOCK_EX);

        return TRUE;
    }

    /**
     * be sure is correct capital letters
     * @param  string $language
     * @return string
     */
    public function language_fix($language)
    {
        if (strlen($language)==5)
        {
            return  substr($language, 0,3).strtoupper(substr($language, 3,5));
        }

        return $language;
    }

}//end of controller
