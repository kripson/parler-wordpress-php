<?php

use function GuzzleHttp\json_decode;

$I = new ApiTester($scenario);

$remoteSitePostIds = array (
    589,
    571,
    572,
    513,
    523,
    532,
    511,
    498,
    483,
    425,
    395,
    401,
    399,
    373,
    370,
    366,
    350,
    339,
    376
);
echo ("

");
$links = array();
foreach($remoteSitePostIds as $postID){
    $ApiUrl = "https://www.politicalentertainmentnetwork.com/wp-json/wp/v2/posts/" . $postID;
    $I->sendGET($ApiUrl);
    $response = $I->grabResponse();
    $response = json_decode($response);
    $link = $response->link;
    array_push($links, $link);
    echo("\"$link\", ");
}
echo(" 

");

