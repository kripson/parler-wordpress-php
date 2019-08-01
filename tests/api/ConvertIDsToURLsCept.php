<?php

use function GuzzleHttp\json_decode;

$I = new ApiTester($scenario);


$I->sendGET("https://www.politicalentertainmentnetwork.com/wp-json/wp/v2/posts/395");
$user_id = $I->grabResponse();
$x = json_decode($user_id);
$y = $x->link;
$I->expect("I saw the URl is $y");