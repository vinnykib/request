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
function activate_rqt_plugin() {
	Includes\Main\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_rqt_plugin' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_rqt_plugin() {
	Includes\Main\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_rqt_plugin' );

/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists( 'Includes\\Init' ) ) {
	Includes\Init::register_services();
}

//Create Services Custom Post type
// add_action( 'init', 'services_post_type' );

// function services_post_type() {
//     $args = array(
//         'name' => __( 'Services' ),
//         'singular_name' => __( 'Service' )
//     );

//     register_post_type( 'service_cpt',
//         array(
//             'labels' => $args, 
//             'public' => false,
//             'has_archive' => false,
//             'show_ui' => false,
//             'show_in_admin_bar' => false,
//             'supports' => array('title', 'editor'),
//             'taxonomies'    => array('service_taxonomy')
//         )
//     );
// }

// // Add services taxonomy
// add_action( 'init', 'services_taxonomy', 0 );

// function services_taxonomy() {
//     $args = array(
//         'name' => _x( 'Services', 'taxonomy general name' ),
//         'singular_name' => _x( 'Service', 'taxonomy singular name' ),
//         'menu_name' => __( 'Services' ),
//         'all_items' => __( 'All Services' ),
//         'edit_item' => __( 'Edit Service' ), 
//         'update_item' => __( 'Update Service' ),
//         'add_new_item' => __( 'Add New Service' ),
//         'new_item_name' => __( 'New Service' ),
//         'not_found'          => __( 'No Services found' ),
//     );

//     register_taxonomy('service_taxonomy','requests',array(
//         'labels' => $args,
//         'query_var'  => true,
//         'hierarchical' => false,
//         'show_ui' => true,
//         'show_admin_column' => false,
//         'rewrite' => true
//         ));
        
// }

//Highlight Service menu

// add_action('parent_file', 'highlight_service_menu');

// function highlight_service_menu($parent_file) {
//     global $current_screen;

//     // Check if it's the desired taxonomy screen
//     if ($current_screen->taxonomy == 'service_taxonomy') {
//         $parent_file = 'requests';
//     }

//     return $parent_file;
// }

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




function rqt_enqueue_script() {   
    // Enqueue the WordPress color picker style and script
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );

    // Enqueue custom scripts and styles
    wp_enqueue_style( 'rqtpluginstyle', plugin_dir_url( __FILE__ ) . 'assets/css/css.css' );
    wp_enqueue_script( 'rqtcalendarscript', plugin_dir_url( __FILE__ ) . 'assets/js/calendar.js', array( 'jquery' ), false, true );
    wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'assets/js/js.js', array( 'jquery', 'wp-color-picker' ), false, true );


    wp_localize_script('rqtcalendarscript', 'calendarSettings', array(
        'disableDays' => array(
            'sunday'    => get_option('sunday'),
            'monday'    => get_option('monday'),
            'tuesday'   => get_option('tuesday'),
            'wednesday' => get_option('wednesday'),
            'thursday'  => get_option('thursday'),
            'friday'    => get_option('friday'),
            'saturday'  => get_option('saturday'),
            'start_day'  => get_option('start_day'),
            'end_day'  => get_option('end_day'),
        )
    ));
}
add_action('wp_enqueue_scripts', 'rqt_enqueue_script');




/***
 * 
 * Color change
 * 
 */


function custom_color_changer_settings_init() {
    // Register settings for various colors and dimensions
    register_setting('custom_color_changer_settings', 'custom_color_changer_calendar_header_color');
    register_setting('custom_color_changer_settings', 'custom_color_changer_calendar_header_text_color');
    register_setting('custom_color_changer_settings', 'custom_color_changer_week_header_color');
    register_setting('custom_color_changer_settings', 'custom_color_changer_week_header_text_color');
    register_setting('custom_color_changer_settings', 'custom_color_changer_calendar_body_color');
    register_setting('custom_color_changer_settings', 'custom_color_changer_calendar_body_text_color');
    register_setting('custom_color_changer_settings', 'custom_color_changer_today_background_color');
    register_setting('custom_color_changer_settings', 'custom_color_changer_today_text_color');
    register_setting('custom_color_changer_settings', 'custom_color_changer_calendar_hover_color');
    register_setting('custom_color_changer_settings', 'custom_color_changer_calendar_hover_text_color');
    register_setting('custom_color_changer_settings', 'custom_color_changer_calendar_header_height');
    register_setting('custom_color_changer_settings', 'custom_color_changer_calendar_height');
    register_setting('custom_color_changer_settings', 'custom_color_changer_calendar_width');

    // Add sections for settings
    add_settings_section(
        'custom_color_changer_section',
        __('Color Settings', 'custom-color-changer'),
        'custom_color_changer_section_callback',
        'custom_color_changer'
    );

    // Add fields for calendar header color
    add_settings_field(
        'custom_color_changer_calendar_header_color',
        __('Calendar Header Color', 'custom-color-changer'),
        'custom_color_changer_calendar_header_color_callback',
        'custom_color_changer',
        'custom_color_changer_section'
    );

    // Add fields for calendar header text color
    add_settings_field(
        'custom_color_changer_calendar_header_text_color',
        __('Calendar Header Text Color', 'custom-color-changer'),
        'custom_color_changer_calendar_header_text_color_callback',
        'custom_color_changer',
        'custom_color_changer_section'
    );

    // Add fields for week header color
    add_settings_field(
        'custom_color_changer_week_header_color',
        __('Week Header Color', 'custom-color-changer'),
        'custom_color_changer_week_header_color_callback',
        'custom_color_changer',
        'custom_color_changer_section'
    );

    // Add fields for week header text color
    add_settings_field(
        'custom_color_changer_week_header_text_color',
        __('Week Header Text Color', 'custom-color-changer'),
        'custom_color_changer_week_header_text_color_callback',
        'custom_color_changer',
        'custom_color_changer_section'
    );

    // Add fields for calendar body background color
    add_settings_field(
        'custom_color_changer_calendar_body_color',
        __('Calendar Body Background Color', 'custom-color-changer'),
        'custom_color_changer_calendar_body_color_callback',
        'custom_color_changer',
        'custom_color_changer_section'
    );

    // Add fields for calendar body text color
    add_settings_field(
        'custom_color_changer_calendar_body_text_color',
        __('Calendar Body Text Color', 'custom-color-changer'),
        'custom_color_changer_calendar_body_text_color_callback',
        'custom_color_changer',
        'custom_color_changer_section'
    );

    // Add fields for today's background color
    add_settings_field(
        'custom_color_changer_today_background_color',
        __('Today\'s Background Color', 'custom-color-changer'),
        'custom_color_changer_today_background_color_callback',
        'custom_color_changer',
        'custom_color_changer_section'
    );

    // Add fields for today's text color
    add_settings_field(
        'custom_color_changer_today_text_color',
        __('Today\'s Text Color', 'custom-color-changer'),
        'custom_color_changer_today_text_color_callback',
        'custom_color_changer',
        'custom_color_changer_section'
    );

    // Add fields for calendar hover color
    add_settings_field(
        'custom_color_changer_calendar_hover_color',
        __('Calendar Hover Color', 'custom-color-changer'),
        'custom_color_changer_calendar_hover_color_callback',
        'custom_color_changer',
        'custom_color_changer_section'
    );

    // Add fields for calendar hover text color
    add_settings_field(
        'custom_color_changer_calendar_hover_text_color',
        __('Calendar Hover Text Color', 'custom-color-changer'),
        'custom_color_changer_calendar_hover_text_color_callback',
        'custom_color_changer',
        'custom_color_changer_section'
    );

    // Add fields for calendar height
    add_settings_field(
        'custom_color_changer_calendar_height',
        __('Calendar Height (px)', 'custom-color-changer'),
        'custom_color_changer_calendar_height_callback',
        'custom_color_changer',
        'custom_color_changer_section'
    );

    // Add fields for calendar width
    add_settings_field(
        'custom_color_changer_calendar_width',
        __('Calendar Width (px)', 'custom-color-changer'),
        'custom_color_changer_calendar_width_callback',
        'custom_color_changer',
        'custom_color_changer_section'
    );

       // Add fields for calendar header height
       add_settings_field(
        'custom_color_changer_calendar_header_height',
        __('Calendar Header Height (px)', 'custom-color-changer'),
        'custom_color_changer_calendar_header_height_callback',
        'custom_color_changer',
        'custom_color_changer_section'
    );

}
add_action('admin_init', 'custom_color_changer_settings_init');

// Callback for the settings section
function custom_color_changer_section_callback() {
    echo __('Select your colors and dimensions for the plugin.', 'custom-color-changer');
}

// Callback for calendar body background color field
function custom_color_changer_calendar_body_color_callback() {
    $calendar_body_color = get_option('custom_color_changer_calendar_body_color', '#FFFFFF');
    echo '<input type="text" id="custom_color_changer_calendar_body_color" name="custom_color_changer_calendar_body_color" value="' . esc_attr($calendar_body_color) . '" class="my-color-field" data-default-color="#FFFFFF" />';
}

// Callback for calendar body text color field
function custom_color_changer_calendar_body_text_color_callback() {
    $calendar_body_text_color = get_option('custom_color_changer_calendar_body_text_color', '#000000');
    echo '<input type="text" id="custom_color_changer_calendar_body_text_color" name="custom_color_changer_calendar_body_text_color" value="' . esc_attr($calendar_body_text_color) . '" class="my-color-field" data-default-color="#000000" />';
}

// Callback for calendar header color field
function custom_color_changer_calendar_header_color_callback() {
    $calendar_header_color = get_option('custom_color_changer_calendar_header_color', '#F0F0F0');
    echo '<input type="text" id="custom_color_changer_calendar_header_color" name="custom_color_changer_calendar_header_color" value="' . esc_attr($calendar_header_color) . '" class="my-color-field" data-default-color="#F0F0F0" />';
}

// Callback for calendar header text color field
function custom_color_changer_calendar_header_text_color_callback() {
    $calendar_header_text_color = get_option('custom_color_changer_calendar_header_text_color', '#000000');
    echo '<input type="text" id="custom_color_changer_calendar_header_text_color" name="custom_color_changer_calendar_header_text_color" value="' . esc_attr($calendar_header_text_color) . '" class="my-color-field" data-default-color="#000000" />';
}

// Callback for week header color field
function custom_color_changer_week_header_color_callback() {
    $week_header_color = get_option('custom_color_changer_week_header_color', '#E0E0E0');
    echo '<input type="text" id="custom_color_changer_week_header_color" name="custom_color_changer_week_header_color" value="' . esc_attr($week_header_color) . '" class="my-color-field" data-default-color="#E0E0E0" />';
}

// Callback for week header text color field
function custom_color_changer_week_header_text_color_callback() {
    $week_header_text_color = get_option('custom_color_changer_week_header_text_color', '#000000');
    echo '<input type="text" id="custom_color_changer_week_header_text_color" name="custom_color_changer_week_header_text_color" value="' . esc_attr($week_header_text_color) . '" class="my-color-field" data-default-color="#000000" />';
}

// Callback for today's background color field
function custom_color_changer_today_background_color_callback() {
    $today_background_color = get_option('custom_color_changer_today_background_color', '#FFDD00');
    echo '<input type="text" id="custom_color_changer_today_background_color" name="custom_color_changer_today_background_color" value="' . esc_attr($today_background_color) . '" class="my-color-field" data-default-color="#FFDD00" />';
}

// Callback for today's text color field
function custom_color_changer_today_text_color_callback() {
    $today_text_color = get_option('custom_color_changer_today_text_color', '#000000');
    echo '<input type="text" id="custom_color_changer_today_text_color" name="custom_color_changer_today_text_color" value="' . esc_attr($today_text_color) . '" class="my-color-field" data-default-color="#000000" />';
}

// Callback for calendar header height field
function custom_color_changer_calendar_header_height_callback() {
    $calendar_header_height = get_option('custom_color_changer_calendar_header_height', '50');
    echo '<input type="number" id="custom_color_changer_calendar_header_height" name="custom_color_changer_calendar_header_height" value="' . esc_attr($calendar_header_height) . '" />';
}

// Callback for calendar hover color field
function custom_color_changer_calendar_hover_color_callback() {
    $calendar_hover_color = get_option('custom_color_changer_calendar_hover_color', '#FFD700');
    echo '<input type="text" id="custom_color_changer_calendar_hover_color" name="custom_color_changer_calendar_hover_color" value="' . esc_attr($calendar_hover_color) . '" class="my-color-field" data-default-color="#FFD700" />';
}

// Callback for calendar hover text color field
function custom_color_changer_calendar_hover_text_color_callback() {
    $calendar_hover_text_color = get_option('custom_color_changer_calendar_hover_text_color', '#000000');
    echo '<input type="text" id="custom_color_changer_calendar_hover_text_color" name="custom_color_changer_calendar_hover_text_color" value="' . esc_attr($calendar_hover_text_color) . '" class="my-color-field" data-default-color="#000000" />';
}

// Callback for calendar height field
function custom_color_changer_calendar_height_callback() {
    $calendar_height = get_option('custom_color_changer_calendar_height', '400');
    echo '<input type="number" id="custom_color_changer_calendar_height" name="custom_color_changer_calendar_height" value="' . esc_attr($calendar_height) . '" />';
}

// Callback for calendar width field
function custom_color_changer_calendar_width_callback() {
    $calendar_width = get_option('custom_color_changer_calendar_width', '600');
    echo '<input type="number" id="custom_color_changer_calendar_width" name="custom_color_changer_calendar_width" value="' . esc_attr($calendar_width) . '" />';
}

// Apply styles for various colors and dimensions on the front end
function custom_color_changer_apply_styles() {
    $calendar_body_color = get_option('custom_color_changer_calendar_body_color', '#FFFFFF');
    $calendar_body_text_color = get_option('custom_color_changer_calendar_body_text_color', '#000000');
    $calendar_header_color = get_option('custom_color_changer_calendar_header_color', '#F0F0F0');
    $calendar_header_text_color = get_option('custom_color_changer_calendar_header_text_color', '#000000');
    $week_header_color = get_option('custom_color_changer_week_header_color', '#E0E0E0');
    $week_header_text_color = get_option('custom_color_changer_week_header_text_color', '#000000');
    $today_background_color = get_option('custom_color_changer_today_background_color', '#FFDD00');
    $today_text_color = get_option('custom_color_changer_today_text_color', '#000000');
    $calendar_header_height = get_option('custom_color_changer_calendar_header_height', '50');
    $calendar_height = get_option('custom_color_changer_calendar_height', '400');
    $calendar_width = get_option('custom_color_changer_calendar_width', '600');
    $calendar_hover_color = get_option('custom_color_changer_calendar_hover_color', '#FFD700');
    $calendar_hover_text_color = get_option('custom_color_changer_calendar_hover_text_color', '#000000');

    $custom_css = "
        .calendar-wrapper {
            background-color: {$calendar_body_color};
            color: {$calendar_body_text_color};
            width: {$calendar_width}px;
        }
        #calendar table {
            height: {$calendar_height}px;
        }
        #calendar-header {
            background-color: {$calendar_header_color};
            color: {$calendar_header_text_color};
            height: {$calendar_header_height}px;
        }
        #calendar table th {
            background-color: {$week_header_color};
            color: {$week_header_text_color};
        }
        #calendar .day.today {
            background-color: {$today_background_color};
            color: {$today_text_color};
        }
        #calendar .day:hover {
            background-color: {$calendar_hover_color};
            color: {$calendar_hover_text_color};
        }
    ";

    wp_register_style('custom-color-changer-style', false);
    wp_enqueue_style('custom-color-changer-style');
    wp_add_inline_style('custom-color-changer-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'custom_color_changer_apply_styles');

// Enqueue the color picker script and style
function custom_color_changer_enqueue_scripts($hook) {
    if ($hook !== 'settings_page_custom_color_changer') {
        return;
    }

    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('custom-color-changer-script', plugin_dir_url(__FILE__) . 'custom-color-changer.js', array('wp-color-picker'), false, true);
}
add_action('admin_enqueue_scripts', 'custom_color_changer_enqueue_scripts');




/**
 * 
 * 
 * Start of woocommerce code
 * 
 * 
*/
// Add custom checkbox to product type options
add_filter('product_type_options', 'add_request_plugin_product_checkbox');
function add_request_plugin_product_checkbox($options) {
    $options['request_plugin_product'] = array(
        'id' => '_request_plugin_product',
        'wrapper_class' => 'show_if_simple show_if_variable',
        'label' => __('Request Plugin Product', 'woocommerce'),
        'description' => __('Check this box to include this product as a request plugin product.', 'woocommerce'),
        'default' => 'no',
    );
    return $options;
}

// Save the custom checkbox field value
add_action('woocommerce_process_product_meta', 'save_request_plugin_product_checkbox');
function save_request_plugin_product_checkbox($post_id) {
    $request_plugin_product = isset($_POST['_request_plugin_product']) ? 'yes' : 'no';
    update_post_meta($post_id, '_request_plugin_product', $request_plugin_product);
}

// Add custom fields to the product data metabox for variations
add_action('woocommerce_product_after_variable_attributes', 'add_custom_fields_to_variants', 10, 3);
function add_custom_fields_to_variants($loop, $variation_data, $variation) {
    echo '<div class="options_group">';
    
    woocommerce_wp_text_input( 
        array( 
            'id'          => 'custom_hours_field[' . $loop . ']', 
            'label'       => __( 'Hours', 'woocommerce' ), 
            'placeholder' => 'e.g. 1 hour',
            'desc_tip'    => 'true',
            'description' => __( 'Enter the number of hours for this variant.', 'woocommerce' ),
            'value'       => get_post_meta($variation->ID, 'custom_hours_field', true)
        )
    );

    woocommerce_wp_text_input( 
        array( 
            'id'          => 'custom_minutes_field[' . $loop . ']', 
            'label'       => __( 'Minutes', 'woocommerce' ), 
            'placeholder' => 'e.g. 30 minutes',
            'desc_tip'    => 'true',
            'description' => __( 'Enter the number of minutes for this variant.', 'woocommerce' ),
            'value'       => get_post_meta($variation->ID, 'custom_minutes_field', true)
        )
    );
    
    echo '</div>';
}

// Save custom fields for variations
add_action('woocommerce_save_product_variation', 'save_custom_fields_on_variants', 10, 2);
function save_custom_fields_on_variants($variation_id, $i) {
    if (isset($_POST['custom_hours_field'][$i])) {
        update_post_meta($variation_id, 'custom_hours_field', sanitize_text_field($_POST['custom_hours_field'][$i]));
    }

    if (isset($_POST['custom_minutes_field'][$i])) {
        update_post_meta($variation_id, 'custom_minutes_field', sanitize_text_field($_POST['custom_minutes_field'][$i]));
    }
}


// Settings for selected product
function register_custom_settings() {
    register_setting('custom_settings_group', 'selected_product_id');

    add_settings_section(
        'custom_settings_section',
        __('Product Selection', 'textdomain'),
        'custom_settings_section_callback',
        'custom-settings-page'
    );

    add_settings_field(
        'selected_product_id',
        __('Select a Product', 'textdomain'),
        'selected_product_id_callback',
        'custom-settings-page',
        'custom_settings_section'
    );
}
add_action('admin_init', 'register_custom_settings');

function custom_settings_section_callback() {
    echo '<p>' . __('Select a product to save as a setting.', 'textdomain') . '</p>';
}



function selected_product_id_callback() {
    $selected_product_id = get_option('selected_product_id', '');
    
    $args = array(
        'post_type' => 'product',
        'meta_query' => array(
            array(
                'key' => '_request_plugin_product',
                'value' => 'yes',
                'compare' => '=',
            ),
        ),
    );

    $query = new \WP_Query($args);

    if ($query->have_posts()) {
        echo '<select name="selected_product_id" id="selected_product_id">';
        echo '<option value="">' . __('Select a product', 'textdomain') . '</option>'; // Initial option
        while ($query->have_posts()) {
            $query->the_post();
            $product_id = get_the_ID();
            $product_title = get_the_title();
            $product = wc_get_product($product_id);
            
            if ($product->is_type('variable')) {
                // Get all variation prices
                $variation_prices = $product->get_variation_prices();
                $min_price = min($variation_prices['price']);
                $max_price = max($variation_prices['price']);
                
                $product_price = wc_price($min_price) . ' - ' . wc_price($max_price);
            } else {
                $product_price = wc_price($product->get_price());
            }

            $selected = ($product_id == $selected_product_id) ? 'selected' : '';
            echo '<option value="' . esc_attr($product_id) . '" ' . $selected . '>' . esc_html($product_title) . ' - ' . $product_price . '</option>';
        }
        echo '</select>';
        wp_reset_postdata();
    } else {
        echo '<p>' . __('No products available', 'textdomain') . '</p>';
    }
}

