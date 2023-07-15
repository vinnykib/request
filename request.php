<?php
/*
Plugin name: Request
Description: Simple and awesome plugin to allow your clients request services.
Version: 1.0.0
Plugin Author: Vinny
Plugin URI: http://www.requestplugin.com
Text Domain: request
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php' ;
}

/**
 * The code that runs during plugin activation
 */
function activate_alecaddd_plugin() {
	Includes\Main\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_alecaddd_plugin' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_alecaddd_plugin() {
	Includes\Main\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_alecaddd_plugin' );

/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists( 'Includes\\Init' ) ) {
	Includes\Init::register_services();
}



//Create Services Custom Post type
add_action( 'init', 'services_post_type' );

function services_post_type() {
    $args = array(
        'name' => __( 'Services' ),
        'singular_name' => __( 'Service' )
    );

    register_post_type( 'service_cpt',
        array(
            'labels' => $args, 
            'public' => true,
            'supports' => array('title', 'editor'),
            'has_archive' => true,
            'show_ui' => true,
            'show_in_menu' => 'requests',
            'taxonomies'    => array('service_taxonomy')
        )
    );
}

// Add services taxonomy
add_action( 'init', 'services_taxonomy', 0 );

function services_taxonomy() {
    $args = array(
        'name' => _x( 'Services', 'taxonomy general name' ),
        'singular_name' => _x( 'Service', 'taxonomy singular name' ),
        'menu_name' => __( 'Services' ),
        'all_items' => __( 'All Services' ),
        'edit_item' => __( 'Edit Service' ), 
        'update_item' => __( 'Update Service' ),
        'add_new_item' => __( 'Add New Service' ),
        'new_item_name' => __( 'New Service' ),
        'not_found'          => __( 'No Services found' ),
    );

    register_taxonomy('service_taxonomy','requests',array(
        'labels' => $args,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_admin_column' => false,
        ));
        
}

//Highlight Service menu
add_filter('parent_file', 'highlightTaxoSubMenu');

function highlightTaxoSubMenu($parent_file){
    global $submenu_file, $current_screen, $pagenow;
    if ($current_screen->post_type == 'service_cpt') {
        if ( $pagenow == 'edit-tags.php' && $current_screen->taxonomy == 'service_taxonomy' ) {
            $submenu_file = 'edit-tags.php?taxonomy=service_taxonomy&post_type=service_cpt';
        }
        $parent_file = 'menu_slug';//your parent menu slug
    }
    return $parent_file;
}

//Add custom user roles
add_action('init', 'custom_user_role');

function custom_user_role(){
    add_role(
        'request_staff', //  System name of the role.
        __( 'Request Staff'  ), // Display name of the role.
        array(
            'read'  => true,
            'delete_posts'  => true,
            'delete_published_posts' => true,
            'edit_posts'   => true,
            'publish_posts' => true,
            'upload_files'  => true,
            'edit_pages'  => true,
            'edit_published_pages'  =>  true,
            'publish_pages'  => true,
            'delete_published_pages' => false, // This user will NOT be able to  delete published pages.
        )
    );

    add_role( 
        'request_customer', 
        'Request Customer', 'request', 
        array(
          'read' => true,
      ));
}
