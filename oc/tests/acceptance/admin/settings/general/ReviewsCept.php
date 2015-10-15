<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('crud a custom field');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

// enable reviews
$I->amOnPage('/oc-panel/Config/update/reviews');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

// check all themes theme to premium
$I->wantTo('activate Splash theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','splash');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->see('leave a review','a');



$I->wantTo('activate Reclassifieds3 theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','reclassifieds3');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->see('leave a review','a');



$I->wantTo('activate Newspaper theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','newspaper');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->see('leave a review','a');




$I->wantTo('activate Czsale theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','czsale');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->see('leave a review','a');



$I->wantTo('activate Ocean Premium theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','ocean');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->see('leave a review','a');



$I->wantTo('activate moderndeluxe3 theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','moderndeluxe3');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->see('leave a review','a');




$I->wantTo('activate Olson theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','olson');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->see('leave a review','a');



$I->wantTo('activate Kamaleon theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','kamaleon');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->see('leave a review','a');




$I->wantTo('activate Default theme again');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','default');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/reviews');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->click('Logout'); 

