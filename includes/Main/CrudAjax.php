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
    }



    public function approve_request()
    {
        if ( ! DOING_AJAX || ! check_ajax_referer('approve-nonce', 'nonce') ) {
            echo "Just an error";
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

    
    
        
            
