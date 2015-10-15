<?php 
$I = new AcceptanceTester($scenario);
$I->am("the admin");
$I->wantTo('create menu items, use them and then delete them to bring default menu back');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

$I->amOnPage('/');
$I->see('Listing','a');
$I->see('Categories','a');
$I->seeElement('.glyphicon.glyphicon-search');
$I->seeElement('.glyphicon.glyphicon-envelope');

// Create
$I->amOnPage('/oc-panel/Config/create');
$I->fillField('#formorm_group_name','general');
$I->fillField('#formorm_config_key','menu');
$I->fillField('#formorm_config_value','[{"title":"item1","url":"http:\/\/google.com\/","target":"_self","icon":"fa fa-music"}]');
$I->click('button[type="submit"]');
$I->see('Item created. Please to see the changes delete the cache');


// Read
$I->amOnPage('/');
$I->see('item1','a');
$I->seeElement('.fa.fa-music');


// Update
$I->amOnPage('/oc-panel/Config/update/menu');
$I->fillField('#formorm_config_value','[{"title":"itemitem1","url":"http:\/\/google.com\/","target":"_self","icon":"fa fa-music"}]');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->see('itemitem1','a');
$I->seeElement('.fa.fa-music');


// Delete
$I->amOnPage('/oc-panel/Config/delete/menu');

$I->amOnPage('/');
$I->see('Listing','a');
$I->see('Categories','a');
$I->seeElement('.glyphicon.glyphicon-search');
$I->seeElement('.glyphicon.glyphicon-envelope');

$I->amOnPage('/');
$I->click('Logout');
