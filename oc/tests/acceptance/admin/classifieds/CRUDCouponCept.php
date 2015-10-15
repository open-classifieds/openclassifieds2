<?php 

// Before this test it's important to import ads: https://mega.co.nz/#!99NgnYKI!3xh_k3A6mQGOJmGqxKUsC2zdiR9SwHtlDa9muH5tSYI

$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('CRUD coupons');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');


// Create from BULK and see it on panel
$I->wantTo('to create from BULK and see it on Panel');
$I->amOnPage('/oc-panel/Coupon/bulk');
$I->see('Bulk coupon generator','h1');
//$I->selectOption('form select[name=id_product]','0'); Select nothing to default "Any"
$I->fillField('#discount_amount','10');
$I->fillField('valid_date','2016-06-23');
$I->fillField('#number_coupons','1');
$I->click('submit');

// Read
$I->amOnPage('/oc-panel/Coupon/');
$I->see('23-06-16');
$I->see('10.00');
$I->seeElement('.btn.btn-danger.index-delete');

// Update
$I->click('a[title="Edit"]');
$I->fillField('#discount_amount','12');
$I->click('submit');
$I->see('updated');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('12.00');

// Delete
$I->amOnPage('/oc-panel/Coupon/');
$I->click('.btn.btn-danger.index-delete');
$I->amOnPage('/oc-panel/Coupon/');
$I->dontseeElement('.btn.btn-danger.index-delete');



// Activate featured ads and bring on top
$I->amOnPage('/oc-panel/Config/update/to_featured');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/featured_plans');
$I->fillField('#formorm_config_value','{"5":"50"}');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/to_top');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/pay_to_go_on_top');
$I->fillField('#formorm_config_value','50');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');



/*
						CREATE
*/



// Create ANY fixed
$I->wantTo('to create ANY fixed');
$I->amOnPage('/oc-panel/Coupon/create');
$I->fillField('#name','ANYF');
$I->fillField('#discount_amount','10');
$I->fillField('valid_date','2016-06-23');
$I->fillField('#number_coupons','1');
$I->click('submit');
$I->see('Coupon ANYF created');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('ANYF');
$I->see('10.00');
$I->see('23-06-16');

// Read
$I->amOnPage('/?coupon=ANYF');
$I->see('Coupon added!');
$I->seeElement('.alert.alert-success');
$I->amOnPage('/jobs/just-random-title-here.html');
$I->click('Go Featured!','a');
$I->see('Checkout');
$I->see('$50.00');
$I->see('$40.00');

// Update
$I->amOnPage('/oc-panel/Coupon/');
$I->click('a[title="Edit"]');
$I->fillField('#discount_amount','12');
$I->click('submit');
$I->see('updated');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('12.00');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->click('Go Featured!','a');
$I->see('Checkout');
$I->see('$50.00');
$I->see('$38.00');

// Delete
$I->amOnPage('/oc-panel/Coupon/');
$I->click('.btn.btn-danger.index-delete');
$I->amOnPage('/oc-panel/Coupon/');
$I->dontseeElement('.btn.btn-danger.index-delete');


// Create ANY percentage
$I->wantTo('to create ANY percentage');
$I->amOnPage('/oc-panel/Coupon/create');
$I->click('.btn.btn-default.btn-percentage');
$I->fillField('#name','ANYP');
//$I->selectOption('Any');
$I->fillField('#discount_percentage','50');
$I->fillField('valid_date','2016-06-23');
$I->fillField('#number_coupons','1');
$I->click('submit');
$I->see('Coupon ANYP created');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('ANYP');
$I->see('50% ');
$I->see('23-06-16');

// Read
$I->amOnPage('/?coupon=ANYP');
$I->see('Coupon added!');
$I->seeElement('.alert.alert-success');
$I->amOnPage('/jobs/just-random-title-here.html');
$I->click('Go Featured!','a');
$I->see('Checkout');
$I->see('$50.00');
$I->see('$25.00');

// Update
$I->amOnPage('/oc-panel/Coupon/');
$I->click('a[title="Edit"]');
$I->fillField('#discount_percentage','20');
$I->click('submit');
$I->see('updated');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('20%');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->click('Go Featured!','a');
$I->see('Checkout');
$I->see('$50.00');
$I->see('$40.00');

// Delete
$I->amOnPage('/oc-panel/Coupon/');
$I->click('.btn.btn-danger.index-delete');
$I->amOnPage('/oc-panel/Coupon/');
$I->dontseeElement('.btn.btn-danger.index-delete');


// Create FEATURED fixed
$I->wantTo('to create FEATURED fixed');
$I->amOnPage('/oc-panel/Coupon/create');
$I->fillField('#name','FEATUREDF');
$I->selectOption('form select[name=id_product]','3');
$I->fillField('#discount_amount','10');
$I->fillField('valid_date','2016-06-23');
$I->fillField('#number_coupons','1');
$I->click('submit');
$I->see('Coupon FEATUREDF created');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('FEATUREDF');
$I->see('10.00');
$I->see('23-06-16');

// Read
$I->amOnPage('/?coupon=FEATUREDF');
$I->see('Coupon added!');
$I->seeElement('.alert.alert-success');
$I->amOnPage('/jobs/just-random-title-here.html');
$I->click('Go Featured!','a');
$I->see('Checkout');
$I->see('$50.00');
$I->see('$40.00');

// Update
$I->amOnPage('/oc-panel/Coupon/');
$I->click('a[title="Edit"]');
$I->fillField('#discount_amount','20');
$I->click('submit');
$I->see('updated');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('20.00');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->click('Go Featured!','a');
$I->see('Checkout');
$I->see('$50.00');
$I->see('$30.00');

// Delete
$I->amOnPage('/oc-panel/Coupon/');
$I->click('.btn.btn-danger.index-delete');
$I->amOnPage('/oc-panel/Coupon/');
$I->dontseeElement('.btn.btn-danger.index-delete');


// Create FEATURED percentage
$I->wantTo('to create FEATURED percentage');
$I->amOnPage('/oc-panel/Coupon/create');
$I->click('.btn.btn-default.btn-percentage');
$I->fillField('#name','FEATUREDP');
$I->selectOption('form select[name=id_product]','3');
$I->fillField('#discount_percentage','50');
$I->fillField('valid_date','2016-06-23');
$I->fillField('#number_coupons','1');
$I->click('submit');
$I->see('Coupon FEATUREDP created');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('FEATUREDP');
$I->see('50%');
$I->see('23-06-16');

// Read
$I->amOnPage('/?coupon=FEATUREDP');
$I->see('Coupon added!');
$I->seeElement('.alert.alert-success');
$I->amOnPage('/jobs/just-random-title-here.html');
$I->click('Go Featured!','a');
$I->see('Checkout');
$I->see('$50.00');
$I->see('$25.00');

// Update
$I->amOnPage('/oc-panel/Coupon/');
$I->click('a[title="Edit"]');
$I->fillField('#discount_percentage','20');
$I->click('submit');
$I->see('updated');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('20%');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->click('Go Featured!','a');
$I->see('Checkout');
$I->see('$50.00');
$I->see('$40.00');

// Delete
$I->amOnPage('/oc-panel/Coupon/');
$I->click('.btn.btn-danger.index-delete');
$I->amOnPage('/oc-panel/Coupon/');
$I->dontseeElement('.btn.btn-danger.index-delete');

// Create BRINGONTOP fixed
$I->wantTo('to create BRINGONTOP fixed');
$I->amOnPage('/oc-panel/Coupon/create');
$I->fillField('#name','BRINGONTOPF');
$I->selectOption('form select[name=id_product]','2');
$I->fillField('#discount_amount','10');
$I->fillField('valid_date','2016-06-23');
$I->fillField('#number_coupons','1');
$I->click('submit');
$I->see('Coupon BRINGONTOPF created');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('BRINGONTOPF');
$I->see('10.00');
$I->see('23-06-16');

// Read
$I->amOnPage('/?coupon=BRINGONTOPF');
$I->see('Coupon added!');
$I->seeElement('.alert.alert-success');
$I->amOnPage('/jobs/just-random-title-here.html');
$I->click('Go Top!','a');
$I->see('Checkout');
$I->see('$50.00');
$I->see('$40.00');

// Update
$I->amOnPage('/oc-panel/Coupon/');
$I->click('a[title="Edit"]');
$I->fillField('#discount_amount','20');
$I->click('submit');
$I->see('updated');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('20.00');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->click('Go Top!','a');
$I->see('Checkout');
$I->see('$50.00');
$I->see('$30.00');

// Delete
$I->amOnPage('/oc-panel/Coupon/');
$I->click('.btn.btn-danger.index-delete');
$I->amOnPage('/oc-panel/Coupon/');
$I->dontseeElement('.btn.btn-danger.index-delete');



// Create BRINGONTOP percentage
$I->wantTo('to create BRINGONTOP percentage');
$I->amOnPage('/oc-panel/Coupon/create');
$I->click('.btn.btn-default.btn-percentage');
$I->fillField('#name','BRINGONTOPP');
$I->selectOption('form select[name=id_product]','2');
$I->fillField('#discount_percentage','50');
$I->fillField('valid_date','2016-06-23');
$I->fillField('#number_coupons','1');
$I->click('submit');
$I->see('Coupon BRINGONTOPP created');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('BRINGONTOPP');
$I->see('50%');
$I->see('23-06-16');

// Read
$I->amOnPage('/?coupon=BRINGONTOPP');
$I->see('Coupon added!');
$I->seeElement('.alert.alert-success');
$I->amOnPage('/jobs/just-random-title-here.html');
$I->click('Go Top!','a');
$I->see('Checkout');
$I->see('$50.00');
$I->see('$25.00');

// Update
$I->amOnPage('/oc-panel/Coupon/');
$I->click('a[title="Edit"]');
$I->fillField('#discount_percentage','20');
$I->click('submit');
$I->see('updated');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('20%');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->click('Go Top!','a');
$I->see('Checkout');
$I->see('$50.00');
$I->see('$40.00');

// Delete
$I->amOnPage('/oc-panel/Coupon/');
$I->click('.btn.btn-danger.index-delete');
$I->amOnPage('/oc-panel/Coupon/');
$I->dontseeElement('.btn.btn-danger.index-delete');



// Create PAIDCAT fixed
$I->wantTo('to create PAIDCAT fixed');
$I->amOnPage('/oc-panel/Coupon/create');
$I->fillField('#name','PAIDCATF');
$I->selectOption('form select[name=id_product]','1');
$I->fillField('#discount_amount','10');
$I->fillField('valid_date','2016-06-23');
$I->fillField('#number_coupons','1');
$I->click('submit');
$I->see('Coupon PAIDCATF created');
$I->amOnPage('/oc-panel/Coupon/');

// Read
$I->see('PAIDCATF');
$I->see('10.00');
$I->see('23-06-16');

// Update
$I->amOnPage('/oc-panel/Coupon/');
$I->click('a[title="Edit"]');
$I->fillField('#discount_amount','20');
$I->click('submit');
$I->see('updated');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('20.00');

// Delete
$I->amOnPage('/oc-panel/Coupon/');
$I->click('.btn.btn-danger.index-delete');
$I->amOnPage('/oc-panel/Coupon/');
$I->dontseeElement('.btn.btn-danger.index-delete');



// Create PAIDCAT percentage
$I->wantTo('to create PAIDCAT percentage');
$I->amOnPage('/oc-panel/Coupon/create');
$I->click('.btn.btn-default.btn-percentage');
$I->fillField('#name','PAIDCATP');
$I->selectOption('form select[name=id_product]','1');
$I->fillField('#discount_percentage','50');
$I->fillField('valid_date','2016-06-23');
$I->fillField('#number_coupons','1');
$I->click('submit');
$I->see('Coupon PAIDCATP created');

// Read
$I->see('PAIDCATP');
$I->see('50%');
$I->see('23-06-16');

// Update
$I->amOnPage('/oc-panel/Coupon/');
$I->click('a[title="Edit"]');
$I->fillField('#discount_percentage','20');
$I->click('submit');
$I->see('updated');
$I->amOnPage('/oc-panel/Coupon/');
$I->see('20%');

// Delete
$I->amOnPage('/oc-panel/Coupon/');
$I->click('.btn.btn-danger.index-delete');
$I->amOnPage('/oc-panel/Coupon/');
$I->dontseeElement('.btn.btn-danger.index-delete');


// Deactivate featured ads and bring on top
$I->amOnPage('/oc-panel/Config/update/to_featured');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/featured_plans');
$I->fillField('#formorm_config_value','{"5":"10"}');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/to_top');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/pay_to_go_on_top');
$I->fillField('#formorm_config_value','5');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');


