<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('enable search_multi_catloc');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

// search_multi_catloc
$I->amOnPage('/oc-panel/Config/update/search_multi_catloc');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/search.html');
$I->see('Search','h1');
$I->selectOption('form select[id=category]', array('Jobs', 'House'));
$I->click("submit");

$I->see('title for the ad','a');
$I->see('just random title here','a');
$I->see('another great title','a');
$I->see('some nice title here','a');

$I->amOnPage('/search.html');
$I->see('Search','h1');
$I->selectOption('form select[id=location]', array('London', 'Madrid'));
$I->click("submit");

$I->dontSee('title for the ad','a');
$I->dontSee('just random title here','a');
$I->see('another great title','a');
$I->see('some nice title here','a');


$I->amOnPage('/oc-panel/Config/update/search_multi_catloc');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/search.html?title=&category[]=house&category[]=jobs&price-min=&price-max=&submit=');
$I->seeElement('div', ['id' => 'kohana_error']);
$I->amOnPage('/search.html?title=&location[]=madrid&location[]=london&price-min=&price-max=&submit=');
$I->seeElement('div', ['id' => 'kohana_error']);


$I->amOnPage('/');

