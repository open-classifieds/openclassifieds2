<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller SETTINGS contains all basic configurations displayed to Admin.
 */


class Controller_Panel_Settings extends Auth_Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Settings'))->set_url(Route::url('oc-panel',array('controller'  => 'settings'))));

    }

    public function action_index()
    {
        HTTP::redirect(Route::url('oc-panel',array('controller'  => 'settings','action'=>'general')));  
    }

    /**
     * Contains all data releated to new advertisment optional form inputs,
     * captcha, uploading text file  
     * @return [view] Renders view with form inputs
     */
    public function action_form()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Advertisement')));
        $this->template->title = __('Advertisement');
       
        // all form config values
        $advertisement = new Model_Config();
        $config = $advertisement->where('group_name', '=', 'advertisement')->find_all();
        $this->template->styles  = array('//cdn.jsdelivr.net/bootstrap.tagsinput/0.3.9/bootstrap-tagsinput.css' => 'screen');
        $this->template->scripts['footer'][] = '//cdn.jsdelivr.net/bootstrap.tagsinput/0.3.9/bootstrap-tagsinput.min.js';
        $this->template->scripts['footer'][]= 'js/jquery.validate.min.js';
        $this->template->scripts['footer'][]= '/js/oc-panel/settings.js';
        
        // save only changed values
        if($this->request->post())
        {
            $validation =   Validation::factory($this->request->post())
            ->rule('advertisements_per_page', 'not_empty')
            ->rule('advertisements_per_page', 'digit')
            ->rule('feed_elements', 'not_empty')
            ->rule('feed_elements', 'digit')
            ->rule('map_elements', 'not_empty')
            ->rule('map_elements', 'digit')
            ->rule('sort_by', 'not_empty')
            ->rule('ads_in_home', 'not_empty')
            ->rule('ads_in_home', 'range', array(':value', 0, 4))
            ->rule('login_to_post', 'range', array(':value', 0, 1))
            ->rule('only_admin_post', 'range', array(':value', 0, 1))
            ->rule('expire_date', 'not_empty')
            ->rule('expire_date', 'digit')
            ->rule('parent_category', 'range', array(':value', 0, 1))
            ->rule('map_pub_new', 'range', array(':value', 0, 1))
            ->rule('captcha', 'range', array(':value', 0, 1))
            ->rule('address', 'range', array(':value', 0, 1))
            ->rule('phone', 'range', array(':value', 0, 1))
            ->rule('website', 'range', array(':value', 0, 1))
            ->rule('location', 'range', array(':value', 0, 1))
            ->rule('price', 'range', array(':value', 0, 1))
            ->rule('upload_file', 'range', array(':value', 0, 1))
            ->rule('num_images', 'not_empty')
            ->rule('num_images', 'digit')
            ->rule('contact', 'range', array(':value', 0, 1))
            ->rule('login_to_contact', 'range', array(':value', 0, 1))
            ->rule('qr_code', 'range', array(':value', 0, 1))
            ->rule('map', 'range', array(':value', 0, 1))
            ->rule('count_visits', 'range', array(':value', 0, 1))
            ->rule('related', 'not_empty')
            ->rule('related', 'digit')
            ->rule('map_zoom', 'digit')
            ->rule('center_lat', 'regex', array(':value', '/^-?+(?=.*[0-9])[0-9]*+'.preg_quote('.').'?+[0-9]*+$/D'))
            ->rule('center_lon', 'regex', array(':value', '/^-?+(?=.*[0-9])[0-9]*+'.preg_quote('.').'?+[0-9]*+$/D'))
            ->rule('reviews', 'range', array(':value', 0, 1))
            ->rule('reviews_paid', 'range', array(':value', 0, 1));
            
            if ($validation->check()) {
                foreach ($config as $c) 
                {
                    $config_res = $this->request->post($c->config_key); 
    
                    if(isset($config_res))
                    {
                        if($config_res !== $c->config_value)
                        {
                            $c->config_value = $config_res;
                            try {
                                $c->save();
                            } catch (Exception $e) {
                                echo $e;
                            }
                        }
                    }
                }
            }
            else {
                $errors = $validation->errors('config');
                
                foreach ($errors as $error) 
                    Alert::set(Alert::ALERT, $error);
                
                $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'form')));
            }
                
            $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'form')));
            
        }

        $this->template->content = View::factory('oc-panel/pages/settings/advertisement', array('config'=>$config));
    }


    /**
     * Email configuration 
     * @return [view] Renders view with form inputs
     */
    public function action_email()
    {        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Email')));
        $this->template->title = __('Email');

        $this->template->scripts['footer'][]= 'js/jquery.validate.min.js';
        $this->template->scripts['footer'][]= '/js/oc-panel/settings.js';

        // all form config values
        $emailconf = new Model_Config();
        $config = $emailconf->where('group_name', '=', 'email')->find_all();

        // save only changed values
        if($this->request->post())
        {
            $validation =   Validation::factory($this->request->post())
            ->rule('notify_email', 'email')
            ->rule('new_ad_notify', 'range', array(':value', 0, 1))
            ->rule('elastic_active', 'range', array(':value', 0, 1))
            ->rule('smtp_active', 'range', array(':value', 0, 1))
            ->rule('smtp_ssl', 'range', array(':value', 0, 1))
            ->rule('smtp_port', 'digit')
            ->rule('smtp_auth', 'range', array(':value', 0, 1));
            
            if ($validation->check()) {
                foreach ($config as $c) 
                {
                    $config_res = $this->request->post($c->config_key); 
    
                    if($config_res != $c->config_value)
                    {
                        $c->config_value = $config_res;
                        try {
                            $c->save();
                        } catch (Exception $e) {
                            throw HTTP_Exception::factory(500,$e->getMessage());
                        }
                    }
                }
            }
            else {
                $errors = $validation->errors('config');
                
                foreach ($errors as $error) 
                    Alert::set(Alert::ALERT, $error);
                
                $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'email')));
            }
                
            // Cache::instance()->delete_all();
            Alert::set(Alert::SUCCESS, __('Email Configuration updated'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'email')));
        }

        $this->template->content = View::factory('oc-panel/pages/settings/email', array('config'=>$config));
    }

    /**
     * All general configuration related with configuring site.
     * @return [view] Renders view with form inputs
     */
    public function action_general()
    {
        // validation active 
        $this->template->scripts['footer'][]= 'js/jquery.validate.min.js';
        $this->template->scripts['footer'][]= '/js/oc-panel/settings.js';
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('General')));
        $this->template->title = __('General');

        // all form config values
        $generalconfig = new Model_Config();
        $config = $generalconfig->where('group_name', '=', 'general')->or_where('group_name', '=', 'i18n')->find_all();

        // config general array
        foreach ($config as $c) 
        {
            $forms[$c->config_key] = $forms[$c->config_key] = array('key'=>$c->group_name.'['.$c->config_key.'][]', 'id'=>$c->config_key, 'value'=>$c->config_value);
        }
        
        //not updatable fields
        $do_nothing = array('menu','locale','allow_query_language','charset','translate','ocacu','minify','subscribe','api_key', 'blog', 'faq', 'forums', 'messaging', 'black_list', 'auto_locate', 'social_auth');

        // save only changed values
        if($this->request->post())
        {
            foreach ($this->request->post('general') as $k => $v)
                $this->request->post('general_'.$k, $v[0]);
            
            $validation =   Validation::factory($this->request->post())
                            ->rule('general_maintenance', 'range', array(':value', 0, 1))
                            ->rule('general_disallowbots', 'range', array(':value', 0, 1))
                            ->rule('general_cookie_consent', 'range', array(':value', 0, 1))
                            ->rule('general_site_name', 'not_empty')
                            ->rule('general_moderation', 'not_empty')
                            ->rule('general_moderation', 'range', array(':value', 0, 5))
                            ->rule('general_blog', 'range', array(':value', 0, 1))
                            ->rule('general_forums', 'range', array(':value', 0, 1))
                            ->rule('general_faq', 'range', array(':value', 0, 1))
                            ->rule('general_black_list', 'range', array(':value', 0, 1))
                            ->rule('general_search_by_description', 'range', array(':value', 0, 1))
                            ->rule('general_recaptcha_active', 'range', array(':value', 0, 1));
            
            if ($validation->check()) {
                //save general
                foreach ($config as $c) 
                {   
                    $config_res = $this->request->post();

                    if ( ! in_array($c->config_key, $do_nothing)
                        AND ($config_res[$c->group_name][$c->config_key][0] != $c->config_value
                            OR Kohana::$_POST_ORIG['general']['html_head'][0] != $c->config_value
                            OR Kohana::$_POST_ORIG['general']['html_footer'][0] != $c->config_value))
                    {
                        if ($c->config_key == 'html_head' OR $c->config_key == 'html_footer')
                            $c->config_value = Kohana::$_POST_ORIG[$c->group_name][$c->config_key][0];
                        else
                            $c->config_value = $config_res[$c->group_name][$c->config_key][0];
    
                        Model_Config::set_value($c->group_name,$c->config_key,$c->config_value);    
                    }
                      
                }
            }
            else {
                $errors = $validation->errors('config');
            
                foreach ($errors as $error) 
                    Alert::set(Alert::ALERT, $error);
            
                $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'general')));
            }

            Alert::set(Alert::SUCCESS, __('General Configuration updated'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'general')));
        }

        $this->template->content = View::factory('oc-panel/pages/settings/general', array('forms'=>$forms));
    }

    /**
     * Payment deatails and paypal configuration can be configured here
     * @return [view] Renders view with form inputs
     */
    public function action_payment()
    {

        //delete featured plan
        if (is_numeric(Core::get('delete_plan')))
        {
            Model_Order::delete_featured_plan(Core::get('delete_plan'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'payment')));
        }

        // validation active 
        $this->template->scripts['footer'][]= 'js/jquery.validate.min.js';
        $this->template->scripts['footer'][]= '/js/oc-panel/settings.js';
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Payments')));
        $this->template->title = __('Payments');

        // all form config values
        $paymentconf = new Model_Config();
        $config = $paymentconf->where('group_name', '=', 'payment')->find_all();
        
        // save only changed values
        if($this->request->post())
        {
            if (is_numeric(Core::request('featured_days')) AND is_numeric(Core::request('featured_price')))
            {
                Model_Order::set_featured_plan(Core::request('featured_days'),Core::request('featured_price'),Core::request('featured_days_key'));

                Alert::set(Alert::SUCCESS, __('Featured plan updated'));
                $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'payment')));
            }

            $validation =   Validation::factory($this->request->post())
            ->rule('pay_to_go_on_top', 'numeric')
            ->rule('to_featured', 'range', array(':value', 0, 1))
            ->rule('to_top', 'range', array(':value', 0, 1))
            ->rule('sandbox', 'range', array(':value', 0, 1))
            ->rule('paypal_seller', 'range', array(':value', 0, 1))
            ->rule('stock', 'range', array(':value', 0, 1))
            ->rule('authorize_sandbox', 'range', array(':value', 0, 1))
            ->rule('stripe_address', 'range', array(':value', 0, 1));
            
            //not updatable fields
            $do_nothing = array('featured_days','pay_to_go_on_feature','featured_plans');

            if ($validation->check()) {
                foreach ($config as $c) 
                {
                    $config_res = $this->request->post($c->config_key); 
                    
                    if(!in_array($c->config_key, $do_nothing) AND $config_res != $c->config_value)
                    {
                        if ($c->config_key == 'pay_to_go_on_top')
                            $config_res = str_replace(',', '.', $config_res);

                        $c->config_value = $config_res;
                        try {
                            $c->save();
                        } catch (Exception $e) {
                            throw HTTP_Exception::factory(500,$e->getMessage());
                        }
                    }
                }
            }
            else {
                $errors = $validation->errors('config');

                foreach ($errors as $error) 
                    Alert::set(Alert::ALERT, $error);

                $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'payment')));
            }
            
            Alert::set(Alert::SUCCESS, __('Payments Configuration updated'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'payment')));
        }

        $pages = array(''=>__('Deactivated'));
        foreach (Model_Content::get_pages() as $key => $value) 
            $pages[$value->seotitle] = $value->title;

        $this->template->content = View::factory('oc-panel/pages/settings/payment', array('config'          => $config,
                                                                                           'pages'          => $pages,
                                                                                           'featured_plans' => Model_Order::get_featured_plans()));
    }

    /**
     * Image configuration 
     * @return [view] Renders view with form inputs
     */
    public function action_image()
    {
        $this->template->scripts['footer'][]= 'js/jquery.validate.min.js';
        $this->template->scripts['footer'][]= '/js/oc-panel/settings.js';
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Image')));
        $this->template->title = __('Image');

        // all form config values
        $imageconf = new Model_Config();
        $config = $imageconf->where('group_name', '=', 'image')->find_all();

        // save only changed values
        if($this->request->post())
        {
            foreach ($this->request->post('image') as $k => $v)
                $this->request->post('image_'.$k, $v[0]);
            
            $validation =   Validation::factory($this->request->post())
                            ->rule('image_max_image_size', 'not_empty')
                            ->rule('image_max_image_size', 'digit')
                            ->rule('image_height', 'digit')
                            ->rule('image_width', 'not_empty')
                            ->rule('image_width', 'digit')
                            ->rule('image_height_thumb', 'not_empty')
                            ->rule('image_height_thumb', 'digit')
                            ->rule('image_width_thumb', 'not_empty')
                            ->rule('image_width_thumb', 'digit')
                            ->rule('image_quality', 'not_empty')
                            ->rule('image_quality', 'digit')
                            ->rule('image_quality', 'range', array(':value', 1, 100))
                            ->rule('image_watermark', 'range', array(':value', 0, 1))
                            ->rule('image_watermark_position', 'not_empty')
                            ->rule('image_watermark_position', 'digit')
                            ->rule('image_watermark_position', 'range', array(':value', 0, 2))
                            ->rule('image_disallow_nudes', 'range', array(':value', 0, 1))
                            ->rule('image_aws_s3_active', 'range', array(':value', 0, 1));
            
            if ($validation->check()) {
                foreach ($config as $c) 
                {
                    $config_res = $this->request->post(); 
                    
                    if (!array_key_exists('allowed_formats', $config_res[$c->group_name]))
                    {
                        Alert::set(Alert::ERROR, __('At least one image format should be allowed.'));
                        $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'image')));
                    }
    
                    if($config_res[$c->group_name][$c->config_key][0] != $c->config_value)
                    {
                        if($c->config_key == 'allowed_formats')
                        {
                          $allowed_formats = '';
                          foreach ($config_res[$c->group_name][$c->config_key] as $key => $value) 
                          {
                              $allowed_formats .= $value.",";
                          }
                          $config_res[$c->group_name][$c->config_key][0] = $allowed_formats;
                        }
                        
                        if($c->config_key == 'aws_s3_domain')
                        {
                            switch ($config_res[$c->group_name]['aws_s3_domain'][0])
                            {
                                case 'bn-s3':
                                    $s3_domain = $config_res[$c->group_name]['aws_s3_bucket'][0].'.s3.amazonaws.com';
                                    break;
                                    
                                case 'bn':
                                    $s3_domain = $config_res[$c->group_name]['aws_s3_bucket'][0];
                                    break;
                                    
                                default:
                                    $s3_domain = 's3.amazonaws.com/'.$config_res[$c->group_name]['aws_s3_bucket'][0];
                                    break;
                            }
                            $config_res[$c->group_name][$c->config_key][0] = $s3_domain.'/';
                        }
    
                        $c->config_value = $config_res[$c->group_name][$c->config_key][0];
                        Model_Config::set_value($c->group_name,$c->config_key,$c->config_value);
                    }
                }
            }
            else {
                $errors = $validation->errors('config');
                
                foreach ($errors as $error) 
                    Alert::set(Alert::ALERT, $error);
                
                $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'image')));
            }
            
            Alert::set(Alert::SUCCESS, __('Image Configuration updated'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'image')));
        }

        $this->template->content = View::factory('oc-panel/pages/settings/image', array('config'=>$config));
    }

    /**
     * Plugins configuration 
     * @return [view] Renders view with form inputs
     */
    public function action_plugins()
    {
        // validation active 
        $this->template->scripts['footer'][]= 'js/jquery.validate.min.js';
        $this->template->scripts['footer'][]= '/js/oc-panel/settings.js';
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Plugins')));
        $this->template->title = __('Plugins');

        // all form config values
        $generalconfig = new Model_Config();
        $config = $generalconfig->where('group_name', '=', 'general')->or_where('group_name', '=', 'i18n')->find_all();

        // config general array
        foreach ($config as $c) 
        {
            $forms[$c->config_key] = $forms[$c->config_key] = array('key'=>$c->group_name.'['.$c->config_key.'][]', 'id'=>$c->config_key, 'value'=>$c->config_value);
        }
        
        // save only changed values
        if($this->request->post())
        {
            //d($this->request->post());
            foreach ($this->request->post('general') as $k => $v)
                $this->request->post('general_'.$k, $v[0]);
            
            $validation = Validation::factory($this->request->post());
            
            if ($validation->check()) {
                //save general
                foreach ($config as $c) 
                {   
                    $config_res = $this->request->post();

                    if (isset($config_res[$c->group_name][$c->config_key][0]) AND $config_res[$c->group_name][$c->config_key][0] != $c->config_value)
                    {
                        $c->config_value = $config_res[$c->group_name][$c->config_key][0];
                        Model_Config::set_value($c->group_name,$c->config_key,$c->config_value);    
                    }
                }
            }
            else {
                $errors = $validation->errors('config');
            
                foreach ($errors as $error) 
                    Alert::set(Alert::ALERT, $error);
            
                $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'plugins')));
            }

            Alert::set(Alert::SUCCESS, __('Plugins configuration updated'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'plugins')));
        }

        $this->template->content = View::factory('oc-panel/pages/settings/plugins', array('forms'=>$forms));
    }

}//end of controller