<?php

$I = new ApiTester($scenario);

$I->wantTo('See the WordPress plugin is activated by hitting a custom API');
$I->expect("the API to return 'true'");

$I->sendGET('/wp-json/parler/is-parler');
$I->seeResponseContains('true');
