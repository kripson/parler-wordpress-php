<?php
/*
 Plugin Name: Parler Graph Tags
 Plugin URI:
 Description:
 Version: 1
 Author: Jim Karlinski
 Author URI: https://generalchicken.net
 */

namespace ParlerGraph;

require_once (plugin_dir_path(__FILE__). 'src/ParlerGraph/autoloader.php');

$GraphTags = new GraphTags;
$GraphTags->enableGraphTags();