<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('enable Featured Ads and Bring on top');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

// Enable Featured Ads + Bring on Top
$I->amOnPage('/oc-panel/Config/update/to_featured');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/to_top');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');


// Read
$I->amOnPage('/oc-panel/myads');
$I->seeElement('.glyphicon.glyphicon-circle-arrow-up');
$I->see('Featured','a');

$I->amOnPage('/jobs/some-nice-title-here.html');
$I->see('Your Advertisement can go on top again! For only', 'p');
$I->see('Go Top!', 'a');
$I->see('Your Advertisement can go to featured! For only', 'p');
$I->see('Go Featured!', 'a');


// Back to default
$I->amOnPage('/oc-panel/Config/update/to_featured');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/to_top');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/myads');
$I->dontSeeElement('.glyphicon.glyphicon-circle-arrow-up');
$I->dontSee('Featured','a');

$I->amOnPage('/jobs/some-nice-title-here.html');
$I->dontSee('Your Advertisement can go on top again! For only', 'p');
$I->dontSee('Go Top!', 'a');
$I->dontSee('Your Advertisement can go to featured! For only', 'p');
$I->dontSee('Go Featured!', 'a');







