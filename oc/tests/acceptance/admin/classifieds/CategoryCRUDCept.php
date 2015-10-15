<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('crud a category');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

// Create
$I->amOnPage('/oc-panel/category/create');
$I->see('New Category');
$I->fillField('#formorm_name','My New Category');
$I->fillField('#formorm_seoname','my-new-category');
$I->fillField('#formorm_description','This is my new category');
$I->click('button[type="submit"]');
$I->see('Category created');

// Read
$I->amOnPage('/my-new-category');
$I->dontSee('Page not found');
$I->see('This is my new category');

// Update
// Not unique button

// Delete
// Not unique button 

$I->click('Logout'); 