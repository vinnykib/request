<?php
// Delete (Submit)
if (isset($_POST["delete_id"])) 
{
    $id = $_POST["delete_id"];
    $delete_result = wp_delete_post($id, true);
    // You can send a response back if needed
    echo json_encode(array("success" => true));
}


//Update
function update_request_post($id, $name, $email, $phone, $description, $status, $rqt_start_time, $rqt_end_time, $request_date) {
    // Sanitize input values
    $user_name = sanitize_user($name);
    $user_email = sanitize_email($email);
    $phone = sanitize_text_field($phone);
    $description = sanitize_text_field($description);
    $status = sanitize_text_field($status);
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

        return "Request updated successfully";
    } else {
        return "Error: Request not updated.";
    }
}

// Calling the function:
if (isset($_POST['rqt-upd-name']) && isset($_POST['rqt-upd-email'])) {
    $update_id = $_POST["update_id"];
    $update_result = update_request_post(
        $update_id,
        $_POST['rqt-upd-name'],
        $_POST['rqt-upd-email'],
        $_POST['rqt-upd-phone'],
        $_POST['rqt-upd-description'],
        $_POST['rqt-upd-status'],
        $_POST['rqt-upd-start-time'],
        $_POST['rqt-upd-end-time'],
        $_POST['rqt-upd-request-date']
    );
    echo $update_result;

}
