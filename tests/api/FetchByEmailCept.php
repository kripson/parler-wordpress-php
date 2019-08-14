<?php

$I = new ApiTester($scenario);

$I->wantTo("test the route \wp-json\parler\graph-tag");

$ApiUrl = "/wp-json/parler/graph-tag/";

$I->sendPOST($ApiUrl, [
    'parler-graph-tag'  => '<meta tag = "parler-tag" value = "yipee!" />', 
    'wp-post-id'        => '803'
]);

$I->seeResponseContains("success");