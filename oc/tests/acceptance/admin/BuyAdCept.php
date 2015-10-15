<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('buy an ad');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/Config/update/paypal_seller');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/jobs/title-for-the-ad.html');
$I->click('Buy Now');

$I->see('Checkout','h1');
$I->see('Purchase: title-for-the-ad');

$I->amOnPage('/oc-panel/Config/update/paypal_seller');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');




