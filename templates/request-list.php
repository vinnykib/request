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
            <?php echo $request_date; ?>
            </td>
            <td>
            <?php echo $rqt_start_time.' - '.$rqt_end_time; ?>
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
            echo '
            <form id="updateForm" method="post" id="edit-request">
              <input type="button" name="update_id" class="updateButton" value="Edit" data-postid="' . $post->ID . '">
            </form>';

              // Delete
              echo '
              <form id="deleteForm" method="post">
                <input type="button" name="delete_id" class="deleteButton" value="Delete" data-postid="' . $post->ID . '">
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


  <form action="" method="post" id="update-form">

      <label for="name">Full name:</label><br>
      <input type="text" class="rqt-name" name="rqt-upd-name" required><br>

      <label for="email">Email:</label><br>
      <input type="email" class="email" name="rqt-upd-email" required><br>

      <label for="phone">Phone Number:</label><br>
      <input type="number" class="phone" name="rqt-upd-phone"><br>

      <label for="description">Description:</label><br>
      <textarea class="description" name="rqt-upd-description"></textarea><br>

      <label for="status">Status:</label><br>
      <select name="rqt-upd-status">
        <option value="pending">Pending</option>
        <option value="draft">Cancelled</option>
        <option value="publish">Approved</option>
      </select><br>

      <label for="Start time">Start time:</label><br>
      <input type="time" id="start-time" name="rqt-upd-start-time"><br>

      <label for="End time">End time:</label><br>
      <input type="time" id="end-time" name="rqt-upd-end-time"><br>     
      
      <label for="Date">Date:</label><br>
      <input type="date" id="request_date" name="rqt-upd-request-date"><br><br>

      <input type="submit" value="Update" name="update_submit"> <br><br>


  </form>

  
</div>

</div>

</div>

<?php   


if (isset($post->ID)) {
// Add JavaScript for Ajax confirmation
  echo '
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    // Step 1: Get the list of elements by class name
    let deleteItems = document.querySelectorAll(".deleteButton");

    for (let i = 0; i < deleteItems.length; i++) {
      deleteItems[i].addEventListener("click", function() {

          let deleteId = deleteItems[i].dataset.postid;
          console.log(deleteId);
      
         var shouldDelete = confirm("Are you sure you want to delete this request?");
         if (shouldDelete) {
           // If confirmed, send an Ajax request to handle deletion
           var xhr = new XMLHttpRequest();
           xhr.open("POST", "admin.php?page=modify", true);
           xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
           xhr.onreadystatechange = function() {
             if (xhr.readyState == 4 && xhr.status == 200) {
               // Handle success if needed
               // Reload the page after deletion
               location.reload();
             }
           };
           xhr.send("delete_id=" + deleteId + "&delete_confirm=true");
         }
    
        });
      }

      //Update
      let updateItems = document.querySelectorAll(".updateButton");
      // Step 3: Add event listeners for update buttons
      for (let i = 0; i < updateItems.length; i++) {
          updateItems[i].addEventListener("click", function() {
              let updateId = updateItems[i].dataset.postid;
              console.log("Update ID:", updateId);

              // Implement your update logic here, such as showing a form or making an Ajax request to update the data
              // Get the modal
                 let updateModal = document.getElementById("update-modal");

                 // When the user clicks on the edit button, open the modal
                 updateModal.style.display = "block";
                 
                // Get the <span> element that closes the modal
                let span = document.getElementsByClassName("close")[0];
               
                 // When the user clicks on <span> (x), close the modal
                 span.onclick = function() {
                  updateModal.style.display = "none";
                 }


                 document.getElementById("update-form").addEventListener("submit", function(event) {
                  event.preventDefault(); // Prevent the default form submission
                  
                  let formData = new FormData(this);
                  formData.append("update_id", updateId); // Append update ID if necessary
                  
                  var xhr = new XMLHttpRequest();
                  xhr.open("POST", "admin.php?page=modify", true);
                  xhr.onload = function() {
                      if (xhr.status == 200) {
                          // Handle success
                          location.reload();
                      } else {
                          console.error("Error:", xhr.responseText);
                      }
                  };
                  
                  xhr.send(formData);
              });
              
                               
              
            });
      }


  });
</script>';

}
