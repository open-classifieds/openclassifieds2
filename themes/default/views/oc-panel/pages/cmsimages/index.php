<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Media') ?>
        </h2>
    </div>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
	<div class="flex flex-col">
	    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
	        <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
	            <table class="min-w-full">
	                <thead>
	                    <tr>
	                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
	                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
	                            <?= __('Image') ?>
	                        </th>
	                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
	                            <?= __('URL') ?>
	                        </th>
	                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
	                    </tr>
	                </thead>
	                <tbody class="bg-white">
	                    <?foreach ($images as $key => $image):?>
	                        <? $last_item = $key === count($images) - 1 ?>
	                        <?= View::factory('oc-panel/pages/cmsimages/_image', ['image' => $image, 'last_item' => $last_item]) ?>
	                    <? endforeach ?>
	                </tbody>
	            </table>
	        </div>
	    </div>
	</div>
</div>
