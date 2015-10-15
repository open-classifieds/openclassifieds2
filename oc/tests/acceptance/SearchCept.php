<?php 

//Before that test it's important import the example file "import_ads_example.csv"

$I = new AcceptanceTester($scenario);
$I->am('a visitor');
$I->wantTo('search from the search page');

// Search for non existing Ad
$I->lookForwardTo('see the message "Your search did not match any advertisement."');
$I->amOnPage('/search.html');
$I->see('Advanced Search');
$I->fillField('title','not exists');
$I->click("submit");
$I->see('Your search did not match any advertisement.');

// Browse by category
$I->lookForwardTo('see the ad with title "another great title"');
$I->amOnPage('/search.html');
$I->see('Advanced Search');
$I->selectOption('form select[name=category]', 'House');
$I->click("submit");
$I->see('another great title');
$I->dontSee('nice'); // included in the title of another ad which must not be displayed
$I->dontSee('just'); // included in the title of another ad which must not be displayed

// Browse by location
$I->lookForwardTo('see the ad with title "another great title"');
$I->amOnPage('/search.html');
$I->see('Advanced Search');
$I->selectOption('form select[name=location]', 'London');
$I->click("submit");
$I->see('another great title');
$I->dontSee('nice'); // included in the title of another ad which must not be displayed
$I->dontSee('just'); // included in the title of another ad which must not be displayed

// Browse by category and location
$I->lookForwardTo('see the ad with title "another great title"');
$I->amOnPage('/search.html');
$I->see('Advanced Search');
$I->selectOption('form select[name=category]', 'House');
$I->selectOption('form select[name=location]', 'London');
$I->click("submit");
$I->see('another great title');
$I->dontSee('nice'); // included in the title of another ad which must not be displayed
$I->dontSee('just'); // included in the title of another ad which must not be displayed








?>
