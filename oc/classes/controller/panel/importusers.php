<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Import tools for admin
 *
 * @package    OC
 * @category   Tools
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class Controller_Panel_ImportUsers extends Controller_Panel_Tools {

    public function action_index()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));

        $this->template->title = __('Import tool for users');
        $this->template->scripts['footer'][] = 'js/oc-panel/import.js';
        $this->template->content = View::factory('oc-panel/pages/tools/import_users', [
            'users_import' => $this->amount_users_import()
        ]);
    }


    /**
     * action for form CSV import
     * @return redirect
     */
    public function action_csv()
    {
        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel', ['controller' => 'import']));
        }

        if(!$_POST OR !isset($_FILES['csv_file_users']))
        {
            $this->redirect(Route::url('oc-panel', [
                'controller'=>'importUsers',
                'action'=>'index'
            ]));
        }

        if ($_FILES['csv_file_users']['size'] > 1048576)
        {
            Alert::set(Alert::ERROR, __('1 MB file'));

            $this->redirect(Route::url('oc-panel', [
                'controller' => 'import',
                'action' => 'index'
            ]));
        }

        $header_expected = self::get_expected_columns();
        $header_expected_with_custom_fields = self::get_expected_columns(TRUE);

        $csv = $_FILES['csv_file_users']["tmp_name"];

        //check if wants to import custom fields too
        if (Core::csv_is_valid($csv, $header_expected_with_custom_fields))
        {
            $header_expected = $header_expected_with_custom_fields;
            $users = Core::csv_to_array($csv, $header_expected_with_custom_fields);
        }
        else
        {
            $users = Core::csv_to_array($csv, $header_expected);
        }

        if (core::count($users) > 10000)
        {
            Alert::set(Alert::ERROR, __('limited to 10.000 at a time'));

            $this->redirect(Route::url('oc-panel', [
                'controller' => 'import',
                'action' => 'index'
            ]));
        }

        if ($users === FALSE OR core::count($users) === 0 OR ($users_imported = $this->insert_into_import($users, $header_expected)) === FALSE)
        {
            Alert::set(Alert::ERROR, __('Something went wrong, please check format of the file! Remove single quotes or strange characters, in case you have any. Make sure your CSV file has these headers: :headers', [':headers' => implode(', ', $header_expected)]));
        }
        else
        {
            Alert::set(Alert::SUCCESS, sprintf(__('%d Users imported, click process to start migration'), $users_imported));
        }

        $this->redirect(Route::url('oc-panel', [
            'controller'=>'importUsers',
            'action'=>'index'
        ]));
    }

    public function action_process()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');

        //finished?
        if ($this->amount_users_import() === 0)
        {
            $this->template->content = json_encode('OK');

            return;
        }

        //how many users we process in each request? pass get/post
        $limit_process = Core::request('limit', 5);

        $users_import = DB::select()
            ->from('usersimport')
            ->where('processed', '=', '0')
            ->limit($limit_process)
            ->as_object()
            ->execute();

        $i=0;

        foreach ($users_import as $useri)
        {
            if ($this->create_user($useri) === TRUE)
            {
                $i++;
            }
        }

        $todo  = $this->amount_users_import();
        $done  = $this->amount_users_import(1);
        $total = $todo + $done;

        if($todo == 0)
        {
            try {
                DB::delete('usersimport')->execute()  ;
            } catch (Exception $e) {

            }
        }

        $this->template->content = json_encode(
            round(100-($todo*100/$total))
        );
    }

    public function action_deletequeue()
    {
        //finished?
        if ($this->amount_users_import() > 0)
        {
            //TRUNCATE oc2_usersimport
            try {
                DB::delete('usersimport')->execute()  ;
            } catch (Exception $e) {

            }

            Alert::set(Alert::SUCCESS, __('Queue cleaned'));
        }

        $this->redirect(Route::url('oc-panel',array('controller'=>'importusers','action'=>'index')));
    }

    /**
     * creates an user from a row of import
     * @param  class usersimport $useri
     * @return boolean
     */
    private function create_user($useri)
    {
        //new user
        $user = new Model_User();

        //create the user
        if(empty($useri->password))
        {
            $user = Model_User::create_user($useri->email, $useri->name);
        }
        else
        {
            $user = Model_User::create_user($useri->email, $useri->name, $useri->password);
        }

        $user->subscriber = (bool) $useri->subscriber;

        foreach (Model_UserField::get_all() as $name => $custom_field)
        {
            $name = 'cf_' . $name;

            if($useri->$name != '')
            {
                $user->$name = $useri->$name;
            }
        }

        try {
            $user->save();
        } catch (Exception $e) {
            return FALSE;
        }

        //save images
        if (($has_image = $this->process_images($user, $useri)) > 0)
        {
            $user->has_image = $has_image;

            try {
                $user->save();
            } catch (Exception $e) {
                return FALSE;
            }
        }

        //mark it as done
        try {
            DB::update('usersimport')
                ->set(['processed' => 1])
                ->where('id_import', '=', $useri->id_import)
                ->execute();

            return TRUE;

        } catch (Exception $e) {
            return FALSE;
        }
    }

    /***Adding images
        image_1
        image_2
        image_3
        image_4
        - image_X needs to be a varchar.
        - If has protocol will download image,
        - if its a route will read formt he path
        - once we have the image, we change size and create thumb
        - if was downloaded we delete it*/

    private function process_images($user, $useri)
    {
        //pattern in row CSV
        $image_pattern = 'image_';

        //amount images in CSV
        $num_images = core::config('advertisement.num_images');

        //how many images has the user, return
        $user_images  = 0;

        for ($i=1; $i <= $num_images ; $i++)
        {
            if(isset($useri->{$image_pattern.$i}))
            {
                $image = $useri->{$image_pattern.$i};

                //trying save image
                if ($this->process_image($user, $image, $user_images+1) === TRUE)
                {
                    $user_images++;
                }
            }
        }

        return $user_images;
    }


    private function process_image($user, $image, $num)
    {
        //from URL
        if (Valid::URL($image))
        {
            //download it, if takes more than 10 seconds...bad deal!
            $image_content = Core::curl_get_contents($image);

            //store if retrieved
            if ($image_content!==FALSE)
            {
                $file = DOCROOT.'images/import_'.$user->id_user.'_'.$num.'.jpg';

                if (!File::write($file, $image_content))
                {
                    return FALSE;
                }
            }
            else
            {
                return FALSE;
            }
        }
        //already in server
        elseif(file_exists(DOCROOT.$image))
        {
            $file = DOCROOT.$image;
        }

        try {
            $is_image = getimagesize($file);
        } catch (Exception $e) {
            $is_image = FALSE;
        }

        //only if its image will be returned
        return ($is_image !== FALSE) ? $user->save_image_file($file, $num) : FALSE;
    }

    /**
     * inserts into table usersimport
     * @param  array $users_array from csv
     * @param array $expected_header
     * @return bool/integer
     */
    private function insert_into_import($users_array, $expected_header)
    {
        $prefix = Database::instance()->table_prefix();

        $columns = [
            "`id_import` int(10) unsigned NOT NULL AUTO_INCREMENT",
            "`name` varchar(145) NOT NULL",
            "`email` varchar(145) NOT NULL",
            "`password` varchar(145) DEFAULT NULL",
            "`subscriber` tinyint(1) NOT NULL DEFAULT '1'",
            "`processed` tinyint(1) NOT NULL DEFAULT '0'"
        ];

        for ($i=1; $i <=core::config('advertisement.num_images') ; $i++)
        {
            $columns[] = '`image_' . $i . '` varchar(200) DEFAULT NULL';
        }

        foreach (Model_UserField::get_all() as $name => $custom_field) {
            $name = 'cf_' . $name;
            switch ($custom_field['type']) {
                case 'textarea':
                    $columns[] = "`" . $name ."` text DEFAULT NULL";
                    break;

                case 'integer':
                    $columns[] = '`' . $name . '` int DEFAULT NULL';
                    break;

                case 'checkbox':
                case 'radio':
                    $columns[] = '`' . $name . '` tinyint(1) DEFAULT NULL';
                    break;

                case 'decimal':
                case 'range':
                    $columns[] = '`' . $name . '` float DEFAULT NULL';
                    break;

                case 'date':
                    $columns[] = '`' . $name . '` date DEFAULT NULL';
                    break;

                case 'select':
                case 'email':
                case 'country':
                    $columns[] = '`' . $name . '` varchar(145) DEFAULT NULL';

                    break;

                case 'string':
                default:
                    $columns[] = '`' . $name . '` varchar(256) DEFAULT NULL';

                    break;
            }
        }

        //create table import if doesnt exists
        $query = DB::query(Database::INSERT, 'CREATE TABLE IF NOT EXISTS `' . $prefix . 'usersimport` (
                                              ' . implode(',', $columns) . '
                                            , PRIMARY KEY (`id_import`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

        try
        {
           $query->execute();
        }
        catch (Exception $e)
        {
            return FALSE;
        }

        $usersimport_columns = array_keys(Database::instance()->list_columns('usersimport'));
        $usersimport_expected_columns = self::get_expected_columns(TRUE);

        sort($usersimport_columns);
        sort($usersimport_expected_columns);

        //drop and create table if not same columns
        if ($usersimport_columns !== $usersimport_expected_columns)
        {
            $drop_table = DB::query(Database::INSERT, 'DROP TABLE IF EXISTS `' . $prefix . 'usersimport`;');
            $create_table = DB::query(Database::INSERT, 'CREATE TABLE IF NOT EXISTS `' . $prefix . 'usersimport` (
                                                ' . implode(',', $columns) . '
                                                , PRIMARY KEY (`id_import`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

            try
            {
                $drop_table->execute();
                $create_table->execute();
            }
            catch (Exception $e)
            {
                return FALSE;
            }
        }

        //insert into table import
        foreach ($users_array as $user => $values)
        {
            // Set NULL to 'NULL' value strings
            array_walk_recursive($values, function(&$value) {
                $value = $value === 'NULL' ? NULL : $value;
            });

            $query = DB::insert('usersimport', $expected_header)->values($values);

            try
            {
               $query->execute();
            }
            catch (Exception $e)
            {
                return FALSE;
            }
        }

        return core::count($users_array);
    }

    /**
     * amount of users left to import
     * @return integer
     */
    private function amount_users_import($processed = 0)
    {
        //how many users left to import?
        try {
            $users_import = DB::select(array(DB::expr('COUNT(`id_import`)'), 'total'))
                ->from('usersimport')
                ->where('processed','=',$processed)
                ->execute()
                ->as_array('total');

            $users_import = key($users_import);
        }
        //in case table doesnt exists...
        catch (Exception $e)
        {
            $users_import = 0;
        }

        return $users_import;
    }

    /**
     * returns the expected cloumns to import
     * @param  boolean $with_cf false
     * @return array
     */
    private static function get_expected_columns($with_cf = FALSE)
    {
        $columns = ['name', 'email', 'password', 'subscriber', 'image_1'];

        if ($with_cf === TRUE)
        {
            $columns = array_merge($columns, preg_filter('/^/', 'cf_', array_keys(Model_UserField::get_all())));
        }

        return $columns;
    }
}
