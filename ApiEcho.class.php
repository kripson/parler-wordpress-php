<?php

/*
 Plugin Name: Parler API Echo
 Plugin URI:
 Description:
 Version: 1.1
 Author: John Dee
 Author URI: https://generalchicken.net
 */

namespace Parler;

class ApiEcho{
    
    public function __construct(){
        $this->registerAPIroutes();
        add_shortcode('apiEcho', array ($this, 'apiSenderShortcode'));
    }
    
    
    public function registerAPIroutes(){
        add_action ('rest_api_init', array($this, 'doRegisterRoutes'));
    }
    
    public function acceptArrayOfPublishedIDs(){
        //die('hello API');
        if(isset($_POST['data-for-api-echo'])){
            $data = json_decode($_POST['data-for-api-echo']);
            update_option( 'data-for-api-echo', $data);
        }
        return $data;
    }
    
    public function returnArrayOfPublishedIDs(){
        $data = get_option('data-for-api-echo');
        
        return $data;
    }
    
    public function apiSenderShortcode(){
        
        $data =  $this->returnArrayOfPublishedIDs();
        
        
        ob_start();
        var_dump($data);
        $data = ob_get_clean();
        
        $output = <<<output
<form method = "post" action = "/wp-json/parler/published-list">
Enter some data:<br />
<input type = "text" name = 'data-for-api-echo' id = 'data-for-api-echo' />
<input type = "submit" /><br /><br />
</form>
Data:<br />
$data
output;
return $output;

    }
    
    public function doRegisterRoutes(){
        register_rest_route(
            'parler',
            'published-list',
            array(
                'methods' => 'GET',
                'callback' =>
                array(
                    $this,
                    'returnArrayOfPublishedIDs',
                ),
                'permission_callback' =>
                function () {
                    return true;
                    
                }
                )
            );
        
        register_rest_route(
            'parler',
            'published-list',
            array(
                'methods' => 'POST',
                'callback' =>
                array(
                    $this,
                    'acceptArrayOfPublishedIDs',
                ),
                'permission_callback' =>
                function () {
                    return true;
                    
                }
                )
            );

    }
}

$ApiEcho = new ApiEcho();