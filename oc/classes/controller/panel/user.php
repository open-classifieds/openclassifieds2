<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_User extends Auth_CrudAjax {

    protected $_filter_fields = array(
                                        'status' => array(0=>'Inactive',1=>'Active',5=>'Spam'),
                                        'id_role' => array('type'=>'SELECT','table'=>'roles','key'=>'id_role','value'=>'name'),
                                        );


    /**
    * @var $_index_fields ORM fields shown in index
    */
    protected $_index_fields = array('name','email','id_role','logins','last_login','status');

    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'user';

    protected $_search_fields = array('name','email');

    protected $_fields_caption = [
        'id_role' => ['model' => 'role', 'caption' => 'name'],
        'status' => 'Model_User::get_status_label',
    ];

    function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_buttons_actions = array(
                                        array( 'url'   => Route::url('oc-panel', array('controller'=>'order')).'?filter__id_user=' ,
                                                'title' => __('Orders'),
                                                'class' => '',
                                                'icon'  => 'fa fa-fw fa-credit-card'
                                                ),
                                        array( 'url'   => Route::url('oc-panel', array('controller'=>'user', 'action'=>'spam')).'/' ,
                                                'title' => __('Spam'),
                                                'class' => '',
                                                'icon'  => 'fa fa-fw fa-fire',
                                                ),
                                        );

        //for OC display ads
        if (class_exists('Model_Ad'))
        {
            array_unshift($this->_buttons_actions,   array( 'url'   => Route::url('oc-panel', array('controller'=>'ad')).'?filter__id_user=' ,
                                                            'title' => __('Ads'),
                                                            'class' => '',
                                                            'icon'  => 'fa fa-fw fa-th'
                                                            ));
        }

        //for OE display tickets
        if (class_exists('Model_Ticket'))
        {
            array_unshift($this->_buttons_actions,   array( 'url'   => Route::url('oc-panel', array('controller'=>'support', 'action'=>'index')).'?filter__id_user=' ,
                                                            'title' => __('Support'),
                                                            'class' => '',
                                                            'icon'  => 'fa fa-fw fa-comment'
                                                            ));
        }
    }

    public function action_index($view = NULL)
    {
        parent::action_index('oc-panel/pages/user/index');
    }

	/**
	 * CRUD controller: CREATE
	 */
	public function action_create()
	{
		$this->template->title = __('New').' '.__($this->_orm_model);

		$form = new FormOrm($this->_orm_model);

		if ($this->request->post())
		{
			if ( $success = $form->submit() )
			{
				$form->object->seoname = (new Model_User())->gen_seo_title($form->object->name);
				$form->save_object();
				Alert::set(Alert::SUCCESS, __('Item created').'. '.__('Please to see the changes delete the cache')
					.'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1" title="'.__('Delete All').'">'
					.__('Delete All').'</a>');

				$this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
			}
			else
			{
				Alert::set(Alert::ERROR, __('Check form for errors'));
			}
		}

		return $this->render('oc-panel/crud/create', array('form' => $form));
	}

	/**
	 * CRUD controller: UPDATE
	 */
	public function action_update()
	{
		$this->template->title = __('Update').' '.__($this->_orm_model).' '.$this->request->param('id');

		$form = new FormOrm($this->_orm_model,$this->request->param('id'));

		if ($this->request->post())
		{
			if ( $success = $form->submit() )
			{
                $form->save_object();
                Alert::set(Alert::SUCCESS, __('Item updated').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1" title="'.__('Delete All').'">'
                    .__('Delete All').'</a>');
                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
			}
			else
			{
				Alert::set(Alert::ERROR, __('Check form for errors'));
			}
		}

		return $this->render('oc-panel/pages/user/update', array('form' => $form));
	}

	public function action_changepass()
	{
		// only admins can change password
		if ($this->request->post() AND $this->user->is_admin())
		{
			$user = new Model_User($this->request->param('id'));

			if (core::post('password1')==core::post('password2'))
			{
				if(!empty(core::post('password1'))){

					$user->password        = core::post('password1');
					$user->last_modified   = Date::unix2mysql();
                    $user->failed_attempts = 0;
                    $user->last_failed     = NULL;

					try
					{
						$user->save();

						// email user with new password
						Email::content($user->email,$user->name,NULL,NULL,'password-changed',array('[USER.PWD]'=>core::post('password1')));
					}
					catch (ORM_Validation_Exception $e)
					{
						throw HTTP_Exception::factory(500,$e->errors(''));
					}
					catch (Exception $e)
					{
						throw HTTP_Exception::factory(500,$e->getMessage());
					}

					Alert::set(Alert::SUCCESS, __('Password is changed'));
				}
				else
				{
					Form::set_errors(array(__('Nothing is provided')));
				}
			}
			else
			{
				Form::set_errors(array(__('Passwords do not match')));
			}

		}

		$this->redirect(Route::url('oc-panel',array('controller'=>'user', 'action'=>'update', 'id'=>$this->request->param('id'))));
	}

    /**
     * mark user as spamer, he can no longer login
     * @return [type] [description]
     */
    public function action_spam()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');

        $user = new Model_User($this->request->param('id'));

        if ($user->loaded())
        {
            try
            {
                $user->user_spam();
            }
            catch (Exception $e)
            {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
            HTTP::redirect(Route::url('oc-panel', array('controller'=>$this->request->controller())));
        }

    }

    /**
     *
     * Loads a basic list info
     * @param string $view template to render
     */
    public function action_export($view = NULL)
    {
        if (class_exists('Model_Ad'))
        {
            //the name of the file that user will download
            $file_name = $this->_orm_model.'_export.csv';
            //name of the TMP file
            $output_file = tempnam(sys_get_temp_dir(), $file_name);

            //instance of the crud
            $users = ORM::Factory($this->_orm_model);

            //writting
            $output = fopen($output_file, 'w');

            //header of the csv
            $header = array('id_user','name','seoname','email','description','num_ads',
                            'image','last_login','last_modified','created','ipaddress','status','phone');
            foreach (Model_UserField::get_all(FALSE) as $key=>$value)
                $header[] = $key;

            //header of the CSV
            fputcsv($output, $header);

            //getting all the users
            $users = $users->find_all();

            //each element
            foreach($users as $user)
                fputcsv($output, array( 'id_user'   => $user->id_user,
                                        'name'      => $user->name,
                                        'seoname'   => $user->seoname,
                                        'email'     => $user->email,
                                        'description'   => $user->description,
                                        'num_ads'       => $user->ads->count_all(),
                                        'image'     => $user->get_profile_image(),
                                        'last_login'    => $user->last_login,
                                        'last_modified' => $user->last_modified,
                                        'created'   => $user->created,
                                        'ipaddress' => long2ip($user->last_ip),
                                        'status'    => $user->status,
                                        'phone'    => $user->phone,
                                       )+$user->custom_columns(FALSE,FALSE));

            fclose($output);

            //returns the file to the browser as attachement and deletes the TMP file
            Response::factory()->send_file($output_file,$file_name,array('delete'=>TRUE));
        }
        else
            return parent::export();
    }

    public function action_delete()
    {
        if ($this->request->param('id') == $this->user->id_user)
        {
            Alert::set(Alert::INFO, __('You can not delete your user'));
            HTTP::redirect(Route::url('oc-panel', array('controller'=>$this->request->controller())));
        }
        else
            return parent::action_delete();
    }
}
