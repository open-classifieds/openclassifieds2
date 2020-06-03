<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller Translations
 */


class Controller_Panel_Newsletter extends Auth_Controller {


    public function action_index()
    {
        if (!in_array(core::config('email.service'), ['elastic', 'elasticemail', 'smtp', 'gmail', 'outlook', 'yahoo', 'zoho']))
        {
            Alert::set(Alert::ERROR, "Newsletters disabled. Please set up the <a target='_blank' href='" . Route::url('oc-panel', array('controller' => 'settings', 'action' => 'email')) . "''>SMTP settings</a>.<br><br>More information and instructions <a href='//docs.yclas.com/smtp-configuration' target='_blank'>here</a>.");
            return;
        }

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Newsletter')));
        $this->template->title = __('Newsletter');

        //count all users
        $user = new Model_User();
        $user->where('status','=',Model_User::STATUS_ACTIVE)->where('subscriber','=',1);
        $count_all_users = $user->count_all();

        //count featured expired
        $query = DB::select(DB::expr('COUNT(id_user) count'))
                        ->from('ads')
                        ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        ->where('featured','<',Date::unix2mysql())
                        ->group_by('id_user')
                        ->execute();

        $count_featured_expired = $query->as_array();
        $count_featured_expired = (isset($count_featured_expired[0]['count']))?$count_featured_expired[0]['count']:0;


        //count all featured
        $query = DB::select(DB::expr('COUNT(id_user) count'))
                        ->from('ads')
                        ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        ->where('featured','IS NOT',NULL)
                        ->group_by('id_user')
                        ->execute();

        $count_featured = $query->as_array();
        $count_featured = (isset($count_featured[0]['count']))?$count_featured[0]['count']:0;

        //users without published ads
        $query = DB::select(DB::expr('COUNT(id_user) count'))
                        ->from(array('users','u'))
                        ->join(array('ads','a'),'LEFT')
                        ->using('id_user')
                        ->where('u.status','=',Model_User::STATUS_ACTIVE)
                        ->where('u.subscriber','=',1)
                        ->where('a.title','is',NULL)
                        ->group_by('u.id_user')
                        ->execute();
        $count_unpub = $query->as_array();
        $count_unpub = (isset($count_unpub[0]['count']))?$count_unpub[0]['count']:0;

        //count all users not login 3 months
        $query = DB::select(DB::expr('COUNT(id_user) count'))
                        ->from('users')
                        ->where('status','=',Model_User::STATUS_ACTIVE)
                        ->where('last_login','<=',Date::unix2mysql(strtotime('-3 month')))
                        ->or_where('last_login','IS',NULL)
                        ->where('subscriber','=',1)
                        ->execute();

        $count_logged = $query->as_array();
        $count_logged = (isset($count_logged[0]['count']))?$count_logged[0]['count']:0;

        //count all users spammers
        $query = DB::select(DB::expr('COUNT(id_user) count'))
                        ->from('users')
                        ->where('status','=',Model_User::STATUS_SPAM)
                        ->where('subscriber','=',1)
                        ->execute();

        $count_spam = $query->as_array();
        $count_spam = (isset($count_spam[0]['count']))?$count_spam[0]['count']:0;


        //post done sending newsletter
        if($this->request->post() AND Core::post('subject')!=NULL)
        {
            $users = array();

            if (core::post('send_all')=='on')
            {
                $query = DB::select('email')->select('name')
                        ->from('users')
                        ->where('status','=',Model_User::STATUS_ACTIVE)
                        ->where('subscriber','=',1)
                        ->execute();

                $users = array_merge($users,$query->as_array());
            }

            if (Core::extra_features() == TRUE)
            {
                if (core::post('send_featured_expired')=='on')
                {
                    $query = DB::select('email')->select('name')
                            ->from(array('users','u'))
                            ->join(array('ads','a'))
                            ->using('id_user')
                            ->where('a.status','=',Model_Ad::STATUS_PUBLISHED)
                            ->where('a.featured','<',Date::unix2mysql())
                            ->where('u.subscriber','=',1)
                            ->group_by('id_user')
                            ->execute();

                    $users = array_merge($users,$query->as_array());
                }

                if (core::post('send_featured')=='on')
                {
                    $query = DB::select('email')->select('name')
                            ->from(array('users','u'))
                            ->join(array('ads','a'))
                            ->using('id_user')
                            ->where('a.status','=',Model_Ad::STATUS_PUBLISHED)
                            ->where('a.featured','IS NOT',NULL)
                            ->where('u.subscriber','=',1)
                            ->group_by('id_user')
                            ->execute();

                    $users = array_merge($users,$query->as_array());
                }

                if (core::post('send_unpub')=='on')
                {
                     //users without published ads
                    $query = DB::select('email')->select('name')
                            ->from(array('users','u'))
                            ->join(array('ads','a'),'LEFT')
                            ->using('id_user')
                            ->where('u.status','=',Model_User::STATUS_ACTIVE)
                            ->where('u.subscriber','=',1)
                            ->where('a.title','is',NULL)
                            ->execute();

                    $users = array_merge($users,$query->as_array());
                }

                if (core::post('send_logged')=='on')
                {
                    $query = DB::select('email')->select('name')
                            ->from('users')
                            ->where('status','=',Model_User::STATUS_ACTIVE)
                            ->where('last_login','<=',Date::unix2mysql(strtotime('-3 month')))
                            ->or_where('last_login','IS',NULL)
                            ->where('subscriber','=',1)
                            ->execute();

                    $users = array_merge($users,$query->as_array());
                }

                if (core::post('send_spam')=='on')
                {
                    $query = DB::select('email')->select('name')
                            ->from('users')
                            ->where('status','=',Model_User::STATUS_SPAM)
                            ->where('subscriber','=',1)
                            ->execute();

                    $users = array_merge($users,$query->as_array());
                }
            }

            //NOTE $users may have duplicated emails, but phpmailer takes care of not sending the email 2 times to same recipient

            //sending!
            if (core::count($users)>0)
            {
                if ( !Email::send($users,'',Core::post('subject'),Kohana::$_POST_ORIG['description'],Core::post('from_email'), Core::post('from') ) )
                    Alert::set(Alert::ERROR,__('Error on mail delivery, not sent'));
                else
                    Alert::set(Alert::SUCCESS,__('Email sent'));
            }
            else
            {
                Alert::set(Alert::ERROR,__('Mail not sent'));
            }

        }

        $this->template->content = View::factory('oc-panel/pages/newsletter',array( 'count_all_users'           => $count_all_users,
                                                                                    'count_featured_expired'    => $count_featured_expired,
                                                                                    'count_featured'    => $count_featured,
                                                                                    'count_unpub'       => $count_unpub,
                                                                                    'count_logged'      => $count_logged,
                                                                                    'count_spam'        => $count_spam,
                                                                                    )
                                                                                );

    }




}//end of controller
