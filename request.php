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
