<?php
/*
 Plugin Name: Parler WordPress Admin
 Plugin URI:
 Description:
 Version: 1
 Author: John Dee
 Author URI: https://generalchicken.net
 */

namespace ParlerAdmin;

require_once (plugin_dir_path(__FILE__). 'src/ParlerAdmin/autoloader.php');

$WebhookReceiver = new WebhookReceiver;

$ManualHookFeature = new ManualHookFeature;
$ManualHookFeature->enableManualHookFeature();


if(isset($_GET['parler-webhook-reset'])){
    add_action('init', 'resetWebhook');
    delete_option('parler-one-time-update' );
    delete_option('parler-urls' );
    die('reset');
}

//enables the CPTs:
include_once('WebhooksCPTs.php');
include_once('src/ParlerAdmin/TicketsCPTs.php');

$RemoteSites = new RemoteSites;
add_shortcode('remote-parler-sites', array($RemoteSites, 'returnShortcode'));
add_action('rest_api_init', array($RemoteSites, 'registerAPI'));

$TicketForm = new TicketForm;
$TicketForm->enableForm();
add_shortcode('parler-ticket-form', array(new TicketForm, 'returnShortcode'));
