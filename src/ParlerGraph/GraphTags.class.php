<?php

namespace ParlerGraph;

class GraphTags{
    
    public function enableGraphTags(){
        
        //add_action('rest_api_init', array($this, 'registerEndpoint'));
        
    }
    
    public function registerEndpoint(){
        
        //die('hello world!');
        register_rest_route(
            'parler',
            'graph-tagxx',
            array(
                'methods'               => 'GET',
                'callback'              => array(
                    new \ParlerGraph\GraphTags(),
                    'incoming',
                ),
                'permission_callback'   => function(){return true;}
                )
            );
        
        
    }
    
    public function incoming(){
        if (isset($_POST['postID'])){
            if (isset($_POST['stringToInsert'])){
                $postID = $_POST['postID'];
                $stingToInsert = $_POST['stringToInsert'];
                $this->insertGraphTag($postID, $stingToInsert);
            }

        }
    }
    
    public function insertGraphTag($postID, $stingToInsert){
        
        
        
    }
    
}