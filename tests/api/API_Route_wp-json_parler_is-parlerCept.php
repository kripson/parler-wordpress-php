<?php

use function GuzzleHttp\json_decode;

$I = new ApiTester($scenario);

$I->wantTo('See the WordPress plugin is activated by hitting a custom API');
$I->expect("the API to return 'true'");

$I->sendGET('/wp-json/parler/is-parler');
$I->seeResponseContains('true');

$I->sendGET("https://www.politicalentertainmentnetwork.com/wp-json/wp/v2/posts/395");
$user_id = $I->grabResponse();
$x = json_decode($user_id);
$y = $x->link;

echo "*******************************************************";
var_dump ($y);
echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!";