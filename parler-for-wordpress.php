<?php
/**
 * Plugin Name:       Parler
 * Plugin URI:        https://home.parler.com/installation/
 * Description:       A Social News and Content Engagement System working to increase community activity, grow audience exposure, and drive site traffic.
 * Version:           2.0.4
 * Author:            Parler LLC
 * Author URI:        https://home.parler.com/
 * Text Domain:       parler
 * Domain Path:       /languages
 */

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

function activate_parler_plugin() {
    if (!( get_option( 'parler-enabled-post-types' ))){
        $activeCPTs = array();
        array_push($activeCPTs, "post");
        update_option('parler-enabled-post-types', $activeCPTs);
    }
}

//register_activation_hook( __FILE__, 'activate_parler_plugin' );

add_action('init', array(new \Parler\TaxonomyFeature, "createParlerTerms"));

add_action('wp', array (new \Parler\CommentsFeature, 'injectParlerIntoCommentTemplate'));

$SyncFeature = new \Parler\SyncFeature;
$SyncFeature->registerAPIroutes();

$OneTimeUpdate = new \Parler\OneTimeUpdate;
$OneTimeUpdate->enqueOneTimeUpdate();

add_action('admin_menu', array(new \Parler\SettingsPage, "renderAdminSettingsPage"));

$WebhookFeature = new \Parler\WebhookFeature;
$WebhookFeature->enableWebhookFeature();

$GraphFeature = new \Parler\GraphFeature;
$GraphFeature->outputDataToHtmlHeader();