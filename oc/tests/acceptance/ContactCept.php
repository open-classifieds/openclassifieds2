<?php 
$I = new AcceptanceTester($scenario);
$I->am('a visitor');
$I->amGoingTo('use the contact form');


$I->amOnPage('/contact.html');
$I->see('Contact Us','h1');
$I->fillField('#name','Ted');
$I->fillField('#email','ted@gmail.com');
$I->fillField('#subject','Testing');
$I->fillField('#message','Hello, I am testing this form!');
$I->click('button[action="http://reoc.lo/contact.html"]');

$I->see('Your message has been sent');


// No need to test other cases (leave some fields empty) because on test it sends the email anyway.








