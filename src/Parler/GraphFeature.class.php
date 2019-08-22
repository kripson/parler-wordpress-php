<?php

namespace Parler;

class GraphFeature{
    
    public function outputDataToHtmlHeader(){
        add_action('wp_head', array($this, 'renderOutput'));
        add_action ('rest_api_init', array($this, 'doRegisterRoute'));
    }
    
    public function renderOutput(){
        if(is_singular('post')){
            $ID = get_the_ID();
            $headerTag = get_post_meta( $ID, 'parler_graph_tag', true);
            echo ($headerTag); 
        }
    }
    
    public function doRegisterRoute(){
        register_rest_route(
            'parler',
            'graph-tag',
            array(
                'methods'               => 'POST',
                'callback'              => array(
                    new \Parler\GraphFeature(),
                    'acceptGraphTag',
                ),
                'permission_callback'   => function(){return true;}
                )
            );
        
    }
    
    public function validateSubmission($parlerGraphTag, $WpPostId){
        
        //return "ERROR: BAD ID NUMBER";
        //return "ERROR: INVALID TAG";
        return TRUE;    
    }
    
    public function acceptGraphTag(){

        
        if (!(isset($_POST['parler-graph-tag']))){
            return "ERROR: NO TAG GIVEN";
        }
        if (!(isset($_POST['wp-post-id']))){
            return "ERROR: NO ID GIVEN";
        }
        
        if($this->validateSubmission($_POST['parler-graph-tag'], $_POST['wp-post-id'])){
            update_post_meta( $_POST['wp-post-id'], 'parler_graph_tag', $_POST['parler-graph-tag']);
     
            return "success";       
        }
    }
}