<?php

namespace Parler;

class CommentsFeature{
    
   
    
    public function injectParlerIntoCommentTemplate() {

        if( has_term( 'comments', 'parler' ) ) {
            
            add_filter( 'comments_template', array( $this, 'comments_template' ), 20 );
           
            // feed_links_extra inserts a comments RSS link.
            add_filter('comment_form_default_fields', array($this, 'unset_url_field'));
            add_filter('comment_form_defaults', array($this, 'wpsites_change_comment_form_submit_label'));
            add_action( 'wp_enqueue_scripts', array($this, 'wpdocs_theme_name_scripts' ));   
        }
        
        
    }
    
    public function wpdocs_theme_name_scripts() {
        wp_enqueue_style( 'parler-for-wordpress-css', 'https://plugin.parler.com/production/parler-for-wordpress-public.css' );
    }
    
    public function comments_template($output) {
        
        $Constants = new Constants();
        $parlerCSS = $Constants->parlerCSS;
        $parlerForWordPressPublic = $Constants->parlerForWordPressPublic;
        $parlerReact = $Constants->parlerReact;
        $parlerDom = $Constants->parlerDom;
        
        wp_enqueue_script('parler-for-wordpress-css', $parlerCSS, array(), "1.3.1", true);
        wp_enqueue_script('parler-for-wordpress-public', $parlerForWordPressPublic);
        wp_enqueue_script('parler-react', $parlerReact);
        wp_enqueue_script('parler-dom', $parlerDom);
        
        return $output;
    }
    
   

    public function unset_url_field($fields){
        
        if(isset($fields['url'])){
            unset($fields['url']);
        }
        if(isset($fields['author'])){
            unset($fields['author']);
        }
        if(isset($fields['email'])){
            unset($fields['email']);
        }
        if(isset($fields['url'])){
            unset($fields['url']);
        }
        if(isset($fields['comment_field'])){
            unset($fields['comment_field']);
        }
        
    }
    
    public function wpsites_change_comment_form_submit_label($fields) {
        $removeThese = array (
            "class_submit",
            'class_form',
            'comment-reply-title',
            'comment-form',
            "form-submit",
            "label_submit",
            'logged_in_as',
            'must_log_in',
            "name_submit",
            'submit',
            'submit_button',
            'comment_notes_before',
            //'comment_notes_after',
            'title_reply',
            'comment_field',
            'title_reply_before',
            'title_reply_after',
            'title_reply_to,',
            'cancel_reply_before',
            'cancel_reply_after',
            'cancel_reply_link',
            "Leave a Reply to %s",
            "Post Comment",
        );
        foreach($removeThese as $removeThis){
            if(isset($fields[$removeThis])){
                $fields[$removeThis] = "";
            }
        }

        $fields['comment_field'] = '<!-- parler.com comment inserter --><div id="comments"></div>';
        
        return $fields;
    }
    
}