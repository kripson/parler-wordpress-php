<?php

namespace Parler;

//this feature will be removed on future versions. The point here is to not lose our current Integration Partners.

class OneTimeUpdate{
    
    public function enqueOneTimeUpdate(){
        add_action('init', array ($this, 'doUpdate'));
    }
    
    public function doUpdate() {
        
        //Uncomment to restart. For testing purposes.
        //delete_option('parler-one-time-update' );
        
        if(!(get_option('parler-one-time-update'))){

           update_option( 'parler-one-time-update', TRUE);
           $CPTs = array();
           array_push($CPTs, 'post');
           
           
           register_taxonomy(
               'parler',
               $CPTs,
               array(
                   'hierarchical' => TRUE,
                   'label' => __('Parler'),
                   'public' => TRUE, 'show_ui' => TRUE,
                   'query_var' => 'paler',
                   'rewrite' => array('slug' => 'parler'),
                   'show_in_rest' => TRUE,
               )
               );
           wp_insert_term('Publish to Parler', 'parler',
               array(
                   'slug'          => 'publish',
                   'description'   => 'Publish a post to the Parler platform.',
               )
               );
           wp_insert_term('Enable Parler Comments', 'parler',
               array(
                   'slug'          => 'comments',
                   'description'   => 'Enable Parler comments.',
               )
               );
           
           $taxonomyFeature = new TaxonomyFeature;
           $taxonomyFeature->setTagsForParticularCPT('post');

        }
    }
    
}