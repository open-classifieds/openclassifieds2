<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Subscribe extends Controller {

	public function action_index()
	{
		$email = Core::post('email_subscribe');

        if (! captcha::check('subscribe'))
        {
            Alert::set(Alert::ERROR, __('Captcha is not correct'));

            $this->redirect(Route::url('default'));
        }

		if (Valid::email($email,TRUE))
		{
			/* find user and compare emails */
			$user = Model_User::find_by_email($email);

			// case when user is not logged in.
    		// We create new user if he doesn't exists in DB
    		// and send him mail for ad created + new profile created
			if(!$user->loaded())
			{
				try
				{
	                $user = Model_User::create_email($email);
	            }
	            catch (ORM_Validation_Exception $e) {
	                $errors = $e->errors('models');
	                foreach ($errors as $f => $err)
	                {
	                    Alert::set(Alert::ALERT, $err);
	                }
	            }
			}
			/* save this user to data base as subscriber */

			$arr_cat = Core::post('category_subscribe');

			// string in this case is returned as "int,int" so we need to format min/max price
			$price = Core::post('price_subscribe');

			if($price = Core::post('price_subscribe'))
			{
				$min_price = substr($price, '0', stripos($price, ','));
				$max_price = substr($price, strrpos($price, ',')+1);
			}
			else
			{
				//in case of mobile version
				// jquery mobile have different slider, so we need to get data differently
				$min_price = Core::post('price_subscribe-1');
				$max_price = Core::post('price_subscribe-2');
			}


			//if categry is not selected, subscribe them for al, set category to 0 thats all...
            if($arr_cat === NULL)
			    $arr_cat[] = 0;


			// create entry table subscriber for each category selected
			foreach ($arr_cat as $c => $id_value)
			{
				$obj_subscribe = new Model_Subscribe();

				$obj_subscribe->id_user = $user->id_user;
                $obj_subscribe->id_category = is_numeric($id_value) ? (int) $id_value : 0;
                $obj_subscribe->id_location = is_numeric(Core::post('location_subscribe')) ? (int) Core::post('location_subscribe') : 0;
                $obj_subscribe->min_price = $min_price;
				$obj_subscribe->max_price = $max_price;

				try {
					$obj_subscribe->save();
				} catch (Exception $e) {
					throw HTTP_Exception::factory(500,$e->getMessage());
				}

			}
			Alert::set(Alert::SUCCESS, __('Thank you for subscribing'));
			$this->redirect(Route::url('default'));
		}
		else
		{
			Alert::set(Alert::ALERT, __('Invalid Email'));
			$this->redirect(Route::url('default'));
		}
	}

	public function action_unsubscribe()
	{
        if (Auth::instance()->logged_in())
        {
            DB::delete('subscribers')->where('id_user', '=', $this->user->id_user)->execute();

    		Alert::set(Alert::SUCCESS, __('You are unsubscribed'));
        }

		$this->redirect(Route::url('default'));

	}

	public function action_subscribe()
	{
		$this->template->content = View::factory('pages/ad/subscribe');
	}
}
