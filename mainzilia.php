<?php

/**
 * Plugin Name: mainzilia
 * Description: mainzilia
 * Version: 1.0
 **/
function mainzilia_post_type()
{

    $labels = array(
        'name'                => _x('subscribes', 'Post Type General Name', 'twentytwenty'),
        'singular_name'       => _x('subscribes', 'Post Type Singular Name', 'twentytwenty'),
        'menu_name'           => __('subscribes', 'twentytwenty'),
        'parent_item_colon'   => __('Parent subscribe', 'twentytwenty'),
        'all_items'           => __('All subscribes', 'twentytwenty'),
        'view_item'           => __('View subscribe', 'twentytwenty'),
        'add_new_item'        => __('Add New subscribe', 'twentytwenty'),
        'add_new'             => __('Add New', 'twentytwenty'),
        'edit_item'           => __('Edit subscribe', 'twentytwenty'),
        'update_item'         => __('Update subscribe', 'twentytwenty'),
        'search_items'        => __('Search subscribe', 'twentytwenty'),
        'not_found'           => __('Not Found', 'twentytwenty'),
        'not_found_in_trash'  => __('Not found in Trash', 'twentytwenty'),
    );


    $args = array(
        'label'               => __('subscribes', 'twentytwenty'),
        'description'         => __('subscribe news and reviews', 'twentytwenty'),
        'labels'              => $labels,
        'supports'            => array('title', 'editor'),
        'taxonomies'          => array('genres'),

        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
        'show_in_rest' => false,
        'capabilities' => array(
            'create_posts' => false,
        ),
        'map_meta_cap' => false,

    );

    register_post_type('subscribes', $args);
}


add_action('init', 'mainzilia_post_type', 0);

function home_page_add_page_template($templates)
{
    $templates['home-mainzilia.php'] = 'home mainzilia';
    return $templates;
}
add_filter('theme_page_templates', 'home_page_add_page_template');

function mainzilia_page_template($template)
{
    $post = get_post();
    $page_template = get_post_meta($post->ID, '_wp_page_template', true);
    if ('home-mainzilia.php' == basename($page_template)) {
        $template = WP_PLUGIN_DIR . '/mainzilia/home-mainzilia.php';
        return $template;
    }
}
add_filter('page_template', 'mainzilia_page_template');
add_action('wp_ajax_nopriv_mainzilia_subscribe', 'mainzilia_subscribe');
add_action('wp_ajax_mainzilia_subscribe', 'mainzilia_subscribe');

function mainzilia_subscribe()
{
    global $wpdb;
    $title = trim($_POST['title']);
    $my_page = array(
        'post_title'    => $title,
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'subscribes',
    );
    if (trim($_POST['title'])) {
        if (!$wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_type ='subscribes' and post_title = '$title'")) {
            wp_insert_post($my_page);
        }
    }


    echo wp_json_encode($my_page);
    exit;
}
