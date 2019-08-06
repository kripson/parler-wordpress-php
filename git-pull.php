<?php
//Pulls from the repo:
$output = shell_exec('git pull origin dev');
echo "<pre>$output</pre>";
//Runs the tests:
shell_exec('sudo php codecept.phar run --html');