<?php

namespace ParlerGraph;

class GraphTags{
    
    public function enableGraphTags(){
        
        add_action('rest_api_init', array($this, 'registerEndpoint'));
        
    }
    
    public function registerEndpoint(){
        
        //die('hello world!');
        register_rest_route(
            'parler',
            'graph-tags',
            array(
                'methods'               => 'GET',
                'callback'              => array(
                    new \ParlerGraph\GraphTags(),
                    'returnSomething',
                ),
                'permission_callback'   => function(){return true;}
                )
            );
        
        
    }
    
    public function returnSomething(){
        
        return ("Hello World!");
        
    }
    
    
}