<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Feed extends Controller {

    public function action_index()
    {
        $this->auto_render = FALSE;

        $info = array(
                        'title'       => 'RSS '.$this->html_chars(UTF8::clean(Core::config('general.site_name'))),
                        'pubDate'     => date("r"),
                        'description' => $this->html_chars(UTF8::clean(__('Latest published'))),
                        'generator'   => 'Yclas',
                        'self-link'   => Request::current()->url(),
        );

        $items = array();

        //last ads, you can modify this value at: advertisement.feed_elements
        $ads = new Model_Ad();

        //only published ads
        $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED);

        //filter by category aor location
        if (Model_Category::current()->loaded())
            $ads->where('id_category','=',Model_Category::current()->id_category);

        if (Model_Location::current()->loaded())
            $ads->where('id_location','=',Model_Location::current()->id_location);

        //order depending on the sort parameter
        switch (core::request('sort',core::config('advertisement.sort_by')))
        {
            //title z->a
            case 'title-asc':
                $ads->order_by('title','asc')->order_by('published','desc');
                break;
            //title a->z
            case 'title-desc':
                $ads->order_by('title','desc')->order_by('published','desc');
                break;
            //cheaper first
            case 'price-asc':
                $ads->order_by('price','asc')->order_by('published','desc');
                break;
            //expensive first
            case 'price-desc':
                $ads->order_by('price','desc')->order_by('published','desc');
                break;
            //featured
            case 'featured':
                $ads->order_by('featured','desc')->order_by('published','desc');
                break;
            //rating
            case 'rating':
                $ads->order_by('rate','desc')->order_by('published','desc');
                break;
            //favorited
            case 'favorited':
                $ads->order_by('favorited','desc')->order_by('published','desc');
                break;
            //oldest first
            case 'published-asc':
                $ads->order_by('published','asc');
                break;
            //newest first
            case 'published-desc':
            default:
                $ads->order_by('published','desc');
                break;
        }

        $ads = $ads->limit(Core::config('advertisement.feed_elements'))->cached()->find_all();

        foreach($ads as $a)
        {
            $url= Route::url('ad',  array('category'=>$a->category->seoname,'seotitle'=>$a->seotitle));
            $item = array(
                                'title'         => $this->html_chars(UTF8::clean($a->title)),
                                'link'          => $url,
                                'pubDate'       => Date::mysql2unix($a->published),
                                'description'   => $this->html_chars(Text::removebbcode(UTF8::clean($a->description))),
                                'guid'          => $url,
                          );
            if($a->get_first_image() !== NULL)
            {
                $item['description'] = '<img src="'.$a->get_first_image().'" />'.$item['description'];
            }

            $items[] = $item;
        }

        $xml = Feed::create($info, $items);

        $this->response->headers('Content-type','text/xml');
        $this->response->body($xml);

    }


    public function action_blog()
    {
        $this->auto_render = FALSE;

        $info = array(
                        'title'         => 'RSS Blog '.$this->html_chars(UTF8::clean(Core::config('general.site_name'))),
                        'pubDate'       => date("r"),
                        'description'   => $this->html_chars(UTF8::clean(__('Latest post published'))),
                        'generator'     => 'Yclas',
                        'link'          =>  Route::url('blog'),
                        'self-link'     => Request::current()->url(),
        );

        $items = array();

        $posts = new Model_Post();
        $posts = $posts->where('status','=', 1)
                ->where('id_forum', '=', NULL)
                ->order_by('created','desc')
                ->limit(Core::config('advertisement.feed_elements'))
                ->cached()
                ->find_all();


        foreach($posts as $post)
        {
            $url= Route::url('blog',  array('seotitle'=>$post->seotitle));

            $items[] = array(
                                'title'         => $this->html_chars(UTF8::clean($post->title)),
                                'link'          => $url,
                                'pubDate'       => Date::mysql2unix($post->created),
                                'description'   => $this->html_chars(Text::removebbcode(UTF8::clean($post->description))),
                                'guid'          => $url,
                          );
        }

        $xml = Feed::create($info, $items);

        $this->response->headers('Content-type','text/xml');
        $this->response->body($xml);

    }

    public function action_forum()
    {
        $this->auto_render = FALSE;

        $info = array(
                        'title'         => 'RSS Forum '.$this->html_chars(UTF8::clean(Core::config('general.site_name'))),
                        'pubDate'       => date("r"),
                        'description'   => $this->html_chars(UTF8::clean(__('Latest post published'))),
                        'generator'     => 'Yclas',
                        'link'          =>  Route::url('forum-home'),
                        'self-link'     => Request::current()->url(),
        );

        $items = array();

        $topics = new Model_Topic();

        if(Model_Forum::current()->loaded())
            $topics->where('id_forum','=',Model_Forum::current()->id_forum);
        else
            $topics->where('id_forum','!=',NULL);//any forum

        $topics = $topics->where('status','=', Model_Topic::STATUS_ACTIVE)
                ->where('id_post_parent','IS',NULL)
                ->order_by('created','desc')
                ->limit(Core::config('advertisement.feed_elements'))
                ->cached()
                ->find_all();

        foreach($topics as $topic)
        {
            $url= Route::url('forum-topic',  array('seotitle'=>$topic->seotitle,'forum'=>$topic->forum->seoname));

            $items[] = array(
                                'title'         => $this->html_chars(UTF8::clean($topic->title)),
                                'link'          => $url,
                                'pubDate'       => Date::mysql2unix($topic->created),
                                'description'   => $this->html_chars(Text::removebbcode(UTF8::clean($topic->description))),
                                'guid'          => $url,
                          );
        }

        $xml = Feed::create($info, $items);

        $this->response->headers('Content-type','text/xml');
        $this->response->body($xml);

    }

    public function action_profile()
    {
        $this->auto_render = FALSE;
        $xml = 'FALSE';

        $seoname = $this->request->param('seoname',NULL);
        if ($seoname!==NULL)
        {
            $user = new Model_User();
            $user->where('seoname','=', $seoname)
                 ->where('status','=', Model_User::STATUS_ACTIVE)
                 ->limit(1)->cached()->find();

            if ($user->loaded())
            {

                $info = array(
                                'title'       => 'RSS '.$this->html_chars(UTF8::clean($user->name)),
                                'pubDate'     => date("r"),
                                'description' => $this->html_chars(UTF8::clean($user->name.' - '.$user->description)),
                                'generator'   => 'Yclas',
                                'link'        =>  Route::url('profile', array('seoname'=>$seoname)),
                                'self-link'   => Request::current()->url(),
                );

                $items = array();

                //last ads, you can modify this value at: advertisement.feed_elements
                $ads = new Model_Ad();
                $ads    ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        ->where('id_user','=',$user->id_user)
                        ->order_by('published','desc')
                        ->limit(Core::config('advertisement.feed_elements'));

                $ads = $ads->cached()->find_all();

                foreach($ads as $a)
                {
                    $url= Route::url('ad',  array('category'=>$a->category->seoname,'seotitle'=>$a->seotitle));
                    $item = array(
                                        'title'         => $this->html_chars(UTF8::clean($a->title)),
                                        'link'          => $url,
                                        'pubDate'       => Date::mysql2unix($a->published),
                                        'description'   => $this->html_chars(Text::removebbcode(UTF8::clean($a->description))),
                                        'guid'          => $url,
                                  );
                    if($a->get_first_image() !== NULL)
                    {
                        $item['description'] = '<img src="'.$a->get_first_image().'" />'.$item['description'];
                    }

                    $items[] = $item;
                }
                $xml = Feed::create($info, $items);
            }
        }

        $this->response->headers('Content-type','text/xml');
        $this->response->body($xml);

    }

    public function action_info()
    {

        //try to get the info from the cache
        $info = Core::cache('action_info',NULL);

        //not cached :(
        if ($info === NULL)
        {
            $ads = new Model_Ad();
            $total_ads = $ads->count_all();

            $last_ad = $ads->select('published')->order_by('published','desc')->limit(1)->find();
            $last_ad = $last_ad->published;

            $ads = new Model_Ad();
            $first_ad = $ads->select('published')->order_by('published','asc')->limit(1)->find();
            $first_ad = $first_ad->published;

            $users = new Model_User();
            $total_users = $users->count_all();

            $info = array(
                            'site_url'      => Core::config('general.base_url'),
                            'site_name'     => Core::config('general.site_name'),
                            'site_description' => Core::config('general.site_description'),
                            'created'       => $first_ad,
                            'updated'       => $last_ad,
                            'email'         => Core::config('email.notify_email'),
                            'version'       => Core::VERSION,
                            'theme'         => Core::config('appearance.theme'),
                            'charset'       => Kohana::$charset,
                            'timezone'      => Core::config('i18n.timezone'),
                            'locale'        => Core::config('i18n.locale'),
                            'currency'      => '',
                            'ads'           => $total_ads,
                            'views'         => Model_Visit::count_all_visits(),
                            'users'         => $total_users,
            );

            Core::cache('action_info',$info);
        }


        $this->response->headers('Content-type','application/javascript');
        $this->response->body(json_encode($info));

    }


    /**
     * after does nothing since we send an XML
     */
    public function after(){}

    protected function html_chars($value)
    {
		return htmlspecialchars( (string) $value, ENT_QUOTES, Kohana::$charset);
    }


} // End feed
