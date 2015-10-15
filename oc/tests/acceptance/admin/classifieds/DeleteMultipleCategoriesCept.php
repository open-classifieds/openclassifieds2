<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('delete multiple categories');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

// Create 1
$I->amOnPage('/oc-panel/category/create');
$I->see('New Category');
$I->fillField('#formorm_name','Category to delete one');
$I->fillField('#formorm_seoname','delete-category-one');
$I->fillField('#formorm_description','Category to delete one');
$I->click('button[type="submit"]');
$I->see('Category created');

// Read 1
$I->amOnPage('/delete-category-one');
$I->dontSee('Page not found');
$I->see('Category to delete one','h1');

// Create 2
$I->amOnPage('/oc-panel/category/create');
$I->see('New Category');
$I->fillField('#formorm_name','Category to delete two');
$I->fillField('#formorm_seoname','delete-category-two');
$I->fillField('#formorm_description','Category to delete two');
$I->click('button[type="submit"]');
$I->see('Category created');

// Read 2
$I->amOnPage('/delete-category-two');
$I->dontSee('Page not found');
$I->see('Category to delete two','h1');

$I->amOnPage('/oc-panel/Category');
$I->checkOption('input[value="27"]');
$I->checkOption('input[value="28"]');
$I->click('delete');

$I->seeElement('.alert.alert-success');
$I->see('Category Category to delete one deleted');
$I->see('Category Category to delete two deleted');

// Read 1
$I->amOnPage('/delete-category-one');
$I->dontSee('Category to delete one','h1');

// Read 2
$I->amOnPage('/delete-category-two');
$I->dontSee('Category to delete two','h1');

$I->click('Logout'); 