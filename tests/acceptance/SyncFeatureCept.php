<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('test the sync feature');
/*

$output = <<<output
SCENARIO: A sync event on a site with a registered Parler account

Given there is a Parler account with the email johndeebdd@gmail.com
And there is a remote WP site http://ec2-3-87-51-67.compute-1.amazonaws.com
And the admin creds for the remote site are admin password
And the admin email of the WP site is johndeebdd@gmail.com
And there are two posts on the site with the term "publish-parler"
When the remote WP site fires a web hook 
And the web hook should fire every 30 seconds 
And the webhook should be http-post url: http://ec2-3-87-51-67.compute-1.amazonaws.com email:  johndeebdd@gmail.com
Then the Parler sync sets off 
And content should be pulled from http://ec2-3-87-51-67.compute-1.amazonaws.com/wp-json/parler/published-list
And content should appear on app

output;

echo $output;
/*

https://www.politicalentertainmentnetwork.com/2019/07/30/trumps-latest-disgusting-act-will-have-you-running-to-the-voters-booth/
https://www.politicalentertainmentnetwork.com/2019/07/30/after-democrats-said-he-was-a-russian-asset-mitch-mcconnell-just-went-berserk-on-the-senate-floor/
https://www.politicalentertainmentnetwork.com/2019/07/30/ben-goldman-show-7-29-2019/
https://www.politicalentertainmentnetwork.com/2019/07/29/ben-goldman-show-7-25-2019/
https://www.politicalentertainmentnetwork.com/2019/07/29/ben-goldman-show-8-hours-of-mueller-in-ten-minutes/
https://www.politicalentertainmentnetwork.com/2019/07/29/366/
https://www.politicalentertainmentnetwork.com/2019/07/29/shock-video-baltimore-mayor-says-you-can-smell-the-rats-in-baltimore-president-vindicated/
https://www.politicalentertainmentnetwork.com/2019/07/28/ben-goldman-show-7-22-2019/
https://www.politicalentertainmentnetwork.com/2019/07/27/ben-goldman-show-7-26-2019/

*/