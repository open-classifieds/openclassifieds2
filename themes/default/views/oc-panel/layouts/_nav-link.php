<?php defined('SYSPATH') or die('No direct script access.');?>

<a
    href="<?= $url ?>"
    class="
        <? if($is_active) : ?>
            mt-1 group flex items-center px-2 py-2 text-sm leading-5 font-medium text-white rounded-md bg-blue-900 focus:outline-none focus:bg-blue-700 transition ease-in-out duration-150
        <? else : ?>
            mt-1 group flex items-center px-2 py-2 text-sm leading-5 font-medium text-blue-300 rounded-md hover:text-white hover:bg-blue-700 focus:outline-none focus:text-white focus:bg-blue-700 transition ease-in-out duration-150
        <? endif ?>
        ">
    <span
        class="
            <? if($is_active) : ?>
                mr-3 h-6 w-6 text-blue-300 group-hover:text-blue-300 group-focus:text-blue-300 transition ease-in-out duration-150
            <? else : ?>
                mr-3 h-6 w-6 text-blue-400 group-hover:text-blue-300 group-focus:text-blue-300 transition ease-in-out duration-150
            <? endif ?>
        ">
        <?= $svg ?>
    </span>
    <?= $label ?>
</a>
