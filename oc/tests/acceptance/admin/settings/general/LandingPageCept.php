<?php 
$I = new AcceptanceTester($scenario);

// Login successfully as the administrator
$I->am('the Administrator');
$I->wantTo('log in with valid account');
$I->lookForwardTo('see the welcome message in the Panel');
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

$I->wantTo('change the landing page to LISTING');
$I->amOnPage('/oc-panel/Config/update/landing_page');
$I->fillField('#formorm_config_value','{"controller":"ad","action":"listing"}');
$I->click("button[type='submit']"); //click save
$I->amOnPage('/');
$I->see('Listings','h1');

$I->amOnPage('/oc-panel/Config/update/landing_page');
$I->fillField('#formorm_config_value','{"controller":"home","action":"index"}');
$I->click("button[type='submit']"); //click save
$I->amOnPage('/');
$I->see('Categories','h3');
$I->dontSee('Listings','h1');
$I->amOnPage('/');
$I->click('Logout');

?>