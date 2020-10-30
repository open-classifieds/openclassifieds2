<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_CarQuery extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Carquery');

        if($this->request->post())
        {
            Model_Config::set_value('general', 'carquery', Core::post('is_active') ?? 0);

            if ((bool) Core::config('general.carquery'))
            {
                $this->create_custom_fields();
            }

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'carquery']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/carquery', [
            'is_active' => (bool) Core::config('general.carquery')
        ]);
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
