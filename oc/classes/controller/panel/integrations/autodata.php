<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_AutoData extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Auto-Data');

        if($this->request->post())
        {
            Model_Config::set_value('general', 'autodata', Core::post('is_active') ?? 0);

            if ((bool) Core::config('general.carquery'))
            {
                $this->create_custom_fields();
            }

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'autodata']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/autodata', [
            'is_active' => (bool) Core::config('general.autodata')
        ]);
    }

    public function action_import()
    {
        if (Core::is_cloud())
        {
            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'autodata']));
        }

        if(! $_POST OR ! isset($_FILES['xml_file']))
        {
            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'autodata']));
        }

        $xml_file = $_FILES['xml_file']['tmp_name'];

        if (! file_exists($xml_file))
        {
            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'autodata']));
        }

        $this->create_tables();

        $this->import_from_xml($xml_file);

        Alert::set(Alert::SUCCESS, __('Auto-Data imported.'));

        $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'autodata']));

    }

    private function import_from_xml($xml_file)
    {
        $brands = simplexml_load_file($xml_file);

        foreach ($brands as $brand)
        {
            $this->import_brand($brand);
        }
    }

    private function import_brand($brand)
    {
        $query = DB::insert('auto_data_brands', ['id_brand', 'name'])->values([$brand->id, $brand->name]);

        try
        {
            $query->execute();
        }
        catch (Exception $e)
        {
            return FALSE;
        }

        $this->import_models_for_brand($brand);
    }

    public function import_models_for_brand($brand)
    {
        foreach ($brand->models->model as $brand_model)
        {
            $query = DB::insert('auto_data_models', ['id_model', 'id_brand', 'name'])->values([$brand_model->id, $brand->id, $brand_model->name]);

            try
            {
                $query->execute();
            }
            catch (Exception $e)
            {
                return FALSE;
            }

            $this->import_generations_for_model($brand_model);
        }
    }

    public function import_generations_for_model($model)
    {

        foreach ($model->generations->generation as $model_generation)
        {
            $query = DB::insert('auto_data_generations', ['id_generation', 'id_model', 'name', 'year'])->values([$model_generation->id, $model->id, $model_generation->name, $model_generation->modelYear ?? NULL]);

            try
            {
                $query->execute();
            }
            catch (Exception $e)
            {
                return FALSE;
            }
        }
    }

    private function create_tables()
    {
        $prefix = Database::instance()->table_prefix();

        $brands_sql = '
            CREATE TABLE IF NOT EXISTS `' . $prefix . 'auto_data_brands` (
                `id_brand` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                PRIMARY KEY (`id_brand`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ';

        $models_sql = '
            CREATE TABLE IF NOT EXISTS `' . $prefix . 'auto_data_models` (
                `id_model` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `id_brand` bigint(20) NOT NULL,
                `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                PRIMARY KEY (`id_model`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ';

        $generations_sql = '
            CREATE TABLE IF NOT EXISTS `' . $prefix . 'auto_data_generations` (
                `id_generation` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `id_model` bigint(20) NOT NULL,
                `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `year` int(11) DEFAULT NULL,
                PRIMARY KEY (`id_generation`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ';

        $query = DB::query(Database::INSERT, $brands_sql);

        try
        {
            $query->execute();
        }
        catch (Exception $e)
        {
            return FALSE;
        }

        $query = DB::query(Database::INSERT, $models_sql);

        try
        {
            $query->execute();
        }
        catch (Exception $e)
        {
            return FALSE;
        }

        $query = DB::query(Database::INSERT, $generations_sql);

        try
        {
            $query->execute();
        }
        catch (Exception $e)
        {
            return FALSE;
        }
    }

    private function create_custom_fields()
    {
        foreach ($this->get_required_custom_fields() as $field) {
            try {
                (new Model_Field())->create($field['name'], $field['type'], $field['values'], NULL, [
                    'label' => $field['label'],
                    'tooltip' => $field['tooltip'],
                    'required' => $field['required'],
                    'searchable' => $field['searchable'],
                    'admin_privilege' => $field['admin_privilege'],
                    'show_listing' => $field['show_listing'],
                ]);
            } catch (Exception $e) {
                throw HTTP_Exception::factory(500, $e->getMessage());
            }
        }

        Core::delete_cache();
    }

    public function get_required_custom_fields()
    {
        return [
            [
                'name' => 'year',
                'type' => 'select',
                'label' => __('Year'),
                'tooltip' => __('Year'),
                'required' => TRUE,
                'searchable' => TRUE,
                'admin_privilege' => FALSE,
                'show_listing' => TRUE,
                'values' => FALSE
            ],
            [
                'name' => 'make',
                'type' => 'select',
                'label' => __('Make'),
                'tooltip' => __('Make'),
                'required' => TRUE,
                'searchable' => TRUE,
                'admin_privilege' => FALSE,
                'show_listing' => TRUE,
                'values' => FALSE
            ],
            [
                'name' => 'model',
                'type' => 'select',
                'label' => __('Model'),
                'tooltip' => __('Model'),
                'required' => TRUE,
                'searchable' => TRUE,
                'admin_privilege' => FALSE,
                'show_listing' => TRUE,
                'values' => FALSE
            ],
        ];
    }
}
