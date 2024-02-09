<?php

    // Delete (Submit)
if (isset($_POST["delete_id"])) {


    $id = $_POST["delete_id"];
    $delete_result = wp_delete_post($id, true);
    // You can send a response back if needed
    echo json_encode(array("success" => true));
    }

    // Update (Submit)
if (isset($_POST["update_submit"])) {
    $id = $_POST["update_id"];
    $title = sanitize_text_field($_POST["update_title"]);
    $content = sanitize_text_field($_POST["update_content"]);
  
    $updated_post = array(
        'ID'           => $id,
        'post_title'   => $title,
        'post_content' => $content,
    );
  
    $update_result = wp_update_post($updated_post);
  
  }