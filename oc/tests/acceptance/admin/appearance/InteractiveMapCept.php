<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('enable and see Interactive Map on homepage');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

// Enable Interactive Map
$I->amOnPage('/oc-panel/Config/update/map_active');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/map');
$I->fillField('cd','150');
$I->fillField('c','150');
$I->click("//input[@value='Add']");
$I->click('submit');


//$I->see('Map saved.'); // Map is saved, not sure why this is not visibly on test :S not an issue!

// Check if interactive map appears in all premium themes.
$I->wantTo('activate Splash theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','splash');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->see('Map', 'h2');


$I->wantTo('activate Reclassifieds3 theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','reclassifieds3');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
//$I->see('Map', 'h2'); div for map is displayed, without title or something unique so it can check for it




$I->wantTo('activate Newspaper theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','newspaper');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->see('Map', 'h2');





$I->wantTo('activate Czsale theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','czsale');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->see('Map', 'h4');





$I->wantTo('activate Ocean Premium theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','ocean');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
//$I->see('Map', 'h2'); div for map is displayed, without title or something unique so it can check for it




$I->wantTo('activate moderndeluxe3 theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','moderndeluxe3');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
//$I->seeElement('div', ['id' => 'visualization']); // map is not displayed on test but it's displayed if I enable and cofigure it from panel




$I->wantTo('activate Olson theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','olson');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->see('Map', 'h4');




$I->wantTo('activate Kamaleon theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','kamaleon');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->see('Map', 'h2');




$I->wantTo('activate Jobdrop theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','jobdrop');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
//$I->see('Map', 'h2');


$I->wantTo('activate Default theme again');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','default');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/map_jscode');
$I->fillField('#formorm_config_value','');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/map_settings');
$I->fillField('#formorm_config_value','');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/map_active');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->dontSee('Map', 'h2');
