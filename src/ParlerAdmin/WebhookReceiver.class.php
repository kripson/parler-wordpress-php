<?php 

namespace ParlerAdmin;

class WebhookReceiver {
    
    public function __construct(){
        add_action('rest_api_init', array($this,'registerAPIroute'));    
    }
    
    public function registerAPIroute(){
        register_rest_route(
            'parler',
            'webhookReceiver',
            array(
                'methods' => 'POST',
                'callback' =>
                array(
                    $this,
                    'createWebhook',
                ),
                'permission_callback' =>
                function () {
                    return true;
                    
                }
                )
            );
    }
       
    public function deleteOldHooks($title){
        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'webhook',
        );
        $the_query = new \WP_Query( $args );
        if ($the_query->have_posts()) {
            while ( $the_query->have_posts() ) :
            $the_query->the_post();
            $oldTitle = get_the_title();
            $ID = get_the_ID();
            if($oldTitle == $title){
                wp_delete_post($ID);
            }

            endwhile;
        }
        wp_reset_postdata();
    }
    
    public function createWebhook(){
        
        $siteUrl = "error. No site URL given.";
        if(isset($_POST['parler-site-url'])){
            $siteUrl = $_POST['parler-site-url'];
            $this->deleteOldHooks($siteUrl);
        }
        
        $senderEmail = "error. No sender email given.";
        if(isset($_POST['parler-sender'])){
            $senderEmail = $_POST['parler-sender'];
        }
        
        $my_post = array(
            'post_title'    => $siteUrl,
            'post_type'     => 'webhook',
            'post_content'  => $senderEmail,
            'post_status'   => 'publish',
            'post_author'   => 1
        );
        
        // Insert the post into the database.
        wp_insert_post( $my_post );
        unset($_REQUEST);
   
    }
}