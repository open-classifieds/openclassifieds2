<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('change themes and theme options for each theme');


$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

$I->wantTo('activate Splash theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','splash');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/theme/options');
$I->fillField('#home_slogan','Homepage site slogan');
$I->click('submit');
$I->see('Theme configuration updated');

$I->amOnPage('/');
$I->see('Homepage site slogan');

$I->amOnPage('/oc-panel/theme/options');
$I->fillField('#home_slogan','Search and place ads easily with open classifieds');
$I->click('submit');
$I->see('Theme configuration updated');

$I->amOnPage('/');
$I->see('Search and place ads easily with open classifieds');



$I->wantTo('activate Reclassifieds3 theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','reclassifieds3');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');




$I->wantTo('activate Newspaper theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','newspaper');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');





$I->wantTo('activate Czsale theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','czsale');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');





$I->wantTo('activate Ocean Premium theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','ocean');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');




$I->wantTo('activate moderndeluxe3 theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','moderndeluxe3');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');




$I->wantTo('activate Olson theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','olson');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');




$I->wantTo('activate Kamaleon theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','kamaleon');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');



$I->wantTo('activate Jobdrop theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','jobdrop');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');



$I->wantTo('activate Mobile theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','mobile');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');




$I->wantTo('activate Default theme again');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','default');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

