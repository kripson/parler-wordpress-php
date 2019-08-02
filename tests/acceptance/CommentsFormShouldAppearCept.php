<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('Confirm Parler comment form appears on client site');


$links = array (
    "https://www.politicalentertainmentnetwork.com/2019/08/01/here-are-the-winners-and-losers-from-last-nights-presidential-debate-the-winner-is-shocking/", "https://www.politicalentertainmentnetwork.com/2019/08/01/marianne-williamson-just-went-viral-when-she-said-this-to-bring-down-trump/",
 );

foreach ($links as $link){
    $I->expect("To see the Parler React app on url: $link");
    $I->amOnUrl($link);
    $I->wait(3);
    $I->executeJS("window.scrollTo(0, document.body.scrollHeight)");
    $I->wait(3);
    $I->canSeeElement('.parler_logo');
 }