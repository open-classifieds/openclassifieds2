<?php defined('SYSPATH') or die('No direct access allowed.');

return [
    'category_badge' => [
        'type' => 'text',
        'display' => 'select',
        'label' => __('Disable category counter badge'),
        'options' => [
            '1' => __('Yes'),
            '0'  => __('No'),
        ],
        'default' => '0',
        'required' => TRUE,
        'category' => __('Layout'),
    ],
    'hide_description_icon' => [
        'type' => 'text',
        'display' => 'select',
        'label' => __('Hide icon on category/location description'),
        'options' => [
            '1' => __('Yes'),
            '0' => __('No'),
        ],
        'default' => '0',
        'required' => TRUE,
        'category' => __('Listing')],
];
