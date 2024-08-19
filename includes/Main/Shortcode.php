<?php

namespace Includes\Main;

use Includes\Main\Main;

class Shortcode extends Main
{
    public function register()
    {
        add_shortcode('request-shortcode', array($this, 'request_shortcode'));
        
        add_action('wp_ajax_submit_request', array($this, 'submit_request'));
        add_action('wp_ajax_nopriv_submit_request', array($this, 'submit_request')); // Ensure non-logged in users can also submit
        
        add_action('woocommerce_thankyou', array($this, 'create_user_and_post_after_checkout'));

        add_action('wp_ajax_price_request', array($this, 'price_request'));
        add_action('wp_ajax_nopriv_price_request', array($this, 'price_request')); // For non-logged-in users
        
    }

      /**
     * 
     * Shortcode
     * 
    */

    public function request_shortcode()
    {
        ob_start();
        
        require_once("$this->plugin_path/shortcodes/add-request.php");
    
        return ob_get_clean();
    }
    
    /**
     * 
     * Submit request
     * 
    */
    public function submit_request() {
        if ( ! DOING_AJAX || ! check_ajax_referer('submit-request-nonce', 'nonce', false) ) {
            wp_send_json_error(array('message' => 'Invalid nonce or not an AJAX request.'));
            exit;
        }
    
        if (isset($_POST['rqt-name']) && isset($_POST['rqt-email'])) {
            $user_name = sanitize_user($_POST['rqt-name']);
            $user_email = sanitize_email($_POST['rqt-email']);
            $phone = sanitize_text_field($_POST['phone']);
            $description = sanitize_textarea_field($_POST['description']);
            $rqt_start_time = sanitize_text_field($_POST['rqt_start_time']);
            $rqt_end_time = sanitize_text_field($_POST['rqt_end_time']);
            $request_date = sanitize_text_field($_POST['request_date']);
            $request_hrs = intval($_POST['request_hrs']);
            $request_mins = intval($_POST['request_mins']);
    
            // Get the selected product ID from the settings
            $selected_product_id = get_option('selected_product_id', '');
    
            $product_id = null;
            $variation_id = null;
            $rounded_hours = null;
            $rounded_minutes = null;
    
            if ($selected_product_id) {
                $product = wc_get_product($selected_product_id);
    
                if ($product) {
                    $available_variants = [];
    
                    if ($product->is_type('variable')) {
                        $variations = $product->get_available_variations();
                        foreach ($variations as $variation) {
                            $hours = intval(get_post_meta($variation['variation_id'], 'custom_hours_field', true));
                            $minutes = intval(get_post_meta($variation['variation_id'], 'custom_minutes_field', true));
    
                            $available_variants[] = array(
                                'product_id' => $selected_product_id,
                                'variation_id' => $variation['variation_id'],
                                'hours' => $hours,
                                'minutes' => $minutes,
                            );
                        }
                    } else {
                        // Handle simple product
                        $hours = intval(get_post_meta($selected_product_id, 'custom_hours_field', true));
                        $minutes = intval(get_post_meta($selected_product_id, 'custom_minutes_field', true));
    
                        $available_variants[] = array(
                            'product_id' => $selected_product_id,
                            'variation_id' => 0, // No variation for simple product
                            'hours' => $hours,
                            'minutes' => $minutes,
                        );
                    }
    
                    // Ensure that the collected variants are sorted by hours and minutes
                    usort($available_variants, function($a, $b) {
                        if ($a['hours'] == $b['hours']) {
                            return $a['minutes'] - $b['minutes'];
                        }
                        return $a['hours'] - $b['hours'];
                    });
    
                    // Function to find the next available time slot, always rounding forward
                    function find_next_variant($request_hrs, $request_mins, $available_variants) {
                        foreach ($available_variants as $variant) {
                            if ($variant['hours'] > $request_hrs || ($variant['hours'] == $request_hrs && $variant['minutes'] >= $request_mins)) {
                                return $variant;
                            }
                        }
                        return end($available_variants); // Return the last variant if no exact match is found
                    }
    
                    // Find the next available variant
                    $selected_variant = find_next_variant($request_hrs, $request_mins, $available_variants);
    
                    // If no matching variant is found, use the last available variant
                    if (!$selected_variant) {
                        $selected_variant = end($available_variants);
                    }
    
                    $product_id = $selected_variant['product_id'];
                    $variation_id = $selected_variant['variation_id'];
                    $rounded_hours = $selected_variant['hours'];
                    $rounded_minutes = $selected_variant['minutes'];
                }
            }
    
            if ($product_id) {
                // Store the form data in the session
                WC()->session->set('request_form_data', array(
                    'user_name' => $user_name,
                    'user_email' => $user_email,
                    'phone' => $phone,
                    'description' => $description,
                    'rqt_start_time' => $rqt_start_time,
                    'rqt_end_time' => $rqt_end_time,
                    'request_date' => $request_date,
                    'product_id' => $product_id,
                    'variation_id' => $variation_id,
                    'request_hrs' => $rounded_hours,
                    'request_mins' => $rounded_minutes,
                ));
    
                // Add the product to the cart
                $cart_item_data = array(
                    'rqt_start_time' => $rqt_start_time,
                    'rqt_end_time' => $rqt_end_time,
                    'request_date' => $request_date,
                    'description' => $description,
                    'request_hrs' => $rounded_hours,
                    'request_mins' => $rounded_minutes,
                );
                WC()->cart->add_to_cart($product_id, 1, $variation_id, array(), $cart_item_data);
    
                // Return the checkout URL for redirection
                wp_send_json_success(array('redirect_url' => wc_get_checkout_url()));
            } else {
                // Create the user immediately if no product is found
                $user = get_user_by('email', $user_email);
    
                if ($user) {
                    $user_id = $user->ID;
                } else {
                    $random_password = wp_generate_password(12, false);
                    $user_id = wp_create_user($user_name, $random_password, $user_email);
    
                    if (is_wp_error($user_id)) {
                        error_log('User creation error: ' . $user_id->get_error_message());
                        wp_send_json_error(array('message' => 'User creation failed: ' . $user_id->get_error_message()));
                        exit;
                    }
    
                    $user = new \WP_User($user_id);
                    $user->remove_role('subscriber');
                    $user->add_role('request_customer');
                    update_user_meta($user_id, 'phone', $phone);
                }
    
                // Create the post
                $new_post = array(
                    'post_title' => 'New Request',
                    'post_content' => $description,
                    'post_type' => 'service_cpt',
                    'post_author' => $user_id,
                    'post_status' => 'pending'
                );
                $post_id = wp_insert_post($new_post);
    
                if ($post_id) {
                    update_post_meta($post_id, 'rqt_start_time', $rqt_start_time);
                    update_post_meta($post_id, 'rqt_end_time', $rqt_end_time);
                    update_post_meta($post_id, 'request_date', $request_date);
                }
    
                // Return a success message or redirect URL if desired
                wp_send_json_success(array('message' => 'Request created successfully.'));
            }
        } else {
            wp_send_json_error(array('message' => 'Missing required fields.'));
        }
        exit;
    }    
     /**
     *
     * Create user after checkout
     * 
    */

    public function create_user_and_post_after_checkout($order_id) {
        if (!$order_id) {
            return;
        }

        $session_data = WC()->session->get('request_form_data');
        if (!$session_data) {
            return;
        }

        $user_name = sanitize_user($session_data['user_name']);
        $user_email = sanitize_email($session_data['user_email']);
        $phone = sanitize_text_field($session_data['phone']);
        $description = sanitize_textarea_field($session_data['description']);
        $rqt_start_time = sanitize_text_field($session_data['rqt_start_time']);
        $rqt_end_time = sanitize_text_field($session_data['rqt_end_time']);
        $request_date = sanitize_text_field($session_data['request_date']);

        $user = get_user_by('email', $user_email);

        if ($user) {
            $user_id = $user->ID;
        } else {
            $random_password = wp_generate_password(12, false);
            $user_id = wp_create_user($user_name, $random_password, $user_email);

            if (is_wp_error($user_id)) {
                return;
            }

            $user = new \WP_User($user_id);
            $user->remove_role('subscriber');
            $user->add_role('request_customer');

            update_user_meta($user_id, 'phone', $phone);
        }

        $new_post = array(
            'post_title' => 'New Request',
            'post_content' => $description,
            'post_type' => 'service_cpt',
            'post_author' => $user_id,
            'post_status' => 'pending'
        );
        $post_id = wp_insert_post($new_post);

        if ($post_id) {
            update_post_meta($post_id, 'rqt_start_time', $rqt_start_time);
            update_post_meta($post_id, 'rqt_end_time', $rqt_end_time);
            update_post_meta($post_id, 'request_date', $request_date);
        }

        WC()->session->__unset('request_form_data');
    }



    /**
     *
     * Get price
     * 
    */
  

    public function price_request() {
        // Check AJAX request and nonce
        if (!DOING_AJAX || !check_ajax_referer('price-request-nonce', 'nonce', false)) {
            wp_send_json_error(array('message' => 'Invalid nonce or not an AJAX request.'));
            return;
        }
    
        // Ensure that time parameters are provided
        if (isset($_POST['request_hrs']) && isset($_POST['request_mins'])) {
            $request_hrs = intval($_POST['request_hrs']);
            $request_mins = intval($_POST['request_mins']);
            
            // Get the selected product ID from the settings
            $selected_product_id = get_option('selected_product_id', '');
    
            if ($selected_product_id) {
                $product = wc_get_product($selected_product_id);
    
                if ($product) {
                    $available_variants = [];
    
                    if ($product->is_type('variable')) {
                        $variations = $product->get_available_variations();
                        foreach ($variations as $variation) {
                            $hours = intval(get_post_meta($variation['variation_id'], 'custom_hours_field', true));
                            $minutes = intval(get_post_meta($variation['variation_id'], 'custom_minutes_field', true));
    
                            $available_variants[] = array(
                                'variation_id' => $variation['variation_id'],
                                'hours' => $hours,
                                'minutes' => $minutes,
                            );
                        }
                    } else {
                        // Handle simple product
                        $hours = intval(get_post_meta($selected_product_id, 'custom_hours_field', true));
                        $minutes = intval(get_post_meta($selected_product_id, 'custom_minutes_field', true));
    
                        $available_variants[] = array(
                            'variation_id' => 0, // No variation for simple product
                            'hours' => $hours,
                            'minutes' => $minutes,
                        );
                    }
    
                    // Sort variants by hours and minutes
                    usort($available_variants, function($a, $b) {
                        if ($a['hours'] == $b['hours']) {
                            return $a['minutes'] - $b['minutes'];
                        }
                        return $a['hours'] - $b['hours'];
                    });
    
                    // Find the next available variant
                    $selected_variant = $this->find_next_variant($request_hrs, $request_mins, $available_variants);
    
                    if ($selected_variant) {
                        $variation_id = $selected_variant['variation_id'];
                        $product_id = $selected_product_id;
                        $price = 0;
                        $currency_symbol = get_woocommerce_currency_symbol();
    
                        if ($variation_id && $product->is_type('variable')) {
                            $variation = wc_get_product($variation_id);
                            $price = $variation->get_price();
                        } else {
                            $price = $product->get_price();
                        }
    
                        // Return the price and currency via AJAX
                        wp_send_json_success(array('price' => $price, 'currency' => $currency_symbol));
                    } else {
                        wp_send_json_error(array('message' => 'No matching variant found.'));
                    }
                } else {
                    wp_send_json_error(array('message' => 'Invalid product.'));
                }
            } else {
                wp_send_json_error(array('message' => 'No product selected.'));
            }
        } else {
            wp_send_json_error(array('message' => 'Missing required fields.'));
        }
        exit;
    }
    
    // Function to find the next available time slot, always rounding forward
    private function find_next_variant($request_hrs, $request_mins, $available_variants) {
        foreach ($available_variants as $variant) {
            if ($variant['hours'] > $request_hrs || ($variant['hours'] == $request_hrs && $variant['minutes'] >= $request_mins)) {
                return $variant;
            }
        }
        return end($available_variants); // Return the last variant if no exact match is found
    }
    
    
    
    
}
    
    
    
    
    
