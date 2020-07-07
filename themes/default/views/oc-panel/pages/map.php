<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Interactive Map') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Content-create-an-interactive-map']) ?>
        </div>
    </div>
</div>

<? if (Core::extra_features() == FALSE) : ?>
    <?= View::factory('oc-panel/components/pro-alert') ?>
<? endif ?>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <?=FORM::open(URL::base() . Request::current()->uri(), ['id' => 'addimap', 'name' => 'addimap'])?>
            <div>
                <div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?= __('General') ?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('map_active', 1, (bool) $map_active, ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('map_active', __('Map on homepage'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500"><?= __('Shows map on homepage') ?>.</p>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('bg_color', __('Background Color'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('bg_color', '#FFFFFF', [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 color {hash:true, adjust:false}',
                                    'onchange' => 'drawVisualization();',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('border_color', __('Map Border Color'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('border_color', '#FFFFFF', [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 color {hash:true, adjust:false}',
                                    'onchange' => 'drawVisualization();',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('border_stroke', __('Map Border Width (px)'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('border_stroke', '0', [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 color {hash:true, adjust:false}',
                                    'onchange' => 'drawVisualization();',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('ina_color', __('Inactive Region Color'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('ina_color', '#f5f5f5', [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 color {hash:true, adjust:false}',
                                    'onchange' => 'drawVisualization();',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('tooltip_color', __('Tooltip Text Color'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('tooltip_color', '#444444', [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 color {hash:true, adjust:false}',
                                    'onchange' => 'drawVisualization();',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('marker_size', __('Markers Size'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('marker_size', '10', [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 color {hash:true, adjust:false}',
                                    'onchange' => 'drawVisualization();',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('width', __('Width (px)'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('width', '0', [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 color {hash:true, adjust:false}',
                                    'onchange' => 'drawVisualization();',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('height', __('Height (px)'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('height', '400', [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 color {hash:true, adjust:false}',
                                    'onchange' => 'drawVisualization();',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('responsive', 1, FALSE, [
                                    'class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out',
                                    'id' => 'responsive'
                                ])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('responsive', __('Responsive  (Beta)'), ['class'=>'font-medium text-gray-700'])?>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('aspect_ratio', 1, FALSE, [
                                    'id' => 'aspratio',
                                    'class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'
                                ])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('aspect_ratio', __('Keep Aspect Ratio'), ['class'=>'font-medium text-gray-700'])?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Google Geocoding')?>
                        </h3>
                        <p><?= __('Some maps will use the Google Geocoding API to convert the region code text into readable data for the API. If your maps are not displaying, get a <a href="https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key" target="_blank">Geocoding API key</a> and place it here.') ?></p>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <?= FORM::label('geoapi', __('API Key'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('geoapi', '', [
                                    'id' => 'geoapi',
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    'onchange' => 'drawVisualization();',
                                ])?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Map Settings')?>
                        </h3>
                    </div>
                    <div id="map-settings" class="block w-full overflow-auto scrolling-touch">
                        <table class="totalp">
                            <tr>
                                <td><strong>
                                    <?=__('Region to Display')?>
                                </strong><br /></td>
                                <td><strong>
                                    <?=__('Display Mode')?>
                                </strong><br /></td>
                                <td><strong>
                                    <?=__('Interactivity')?>
                                </strong><br /></td>
                                <td><strong>
                                    <?=__('Active Region Action')?>
                                </strong></td>
                            </tr>
                            <tr>
                                <td><select name="region" id="region" onChange="isolinkcheck()">
                                    <option value="world,countries">World</option>
                                    <option value="world,continents">World - Continent Regions</option>
                                    <option value="world,subcontinents">World - Subcontinents Regions</option>
                                    <option value="002,countries">Africa</option>
                                    <option value="002,subcontinents">Africa - Subcontinents Regions</option>
                                    <option value="015,countries">Africa - Northern Africa</option>
                                    <option value="011,countries">Africa - Western Africa</option>
                                    <option value="017,countries">Africa - Middle Africa</option>
                                    <option value="014,countries">Africa - Eastern Africa</option>
                                    <option value="018,countries">Africa - Southern Africa</option>
                                    <option value="150,countries">Europe</option>
                                    <option value="150,subcontinents">Europe - Subcontinents Regions</option>
                                    <option value="154,countries">Europe - Northern Europe</option>
                                    <option value="155,countries">Europe - Western Europe</option>
                                    <option value="151,countries">Europe - Eastern Europe</option>
                                    <option value="039,countries">Europe - Southern Europe</option>
                                    <option value="019,countries">Americas</option>
                                    <option value="019,subcontinents">Americas - Subcontinents Regions</option>
                                    <option value="021,countries">Americas - Northern America</option>
                                    <option value="029,countries">Americas - Caribbean</option>
                                    <option value="013,countries">Americas - Central America</option>
                                    <option value="005,countries">Americas - South America</option>
                                    <option value="142,countries">Asia</option>
                                    <option value="142,subcontinents">Asia - Subcontinents Regions</option>
                                    <option value="143,countries">Asia - Central Asia</option>
                                    <option value="030,countries">Asia - Eastern Asia</option>
                                    <option value="034,countries">Asia - Southern Asia</option>
                                    <option value="035,countries">Asia - South-Eastern Asia</option>
                                    <option value="145,countries">Asia - Western Asia</option>
                                    <option value="009,countries">Oceania</option>
                                    <option value="009,subcontinents">Oceania - Subcontinents Regions</option>
                                    <option value="053,countries">Oceania - Australia and New Zealand</option>
                                    <option value="054,countries">Oceania - Melanesia</option>
                                    <option value="057,countries">Oceania - Micronesia</option>
                                    <option value="061,countries">Oceania - Polynesia</option>
                                    <option value="US,countries">United States of America</option>
                                    <option value="US,provinces">United States of America - States</option>
                                    <option value="US,metros">United States of America - Metropolitan Areas</option>
                                    <option value="US-AL,metros">USA - Alabama - Metropolitan Areas</option>
                                    <option value="US-AL,provinces">USA - Alabama State</option>
                                    <option value="US-AK,metros">USA - Alaska - Metropolitan Areas</option>
                                    <option value="US-AK,provinces">USA - Alaska State</option>
                                    <option value="US-AZ,metros">USA - Arizona - Metropolitan Areas</option>
                                    <option value="US-AZ,provinces">USA - Arizona State</option>
                                    <option value="US-AR,metros">USA - Arkansas - Metropolitan Areas</option>
                                    <option value="US-AR,provinces">USA - Arkansas State</option>
                                    <option value="US-CA,metros">USA - California - Metropolitan Areas</option>
                                    <option value="US-CA,provinces">USA - California State</option>
                                    <option value="US-CO,metros">USA - Colorado - Metropolitan Areas</option>
                                    <option value="US-CO,provinces">USA - Colorado State</option>
                                    <option value="US-CT,metros">USA - Connecticut - Metropolitan Areas</option>
                                    <option value="US-CT,provinces">USA - Connecticut State</option>
                                    <option value="US-DE,metros">USA - Delaware - Metropolitan Areas</option>
                                    <option value="US-DE,provinces">USA - Delaware State</option>
                                    <option value="US-DC,metros">USA - District of Columbia - Metropolitan Areas</option>
                                    <option value="US-DC,provinces">USA - District of Columbia</option>
                                    <option value="US-FL,metros">USA - Florida - Metropolitan Areas</option>
                                    <option value="US-FL,provinces">USA - Florida State</option>
                                    <option value="US-GA,metros">USA - Georgia - Metropolitan Areas</option>
                                    <option value="US-GA,provinces">USA - Georgia State</option>
                                    <option value="US-HI,metros">USA - Hawaii - Metropolitan Areas</option>
                                    <option value="US-HI,provinces">USA - Hawaii State</option>
                                    <option value="US-ID,metros">USA - Idaho - Metropolitan Areas</option>
                                    <option value="US-ID,provinces">USA - Idaho State</option>
                                    <option value="US-IL,metros">USA - Illinois - Metropolitan Areas</option>
                                    <option value="US-IL,provinces">USA - Illinois State</option>
                                    <option value="US-IN,metros">USA - Indiana - Metropolitan Areas</option>
                                    <option value="US-IN,provinces">USA - Indiana State</option>
                                    <option value="US-IA,metros">USA - Iowa - Metropolitan Areas</option>
                                    <option value="US-IA,provinces">USA - Iowa State</option>
                                    <option value="US-KS,metros">USA - Kansas - Metropolitan Areas</option>
                                    <option value="US-KS,provinces">USA - Kansas State</option>
                                    <option value="US-KY,metros">USA - Kentucky - Metropolitan Areas</option>
                                    <option value="US-KY,provinces">USA - Kentucky State</option>
                                    <option value="US-LA,metros">USA - Louisiana - Metropolitan Areas</option>
                                    <option value="US-LA,provinces">USA - Louisiana State</option>
                                    <option value="US-ME,metros">USA - Maine - Metropolitan Areas</option>
                                    <option value="US-ME,provinces">USA - Maine State</option>
                                    <option value="US-MD,metros">USA - Maryland - Metropolitan Areas</option>
                                    <option value="US-MD,provinces">USA - Maryland State</option>
                                    <option value="US-MA,metros">USA - Massachusetts - Metropolitan Areas</option>
                                    <option value="US-MA,provinces">USA - Massachusetts State</option>
                                    <option value="US-MI,metros">USA - Michigan - Metropolitan Areas</option>
                                    <option value="US-MI,provinces">USA - Michigan State</option>
                                    <option value="US-MN,metros">USA - Minnesota - Metropolitan Areas</option>
                                    <option value="US-MN,provinces">USA - Minnesota State</option>
                                    <option value="US-MS,metros">USA - Mississippi - Metropolitan Areas</option>
                                    <option value="US-MS,provinces">USA - Mississippi State</option>
                                    <option value="US-MO,metros">USA - Missouri - Metropolitan Areas</option>
                                    <option value="US-MO,provinces">USA - Missouri State</option>
                                    <option value="US-MT,metros">USA - Montana - Metropolitan Areas</option>
                                    <option value="US-MT,provinces">USA - Montana State</option>
                                    <option value="US-NE,metros">USA - Nebraska - Metropolitan Areas</option>
                                    <option value="US-NE,provinces">USA - Nebraska State</option>
                                    <option value="US-NV,metros">USA - Nevada - Metropolitan Areas</option>
                                    <option value="US-NV,provinces">USA - Nevada State</option>
                                    <option value="US-NH,metros">USA - New Hampshire - Metropolitan Areas</option>
                                    <option value="US-NH,provinces">USA - New Hampshire State</option>
                                    <option value="US-NJ,metros">USA - New Jersey - Metropolitan Areas</option>
                                    <option value="US-NJ,provinces">USA - New Jersey State</option>
                                    <option value="US-NM,metros">USA - New Mexico - Metropolitan Areas</option>
                                    <option value="US-NM,provinces">USA - New Mexico State</option>
                                    <option value="US-NY,metros">USA - New York - Metropolitan Areas</option>
                                    <option value="US-NY,provinces">USA - New York State</option>
                                    <option value="US-NC,metros">USA - North Carolina - Metropolitan Areas</option>
                                    <option value="US-NC,provinces">USA - North Carolina State</option>
                                    <option value="US-ND,metros">USA - North Dakota - Metropolitan Areas</option>
                                    <option value="US-ND,provinces">USA - North Dakota State</option>
                                    <option value="US-OH,metros">USA - Ohio - Metropolitan Areas</option>
                                    <option value="US-OH,provinces">USA - Ohio State</option>
                                    <option value="US-OK,metros">USA - Oklahoma - Metropolitan Areas</option>
                                    <option value="US-OK,provinces">USA - Oklahoma State</option>
                                    <option value="US-OR,metros">USA - Oregon - Metropolitan Areas</option>
                                    <option value="US-OR,provinces">USA - Oregon State</option>
                                    <option value="US-PA,metros">USA - Pennsylvania - Metropolitan Areas</option>
                                    <option value="US-PA,provinces">USA - Pennsylvania State</option>
                                    <option value="US-RI,metros">USA - Rhode Island - Metropolitan Areas</option>
                                    <option value="US-RI,provinces">USA - Rhode Island State</option>
                                    <option value="US-SC,metros">USA - South Carolina - Metropolitan Areas</option>
                                    <option value="US-SC,provinces">USA - South Carolina State</option>
                                    <option value="US-SD,metros">USA - South Dakota - Metropolitan Areas</option>
                                    <option value="US-SD,provinces">USA - South Dakota State</option>
                                    <option value="US-TN,metros">USA - Tennessee - Metropolitan Areas</option>
                                    <option value="US-TN,provinces">USA - Tennessee State</option>
                                    <option value="US-TX,metros">USA - Texas - Metropolitan Areas</option>
                                    <option value="US-TX,provinces">USA - Texas State</option>
                                    <option value="US-UT,metros">USA - Utah - Metropolitan Areas</option>
                                    <option value="US-UT,provinces">USA - Utah State</option>
                                    <option value="US-VT,metros">USA - Vermont - Metropolitan Areas</option>
                                    <option value="US-VT,provinces">USA - Vermont State</option>
                                    <option value="US-VA,metros">USA - Virginia - Metropolitan Areas</option>
                                    <option value="US-VA,provinces">USA - Virginia State</option>
                                    <option value="US-WA,metros">USA - Washington - Metropolitan Areas</option>
                                    <option value="US-WA,provinces">USA - Washington State</option>
                                    <option value="US-WV,metros">USA - West Virginia - Metropolitan Areas</option>
                                    <option value="US-WV,provinces">USA - West Virginia State</option>
                                    <option value="US-WI,metros">USA - Wisconsin - Metropolitan Areas</option>
                                    <option value="US-WI,provinces">USA - Wisconsin State</option>
                                    <option value="US-WY,metros">USA - Wyoming - Metropolitan Areas</option>
                                    <option value="US-WY,provinces">USA - Wyoming State</option>
                                    <option value="AF,countries">Afghanistan</option>
                                    <option value="AF,provinces">Afghanistan - Provinces</option>
                                    <option value="AX,countries">Aland Islands</option>
                                    <option value="AX,provinces">Aland Islands - Provinces</option>
                                    <option value="AL,countries">Albania</option>
                                    <option value="AL,provinces">Albania - Provinces</option>
                                    <option value="DZ,countries">Algeria</option>
                                    <option value="DZ,provinces">Algeria - Provinces</option>
                                    <option value="AS,countries">American Samoa</option>
                                    <option value="AS,provinces">American Samoa - Provinces</option>
                                    <option value="AD,countries">Andorra</option>
                                    <option value="AD,provinces">Andorra - Provinces</option>
                                    <option value="AO,countries">Angola</option>
                                    <option value="AO,provinces">Angola - Provinces</option>
                                    <option value="AI,countries">Anguilla</option>
                                    <option value="AI,provinces">Anguilla - Provinces</option>
                                    <option value="AQ,countries">Antarctica</option>
                                    <option value="AQ,provinces">Antarctica - Provinces</option>
                                    <option value="AG,countries">Antigua and Barbuda</option>
                                    <option value="AG,provinces">Antigua and Barbuda - Provinces</option>
                                    <option value="AR,countries">Argentina</option>
                                    <option value="AR,provinces">Argentina - Provinces</option>
                                    <option value="AM,countries">Armenia</option>
                                    <option value="AM,provinces">Armenia - Provinces</option>
                                    <option value="AW,countries">Aruba</option>
                                    <option value="AW,provinces">Aruba - Provinces</option>
                                    <option value="AU,countries">Australia</option>
                                    <option value="AU,provinces">Australia - Provinces</option>
                                    <option value="AT,countries">Austria</option>
                                    <option value="AT,provinces">Austria - Provinces</option>
                                    <option value="AZ,countries">Azerbaijan</option>
                                    <option value="AZ,provinces">Azerbaijan - Provinces</option>
                                    <option value="BS,countries">Bahamas</option>
                                    <option value="BS,provinces">Bahamas - Provinces</option>
                                    <option value="BH,countries">Bahrain</option>
                                    <option value="BH,provinces">Bahrain - Provinces</option>
                                    <option value="BD,countries">Bangladesh</option>
                                    <option value="BD,provinces">Bangladesh - Provinces</option>
                                    <option value="BB,countries">Barbados</option>
                                    <option value="BB,provinces">Barbados - Provinces</option>
                                    <option value="BY,countries">Belarus</option>
                                    <option value="BY,provinces">Belarus - Provinces</option>
                                    <option value="BE,countries">Belgium</option>
                                    <option value="BE,provinces">Belgium - Provinces</option>
                                    <option value="BZ,countries">Belize</option>
                                    <option value="BZ,provinces">Belize - Provinces</option>
                                    <option value="BJ,countries">Benin</option>
                                    <option value="BJ,provinces">Benin - Provinces</option>
                                    <option value="BM,countries">Bermuda</option>
                                    <option value="BM,provinces">Bermuda - Provinces</option>
                                    <option value="BT,countries">Bhutan</option>
                                    <option value="BT,provinces">Bhutan - Provinces</option>
                                    <option value="BO,countries">Bolivia, Plurinational State of</option>
                                    <option value="BO,provinces">Bolivia, Plurinational State of - Provinces</option>
                                    <option value="BQ,countries">Bonaire, Sint Eustatius and Saba</option>
                                    <option value="BQ,provinces">Bonaire, Sint Eustatius and Saba - Provinces</option>
                                    <option value="BA,countries">Bosnia and Herzegovina</option>
                                    <option value="BA,provinces">Bosnia and Herzegovina - Provinces</option>
                                    <option value="BW,countries">Botswana</option>
                                    <option value="BW,provinces">Botswana - Provinces</option>
                                    <option value="BV,countries">Bouvet Island</option>
                                    <option value="BV,provinces">Bouvet Island - Provinces</option>
                                    <option value="BR,countries">Brazil</option>
                                    <option value="BR,provinces">Brazil - Provinces</option>
                                    <option value="IO,countries">British Indian Ocean Territory</option>
                                    <option value="IO,provinces">British Indian Ocean Territory - Provinces</option>
                                    <option value="BN,countries">Brunei Darussalam</option>
                                    <option value="BN,provinces">Brunei Darussalam - Provinces</option>
                                    <option value="BG,countries">Bulgaria</option>
                                    <option value="BG,provinces">Bulgaria - Provinces</option>
                                    <option value="BF,countries">Burkina Faso</option>
                                    <option value="BF,provinces">Burkina Faso - Provinces</option>
                                    <option value="BI,countries">Burundi</option>
                                    <option value="BI,provinces">Burundi - Provinces</option>
                                    <option value="KH,countries">Cambodia</option>
                                    <option value="KH,provinces">Cambodia - Provinces</option>
                                    <option value="CM,countries">Cameroon</option>
                                    <option value="CM,provinces">Cameroon - Provinces</option>
                                    <option value="CA,countries">Canada</option>
                                    <option value="CA,provinces">Canada - Provinces</option>
                                    <option value="CV,countries">Cape Verde</option>
                                    <option value="CV,provinces">Cape Verde - Provinces</option>
                                    <option value="KY,countries">Cayman Islands</option>
                                    <option value="KY,provinces">Cayman Islands - Provinces</option>
                                    <option value="CF,countries">Central African Republic</option>
                                    <option value="CF,provinces">Central African Republic - Provinces</option>
                                    <option value="TD,countries">Chad</option>
                                    <option value="TD,provinces">Chad - Provinces</option>
                                    <option value="CL,countries">Chile</option>
                                    <option value="CL,provinces">Chile - Provinces</option>
                                    <option value="CN,countries">China</option>
                                    <option value="CN,provinces">China - Provinces</option>
                                    <option value="CX,countries">Christmas Island</option>
                                    <option value="CX,provinces">Christmas Island - Provinces</option>
                                    <option value="CC,countries">Cocos (Keeling) Islands</option>
                                    <option value="CC,provinces">Cocos (Keeling) Islands - Provinces</option>
                                    <option value="CO,countries">Colombia</option>
                                    <option value="CO,provinces">Colombia - Provinces</option>
                                    <option value="KM,countries">Comoros</option>
                                    <option value="KM,provinces">Comoros - Provinces</option>
                                    <option value="CG,countries">Congo</option>
                                    <option value="CG,provinces">Congo - Provinces</option>
                                    <option value="CD,countries">Congo, the Democratic Republic of the</option>
                                    <option value="CD,provinces">Congo, the Democratic Republic of the - Provinces</option>
                                    <option value="CK,countries">Cook Islands</option>
                                    <option value="CK,provinces">Cook Islands - Provinces</option>
                                    <option value="CR,countries">Costa Rica</option>
                                    <option value="CR,provinces">Costa Rica - Provinces</option>
                                    <option value="CI,countries">Cote d'Ivoire </option>
                                    <option value="CI,provinces">Cote d'Ivoire  - Provinces</option>
                                    <option value="HR,countries">Croatia</option>
                                    <option value="HR,provinces">Croatia - Provinces</option>
                                    <option value="CU,countries">Cuba</option>
                                    <option value="CU,provinces">Cuba - Provinces</option>
                                    <option value="CW,countries">Curaçao</option>
                                    <option value="CW,provinces">Curaçao - Provinces</option>
                                    <option value="CY,countries">Cyprus</option>
                                    <option value="CY,provinces">Cyprus - Provinces</option>
                                    <option value="CZ,countries">Czech Republic</option>
                                    <option value="CZ,provinces">Czech Republic - Provinces</option>
                                    <option value="DK,countries">Denmark</option>
                                    <option value="DK,provinces">Denmark - Provinces</option>
                                    <option value="DJ,countries">Djibouti</option>
                                    <option value="DJ,provinces">Djibouti - Provinces</option>
                                    <option value="DM,countries">Dominica</option>
                                    <option value="DM,provinces">Dominica - Provinces</option>
                                    <option value="DO,countries">Dominican Republic</option>
                                    <option value="DO,provinces">Dominican Republic - Provinces</option>
                                    <option value="EC,countries">Ecuador</option>
                                    <option value="EC,provinces">Ecuador - Provinces</option>
                                    <option value="EG,countries">Egypt</option>
                                    <option value="EG,provinces">Egypt - Provinces</option>
                                    <option value="SV,countries">El Salvador</option>
                                    <option value="SV,provinces">El Salvador - Provinces</option>
                                    <option value="GQ,countries">Equatorial Guinea</option>
                                    <option value="GQ,provinces">Equatorial Guinea - Provinces</option>
                                    <option value="ER,countries">Eritrea</option>
                                    <option value="ER,provinces">Eritrea - Provinces</option>
                                    <option value="EE,countries">Estonia</option>
                                    <option value="EE,provinces">Estonia - Provinces</option>
                                    <option value="ET,countries">Ethiopia</option>
                                    <option value="ET,provinces">Ethiopia - Provinces</option>
                                    <option value="FK,countries">Falkland Islands (Malvinas)</option>
                                    <option value="FK,provinces">Falkland Islands (Malvinas) - Provinces</option>
                                    <option value="FO,countries">Faroe Islands</option>
                                    <option value="FO,provinces">Faroe Islands - Provinces</option>
                                    <option value="FJ,countries">Fiji</option>
                                    <option value="FJ,provinces">Fiji - Provinces</option>
                                    <option value="FI,countries">Finland</option>
                                    <option value="FI,provinces">Finland - Provinces</option>
                                    <option value="FR,countries">France</option>
                                    <option value="FR,provinces">France - Provinces</option>
                                    <option value="GF,countries">French Guiana</option>
                                    <option value="GF,provinces">French Guiana - Provinces</option>
                                    <option value="PF,countries">French Polynesia</option>
                                    <option value="PF,provinces">French Polynesia - Provinces</option>
                                    <option value="TF,countries">French Southern Territories</option>
                                    <option value="TF,provinces">French Southern Territories - Provinces</option>
                                    <option value="GA,countries">Gabon</option>
                                    <option value="GA,provinces">Gabon - Provinces</option>
                                    <option value="GM,countries">Gambia</option>
                                    <option value="GM,provinces">Gambia - Provinces</option>
                                    <option value="GE,countries">Georgia</option>
                                    <option value="GE,provinces">Georgia - Provinces</option>
                                    <option value="DE,countries">Germany</option>
                                    <option value="DE,provinces">Germany - Provinces</option>
                                    <option value="GH,countries">Ghana</option>
                                    <option value="GH,provinces">Ghana - Provinces</option>
                                    <option value="GI,countries">Gibraltar</option>
                                    <option value="GI,provinces">Gibraltar - Provinces</option>
                                    <option value="GR,countries">Greece</option>
                                    <option value="GR,provinces">Greece - Provinces</option>
                                    <option value="GL,countries">Greenland</option>
                                    <option value="GL,provinces">Greenland - Provinces</option>
                                    <option value="GD,countries">Grenada</option>
                                    <option value="GD,provinces">Grenada - Provinces</option>
                                    <option value="GP,countries">Guadeloupe</option>
                                    <option value="GP,provinces">Guadeloupe - Provinces</option>
                                    <option value="GU,countries">Guam</option>
                                    <option value="GU,provinces">Guam - Provinces</option>
                                    <option value="GT,countries">Guatemala</option>
                                    <option value="GT,provinces">Guatemala - Provinces</option>
                                    <option value="GG,countries">Guernsey</option>
                                    <option value="GG,provinces">Guernsey - Provinces</option>
                                    <option value="GN,countries">Guinea</option>
                                    <option value="GN,provinces">Guinea - Provinces</option>
                                    <option value="GW,countries">Guinea-Bissau</option>
                                    <option value="GW,provinces">Guinea-Bissau - Provinces</option>
                                    <option value="GY,countries">Guyana</option>
                                    <option value="GY,provinces">Guyana - Provinces</option>
                                    <option value="HT,countries">Haiti</option>
                                    <option value="HT,provinces">Haiti - Provinces</option>
                                    <option value="HM,countries">Heard Island and McDonald Islands</option>
                                    <option value="HM,provinces">Heard Island and McDonald Islands - Provinces</option>
                                    <option value="VA,countries">Holy See (Vatican City State)</option>
                                    <option value="HN,countries">Honduras</option>
                                    <option value="HN,provinces">Honduras - Provinces</option>
                                    <option value="HK,countries">Hong Kong</option>
                                    <option value="HK,provinces">Hong Kong - Provinces</option>
                                    <option value="HU,countries">Hungary</option>
                                    <option value="HU,provinces">Hungary - Provinces</option>
                                    <option value="IS,countries">Iceland</option>
                                    <option value="IS,provinces">Iceland - Provinces</option>
                                    <option value="IN,countries">India</option>
                                    <option value="IN,provinces">India - Provinces</option>
                                    <option value="ID,countries">Indonesia</option>
                                    <option value="ID,provinces">Indonesia - Provinces</option>
                                    <option value="IR,countries">Iran, Islamic Republic of</option>
                                    <option value="IR,provinces">Iran, Islamic Republic of - Provinces</option>
                                    <option value="IQ,countries">Iraq</option>
                                    <option value="IQ,provinces">Iraq - Provinces</option>
                                    <option value="IE,countries">Ireland</option>
                                    <option value="IE,provinces">Ireland - Provinces</option>
                                    <option value="IM,countries">Isle of Man</option>
                                    <option value="IM,provinces">Isle of Man - Provinces</option>
                                    <option value="IL,countries">Israel</option>
                                    <option value="IL,provinces">Israel - Provinces</option>
                                    <option value="IT,countries">Italy</option>
                                    <option value="IT,provinces">Italy - Provinces</option>
                                    <option value="JM,countries">Jamaica</option>
                                    <option value="JM,provinces">Jamaica - Provinces</option>
                                    <option value="JP,countries">Japan</option>
                                    <option value="JP,provinces">Japan - Provinces</option>
                                    <option value="JE,countries">Jersey</option>
                                    <option value="JE,provinces">Jersey - Provinces</option>
                                    <option value="JO,countries">Jordan</option>
                                    <option value="JO,provinces">Jordan - Provinces</option>
                                    <option value="KZ,countries">Kazakhstan</option>
                                    <option value="KZ,provinces">Kazakhstan - Provinces</option>
                                    <option value="KE,countries">Kenya</option>
                                    <option value="KE,provinces">Kenya - Provinces</option>
                                    <option value="KI,countries">Kiribati</option>
                                    <option value="KI,provinces">Kiribati - Provinces</option>
                                    <option value="KP,countries">Korea, Democratic People's Republic of</option>
                                    <option value="KP,provinces">Korea, Democratic People's Republic of - Provinces</option>
                                    <option value="KR,countries">Korea, Republic of</option>
                                    <option value="KR,provinces">Korea, Republic of - Provinces</option>
                                    <option value="KW,countries">Kuwait</option>
                                    <option value="KW,provinces">Kuwait - Provinces</option>
                                    <option value="KG,countries">Kyrgyzstan</option>
                                    <option value="KG,provinces">Kyrgyzstan - Provinces</option>
                                    <option value="LA,countries">Lao People's Democratic Republic</option>
                                    <option value="LA,provinces">Lao People's Democratic Republic - Provinces</option>
                                    <option value="LV,countries">Latvia</option>
                                    <option value="LV,provinces">Latvia - Provinces</option>
                                    <option value="LB,countries">Lebanon</option>
                                    <option value="LB,provinces">Lebanon - Provinces</option>
                                    <option value="LS,countries">Lesotho</option>
                                    <option value="LS,provinces">Lesotho - Provinces</option>
                                    <option value="LR,countries">Liberia</option>
                                    <option value="LR,provinces">Liberia - Provinces</option>
                                    <option value="LY,countries">Libya</option>
                                    <option value="LY,provinces">Libya - Provinces</option>
                                    <option value="LI,countries">Liechtenstein</option>
                                    <option value="LI,provinces">Liechtenstein - Provinces</option>
                                    <option value="LT,countries">Lithuania</option>
                                    <option value="LT,provinces">Lithuania - Provinces</option>
                                    <option value="LU,countries">Luxembourg</option>
                                    <option value="LU,provinces">Luxembourg - Provinces</option>
                                    <option value="MO,countries">Macao</option>
                                    <option value="MO,provinces">Macao - Provinces</option>
                                    <option value="MK,countries">Macedonia, the former Yugoslav Republic of</option>
                                    <option value="MK,provinces">Macedonia, the former Yugoslav Republic of - Provinces</option>
                                    <option value="MG,countries">Madagascar</option>
                                    <option value="MG,provinces">Madagascar - Provinces</option>
                                    <option value="MW,countries">Malawi</option>
                                    <option value="MW,provinces">Malawi - Provinces</option>
                                    <option value="MY,countries">Malaysia</option>
                                    <option value="MY,provinces">Malaysia - Provinces</option>
                                    <option value="MV,countries">Maldives</option>
                                    <option value="MV,provinces">Maldives - Provinces</option>
                                    <option value="ML,countries">Mali</option>
                                    <option value="ML,provinces">Mali - Provinces</option>
                                    <option value="MT,countries">Malta</option>
                                    <option value="MT,provinces">Malta - Provinces</option>
                                    <option value="MH,countries">Marshall Islands</option>
                                    <option value="MH,provinces">Marshall Islands - Provinces</option>
                                    <option value="MQ,countries">Martinique</option>
                                    <option value="MQ,provinces">Martinique - Provinces</option>
                                    <option value="MR,countries">Mauritania</option>
                                    <option value="MR,provinces">Mauritania - Provinces</option>
                                    <option value="MU,countries">Mauritius</option>
                                    <option value="MU,provinces">Mauritius - Provinces</option>
                                    <option value="YT,countries">Mayotte</option>
                                    <option value="YT,provinces">Mayotte - Provinces</option>
                                    <option value="MX,countries">Mexico</option>
                                    <option value="MX,provinces">Mexico - Provinces</option>
                                    <option value="FM,countries">Micronesia, Federated States of</option>
                                    <option value="FM,provinces">Micronesia, Federated States of - Provinces</option>
                                    <option value="MD,countries">Moldova, Republic of</option>
                                    <option value="MD,provinces">Moldova, Republic of - Provinces</option>
                                    <option value="MC,countries">Monaco</option>
                                    <option value="MC,provinces">Monaco - Provinces</option>
                                    <option value="MN,countries">Mongolia</option>
                                    <option value="MN,provinces">Mongolia - Provinces</option>
                                    <option value="ME,countries">Montenegro</option>
                                    <option value="ME,provinces">Montenegro - Provinces</option>
                                    <option value="MS,countries">Montserrat</option>
                                    <option value="MS,provinces">Montserrat - Provinces</option>
                                    <option value="MA,countries">Morocco</option>
                                    <option value="MA,provinces">Morocco - Provinces</option>
                                    <option value="MZ,countries">Mozambique</option>
                                    <option value="MZ,provinces">Mozambique - Provinces</option>
                                    <option value="MM,countries">Myanmar</option>
                                    <option value="MM,provinces">Myanmar - Provinces</option>
                                    <option value="NA,countries">Namibia</option>
                                    <option value="NA,provinces">Namibia - Provinces</option>
                                    <option value="NR,countries">Nauru</option>
                                    <option value="NR,provinces">Nauru - Provinces</option>
                                    <option value="NP,countries">Nepal</option>
                                    <option value="NP,provinces">Nepal - Provinces</option>
                                    <option value="NL,countries">Netherlands</option>
                                    <option value="NL,provinces">Netherlands - Provinces</option>
                                    <option value="NC,countries">New Caledonia</option>
                                    <option value="NC,provinces">New Caledonia - Provinces</option>
                                    <option value="NZ,countries">New Zealand</option>
                                    <option value="NZ,provinces">New Zealand - Provinces</option>
                                    <option value="NI,countries">Nicaragua</option>
                                    <option value="NI,provinces">Nicaragua - Provinces</option>
                                    <option value="NE,countries">Niger</option>
                                    <option value="NE,provinces">Niger - Provinces</option>
                                    <option value="NG,countries">Nigeria</option>
                                    <option value="NG,provinces">Nigeria - Provinces</option>
                                    <option value="NU,countries">Niue</option>
                                    <option value="NU,provinces">Niue - Provinces</option>
                                    <option value="NF,countries">Norfolk Island</option>
                                    <option value="NF,provinces">Norfolk Island - Provinces</option>
                                    <option value="MP,countries">Northern Mariana Islands</option>
                                    <option value="MP,provinces">Northern Mariana Islands - Provinces</option>
                                    <option value="NO,countries">Norway</option>
                                    <option value="NO,provinces">Norway - Provinces</option>
                                    <option value="OM,countries">Oman</option>
                                    <option value="OM,provinces">Oman - Provinces</option>
                                    <option value="PK,countries">Pakistan</option>
                                    <option value="PK,provinces">Pakistan - Provinces</option>
                                    <option value="PW,countries">Palau</option>
                                    <option value="PW,provinces">Palau - Provinces</option>
                                    <option value="PS,countries">Palestinian Territory, Occupied</option>
                                    <option value="PS,provinces">Palestinian Territory, Occupied - Provinces</option>
                                    <option value="PA,countries">Panama</option>
                                    <option value="PA,provinces">Panama - Provinces</option>
                                    <option value="PG,countries">Papua New Guinea</option>
                                    <option value="PG,provinces">Papua New Guinea - Provinces</option>
                                    <option value="PY,countries">Paraguay</option>
                                    <option value="PY,provinces">Paraguay - Provinces</option>
                                    <option value="PE,countries">Peru</option>
                                    <option value="PE,provinces">Peru - Provinces</option>
                                    <option value="PH,countries">Philippines</option>
                                    <option value="PH,provinces">Philippines - Provinces</option>
                                    <option value="PN,countries">Pitcairn</option>
                                    <option value="PN,provinces">Pitcairn - Provinces</option>
                                    <option value="PL,countries">Poland</option>
                                    <option value="PL,provinces">Poland - Provinces</option>
                                    <option value="PT,countries">Portugal</option>
                                    <option value="PT,provinces">Portugal - Provinces</option>
                                    <option value="PR,countries">Puerto Rico</option>
                                    <option value="PR,provinces">Puerto Rico - Provinces</option>
                                    <option value="QA,countries">Qatar</option>
                                    <option value="QA,provinces">Qatar - Provinces</option>
                                    <option value="RE,countries">Reunion !Réunion</option>
                                    <option value="RE,provinces">Reunion !Réunion - Provinces</option>
                                    <option value="RO,countries">Romania</option>
                                    <option value="RO,provinces">Romania - Provinces</option>
                                    <option value="RU,countries">Russian Federation</option>
                                    <option value="RU,provinces">Russian Federation - Provinces</option>
                                    <option value="RW,countries">Rwanda</option>
                                    <option value="RW,provinces">Rwanda - Provinces</option>
                                    <option value="BL,countries">Saint Barthélemy</option>
                                    <option value="BL,provinces">Saint Barthélemy - Provinces</option>
                                    <option value="SH,countries">Saint Helena, Ascension and Tristan da Cunha</option>
                                    <option value="SH,provinces">Saint Helena, Ascension and Tristan da Cunha - Provinces</option>
                                    <option value="KN,countries">Saint Kitts and Nevis</option>
                                    <option value="KN,provinces">Saint Kitts and Nevis - Provinces</option>
                                    <option value="LC,countries">Saint Lucia</option>
                                    <option value="LC,provinces">Saint Lucia - Provinces</option>
                                    <option value="MF,countries">Saint Martin (French part)</option>
                                    <option value="MF,provinces">Saint Martin (French part) - Provinces</option>
                                    <option value="PM,countries">Saint Pierre and Miquelon</option>
                                    <option value="PM,provinces">Saint Pierre and Miquelon - Provinces</option>
                                    <option value="VC,countries">Saint Vincent and the Grenadines</option>
                                    <option value="VC,provinces">Saint Vincent and the Grenadines - Provinces</option>
                                    <option value="WS,countries">Samoa</option>
                                    <option value="WS,provinces">Samoa - Provinces</option>
                                    <option value="SM,countries">San Marino</option>
                                    <option value="SM,provinces">San Marino - Provinces</option>
                                    <option value="ST,countries">Sao Tome and Principe</option>
                                    <option value="ST,provinces">Sao Tome and Principe - Provinces</option>
                                    <option value="SA,countries">Saudi Arabia</option>
                                    <option value="SA,provinces">Saudi Arabia - Provinces</option>
                                    <option value="SN,countries">Senegal</option>
                                    <option value="SN,provinces">Senegal - Provinces</option>
                                    <option value="RS,countries">Serbia</option>
                                    <option value="RS,provinces">Serbia - Provinces</option>
                                    <option value="SC,countries">Seychelles</option>
                                    <option value="SC,provinces">Seychelles - Provinces</option>
                                    <option value="SL,countries">Sierra Leone</option>
                                    <option value="SL,provinces">Sierra Leone - Provinces</option>
                                    <option value="SG,countries">Singapore</option>
                                    <option value="SG,provinces">Singapore - Provinces</option>
                                    <option value="SX,countries">Sint Maarten (Dutch part)</option>
                                    <option value="SX,provinces">Sint Maarten (Dutch part) - Provinces</option>
                                    <option value="SK,countries">Slovakia</option>
                                    <option value="SK,provinces">Slovakia - Provinces</option>
                                    <option value="SI,countries">Slovenia</option>
                                    <option value="SI,provinces">Slovenia - Provinces</option>
                                    <option value="SB,countries">Solomon Islands</option>
                                    <option value="SB,provinces">Solomon Islands - Provinces</option>
                                    <option value="SO,countries">Somalia</option>
                                    <option value="SO,provinces">Somalia - Provinces</option>
                                    <option value="ZA,countries">South Africa</option>
                                    <option value="ZA,provinces">South Africa - Provinces</option>
                                    <option value="GS,countries">South Georgia and the South Sandwich Islands</option>
                                    <option value="GS,provinces">South Georgia and the South Sandwich Islands - Provinces</option>
                                    <option value="SS,countries">South Sudan</option>
                                    <option value="SS,provinces">South Sudan - Provinces</option>
                                    <option value="ES,countries">Spain</option>
                                    <option value="ES,provinces">Spain - Provinces</option>
                                    <option value="LK,countries">Sri Lanka</option>
                                    <option value="LK,provinces">Sri Lanka - Provinces</option>
                                    <option value="SD,countries">Sudan</option>
                                    <option value="SD,provinces">Sudan - Provinces</option>
                                    <option value="SR,countries">Suriname</option>
                                    <option value="SR,provinces">Suriname - Provinces</option>
                                    <option value="SJ,countries">Svalbard and Jan Mayen</option>
                                    <option value="SJ,provinces">Svalbard and Jan Mayen - Provinces</option>
                                    <option value="SZ,countries">Swaziland</option>
                                    <option value="SZ,provinces">Swaziland - Provinces</option>
                                    <option value="SE,countries">Sweden</option>
                                    <option value="SE,provinces">Sweden - Provinces</option>
                                    <option value="CH,countries">Switzerland</option>
                                    <option value="CH,provinces">Switzerland - Provinces</option>
                                    <option value="SY,countries">Syrian Arab Republic</option>
                                    <option value="SY,provinces">Syrian Arab Republic - Provinces</option>
                                    <option value="TW,countries">Taiwan, Province of China</option>
                                    <option value="TW,provinces">Taiwan, Province of China - Provinces</option>
                                    <option value="TJ,countries">Tajikistan</option>
                                    <option value="TJ,provinces">Tajikistan - Provinces</option>
                                    <option value="TZ,countries">Tanzania, United Republic of</option>
                                    <option value="TZ,provinces">Tanzania, United Republic of - Provinces</option>
                                    <option value="TH,countries">Thailand</option>
                                    <option value="TH,provinces">Thailand - Provinces</option>
                                    <option value="TL,countries">Timor-Leste</option>
                                    <option value="TL,provinces">Timor-Leste - Provinces</option>
                                    <option value="TG,countries">Togo</option>
                                    <option value="TG,provinces">Togo - Provinces</option>
                                    <option value="TK,countries">Tokelau</option>
                                    <option value="TK,provinces">Tokelau - Provinces</option>
                                    <option value="TO,countries">Tonga</option>
                                    <option value="TO,provinces">Tonga - Provinces</option>
                                    <option value="TT,countries">Trinidad and Tobago</option>
                                    <option value="TT,provinces">Trinidad and Tobago - Provinces</option>
                                    <option value="TN,countries">Tunisia</option>
                                    <option value="TN,provinces">Tunisia - Provinces</option>
                                    <option value="TR,countries">Turkey</option>
                                    <option value="TR,provinces">Turkey - Provinces</option>
                                    <option value="TM,countries">Turkmenistan</option>
                                    <option value="TM,provinces">Turkmenistan - Provinces</option>
                                    <option value="TC,countries">Turks and Caicos Islands</option>
                                    <option value="TC,provinces">Turks and Caicos Islands - Provinces</option>
                                    <option value="TV,countries">Tuvalu</option>
                                    <option value="TV,provinces">Tuvalu - Provinces</option>
                                    <option value="UG,countries">Uganda</option>
                                    <option value="UG,provinces">Uganda - Provinces</option>
                                    <option value="UA,countries">Ukraine</option>
                                    <option value="UA,provinces">Ukraine - Provinces</option>
                                    <option value="AE,countries">United Arab Emirates</option>
                                    <option value="AE,provinces">United Arab Emirates - Provinces</option>
                                    <option value="GB,countries">United Kingdom</option>
                                    <option value="GB,provinces">United Kingdom - Provinces</option>
                                    <option value="US,countries">United States</option>
                                    <option value="US,provinces">United States - Provinces</option>
                                    <option value="UM,countries">United States Minor Outlying Islands</option>
                                    <option value="UM,provinces">United States Minor Outlying Islands - Provinces</option>
                                    <option value="UY,countries">Uruguay</option>
                                    <option value="UY,provinces">Uruguay - Provinces</option>
                                    <option value="UZ,countries">Uzbekistan</option>
                                    <option value="UZ,provinces">Uzbekistan - Provinces</option>
                                    <option value="VU,countries">Vanuatu</option>
                                    <option value="VU,provinces">Vanuatu - Provinces</option>
                                    <option value="VE,countries">Venezuela, Bolivarian Republic of</option>
                                    <option value="VE,provinces">Venezuela, Bolivarian Republic of - Provinces</option>
                                    <option value="VN,countries">Viet Nam</option>
                                    <option value="VN,provinces">Viet Nam - Provinces</option>
                                    <option value="VG,countries">Virgin Islands, British</option>
                                    <option value="VG,provinces">Virgin Islands, British - Provinces</option>
                                    <option value="VI,countries">Virgin Islands, U.S.</option>
                                    <option value="VI,provinces">Virgin Islands, U.S. - Provinces</option>
                                    <option value="WF,countries">Wallis and Futuna</option>
                                    <option value="WF,provinces">Wallis and Futuna - Provinces</option>
                                    <option value="EH,countries">Western Sahara</option>
                                    <option value="EH,provinces">Western Sahara - Provinces</option>
                                    <option value="YE,countries">Yemen</option>
                                    <option value="YE,provinces">Yemen - Provinces</option>
                                    <option value="ZM,countries">Zambia</option>
                                    <option value="ZM,provinces">Zambia - Provinces</option>
                                    <option value="ZW,countries">Zimbabwe</option>
                                    <option value="ZW,provinces">Zimbabwe - Provinces</option>
                                </select></td>
                                <td><select name="display_mode" onChange="isolinkcheck();">
                                    <option value="regions">
                                        <?=__('Regions')?>
                                    </option>
                                    <option value="markers">
                                        <?=__('Markers (Text Location)')?>
                                    </option>
                                    <option value="markers02">
                                        <?=__('Markers (Coordinates)')?>
                                    </option>
                                </select></td>
                                <td>
                                    <div class="checkbox check-success">
                                        <input name="interactive" type="checkbox"  id="interactive" onchange="drawVisualization();" value="1" checked />
                                        <label for="interactive"><?=__('Enable')?></label>
                                    </div>
                                    <br>
                                    <div class="checkbox check-success">
                                        <input name="tooltipt" type="checkbox"  id="tooltipt" onchange="drawVisualization();" value="1" checked />
                                        <label for="tooltipt"><?=__('Show Tooltip')?></label>
                                    </div>
                                    <br>
                                    <div class="checkbox check-success">
                                        <input name="tooltipthtml" type="checkbox"  id="tooltipthtml" onchange="drawVisualization();" value="1" />
                                        <label for="tooltipt"><?= __('Render HTML in Tooltip') ?></label>
                                    </div>
                                </td>
                                <td><select name="map_action" onChange="isolinkcheck()">
                                    <option value="none" selected="selected">
                                        <?=__('None')?>
                                    </option>
                                    <option value="i_map_action_open_url">
                                        <?=__('Open URL (same window)')?>
                                    </option>
                                    <option value="i_map_action_open_url_new">
                                        <?=__('Open URL (new window)')?>
                                    </option>
                                </select></td>
                            </tr>
                        </table>
                    </div>
                    <span id="iso-code-msg"></span>
                    <div id="latlondiv">
                        <table width="100%" border="0" cellspacing="5" cellpadding="5" class="latlon">
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>
                                            <?=__('Use the form below to help you get the coordinates values')?>
                                        </strong>
                                        <br>
                                        <?=__('Convert Address into Lat/Lon:')?>
                                        <label for="mapsearch">
                                            <input type="text" name="mapsearch" id="mapsearch">
                                            <input type="button" name="convert" id="convert" value="<?=__('Convert')?>" onclick="getAddress()">
                                        </label>
                                        <span id="latlonvalues"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Google Geocoding')?>
                        </h3>
                        <p><?= __('Some maps will use the Google Geocoding API to convert the region code text into readable data for the API. If your maps are not displaying, get a <a href="https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key" target="_blank">Geocoding API key</a> and place it here.') ?></p>
                    </div>
                    <div id="map-data">
                        <div id="custom-action"></div>
                        <input type="hidden" name="places" id="places" value="">
                        <input type="hidden" name="custom_action" value="">
                        <div id="simple-table" class="block w-full overflow-auto scrolling-touch">
                            <table id="add-table">
                                <tr>
                                    <td>
                                        <table id="add-table-simple">
                                            <tr>
                                                <td><?=__('Region Code:')?></td>
                                                <td><?=__('Title:')?></td>
                                                <td><?=__('Tooltip:')?></td>
                                                <td><?=__('Action Value:')?></td>
                                                <td><?=__('Color:')?></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td><input name="cd" type="text" id="cd" size="15" />
                                                <br />
                                                <small>
                                                <?=__('Follow the suggestions above.')?>
                                                <br />
                                                <?=__('MANDATORY')?>
                                            </small></td>
                                            <td><input name="c" type="text" id="c" size="15" />
                                            <br />
                                            <small>
                                            <?=__('It will be the first line of the tooltip.')?>
                                        </small></td>
                                        <td><input name="t" type="text" id="t" size="20" />
                                        <br />
                                        <small>
                                        <?=__('It will be the second line of the tooltip.')?>
                                    </small></td>
                                    <td><input name="u" type="text" id="u" size="20" />
                                    <br />
                                    <small>
                                    <?=__('Paramater for the action.')?>
                                    <br />
                                    <?=__('Ex. Url for Open Url Action.')?>
                                    <br />
                                    <?=__('You can leave it blank for no action.')?>
                                </small></td>
                                <td><input name="cl" type="text" id="cl" size="15" value="#6699CC" class="color {hash:true, adjust:false}"  />
                            <br /></td>
                            <td><input type="button" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  btn-primary-outline" value="<?=__('Add')?>" onclick="addPlaceToTable();" />
                        <br /></td>
                    </tr>
                    </table>
                    <div id="htmlplacetable"></div></td>
                    </tr>
                    </table>
                    </div>
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Preview')?>
                        </h3>
                        <p>
                            <?=__('The "Active Region Action" will not work on this preview. When an active region is clicked an alert message with the value inserted will display for debugging.')?>
                        </p>
                    </div>
                    <div id="visualization" class="block w-full overflow-auto scrolling-touch"></div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-5">
                <input type="hidden" id="jscode" name="jscode" value="">
                <input type="hidden" name="current_settings" value="">
                <input type="hidden" name="load_settings" value="<?=htmlentities($map_settings)?>">

                <div class="flex justify-end">
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <?=FORM::button('submit', __('Save'), ['type'=>'submit', 'class'=>'inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out'])?>
                    </span>
                </div>
            </div>
        <?= Form::close() ?>
    </div>
</div>
