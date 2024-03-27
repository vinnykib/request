        <div class="wrapper">

        <div class="rqt-panel-head">
        <h3>Add Request</h3>
    </div>
      <header>
        
        <div class="header" id="header">
          <span id="prev" class="material-symbols-rounded"><</span>
          <button id="prevMonth">Previous Month</button>
          <button id="nextMonth">Next Month</button>
          <p class="current-date"></p>
          <span id="next" class="material-symbols-rounded">></span>
        </div>
      </header>

                
      
      <div id="calendar"></div>
      
</div>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>Some text in the Modal..</p>


    <form action="" method="post">

        <label for="name">Full name:</label><br>
        <input type="text" class="name" name="name" required><br>

        <label for="email">Email:</label><br>
        <input type="email" class="email" name="email" required><br>

        <label for="phone">Phone Number:</label><br>
        <input type="number" class="phone" name="phone"><br>

        <label for="description">Description:</label><br>
        <textarea class="description" name="description" ></textarea><br><br>

        <input type="hidden" id="start-time" name="rqt_start_time">
        <input type="hidden" id="end-time" name="rqt_end_time">            

        <input type="hidden" id="request_date" name="request_date">

        <input type="submit" value="Save"> <br><br>


    </form>

    </div>
    </div>

  <?php

add_action('init', 'submit_post');

//submit post
function submit_post() {

if (isset($_POST['name']) && isset($_POST['email'])) {

  $user_name = sanitize_user($_POST['name']);
  $user_email = sanitize_email($_POST['email']);
  $phone = sanitize_text_field($_POST['phone']);
  $description = sanitize_text_field($_POST['description']);

  $rqt_start_time = sanitize_text_field($_POST['rqt_start_time']);
  $rqt_end_time = sanitize_text_field($_POST['rqt_end_time']);

  $request_date = sanitize_text_field($_POST['request_date']);


  $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );

  $user = wp_create_user($user_name, $random_password, $user_email);

  if (!is_wp_error($user)) {

  $user_id = new WP_User($user);

   // Assign a role to the user
  $user_id->remove_role('subscriber');
  $user_id->add_role('request_customer');

  // Update user meta data
  update_user_meta($user, 'phone', $phone);

  if($user_id){

  $new_post = array(
    'post_title' => 'New Request',
    'post_content' => $description,
    'post_type' => 'service_cpt',
    'post_author' => $user_id->ID,
    'post_status' => 'draft'
   );
   
   $post_id = wp_insert_post($new_post);
   
   if($post_id){

    // Update post meta data
    update_post_meta( $post_id, 'rqt_start_time', $rqt_start_time );
    update_post_meta( $post_id, 'rqt_end_time', $rqt_end_time );
    update_post_meta( $post_id, 'request_date', $request_date );
    

    echo "Request added successfully with the ID of ".$post_id." and user id of ".$user_id->ID;

   } else {
    echo "Error, Request not created";
   }

  }


  }

  else {
      // An error occurred
      $error_message = $user->get_error_message();
      echo $error_message;
  }

    
  }
}


