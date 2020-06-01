<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Coupon extends Auth_Crud {

    /**
     * @var $_index_fields ORM fields shown in index
     */
    protected $_index_fields = array('name','id_product','number_coupons','discount_amount','discount_percentage','valid_date','created');

    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'coupon';


    /**
     *
     * Loads a basic list info
     * @param string $view template to render
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __('Coupons');
        $this->template->scripts['footer'][] = 'js/oc-panel/coupon.js';
        $this->template->scripts['footer'][] = 'js/jquery.toolbar.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/moderation.js';

        $elements = new Model_Coupon();

        if (core::get('name')!==NULL)
            $elements = $elements->where('name', '=', core::get('name'));


        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $elements->count_all(),
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $pagination->title($this->template->title);

        $elements = $elements->order_by('created','desc')
        ->limit($pagination->items_per_page)
        ->offset($pagination->offset)
        ->find_all();

        $pagination = $pagination->render();


        $this->render('oc-panel/pages/coupon/index', array('elements' => $elements,'pagination'=>$pagination));
    }

    /**
     * CRUD controller: CREATE
     */
    public function action_create()
    {
        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel', ['controller' => 'fields']));
        }

        $this->template->title = __('New').' '.__($this->_orm_model);

        $this->template->styles             = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer']  = array(
                                                    '//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                    'js/oc-panel/coupon.js'
                                                );


        if ($this->request->post())
        {

            $c = new Model_Coupon();

            $c->name                = Core::post('name');
            $c->id_product          = Core::post('id_product');
            $c->discount_amount     = Core::post('discount_amount');
            $c->discount_percentage = Core::post('discount_percentage');
            $c->valid_date          = Core::post('valid_date');
            $c->number_coupons      = Core::post('number_coupons');
            $c->status              = 1;

            try {
                $c->save();
                Alert::set(Alert::SUCCESS, sprintf(__('Coupon %s created'),$c->name));
            }
            catch (ORM_Validation_Exception $e)
            {
                $errors = '';
                $e = $e->errors('coupon');

                foreach ($e as $f => $err)
                    $errors.=$err.' - ';

                Alert::set(Alert::ERROR, sprintf(__('Coupon %s not created, errors: %s'),$c->name,$errors));
            }
            catch (Exception $e) {
                Alert::set(Alert::ERROR, sprintf(__('Coupon %s not created'),$c->name));
            }

            $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
        }

        return $this->render('oc-panel/pages/coupon/create', array('products' => $this->get_products()));
    }


    /**
     * CRUD controller: CREATE
     */
    public function action_bulk()
    {
        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel', ['controller' => 'fields']));
        }

        $this->template->title = __('Bulk').' '.__($this->_orm_model);

        $this->template->styles             = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer']  = array(
                                                    '//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                    'js/oc-panel/coupon.js'
                                                );

        if ($this->request->post())
        {
            $id_product             = Core::post('id_product');
            $discount_amount        = Core::post('discount_amount');
            $discount_percentage    = Core::post('discount_percentage');
            $valid_date             = Core::post('valid_date');
            $number_coupons         = Core::post('number_coupons');

            if ($number_coupons > 10000)
            {
                Alert::set(Alert::ERROR, __('limited to 10.000 at a time'));
                $this->redirect(Route::url('oc-panel',array('controller'=>'coupon','action'=>'bulk')));
            }

            for ($i=0; $i < $number_coupons; $i++)
            {
                $c = new Model_Coupon();

                //get unique coupon name
                do
                {
                    $c->name = strtoupper(Text::random('alnum', 8));
                }
                while(ORM::factory('coupon', array('name' => $c->name))->limit(1)->loaded());

                $c->id_product          = $id_product;
                $c->discount_amount     = $discount_amount;
                $c->discount_percentage = $discount_percentage;
                $c->valid_date          = $valid_date;
                $c->number_coupons      = 1;
                $c->status              = 1;
                $c->save();
            }


            $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
        }

        return $this->render('oc-panel/pages/coupon/bulk', array('products' => $this->get_products()));
    }

    public function action_import()
    {
        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel', ['controller' => 'fields']));
        }

        //sending a CSV
        if($_POST)
        {
            foreach($_FILES as $file => $path)
            {
                $csv = $path["tmp_name"];

                if($file=='csv_file_coupons' AND $csv != FALSE)
                {
                    if ($path['size'] > 1048576)
                    {
                        Alert::set(Alert::ERROR, __('1 MB file'));
                        $this->redirect(Route::url('oc-panel',array('controller'=>'coupon','action'=>'index')));
                    }

                    $expected_header = array('name','id_product','discount_amount','discount_percentage','number_coupons','valid_date','status');

                    $coupon_array = Core::csv_to_array($csv,$expected_header);

                    if (core::count($coupon_array) > 10000)
                    {
                        Alert::set(Alert::ERROR, __('limited to 10.000 at a time'));
                        $this->redirect(Route::url('oc-panel',array('controller'=>'coupon','action'=>'index')));
                    }

                    if ($coupon_array===FALSE)
                    {
                        Alert::set(Alert::ERROR, __('Something went wrong, please check format of the file! Remove single quotes or strange characters, in case you have any.'));
                    }
                    else
                    {
                        foreach ($coupon_array as $coupon)
                        {
                            $c = new Model_Coupon();
                            $c->name                = $coupon[0];
                            $c->id_product          = (is_numeric($coupon[1]))?$coupon[1]:NULL;
                            $c->discount_amount     = (is_numeric($coupon[2]))?$coupon[2]:NULL;
                            $c->discount_percentage = (is_numeric($coupon[3]))?$coupon[3]:NULL;
                            $c->number_coupons      = $coupon[4];
                            $c->valid_date          = $coupon[5];
                            $c->status              = $coupon[6];

                            try {
                                $c->save();
                            }
                            catch (ORM_Validation_Exception $e)
                            {
                                $errors = '';
                                $e = $e->errors('coupon');

                                foreach ($e as $f => $err)
                                    $errors.=$err.' - ';

                                Alert::set(Alert::ERROR, sprintf(__('Coupon %s not imported, errors: %s'),$c->name,$errors));
                            }
                            catch (Exception $e) {
                                Alert::set(Alert::ERROR, sprintf(__('Coupon %s not imported'),$c->name));
                            }
                        }

                        Alert::set(Alert::SUCCESS, __('Coupons successfully imported.'));
                    }
                }
            }
        }

        $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));

    }

    /**
     * CRUD controller: UPDATE
     */
    public function action_update()
    {
        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel', ['controller' => 'fields']));
        }

        $this->template->title = __('Update').' '.__($this->_orm_model).' '.$this->request->param('id');

        $this->template->styles             = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer']  = array(
                                                    '//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                    'js/oc-panel/coupon.js'
                                                );

        $coupon = new Model_Coupon($this->request->param('id'));

        if ($this->request->post())
        {
            $coupon->id_product          = Core::post('id_product');
            $coupon->discount_amount     = Core::post('discount_amount');
            $coupon->discount_percentage = Core::post('discount_percentage');
            $coupon->valid_date          = Core::post('valid_date');
            $coupon->number_coupons      = Core::post('number_coupons');
            $coupon->status              = (Core::post('status')=='on')?1:0;

            try {
                $coupon->save();
                Alert::set(Alert::SUCCESS, sprintf(__('Coupon %s updated'),$coupon->name));
            }
            catch (ORM_Validation_Exception $e)
            {
                $errors = '';
                $e = $e->errors('coupon');

                foreach ($e as $f => $err)
                    $errors.=$err.' - ';

                Alert::set(Alert::ERROR, sprintf(__('Coupon %s not updated, errors: %s'),$coupon->name,$errors));
            }
            catch (Exception $e) {
                Alert::set(Alert::ERROR, sprintf(__('Coupon %s not updated'),$coupon->name));
            }

            $this->redirect(Route::url('oc-panel', array('controller'=> 'coupon', 'action'=>'update','id'=>$coupon->id_coupon)));
        }

        return $this->render('oc-panel/pages/coupon/update', array('coupon' => $coupon,'products' => $this->get_products()));
    }

    /**
     * returns products to use in views in selects
     * @return array
     */
    public function get_products()
    {
        //for OC
        if(method_exists('Model_Order','products'))
        {
            //product without ad sell
            $products =Model_Order::products();
            unset($products[4]);
        }
        //for oe
        elseif(class_exists('Model_Product'))
        {
            $products = array();
            $ps = new Model_Product();
            $ps = $ps->find_all();
            foreach ($ps as $p)
                $products[$p->id_product] = $p->title;
        }

        return $products;
    }
}
