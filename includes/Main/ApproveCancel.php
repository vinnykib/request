<?php

/**
 * @package  Request
 */

namespace Includes\Main;

use Includes\Main\Main;

class ApproveCancel extends Main
{
    public function register()
    {
        add_action('wp_ajax_approve_request', array($this, 'approve_request'));
        add_action('wp_ajax_cancel_request', array($this, 'cancel_request'));
        // add_action('wp_ajax_nopriv_cancel_request', array($this, 'cancel_request'));
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


    public function cancel_request()
    {

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


}
