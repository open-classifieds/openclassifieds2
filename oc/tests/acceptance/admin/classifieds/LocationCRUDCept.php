<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('crud a location');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/location');
$I->click('a[href="http://reoc.lo/oc-panel/location/create"]');
$I->see('New Location');

$I->fillField('#formorm_name','Loc');
$I->fillField('#formorm_seoname','my-new-loc');
$I->fillField('#formorm_description','This is my new location');
$I->click('button[type="submit"]');

$I->see('Location created');

$I->amOnPage('/oc-panel/location');
$I->see('Loc');

$I->click('Logout'); 
