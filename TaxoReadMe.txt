<?php
    // Note - This is the logic attempted to create the taxonomy. Since the addition of the CPT checkboxes on the
    // Parler settings it can be reworked to be a button submit and option check. 
    function parler_create_taxonomy($params) {
    register_taxonomy( $params['post_type'] . '_parler', $params['post_type'], $params['args'] );
    } // end taxo function

    $args = array(
        'public'   => true,
    );
    
    $output = 'names';
    $operator = 'and';
    
    $post_types = get_post_types( $args, $output, $operator ); 

    foreach ( $post_types  as $post_type ) {
        $cpt_taxo_ar = get_object_taxonomies($post_type);
        $cpt_taxo_ar = array_shift($cpt_taxo_ar);
        $parlerPost = $post_type . '_parler';

        if($cpt_taxo_ar != $parlerPost){
            // echo $post_type . '_parler';
            echo '<p>taxo not created yet for ' . $post_type . '</p>';

            // Pass callable not anonymous function
            // add_action( 'init', array('parler_create_taxonomy', $params), 1);
            add_action('init', array('parler_create_taxonomy', $post_type), 0);
            // add_action('init', function() use (&$post_type) { 
            // 	$labels = array(
            // 		'name'                       => _x( 'Parler Tags', 'Parler Tags', 'parler_taxo_domain' ),
            // 		'singular_name'              => _x( 'Parler Tag', 'Parler Tag', 'parler_taxo_domain' ),
            // 		'menu_name'                  => __( 'Parler Tags', 'parler_taxo_domain' ),
            // 		'all_items'                  => __( 'All Tags', 'parler_taxo_domain' ),
            // 		'parent_item'                => __( 'Parent Tag', 'parler_taxo_domain' ),
            // 		'parent_item_colon'          => __( 'Parent Tag:', 'parler_taxo_domain' ),
            // 		'new_item_name'              => __( 'New Tag Name', 'parler_taxo_domain' ),
            // 		'add_new_item'               => __( 'Add New Tag', 'parler_taxo_domain' ),
            // 		'edit_item'                  => __( 'Edit Tag', 'parler_taxo_domain' ),
            // 		'update_item'                => __( 'Update Tag', 'parler_taxo_domain' ),
            // 		'view_item'                  => __( 'View Tag', 'parler_taxo_domain' ),
            // 		'separate_items_with_commas' => __( 'Separate items with commas', 'parler_taxo_domain' ),
            // 		'add_or_remove_items'        => __( 'Add or remove tags', 'parler_taxo_domain' ),
            // 		'choose_from_most_used'      => __( 'Choose from the most used', 'parler_taxo_domain' ),
            // 		'popular_items'              => __( 'Popular tags', 'parler_taxo_domain' ),
            // 		'search_items'               => __( 'Search tags', 'parler_taxo_domain' ),
            // 		'not_found'                  => __( 'Not Found', 'parler_taxo_domain' ),
            // 		'no_terms'                   => __( 'No items', 'parler_taxo_domain' ),
            // 		'items_list'                 => __( 'Tags list', 'parler_taxo_domain' ),
            // 		'items_list_navigation'      => __( 'Tags list navigation', 'parler_taxo_domain' )
            // 	);

            // 	$args = array(
            // 		'labels'                     => $labels,
            // 		'hierarchical'               => false,
            // 		'public'                     => true,
            // 		'show_ui'                    => true,
            // 		'show_admin_column'          => true
            // 	);

            // 	$params = array(
            // 		'post_type' => $post_type,
            // 		'args' => $args
            // 	);

            // 	register_taxonomy( $post_type . '_parler', $post_type, $args );
            // }, 0 );

            $taxo_function = function() use ($post_type) { 
                $labels = array(
                    'name'                       => _x( 'Parler Tags', 'Parler Tags', 'parler_taxo_domain' ),
                    'singular_name'              => _x( 'Parler Tag', 'Parler Tag', 'parler_taxo_domain' ),
                    'menu_name'                  => __( 'Parler Tags', 'parler_taxo_domain' ),
                    'all_items'                  => __( 'All Tags', 'parler_taxo_domain' ),
                    'parent_item'                => __( 'Parent Tag', 'parler_taxo_domain' ),
                    'parent_item_colon'          => __( 'Parent Tag:', 'parler_taxo_domain' ),
                    'new_item_name'              => __( 'New Tag Name', 'parler_taxo_domain' ),
                    'add_new_item'               => __( 'Add New Tag', 'parler_taxo_domain' ),
                    'edit_item'                  => __( 'Edit Tag', 'parler_taxo_domain' ),
                    'update_item'                => __( 'Update Tag', 'parler_taxo_domain' ),
                    'view_item'                  => __( 'View Tag', 'parler_taxo_domain' ),
                    'separate_items_with_commas' => __( 'Separate items with commas', 'parler_taxo_domain' ),
                    'add_or_remove_items'        => __( 'Add or remove tags', 'parler_taxo_domain' ),
                    'choose_from_most_used'      => __( 'Choose from the most used', 'parler_taxo_domain' ),
                    'popular_items'              => __( 'Popular tags', 'parler_taxo_domain' ),
                    'search_items'               => __( 'Search tags', 'parler_taxo_domain' ),
                    'not_found'                  => __( 'Not Found', 'parler_taxo_domain' ),
                    'no_terms'                   => __( 'No items', 'parler_taxo_domain' ),
                    'items_list'                 => __( 'Tags list', 'parler_taxo_domain' ),
                    'items_list_navigation'      => __( 'Tags list navigation', 'parler_taxo_domain' )
                );

                $args = array(
                    'labels'                     => $labels,
                    'hierarchical'               => false,
                    'public'                     => true,
                    'show_ui'                    => true,
                    'show_admin_column'          => true
                );

                $params = array(
                    'post_type' => $post_type,
                    'args' => $args
                );

                $taxo_name = $post_type . '_parler';

                register_taxonomy( $taxo_name, $post_type, $args );
            };
            add_action( 'init', $taxo_function, 0 );
            
        } //end if statement
    } //end for loop

    /*
    * Files and lines that need to be uncommented to add the CPT functionality back in:
    * class-parler-for-wordpress-activator.php Line 50-60
    * class-parler-for-wordpress-deactivator.php Line 43-53
    * uninstall.php Line 36-46
    */
?>