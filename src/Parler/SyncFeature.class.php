<?php

namespace Parler;

class SyncFeature{
  
    public function registerAPIroutes(){
        add_action ('rest_api_init', array($this, 'doRegisterRoutes'));

    }
    
    public function doRegisterRoutes(){
        register_rest_route(
            'parler',
            'fetch-by-email',
            array(
                'methods'               => 'POST',
                'callback'              => array(
                    new \Parler\SyncFeature(),
                    'returnArrayOfPublishedParlerIDsAuthorizedForAParticularEmail',
                ),
                'permission_callback'   => function(){return true;}
            )
            );
        
        
        register_rest_route(
            'parler',
            'published-list',
            array(
                'methods' => 'GET',
                'callback' =>
                array(
                    new \Parler\SyncFeature(),
                    'returnArrayOfPublishedIDs',
                ),
                'permission_callback' =>
                function () {
                    //todo!!
                    return true;
                    //return current_user_can( 'edit_others_posts' );
                }
                )
            );
        
        register_rest_route(
            'parler',
            'is-parler',
            array(
                'methods'               => 'GET',
                'callback'              => function(){return true;},
                'permission_callback'   => function(){return true;}
            )
            );

        register_rest_route(
            'parler',
            'published-tag-id',
            array(
                'methods' => 'GET',
                'callback' =>
                array(
                    new \Parler\SyncFeature(),
                    'returnPublishedTagID',
                ),
                'permission_callback' => function () {
                //todo!!
                return true;
                //return current_user_can( 'edit_others_posts' );
                }
                )
            );
    }
    
    public function returnPublishedTagID(){

            $term =  term_exists( 'publish', 'parler');
            $term = $term['term_id'];
            $term = (int)$term;
            $term = json_encode($term);
            return $term;
    }
    
    public function returnArrayOfPublishedParlerIDsAuthorizedForAParticularEmail(){
        
        
        
        if (isset($_GET['email'])){
            $email = $_GET['email'];
        }else{
            $email = null;
        }
        
        
        $ID = $this->returnUserID($email);
        if($ID == false){return false;}
        if(user_can( $ID, 'publish_posts')){
            return $this->returnArrayOfPublishedIDs();
        }
        
        return $this->returnArrayOfPublishedIDsByAuthor($ID);

        
    }
    
    public function returnUserID($email){
        $user = email_exists($email);
      return $user;
    }
    
    public function returnArrayOfPublishedIDsByAuthor($authorID){
        wp_reset_query();
        $args = array(
            'posts_per_page'   => -1,
            'author'      => $authorID,
            //'post_type' => 'page',
            'tax_query' => array(
                array(
                    
                    'taxonomy' => 'parler',
                    'field' => 'slug',
                    'terms' => 'publish',
                ),
            ),
        );
        
        
        $IDs = array();
        $query = new \WP_Query( $args );
        if ( $query->have_posts() ) {
            
            // Start looping over the query results.
            while ( $query->have_posts() ) {
                
                $query->the_post();
                $ID = get_the_ID();
                array_push($IDs, $ID);
                
            }
            
        }
        
        return $IDs;
    }
    public function returnArrayOfPublishedIDs(){
        
        wp_reset_query();
        $args = array(
            'posts_per_page'   => -1,
            //'post_type' => 'page',
            'tax_query' => array(
                array(
                    
                    'taxonomy' => 'parler',
                    'field' => 'slug',
                    'terms' => 'publish',
                ),
            ),
        );
        
        
        $IDs = array();
        $query = new \WP_Query( $args );
        if ( $query->have_posts() ) {
            
            // Start looping over the query results.
            while ( $query->have_posts() ) {
                
                $query->the_post();
                $ID = get_the_ID();
                array_push($IDs, $ID);
                
            }
            
        }
        
        return $IDs;
        
    }
}