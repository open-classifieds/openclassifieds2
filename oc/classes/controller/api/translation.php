<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Translation extends Api_Auth {


    public function action_index()
    {
        $selectable_languages = [];

        foreach (i18n::get_languages() as $language) 
        {
            $selectable_languages[$language] =  [
                                                    'name' =>i18n::get_display_language($language),
                                                    'last_update_apps' => Core::Config('translations.lastupdate-apps-'.$language),
                                                    'last_update_messages' => Core::Config('translations.lastupdate-'.$language),
                                                ];
        }    

        $this->rest_output(array('locales' => $selectable_languages),200);
    }
    
    public function action_translate()
    {
        if ( core::request('q')!=NULL )
        {
            $this->action_single();
        }
        else
        {
            try
            {
                $locale = array_key_exists($this->request->param('id'),i18n::get_languages())?$this->request->param('id'):'en_US';
                $translations =  $this->translations($locale,((core::request('all')==1)?TRUE:FALSE),core::request('file','apps'));
                if ($translations == FALSE)
                    $this->_error('Translation '.$locale.' not found',404);
                else
                    $this->rest_output([$locale => $translations],200,count($translations));
            }   
            catch (Kohana_HTTP_Exception $khe)
            {
                $this->_error($khe);
            }
        }
        
    }

    public function action_single()
    {
        try
        {
            if ( core::request('q')!=NULL )
            {
                $locale = array_key_exists($this->request->param('id'),i18n::get_languages())?$this->request->param('id'):'en_US';
                $q = core::request('q');
                $t = $this->translations($locale, FALSE,core::request('file','apps'));
                if (is_array($t) AND array_key_exists($q,$t))
                     $this->rest_output([$locale => [$q =>$t[$q]]]);
                else
                    $this->_error($locale.' Translation "'.$q.'" not found',404);
            }
            else
                $this->_error($locale.' Translation not found',404);

        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }

    }

    /*
        gets translated words only
     */
    private function translations($language, $get_all = FALSE, $translation_file = 'apps')
    {
        if ($get_all === TRUE)
            return $this->translations_all($language, $translation_file);

        $mo_translation = i18n::get_language_path($language, $translation_file);
        if(!file_exists($mo_translation))
            return FALSE;

        $base_translation = i18n::get_language_path(NULL, $translation_file);

        //pear gettext scripts
        require_once Kohana::find_file('vendor', 'GT/Gettext','php');
        require_once Kohana::find_file('vendor', 'GT/Gettext/PO','php');
       
        //the translation file
        $pocreator_translated = new File_Gettext_PO();
        $pocreator_translated->load($mo_translation);
        
        //array with translated language may contain missing from EN
        $translation = $pocreator_translated->strings;

        foreach ($translation as $origin => $value) 
        {
            //do we have the translation?
            if (!isset($value) OR empty($value))
               unset($translation[$origin]);
        }

        //sort alphabetical using locale
        ksort($translation,SORT_LOCALE_STRING);

        return $translation;
    }

    /*
        gets all translations inclding missing
     */
    private function translations_all($language, $translation_file = 'apps')
    {
        $mo_translation = i18n::get_language_path($language, $translation_file);
        if(!file_exists($mo_translation))
            return FALSE;

        $base_translation = i18n::get_language_path(NULL, $translation_file);

        //pear gettext scripts
        require_once Kohana::find_file('vendor', 'GT/Gettext','php');
        require_once Kohana::find_file('vendor', 'GT/Gettext/PO','php');

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

        foreach ($en_array_order as $origin => $value) 
        {
            //do we have the translation?
            if (isset($origin_translation[$origin]) AND !empty($origin_translation[$origin]))
                $translated = $origin_translation[$origin];
            else
                $translated = '';
            
            $translation_array[$origin] = $translated;
        }

        return $translation_array;
    }


} // END