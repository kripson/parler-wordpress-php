<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('Confirm Parler comment form appears on client site');


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

$remoteSitePostIds = array (
532
);

$remoteSiteUrl = "https://www.politicalentertainmentnetwork.com/";

//foreach ($remoteSitePostIds as $remoteSitePostID){
/*
    $Url = $remoteSiteUrl . "?post=" . $remoteSitePostID;
    $I->expect("To see the Parler comment field on $Url");
    $I->amOnUrl("https://www.politicalentertainmentnetwork.com/2019/08/01/after-mario-lopez-said-he-wouldnt-raise-kids-to-be-transgender-something-awful-happened/");
    
    //$I->wait(10);

    //$I->scrollTo('.site-info');
    $I->wait(3);
    $I->executeJS("window.scrollTo(0, document.body.scrollHeight)");
    $I->wait(3);
    $I->canSeeElement('.parler_logo');

//}

*/

$I->sendGET("https://www.politicalentertainmentnetwork.com/wp-json/wp/v2/posts/395");
$user_id = $I->grabResponse();
$x = json_decode($user_id);
$y = $x->link;
