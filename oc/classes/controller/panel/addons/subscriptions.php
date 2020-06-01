<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Addons_Subscriptions extends Auth_CrudAjax {

    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'plan';

    /**
     * @var $_route_name Route to be used for actions (default: user, check /oc/config/routes.php)
     */
    protected $_route_name = 'oc-panel/addons';

    /**
     *
     * Contruct that checks you are loged in before nothing else happens!
     */
    function __construct(Request $request, Response $response)
    {
        if (Theme::get('premium')!=1)
        {
            Alert::set(Alert::INFO,  __('Upgrade your Yclas site to PRO to activate this feature.'));
        }

        parent::__construct($request,$response);
    }

    public function action_index($view = NULL)
    {
        $this->template->title = __('Subscription');

        if ($this->request->post() AND Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'subscriptions']));
        }

        if($this->request->post())
        {
            if (Core::post('subscriptions_expire') == 1 AND (Core::post('is_active') ?? 0))
            {
                $plan = (new Model_Plan())
                    ->where('status','=',1)
                    ->find();

                if (!$plan->loaded())
                {
                    $url = Route::url('oc-panel',array('controller'=>'plan'));
                    Alert::set(Alert::INFO, __('Please, <a href="'.$url.'">create a plan</a> first. More information <a href="//docs.yclas.com/membership-plans/#subscription-expire" target="_blank">here</a>'));
                }
            }

            Model_Config::set_value('general', 'subscriptions', Core::post('is_active') ?? 0);
            Model_Config::set_value('general', 'subscriptions_expire', Core::post('subscriptions_expire'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'subscriptions']));
        }

        $this->template->scripts['footer'][] = Route::url($this->_route_name, array('controller'=> Request::current()->controller(), 'action'=>'bootgrid'));
        $this->template->styles = array('css/jquery.bootgrid.min.css' => 'screen','//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');

        $plans = ORM::Factory('plan');

        $this->render('oc-panel/pages/addons/subscriptions/index', [
            'elements' => $plans,
            'filters'  => $this->_filter_fields,
            'extra_info_view' => $this->_extra_info_view,
            'captions' => $this->_fields_caption,
            'is_active' => (bool) Core::config('general.subscriptions'),
        ]);
    }
}
