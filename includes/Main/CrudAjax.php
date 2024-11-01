<?php

/**
 * @package  Request
 */

namespace Includes\Main;

use Includes\Main\Main;

class CrudAjax extends Main
{
    public function register()
    {
        add_action('wp_ajax_approve_request', array($this, 'approve_request'));
        add_action('wp_ajax_cancel_request', array($this, 'cancel_request'));
        add_action('wp_ajax_update_request', array($this, 'update_request'));
        add_action('wp_ajax_delete_request', array($this, 'delete_request'));

        add_action('wp_ajax_save_dynamic_data', array($this, 'save_dynamic_data'));
    }

    public function save_dynamic_data() {
        error_log('Request data: ' . print_r($_POST, true));
    
        if (!isset($_POST['nonce']) || !check_ajax_referer('selected-dates-nonce', 'nonce', false)) {
            error_log('Invalid nonce');
            wp_send_json_error(array('message' => 'Invalid nonce.'));
            wp_die();
        }
    
        // Check if 'selected_dates' is set and not empty
        if (isset($_POST['selected_dates']) && !empty($_POST['selected_dates'])) {
            $selected_dates = $_POST['selected_dates'];
            $stored_dates = array_map('sanitize_text_field', $selected_dates);
    
            error_log('Saving dates: ' . print_r($stored_dates, true));
    
            $result = update_option('dynamic_dates', $stored_dates);
    
            if ($result !== false) {
                wp_send_json_success('Data saved successfully!');
            } else {
                error_log('Failed to save data');
                wp_send_json_error(array('message' => 'Failed to save data.'));
            }
        } else {
            // If 'selected_dates' is not set or is empty, save null
            $stored_dates = null;
    
            error_log('Saving dates: ' . print_r($stored_dates, true));
    
            $result = update_option('dynamic_dates', $stored_dates);
    
            if ($result !== false) {
                wp_send_json_success('Data saved successfully!');
            } else {
                error_log('Failed to save data');
                wp_send_json_error(array('message' => 'Failed to save data.'));
            }
        }
    
        wp_die();
    }
    
    

    

    public function approve_request()
    {
          // Check if the request is an AJAX call and if the nonce is valid
          if ( ! DOING_AJAX || ! check_ajax_referer('approve-nonce', 'nonce') ) {
            echo json_encode(array('error' => 'Invalid AJAX request'));
            wp_die();
        }

        if (isset($_POST["approve_id"])) {
            $id = $_POST["approve_id"];
            // Update post data
            $updated_post = array(
                'ID'           => $id,
                'post_status' => 'publish',
            );
            // Update the post in the database  
            $update_result = wp_update_post($updated_post);

            if ($update_result !== 0) {
              // Get post author's email
$post = get_post($id);
$post_author_email = get_the_author_meta('user_email', $post->post_author);
$post_author = get_the_author_meta('display_name', $post->post_author);

$request_date = get_post_meta($post->ID, 'request_date', true);
$dateTimestamp = strtotime($request_date);
$formattedDate = date("l, F j, Y", $dateTimestamp);

$rqt_start_time = get_post_meta($post->ID, 'rqt_start_time', true);
$rqt_end_time = get_post_meta($post->ID, 'rqt_end_time', true);

// Retrieve the plain text email template from the options table
$email_template = get_option('approve_email_textarea', '');

// Replace placeholders with actual values
$email_content_plain = str_replace(
    array('%name%', '%date%', '%time%'),
    array($post_author, $formattedDate, $rqt_start_time . ' - ' . $rqt_end_time),
    $email_template
);

// Apply styling
$email_content = '
<html>
<head>
    <style>
        .email-content {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .email-header {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .email-body {
            margin-top: 10px;
        }

    </style>
</head>
<body>
    <div class="email-content">
        <div class="email-header">Request Approval Notification</div>
        <div class="email-body">' . nl2br($email_content_plain) . '</div>
    </div>
</body>
</html>';

// Prepare and send the email
$to = $post_author_email;
$subject = get_option('approve_email_input', '');
$headers = array('Content-Type: text/html; charset=UTF-8');

wp_mail($to, $subject, $email_content, $headers);

                echo json_encode(array("success" => true));
            } else {
                echo json_encode(array("success" => false, "error" => "Failed to approve request."));
            }
        }

        wp_die();
    }


    public function cancel_request() {

        if ( ! DOING_AJAX || ! check_ajax_referer('cancel-nonce', 'nonce') ) {
            echo "Just an error";
        }

        if (isset($_POST["cancel_id"])) {
            $id = $_POST["cancel_id"];
            // Update post data
            $updated_post = array(
                'ID'           => $id,
                'post_status' => 'draft',
            );

            // Update the post in the database  
            $update_result = wp_update_post($updated_post);

            if ($update_result !== 0) {
// Get post author's email
$post = get_post($id);
$post_author_email = get_the_author_meta('user_email', $post->post_author);
$post_author = get_the_author_meta('display_name', $post->post_author);


$request_date = get_post_meta($post->ID, 'request_date', true);
$dateTimestamp = strtotime($request_date);
$formattedDate = date("l, F j, Y", $dateTimestamp);

$rqt_start_time = get_post_meta($post->ID, 'rqt_start_time', true);
$rqt_end_time = get_post_meta($post->ID, 'rqt_end_time', true);

// Retrieve the plain text email template from the options table
$email_template = get_option('cancel_email_textarea', '');

// Replace placeholders with actual values
$email_content_plain = str_replace(
    array('%name%', '%date%', '%time%'),
    array($post_author, $formattedDate, $rqt_start_time . ' - ' . $rqt_end_time),
    $email_template
);

// Apply styling
$email_content = '
<html>
<head>
    <style>
        .email-content {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .email-header {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .email-body {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="email-content">
        <div class="email-body">' . nl2br($email_content_plain) . '</div>
    </div>
</body>
</html>';

// Prepare and send the email
$to = $post_author_email;
$subject = get_option('cancel_email_input', '');
$headers = array('Content-Type: text/html; charset=UTF-8');

wp_mail($to, $subject, $email_content, $headers);


                echo json_encode(array("success" => true));
            } else {
                echo json_encode(array("success" => false, "error" => "Failed to cancel request."));
            }
        }

        wp_die();
    }

    public function update_request() {
        // Check if the request is an AJAX call and if the nonce is valid
        if ( ! DOING_AJAX || ! check_ajax_referer('update-nonce', 'nonce') ) {
            echo json_encode(array('error' => 'Invalid AJAX request'));
            wp_die();
        }
    
        // Check if required POST variables are set
        if (isset($_POST['rqt-upd-name']) && isset($_POST['rqt-upd-email'])) {
            $id = $_POST["update_id"];
            $name = $_POST['rqt-upd-name'];
            $email = $_POST['rqt-upd-email'];
            $phone = $_POST['rqt-upd-phone'];
            $description = $_POST['rqt-upd-description'];
            $status = $_POST['rqt-upd-status'];
            $rqt_start_time = $_POST['rqt-upd-start-time'];
            $rqt_end_time = $_POST['rqt-upd-end-time'];
            $request_date = $_POST['rqt-upd-request-date'];
    
            // Sanitize input values
            $user_name = sanitize_user($name);
            $user_email = sanitize_email($email);
            $phone = sanitize_text_field($phone);
            $description = sanitize_text_field($description);
            $status = sanitize_text_field($status);
    
            if ($status == "Pending") {
                $status = "pending";
            } elseif ($status == "Cancelled") {
                $status = "draft";
            } else {
                $status = "publish";
            }
    
            $rqt_start_time = sanitize_text_field($rqt_start_time);
            $rqt_end_time = sanitize_text_field($rqt_end_time);
            $request_date = sanitize_text_field($request_date);
    
            // Get post author's email
            $post = get_post($id);
            $post_author_email = get_the_author_meta('user_email', $post->post_author);
    
            // Prepare post data
            $updated_post = array(
                'ID'           => $id,
                'post_title'   => 'Updated Request',
                'post_content' => $description,
                'post_status'  => $status
            );
    
            // Update the post
            $update_result = wp_update_post($updated_post);
    
            // Update user data if the email matches
            if ($update_result) {
                $userdata = array(
                    'ID'         => $post->post_author,
                    'user_email' => $user_email,
                    'display_name' => $user_name,
                );
    
                $update_user_result = wp_update_user($userdata);
            }
    
            if ($update_result) {
                // Update post meta data
                update_post_meta($id, 'rqt_start_time', $rqt_start_time);
                update_post_meta($id, 'rqt_end_time', $rqt_end_time);
                update_post_meta($id, 'request_date', $request_date);
    
                echo json_encode(array('success' => 'Request updated successfully'));
            } else {
                echo json_encode(array('error' => 'Error: Request not updated.'));
            }
        } else {
            echo json_encode(array('error' => 'Error: Required fields are missing.'));
        }
    
        wp_die();
    }
    



public function delete_request() {

    if ( ! DOING_AJAX || ! check_ajax_referer('delete-nonce', 'nonce') ) {
        echo "Just an error";
    }

    if (isset($_POST["delete_id"])) {
        $id = $_POST["delete_id"];
        $delete_result = wp_delete_post($id, true);
        // You can send a response back if needed
        echo json_encode(array("success" => true));
    }

        wp_die();
    }    
    

}

    
    
        
            
