<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that frontpage works');
$I->amOnPage('/'); 
$I->see('Listing');
$I->see('Categories');
$I->see('Login');
$I->see('Publish New');
?>