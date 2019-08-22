<?php

function enableTickets() {
        
        $labels = array(
            'name'                  => _x( 'Ticket', 'Post Type General Name', 'parler' ),
            'singular_name'         => _x( 'Ticket', 'Post Type Singular Name', 'parler' ),
            'menu_name'             => __( 'Tickets', 'parler' ),
            'name_admin_bar'        => __( 'Ticket', 'parler' ),
            'archives'              => __( 'Ticket Archives', 'parler' ),
            'attributes'            => __( 'Ticket Attributes', 'parler' ),
            'parent_item_colon'     => __( 'Parent Ticket:', 'parler' ),
            'all_items'             => __( 'All Tickets', 'parler' ),
            'add_new_item'          => __( 'Add New Ticket', 'parler' ),
            'add_new'               => __( 'Add New', 'parler' ),
            'new_item'              => __( 'New Ticket', 'parler' ),
            'edit_item'             => __( 'Edit Ticket', 'parler' ),
            'update_item'           => __( 'Update Ticket', 'parler' ),
            'view_item'             => __( 'View Ticket', 'parler' ),
            'view_items'            => __( 'View Tickets', 'parler' ),
            'search_items'          => __( 'Search Ticket', 'parler' ),
            'not_found'             => __( 'Not found', 'parler' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'parler' ),
            'featured_image'        => __( 'Featured Image', 'parler' ),
            'set_featured_image'    => __( 'Set featured image', 'parler' ),
            'remove_featured_image' => __( 'Remove featured image', 'parler' ),
            'use_featured_image'    => __( 'Use as featured image', 'parler' ),
            'insert_into_item'      => __( 'Insert into item', 'parler' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'parler' ),
            'items_list'            => __( 'Items list', 'parler' ),
            'items_list_navigation' => __( 'Items list navigation', 'parler' ),
            'filter_items_list'     => __( 'Filter items list', 'parler' ),
        );
        $args = array(
            'label'                 => __( 'Ticket', 'parler' ),
            'description'           => __( 'Incoming HTTP request', 'parler' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'custom-fields', 'thumbnail' ),
            'taxonomies'            => array( 'category', 'post_tag' ),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
        );
        register_post_type( 'ticket', $args );
        
    }
    add_action( 'init', 'enableTickets');
    
    