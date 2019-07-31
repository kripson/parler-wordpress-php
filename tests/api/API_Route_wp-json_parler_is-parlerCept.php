<?php

$I = new ApiTester($scenario);
$I->wantTo('See the WordPress plugin is activated by hitting a custom API');

$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
$I->sendGET('/wp-json/parler/is-parler', []);

$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // html code 200 is expected
$I->seeResponseContains('true');
