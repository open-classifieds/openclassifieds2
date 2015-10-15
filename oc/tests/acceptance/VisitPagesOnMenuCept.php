<?php 
$I = new AcceptanceTester($scenario);
$I->am("a visitor");
$I->wantTo('visit pages on the top menu');
$I->wantTo('visit homepage');
$I->lookForwardTo('see the keyword "homepage"');
$I->amOnPage('/'); 
$I->see('Listing');
$I->see('Categories');
$I->see('Login');
$I->see('Publish New');

$I->wantTo('visit listings page');
$I->lookForwardTo('see the title "Listings"');
$I->click("//a[@href='http://reoc.lo/all']");
$I->see("Listings","h1");

$I->click("//a[@href='http://reoc.lo/']");

$I->wantTo('visit search page');
$I->lookForwardTo('see the keyword "search"');
$I->click("//a[@href='http://reoc.lo/search.html']");
$I->see("Search");
$I->click("//a[@href='http://reoc.lo/']");

$I->wantTo('visit contact page');
$I->lookForwardTo('see the keyword "contact"');
$I->click("//a[@href='http://reoc.lo/contact.html']");
$I->see("Contact Us");
$I->click("//a[@href='http://reoc.lo/']");

$I->wantTo('visit subcategory page from the dropdown menu');
$I->lookForwardTo('see the keywords "Apartments, flats, monthly rentals, ...."');
$I->click("//a[@class='dropdown-toggle']");
$I->click("//a[@href='http://reoc.lo/apartment']");
$I->see("Apartments, flats, monthly rentals, long terms, for days... this is the section to have your apartment in the City! ");
$I->click("//a[@href='http://reoc.lo/']");

$I->wantTo('visit category page from the dropdown menu');
$I->lookForwardTo('see the keywords "Do you need a place to sleep, or you have something to offer; rooms, shared apartments, houses... etc."');
$I->click("//a[@class='dropdown-toggle']");
$I->click("//a[@href='http://reoc.lo/housing']");
$I->see("Do you need a place to sleep, or you have something to offer; rooms, shared apartments, houses... etc.");
$I->click("//a[@href='http://reoc.lo/']");

$I->wantTo('see the "publish new form');
$I->lookForwardTo('see the title of the form');
$I->click("//a[@href='http://reoc.lo/publish-new.html']");
$I->see("Publish new advertisement");
$I->click("//a[@href='http://reoc.lo/']");

$I->wantTo('see the map');
$I->lookForwardTo('see the keyword "map"');
$I->amOnPage("/map.html");
$I->dontSee("Page not found");
$I->see("map");

$I->wantTo('see the rss');
$I->lookForwardTo('see the keyword "Latest published"');
$I->amOnPage("/rss.xml");
$I->dontSee("Page not found");
$I->see("Latest published","rss");


?>