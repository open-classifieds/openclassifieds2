<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Widget
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Controller_Panel_Widget extends Auth_Controller {

    public function action_index()
    {
        // $this->before('oc-panel/pages/widgets/main');

        //template header
        $this->template->title  = __('Widgets');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Widgets')));

        $this->template->styles = ['css/sortable.css' => 'screen'];

        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/widgets.js';


        $widgets           = Widgets::get_widgets();
        $placeholders      = Widgets::get_placeholders();
        $this->template->content = View::factory('oc-panel/pages/widgets/widget',array('widgets' => $widgets,'placeholders'=>$placeholders));

    }


   	/**
   	 * action_save
   	 * @return save widget (make active)
   	 */
   	public function action_save()
   	{

        // save only changed values
        if($this->request->post())
        {
            //deleting the fragment cache...a bit ugly but works.
            View::delete_fragment('sidebar_front');
            View::delete_fragment('footer_front');

            //get place holder name
            $placeholder    = core::post('placeholder');
            //get widget class
            $widget         = core::post('widget_class');
            //widget name
            $widget_name    = core::post('widget_name');

            //$data = array();
            //extract all the data and prepare array
            foreach ($this->request->post() as $name=>$value)
            {
                if ($name!='placeholder' AND $name!='widget_class' AND $name!='widget_name')
                    $data[$name] = $value;
            }

            $old_placeholder = NULL;

            $widget = new $widget();

            //the widget exists, we load it since we need the previous placeholder
            if ($widget_name!=NULL)
            {
                $widget->load($widget_name);
                $old_placeholder = $widget->placeholder;
            }

            $widget->placeholder = $placeholder;
            $widget->data = $data;


            try {

                $widget->save($old_placeholder);

                //clean cache config
                $c = new ConfigDB();
                $c->reload_config();

                if ($widget_name!=NULL)
                    Alert::set(Alert::SUCCESS,sprintf(__('Widget %s saved in %s'),$widget_name,$placeholder));
                else
                    Alert::set(Alert::SUCCESS,sprintf(__('Widget created in %s'),$placeholder));

            } catch (Exception $e) {
                //throw 500
                throw HTTP_Exception::factory(500,$e->getMessage());
            }

            $this->redirect(Route::url('oc-panel', array('controller'=>'widget', 'action'=>'index')));
        }


   	}

   	/**
   	 * action_remove
   	 * @return remove widget (deactivate)
   	 */
   	public function action_remove()
   	{
        $widget_name = $this->request->param('id');
        if ($widget_name!==NULL)
        {
            $w = Widget::factory($widget_name);

            if ($w AND $w->delete())
                Alert::set(Alert::SUCCESS,sprintf(__('Widget %s deleted'),$widget_name));
            else
                Alert::set(Alert::ERROR,sprintf(__('Cannot delete widget %s'),$widget_name));
        }
        else
            Alert::set(Alert::ERROR,__('Widget parameter missing'));

        $this->redirect(Route::url('oc-panel', array('controller'=>'widget', 'action'=>'index')));
    }


    public function action_saveplaceholders()
    {
        //deleting the fragment cache...a bit ugly but works.
        View::delete_fragment('sidebar_front');
        View::delete_fragment('footer_front');

        $this->auto_render = FALSE;
        $this->template = View::factory('js');


        DB::delete('config')->where('group_name','=','placeholder')->execute();

        //for each placeholder
        foreach ($_GET as $placeholder => $widgets)
        {
            if (!is_array($widgets))
            {
                $widgets = array($widgets);
            }

            // save palceholder to DB
            $confp = new Model_Config();
            $confp->where('group_name','=','placeholder')
                ->where('config_key','=',$placeholder)
                ->limit(1)->find();
            if (!$confp->loaded())
            {
                $confp->group_name = 'placeholder';
                $confp->config_key = $placeholder;
            }

            $confp->config_value = json_encode($widgets);
            $confp->save();

            //edit each widget change placeholder
            foreach ($widgets as $wname)
            {
                $w = Widget::factory($wname);
                if ($w!==NULL)
                {
                    if ($w->loaded AND $w->placeholder != $placeholder)
                    {
                        $w->placeholder = $placeholder;
                        $w->save();
                    }
                }

            }

        }

        $this->template->content = __('Saved');

    }
}
