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



// Contact Form
$I->amOnPage('/oc-panel/Config/update/contact');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
//Read
$I->amOnPage('/jobs/just-random-title-here.html');
$I->dontSee('Send Message');
$I->dontSee('Phone: 8848585', 'a');

// Back to default
$I->amOnPage('/oc-panel/Config/update/contact');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
//Read
$I->amOnPage('/jobs/just-random-title-here.html');
$I->see('Send Message');
$I->see('Phone: 8848585', 'a');



// Require login to contact
$I->amOnPage('/oc-panel/Config/update/login_to_contact');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
//Read
$I->amOnPage('/');
$I->click('Logout');

$I->amOnPage('/jobs/just-random-title-here.html');
$I->seeElement('a', ['href' => 'http://reoc.lo/oc-panel/auth/login#login-modal']);

// Back to default
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/Config/update/login_to_contact');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
//Read
$I->amOnPage('/jobs/just-random-title-here.html');
$I->dontSeeElement('a', ['href' => 'http://reoc.lo/oc-panel/auth/login#login-modal']);




// QR Code
$I->amOnPage('/oc-panel/Config/update/qr_code');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
//Read
$I->amOnPage('/jobs/just-random-title-here.html');
$I->seeElement('img',['alt' => 'QR code']);

// Back to default
$I->amOnPage('/oc-panel/Config/update/qr_code');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
//Read
$I->amOnPage('/jobs/just-random-title-here.html');
$I->dontSeeElement('img',['alt' => 'QR code']);



// Google Maps in Ad
$I->amOnPage('/oc-panel/Config/update/map');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/oc-panel/Config/update/map_pub_new'); // enable google map in publish new
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
// Edit ad's address
$I->amOnPage('/oc-panel/myads/update/1');
$I->fillField('address','Madrid');
$I->click('submit_btn');
$I->see('Advertisement is updated');
// Import Lat & Long
$I->amOnPage('/oc-panel/import');
$I->click('a[href="http://reoc.lo/oc-panel/tools/get_ads_latlgn"]');

//Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->seeElement('.img-responsive');

// Back to default
$I->amOnPage('/oc-panel/Config/update/map');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/oc-panel/Config/update/map_pub_new'); // enable google map in publish new
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
// Edit ad's address
$I->amOnPage('/oc-panel/myads/update/1');
$I->fillField('address','optional address');
$I->click('submit_btn');
$I->see('Advertisement is updated');
//Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->dontSeeElement('.img-responsive');



// Count Visits Ads
$I->amOnPage('/oc-panel/Config/update/count_visits');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

//Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->dontSee('Hits','span');

// Back to default
$I->amOnPage('/oc-panel/Config/update/count_visits');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

//Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->see('Hits','span');



// Show sharing buttons
$I->amOnPage('/oc-panel/Config/update/sharing');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

//Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->seeElement('.st_facebook_large');
$I->seeElement('.st_twitter_large');
$I->seeElement('.st_linkedin_large');
$I->seeElement('.st_pinterest_large');
$I->seeElement('.st_googleplus_large');
$I->seeElement('.st_email_large');
$I->seeElement('.st_print_large');

// Back to default
$I->amOnPage('/oc-panel/Config/update/sharing');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

//Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->dontSeeElement('.st_facebook_large');
$I->dontSeeElement('.st_twitter_large');
$I->dontSeeElement('.st_linkedin_large');
$I->dontSeeElement('.st_pinterest_large');
$I->dontSeeElement('.st_googleplus_large');
$I->dontSeeElement('.st_email_large');
$I->dontSeeElement('.st_print_large');



// Related Ads
$I->amOnPage('/oc-panel/Config/update/related');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

//Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->dontSee('title for the ad');
$I->dontSee('just random title here');
$I->dontSee('Related ads','h3');

// Back to default
$I->amOnPage('/oc-panel/Config/update/related');
$I->fillField('#formorm_config_value','5');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

//Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->see('title for the ad');
$I->see('just random title here');
$I->see('Related ads','h3');



// Facebook Comments
$I->amOnPage('/oc-panel/Config/update/fbcomments');
$I->fillField('#formorm_config_value','367576600118660');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/oc-panel/tools/cache?force=1'); // Delete cache ALL
$I->see('All cache deleted');

//Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->see("//connect.facebook.net/en_US/all.js#xfbml=1&appId=367576600118660");

// Back to default
$I->amOnPage('/oc-panel/Config/update/fbcomments');
$I->fillField('#formorm_config_value','');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/oc-panel/tools/cache?force=1'); // Delete cache ALL
$I->see('All cache deleted');
//Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->dontSee("//connect.facebook.net/en_US/all.js#xfbml=1&appId=367576600118660");



// Disqus
$I->amOnPage('/oc-panel/Config/update/disqus');
$I->fillField('#formorm_config_value','testoc');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/oc-panel/tools/cache?force=1'); // Delete cache ALL
$I->see('All cache deleted');

//Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->see("comments powered by Disqus");

// Back to default
$I->amOnPage('/oc-panel/Config/update/disqus');
$I->fillField('#formorm_config_value','');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/oc-panel/tools/cache?force=1'); // Delete cache ALL
$I->see('All cache deleted');
//Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->dontSee("comments powered by Disqus");

