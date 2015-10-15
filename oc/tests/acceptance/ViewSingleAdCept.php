<?php 
$I = new AcceptanceTester($scenario);
$I->am('a visitor');
$I->amGoingTo('visit and view a single ad');
$I->lookForwardTo('some details');
$I->amOnPage('/');
$I->click("//a[@href='http://reoc.lo/all']");
$I->click("//a[@class='pull-left']");
$I->see('send message');
$I->dontsee('listings');


