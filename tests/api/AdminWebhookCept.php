<?php

$I = new ApiTester($scenario);

$I->wantTo('test the admin webhook receiver');
$I->expect("the hook to receive the data");
$I->requireAutoloaders();

require_once('/var/www/html/wp-content/plugins/parler-wordpress-php/src/Parler/Constants.class.php');
$Constants = new \Parler\Constants;
$parlerServerUrl = $Constants->parlerServerUrl;
$ApiUrl = $Constants->syncApiEndpoint;

$I->sendPOST($ApiUrl, [
    'parler-site-url'   => 'http://testingsite.com', 
    'parler-sender'        => 'jiminac@aol.com'
]);

$I->seePostInDatabase(['post_title' => 'http://testingsite.com']);
$I->dontHavePostInDatabase(['post_title' => 'http://testingsite.com']);