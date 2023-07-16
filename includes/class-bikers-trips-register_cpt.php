<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

    // add cpt and custom taxoonomies
    function create_trips_cpt(){
        $labels = array(
            'name' => __('trips', 'Post Type General Name', 'bikers-trips'),
            'singular_name' => __('Trip', 'Post Type Singular Name', 'bikers-trips'),
            'menu_name' => __('Bikers Trip' , 'bikers-trips'),
            'name_admin_bar' => __('Bikers Trips' , 'bikers-trips'),
            'archives' => __('Bikers Trips Archives' , 'bikers-trips'),
            'attributes' => __('Trips Attributes ' , 'bikers-trips'),
            'parent_item_colon' => __('Parent Trips ' , 'bikers-trips'),
            'all_items' => __('All Bikers Trips ' , 'bikers-trips'),
            'add_new_item' => __('Add New Trips ' , 'bikers-trips'),
            'add_new' => __('Add New ' , 'bikers-trips'),
            'new_item' => __('New Trip ' , 'bikers-trips'),
            'edit_item' => __('Edit Trip ' , 'bikers-trips'),
            'update_item' => __('Update Trip ' , 'bikers-trips'),
            'view_item' => __('View Trip ' , 'bikers-trips'),
            'search_item' => __('Search Trip ' , 'bikers-trips'),
            'not_found_in_trash' => __('No Trips Found in trash' , 'bikers-trips'),
            'featured_image' => __('Trip Featured Image' , 'bikers-trips'),
            'set_featured_image' => __('Set Trip Featured Image' , 'bikers-trips'),
            'remove_featured_image' => __('Remove Trip Featured Image' , 'bikers-trips'),
            'use_featured_image' => __('Use as Trip Featured Image' , 'bikers-trips'),
            'insert_into_item' => __('Insert into Trips' , 'bikers-trips'),
            'uploaded_to_this_item' => __('Uploaded to this Trips' , 'bikers-trips'),
            'items_list' => __('Trips List' , 'bikers-trips'),
            'items_list_navigation' => __('Trips List Navigation' , 'bikers-trips'),
            'filter_items_list' => __('Filter Trips List' , 'bikers-trips'),
        );

        $args = array(
            'label' => __('Trips' , 'bikers-trips'),
            'description' => __('Bikers Trips Module' , 'bikers-trips'),
            'labels' => $labels,
            'menu_icon' => 'dashicons-performance',
            'supports' => array('title', 'thumbnails', 'revisions', 'author'),
            'taxonomies' => array('category', 'post_tag'),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'exclude_from_search' => false,
            'show_in_rest' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'rewrite' => array('slug' => 'bikers-trips')
        );

        register_post_type('bikers-trips', $args);
    }

    add_action('init', 'create_trips_cpt', 0);


function rewrite_trips_flush(){
    create_trips_cpt();
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'rewrite_trips_flush');



