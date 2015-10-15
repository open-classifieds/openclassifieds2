<?php 
$I = new AcceptanceTester($scenario);
$I->am("the admin");
$I->wantTo('change configurations and see changes on frontend');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');


// Advertisements per page
$I->amOnPage('/oc-panel/settings/form');
$I->fillField('#advertisements_per_page','2');
$I->click('submit');
// Read
$I->amOnPage('/all');
$I->seeElement('.pagination');
$I->dontSee('another great title');

// Back to default
$I->amOnPage('/oc-panel/settings/form');
$I->fillField('#advertisements_per_page','10');
$I->click('submit');
$I->amOnPage('/all');
$I->dontSeeElement('.pagination');
$I->see('another great title');




// Advertisements in RSS
$I->amOnPage('/oc-panel/settings/form');
$I->fillField('#feed_elements','2');
$I->click('submit');
// Read
$I->amOnPage('/rss.xml');
$I->dontSee('another great title');

// Back to default
$I->amOnPage('/oc-panel/settings/form');
$I->fillField('#feed_elements','20');
$I->click('submit');
$I->amOnPage('/rss.xml');
$I->see('another great title');


// Sort by in Listing

// Name (A-Z)
$I->amOnPage('/oc-panel/settings/form');
$I->fillField('#advertisements_per_page','1');
$I->selectOption('#sort_by','Name (A-Z)');
$I->click('submit');
// Read
$I->amOnPage('/all');
$I->seeElement('.pagination');
//$I->see('another great title');  maybe another ad will be here from other test (publish new)
$I->dontSee('just random title here');
$I->dontSee('some nice title here');
$I->dontSee('title for the ad');

// Name (Z-A)
$I->amOnPage('/oc-panel/settings/form');
$I->selectOption('#sort_by','Name (Z-A)');
$I->click('submit');
// Read
$I->amOnPage('/all');
$I->seeElement('.pagination');
// $I->see('title for the ad');  maybe another ad will be here from other test (publish new)
$I->dontSee('just random title here');
$I->dontSee('some nice title here');
$I->dontSee('another great title');

// Price (Low)
$I->amOnPage('/oc-panel/settings/form');
$I->selectOption('#sort_by','Price (Low)');
$I->click('submit');
// Read
$I->amOnPage('/all');
$I->seeElement('.pagination');
// $I->see('some nice title here');  maybe another ad will be here from other test (publish new)
$I->dontSee('just random title here');
$I->dontSee('title for the ad');
//$I->dontSee('another great title'); maybe another ad will be here from other test (publish new)


// Price (High)
$I->amOnPage('/oc-panel/settings/form');
$I->selectOption('#sort_by','Price (High)');
$I->click('submit');
// Read
$I->amOnPage('/all');
$I->seeElement('.pagination');
$I->see('title for the ad');
$I->dontSee('just random title here');
$I->dontSee('some nice title here');
$I->dontSee('another great title');

// Favorited
$I->amOnPage('/all?page=3');
$I->click('.add-favorite');

$I->amOnPage('/oc-panel/settings/form');
$I->selectOption('#sort_by','Favorited');
$I->click('submit');
// Read
$I->amOnPage('/all');
$I->seeElement('.pagination');
// $I->see('another great title'); maybe another ad will be here from other test (publish new)
$I->dontSee('just random title here');
$I->dontSee('some nice title here');
$I->dontSee('title for the ad');

$I->amOnPage('/all');
$I->click('.add-favorite.remove-favorite');

// Oldest
$I->amOnPage('/oc-panel/settings/form');
$I->selectOption('#sort_by','Oldest');
$I->click('submit');
// Read
$I->amOnPage('/all');
$I->seeElement('.pagination');
$I->see('some nice title here ');
$I->dontSee('just random title here');
$I->dontSee('another great title');
$I->dontSee('title for the ad');

// Back to default
// Newest
$I->amOnPage('/oc-panel/settings/form');
$I->selectOption('#sort_by','Newest');
$I->click('submit');
// Read
$I->amOnPage('/all');
$I->seeElement('.pagination');
// $I->see('title for the ad'); maybe another ad will be here from other test (publish new)
$I->dontSee('just random title here');
$I->dontSee('another great title');
$I->dontSee('some nice title here');

$I->amOnPage('/oc-panel/settings/form');
$I->fillField('#advertisements_per_page','10');
$I->click('submit');


//Advertisements in home
$I->amOnPage('/oc-panel/settings/form');
$I->selectOption('#ads_in_home','None');
$I->click('submit');
// Read
$I->amOnPage('/');
$I->dontSeeElement('.thumbnail.latest_ads');

// Back to default
$I->amOnPage('/oc-panel/settings/form');
$I->selectOption('#ads_in_home','0');
$I->click('submit');
// Read
$I->amOnPage('/');
$I->seeElement('.thumbnail.latest_ads');





















