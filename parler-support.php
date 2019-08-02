<?php
/*
 Plugin Name: Parler Support
 Plugin URI:
 Description:
 Version: 1
 Author: Jim Karlinski
 Author URI: https://generalchicken.net
 */

namespace ParlerSupport;

require_once (plugin_dir_path(__FILE__). 'src/ParlerSupport/autoloader.php');
require_once (plugin_dir_path(__FILE__). 'src/ParlerSupport/SupportTicketCPT.php');

$SupportForm = new SupportForm;
$SupportForm->enableForm();