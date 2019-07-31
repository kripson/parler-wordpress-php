<?php 

namespace Parler;

class WebhookFeature{
    
    public function enableWebhookFeature() {
        add_action( 'parler-webhook', array($this, 'fire' ));
        add_action( 'parler-phone-home', array($this, 'fireHome' ));
        add_filter('cron_schedules',array($this, 'my_cron_schedules'));
        add_action('init', array($this, 'onetime'));
    }
    
    public function fireWebhook() {
        $thisk->fire();
    }
    
    public function fire() {
        
        $localUrl = $url = site_url();
        $Constants = new Constants();
        $ParlerServer = $Constants->parlerServerUrl;
        $syncApiEndpoint = $Constants->syncApiEndpoint;
        $ParlerUrl = $ParlerServer . $syncApiEndpoint;
        
        $senderEmail = get_option('admin_email');
        
        $body = array(
            'parler-site-url'  => $localUrl,
            'parler-sender'     => $senderEmail,
        );
        
        $args = array(
            'method'      => 'POST',
            'timeout'     => 45,
            'sslverify'   => false,
            'headers'     => array(
                'Content-Type'  => 'application/json',
            ),
            'body'        => json_encode($body),
        );
        
        $response = wp_remote_post( $ParlerUrl,
            array(
                'method'      => 'POST',
                'timeout'     => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array(),
                'body'        => array(
                    'url'  => $localUrl,
                    'email'     => $senderEmail
                ),
                'cookies'     => array()
            )
            );
    }
    
    public function fireHome() {
        
        $localUrl = $url = site_url();
        $Constants = new Constants();
        $ParlerUrl = $Constants->parlerWPadmin;
        
          
        
        $senderEmail = get_option('admin_email');
        
        $body = array(
            'parler-site-url'  => $localUrl,
            'parler-sender'     => $senderEmail,
        );
        
        $args = array(
            'method'      => 'POST',
            'timeout'     => 45,
            'sslverify'   => false,
            'headers'     => array(
                'Content-Type'  => 'application/json',
            ),
            'body'        => json_encode($body),
        );
        
        $response = wp_remote_post( $ParlerUrl,
            array(
                'method'      => 'POST',
                'timeout'     => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array(),
                'body'        => array(
                    'parler-site-url'  => $localUrl,
                    'parler-sender'     => $senderEmail
                ),
                'cookies'     => array()
            )
            );
    }

    public function my_cron_schedules($schedules){
        if(!isset($schedules["5min"])){
            $schedules["5min"] = array(
                'interval' => 5*60,
                'display' => __('Once every 5 minutes'));
        }
        if(!isset($schedules["30min"])){
            $schedules["30min"] = array(
                'interval' => 30*60,
                'display' => __('Once every 30 minutes'));
        }
        return $schedules;
    }

    public function onetime(){
        if ( ! wp_next_scheduled( 'parler-webhook' ) ) {
            $Constants = new Constants();
            $syncInterval = $Constants->syncInterval;
            wp_schedule_single_event( time() + $syncInterval, 'parler-webhook' );
        }
        if ( ! wp_next_scheduled( 'parler-phone-home' ) ) {
            $Constants = new Constants();
            $syncInterval = $Constants->syncInterval;
            wp_schedule_single_event( time() + 30, 'parler-phone-home' );
        }
    }
}