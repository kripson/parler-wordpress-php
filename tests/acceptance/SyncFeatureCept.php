<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('test the sync feature');


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