<?php

namespace Parler;

class TaxonomyFeature{
    
    public function enableParlerTerms(){
        add_action('init', array($this, "createParlerTerms"));
    }
    
    public function preClickParlerTaxosInEditors(){
        
    }
    
    public function removeParlerTagsFromEntireSite($CPT){
        

        
        $args = array(
            //'post_status'   => 'publish',
            'post_type'     => $CPT,
        );
        
        // Custom query.
        $query = new \WP_Query( $args );
        
        // Check that we have query results.
        if ( $query->have_posts() ) {
            
            // Start looping over the query results.
            while ( $query->have_posts() ) {
                
                $query->the_post();
                $post_id = get_the_ID();
                $term =  term_exists( 'publish', 'parler');
                $term = $term['term_id'];
                wp_remove_object_terms( $post_id, $term, 'parler' );
                //echo( $post_id. $term. 'parler' );
            }
            
        }
        
        // Restore original post data.
        wp_reset_postdata();
        
    }
    
    public function setTagsForParticularCPT($CPT){
        $args = array(
            'post_status'   => 'publish',
            'post_type'     => $CPT,
        );
        
        // Custom query.
        $query = new \WP_Query( $args );
        
        // Check that we have query results.
        if ( $query->have_posts() ) {
            
            // Start looping over the query results.
            while ( $query->have_posts() ) {
                
                $query->the_post();
                $post_id = get_the_ID();
                $term =  term_exists( 'publish', 'parler');
                $term = $term['term_id'];
                wp_set_post_terms( $post_id, $term, 'parler', TRUE);
                $term =  term_exists( 'comments', 'parler');
                $term = $term['term_id'];
                wp_set_post_terms( $post_id, $term, 'parler', TRUE );
            }
            
        }
        
        // Restore original post data.
        wp_reset_postdata();
        
    }
    
    public function defaultClickParlerTermsOnPostNewPHPtemplate(){
        add_action('init', array($this, 'outputDefaultClickParlerTermsOnPostNewPHPtemplate'));
    }
    
    public function outputDefaultClickParlerTermsOnPostNewPHPtemplate(){
        
$output = <<<output
<script>
jQuery( document ).ready(function() {
    alert("jQuery ready!");
});
</script>

output;
    echo $output;
        
    }
    //the taxo selector is used to select which taxos are to be published to Parler   
    
    public function categoryColumns($out, $column_name, $category_id) {
        $category = get_term($category_id, 'category');
        switch ($column_name) {
            case 'header_icon':
                $out = $this->returnSyncNoSyncRowElementHTML($category_id);
                break;
                
            default:
                break;
        }
        return $out;
    }
    
    public function post_tagColumns($out, $column_name, $post_tag_id) {
        $category = get_term($post_tag_id, 'category');
        switch ($column_name) {
            case 'header_icon':
                $out = $this->returnSyncNoSyncRowElementHTML($post_tag_id);
                break;
                
            default:
                break;
        }
        return $out;
    }
    
    public function categoryHeader($category_columns) {
        $LogoImage = new \Parler\LogoImage();
        $ParlerLogoImg = $LogoImage->returnParlerIconImgTagDiv();
        $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'description' => __('Description'),
            'slug' => __('Slug'),
            'posts' => __('Posts'),
            'header_icon' => $ParlerLogoImg,
        );
        return $new_columns;
    }
    
    public function post_tagHeader($post_tag_columns) {
        $LogoImage = new \Parler\LogoImage();
        $ParlerLogoImg = $LogoImage->returnParlerIconImgTagDiv();
        $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'description' => __('Description'),
            'slug' => __('Slug'),
            'posts' => __('Posts'),
            'header_icon' => $ParlerLogoImg,
        );
        return $new_columns;
    }
    
    public function stripBadItemsFromTaxoList() {
        
        //WordPress comes with several default taxonomies
        //We only want tags, categories, and any custom taxos
        $x = get_taxonomies(array(), 'names');
        if (($key = array_search("nav_menu", $x)) !== false) {
            unset($x["nav_menu"]);
        }
        if (($key = array_search("link_category", $x)) !== false) {
            unset($x["link_category"]);
        }
        if (($key = array_search("post_format", $x)) !== false) {
        }
            unset($x["post_format"]);
        
        //uncomment this line to see taonomies:
        //var_dump($x);die();
        
        return $x;
    }
 
    public function addParlerTerm($post_type){

        register_taxonomy(
            'parler',
            $post_type,
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
        
    }
    
    public function createParlerTerms( ) {
        if (!taxonomy_exists('parler')) {
            $activeCPTs = get_option('parler-enabled-post-types');
        //die('194');
            if($activeCPTs) {
                $CPTarray = array();
                foreach($activeCPTs as $post_type){
                    array_push($CPTarray, $post_type);

                }
                $this->addParlerTerm($CPTarray);
            }
        }
    }  

    public function returnSyncNoSyncRowElementHTML($objectID) {
        $out = "
<div style = 'text-align:center;margin:auto;'>
    <input type = 'checkbox' name = 'parler-term-selector-checkbox-$objectID' value = '$objectID' checked>
<div />";
        return $out;
    }
    
    public function addTagToAllPosts(){
        wp_set_post_terms( $post_id, $terms, $taxonomy, $append);
    }
    
}