<?php 

namespace Parler;

class WebhookFeature{
    
    public function enableWebhookFeature() {
        add_action( 'parler-webhook', array($this, 'fire' ));
        add_action('init', array($this, 'onetime'));
    }
    
    public function fire() {
        
        $localUrl = $url = site_url();
        $Constants = new Constants();
        $ParlerUrl = ($Constants->parlerServerUrl) . ($Constants->syncApiEndpoint);
        $senderEmail = get_option('admin_email');
        
        $body = array(
            'parler-site-url'   => $localUrl,
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
                'body'        => $body,
                'cookies'     => array()
            )
            );
    }
    
    public function onetime(){
        if ( ! wp_next_scheduled( 'parler-webhook' ) ) {
            $Constants = new Constants();
            $syncInterval = $Constants->syncInterval;
            wp_schedule_single_event( time() + $syncInterval, 'parler-webhook' );
        }
    }
}