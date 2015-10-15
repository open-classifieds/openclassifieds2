<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('CRUD widgets');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');


// Categories
$I->wantTo('create a "Categories" widget');
$I->amOnPage('/oc-panel/widget');
$I->click('//button[@data-target="#modal_Widget_Categories"]');
$I->selectOption('placeholder','sidebar');
$I->click('Save changes');

// See
$I->amOnPage('/');
$I->seeElement('.panel.panel-sidebar.Widget_Categories');

// Delete
$I->amOnPage('/oc-panel/widget');
$I->click('//a[@class="btn btn-danger pull-left"]');








$I->amOnPage('/');
$I->click('Logout');























