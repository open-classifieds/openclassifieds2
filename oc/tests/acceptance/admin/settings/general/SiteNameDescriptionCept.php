<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('change Site Name and Site Description');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/Config/update/site_name');
$I->fillField('#formorm_config_value','Site Name');
$I->click("button[type='submit']"); //click save
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/site_description');
$I->fillField('#formorm_config_value','Site Description');
$I->click("button[type='submit']"); //click save
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->see('Site Name','h1');
//$I->see('Site Description');

$I->click('Logout');






