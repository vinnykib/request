<div class="rqt-container">
    <div class="rqt-panel-head">
        <h3>Requests</h3>
    </div>
    <div class="rqt-panel-body">
      
      <?php

// $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

      $paged = isset( $_GET['paged'] ) ? $_GET['paged'] : 1; 

       $args = array(
        'post_type' => 'service_cpt',
        'posts_per_page' => 10,
        'paged' => $paged
    );

    $post_query = new WP_Query($args);  
    if($post_query->have_posts() ) :

      ?>

      
<table class="rqt-table">
      <thead>
      <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Date</th>
          <th>Time</th>
          <th>Status</th>
          <th>Actions</th>
      </tr>
      </thead>

      <tbody>

      <?php       


        while($post_query->have_posts() ) : 
        
        $post_query->the_post();
        global $post;  

        $request_user_id = $post->post_author;
        $user_id = get_userdata($request_user_id);

 

        $request_date = get_post_meta( $post->ID,'request_date',true);

        $dateTimestamp = strtotime($request_date);
        $formattedDate = date("l, F j, Y", $dateTimestamp);

        $rqt_start_time = get_post_meta( $post->ID,'rqt_start_time',true);
        $rqt_end_time = get_post_meta( $post->ID,'rqt_end_time',true);
            
            ?>
            <tr>
            <td>
            <?php echo $user_id->display_name; ?>
            </td>
            <td>
            <?php echo $user_id->user_email; ?>
            </td>
            <td data-postdate="<?php echo $request_date; ?>">
            <?php echo $formattedDate; ?>
            </td>
            <td>
            <?php echo '<span>' . $rqt_start_time . ' </span> - ' . '<span>' . $rqt_end_time. '</span>';  ?>
            </td>
            <?php
            if($post->post_status=='publish'):
              echo '<td>Approved</td>';
            elseif($post->post_status=='pending'):
              echo '<td>Pending</td>';
            else:
                echo '<td>Cancelled</td>';
            endif;
            ?>
            <td>
          <?php
             
            if (isset($post->ID)) {   

              // Edit         
              echo '<button type="submit" class="updateButton" data-postid="'. $post->ID .'">Edit</button>';

              // Delete
              echo '
              <form id="deleteForm" method="post" data-url="' . admin_url("admin-ajax.php"). '">

                <button type="submit" class="deleteButton" data-postid="'. $post->ID .'">Delete</button>

                <input type="hidden" name="delete_id" id="delete-id"> 
        
                <input type="hidden" name="action" value="delete_request"> 
        
                <input type="hidden" name="nonce" value="'. wp_create_nonce('delete-nonce') .'">

              </form>';

            }

          ?>

          </td>
            </tr>
                
            <?php
           
          endwhile; 
 
          ?> 
      </tbody>      

      <!-- Request table pagination -->
      </table>

      <?php

      echo '<nav aria-label="Page navigation">';
        echo '<ul class="pagination">';
        echo '<li class="page-item">';
          echo paginate_links( array(
            'base' => 'admin.php?page=requests&paged=%#%',
            'total' => $post_query->max_num_pages,
            'current' => $paged
        ));
        echo '</li>';     
        echo '</ul>';
        echo '</nav>';


        ?>



      <?php else: ?>
      <div class="no-apt">
      <h3>There are no Requests</h3>
      </div>
      <?php endif; ?>

      
  </div>

<!-- The Modal -->
<div id="update-modal" class="rqt-modal">

<!-- Modal content -->
<div class="rqt-modal-content">
  <span class="close">&times;</span>
  <p>Update details..</p>

  <form method="post" id="update-form" data-url="<?php echo admin_url('admin-ajax.php'); ?>">

      <label for="name">Full name:</label><br>
      <input type="text" class="rqt-name" name="rqt-upd-name" value="" required><br>

      <label for="email">Email:</label><br>
      <input type="email" class="email" name="rqt-upd-email" required><br>

      <label for="phone">Phone Number:</label><br>
      <input type="number" class="phone" name="rqt-upd-phone"><br>

      <label for="description">Description:</label><br>
      <textarea class="description" name="rqt-upd-description"></textarea><br>

      <label for="status">Status:</label><br>
      <select name="rqt-upd-status">
        <option>Pending</option>
        <option>Cancelled</option>
        <option>Approved</option>
      </select><br>

      <label for="Start time">Start time:</label><br>
      <input type="time" id="start-time" name="rqt-upd-start-time"><br>

      <label for="End time">End time:</label><br>
      <input type="time" id="end-time" name="rqt-upd-end-time"><br>     
      
      <label for="Date">Date:</label><br>
      <input type="date" id="request_date" name="rqt-upd-request-date"><br><br>

      <button type="submit" id="editButton">Update</button>

      <input type="hidden" name="update_id" id="update-id"> 

      <input type="hidden" name="action" value="update_request"> 
       
      <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('update-nonce'); ?>">


  </form>

</div>

</div>

</div>

