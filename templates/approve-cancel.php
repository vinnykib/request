<?php
// Approve (Submit)
if (isset($_POST["approve_id"])) {
    $id = $_POST["approve_id"];
     // Update post data
     $updated_post = array(
        'ID'           => $id,
        'post_status' => 'publish',
    );
    // Update the post in the database  
    $update_result = wp_update_post($updated_post);
  
    echo json_encode(array("success" => true));    
  
  }

  // Cancel (Submit)
if (isset($_POST["cancel_id"])) {

    $id = $_POST["cancel_id"];
    // Update post data
    $updated_post = array(
        'ID'           => $id,
        'post_status' => 'draft',
    );

    // Update the post in the database  
    $update_result = wp_update_post($updated_post);
    echo json_encode(array("success" => true));

    }