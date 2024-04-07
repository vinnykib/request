<?php
// Delete (Submit)
if (isset($_POST["delete_id"])) {


    $id = $_POST["delete_id"];
    $delete_result = wp_delete_post($id, true);
    // You can send a response back if needed
    echo json_encode(array("success" => true));
    }

    // Update (Submit)
    if (isset($_POST["update_id"])) {
        $id = $_POST["update_id"];
            
        // Sanitize input values
        $user_name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $user_email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $description = isset($_POST['description']) ? sanitize_text_field($_POST['description']) : '';
        $rqt_start_time = isset($_POST['rqt_start_time']) ? sanitize_text_field($_POST['rqt_start_time']) : '';
        $rqt_end_time = isset($_POST['rqt_end_time']) ? sanitize_text_field($_POST['rqt_end_time']) : '';
        $request_date = isset($_POST['request_date']) ? sanitize_text_field($_POST['request_date']) : '';
    
        // Update user name and email
        $update_user_data = array(
            // 'ID' => $id,
            'user_email' => $user_email,
            'display_name' => $user_name
        );
        wp_update_user($update_user_data);
    
        // Prepare post data
        $updated_post = array(
            'ID'           => $id,
            'post_title'   => 'Updated Request',
            'post_content' => $description,
            'post_status'  => 'publish'
        );
      
        // Update the post
        $update_result = wp_update_post($updated_post);
    
        if ($update_result !== 0 && !is_wp_error($update_result)) {
            // Update post meta data
            update_post_meta($update_result, 'rqt_start_time', $rqt_start_time);
            update_post_meta($update_result, 'rqt_end_time', $rqt_end_time);
            update_post_meta($update_result, 'request_date', $request_date);
            echo "Request updated successfully";
        } else {
            echo "Error: Request not updated.";
        }
    
        wp_die();
    }
    