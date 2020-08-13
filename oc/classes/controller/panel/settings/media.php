<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Settings_Media extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Media settings');

        $validation = $this->validation();

        if ($this->request->post() AND $validation->check())
        {
            $this->store_settings($validation->data());

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/settings', ['controller' => 'media']));
        }

        return $this->template->content = View::factory('oc-panel/pages/settings/media', [
            'errors' => $validation->errors('validation'),
        ]);
    }

    private function store_settings($data)
    {
        Model_Config::set_value('image', 'allowed_formats', implode($data['allowed_formats'], ','));

        $max_image_size = $data['max_image_size'];

        if (Core::is_cloud())
        {
            //max_image_size no bigger than 12MB
            $max_image_size = $max_image_size > 12 ? 12 : $max_image_size;
        }

        Model_Config::set_value('image', 'max_image_size', $max_image_size);

        Model_Config::set_value('image', 'height', $data['height']);
        Model_Config::set_value('image', 'width', $data['width']);
        Model_Config::set_value('image', 'height_thumb', $data['height_thumb']);
        Model_Config::set_value('image', 'width_thumb', $data['width_thumb']);
        Model_Config::set_value('image', 'quality', $data['quality']);
        Model_Config::set_value('image', 'watermark', $data['watermark'] ?? 0);

        if (Core::is_cloud())
        {
            Model_Config::set_value('image', 'watermark_text', $data['watermark_text']);
            Model_Config::set_value('image', 'watermark_text_size', $data['watermark_text_size']);
            Model_Config::set_value('image', 'watermark_text_color', $data['watermark_text_color']);
            Model_Config::set_value('image', 'watermark_bg_transparent', $data['watermark_bg_transparent'] ?? 0);
            Model_Config::set_value('image', 'watermark_bg_color', $data['watermark_bg_color']);
        }
        else
        {
            Model_Config::set_value('image', 'watermark_path', $data['watermark_path']);
        }

        Model_Config::set_value('image', 'watermark_position', $data['watermark_position']);
        Model_Config::set_value('image', 'disallow_nudes', $data['disallow_nudes'] ?? 0);
    }

    private function validation()
    {
        return Validation::factory($this->request->post())
            ->rule('max_image_size', 'not_empty')
            ->rule('max_image_size', 'digit')
            ->rule('height', 'digit')
            ->rule('width', 'not_empty')
            ->rule('width', 'digit')
            ->rule('height_thumb', 'not_empty')
            ->rule('height_thumb', 'digit')
            ->rule('width_thumb', 'not_empty')
            ->rule('width_thumb', 'digit')
            ->rule('quality', 'not_empty')
            ->rule('quality', 'digit')
            ->rule('quality', 'range', array(':value', 1, 100))
            ->rule('watermark', 'range', array(':value', 0, 1))
            ->rule('watermark_position', 'not_empty')
            ->rule('watermark_position', 'digit')
            ->rule('watermark_position', 'range', array(':value', 0, 2))
            ->rule('disallow_nudes', 'range', array(':value', 0, 1));
    }
}
