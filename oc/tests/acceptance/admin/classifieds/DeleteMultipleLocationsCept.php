<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('delete multiple locations');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

// Create 1
$I->amOnPage('/oc-panel/location/create');
$I->see('New Location');
$I->fillField('#formorm_name','Location One');
$I->fillField('#formorm_seoname','delete-location-one');
$I->fillField('#formorm_description','Location to delete one');
$I->click('button[type="submit"]');
$I->see('Location created');

// Read 1
$I->amOnPage('/all/delete-location-one');
$I->dontSee('Page not found');
$I->see('Location One','h1');

// Create 2
$I->amOnPage('/oc-panel/location/create');
$I->see('New Location');
$I->fillField('#formorm_name','Location Two');
$I->fillField('#formorm_seoname','delete-location-two');
$I->fillField('#formorm_description','Location to delete two');
$I->click('button[type="submit"]');
$I->see('Location created');

// Read 2
$I->amOnPage('/all/delete-location-two');
$I->dontSee('Page not found');
$I->see('Location Two','h1');

$I->amOnPage('/oc-panel/Location');
$I->checkOption('input[value="6"]');
$I->checkOption('input[value="7"]');
$I->click('delete');

$I->seeElement('.alert.alert-success');
$I->see('Location Location One deleted');
$I->see('Location Location Two deleted');

// Read 1
$I->amOnPage('/all/delete-location-one');
$I->dontSee('Location to delete one','h1');

// Read 2
$I->amOnPage('/all/delete-location-two');
$I->dontSee('Location to delete two','h1');

$I->click('Logout'); 