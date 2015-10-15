<?php 
$I = new AcceptanceTester($scenario);

//login as a user
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','gazzasdasd@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect'); 

$I->am("a user");
$I->wantTo('mark an ad as favorite and see this ad on my favorites');

$I->amOnPage('/oc-panel/profile/favorites');
$I->seeElement('th');
$I->dontSeeElement('.btn.btn-danger.index-delete.index-delete-inline');

$I->amOnPage('/all');
$I->seeElement('.glyphicon-heart-empty');
$I->click("//a[@title='Add to Favorites']");
$I->dontseeElement('.glyphicon-heart-empty');

$I->amOnPage('/oc-panel/profile/favorites');
$I->seeElement('.btn.btn-danger.index-delete.index-delete-inline');

$I->click("//a[@class='btn btn-danger index-delete index-delete-inline']");
$I->amOnPage('/');
$I->click('Logout'); 
