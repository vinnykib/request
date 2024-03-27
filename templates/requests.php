<div class="rqt-container">
    <div class="rqt-panel-head">
        <h3>Requests</h3>
    </div>
    <div class="rqt-panel-body">
      
      <?php

// $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

      $paged = isset( $_GET['paged'] ) ? $_GET['paged'] : 1; 
      // $prevpage = max( ($paged - 1), 0 );
      // $nextpage = $paged + 1;


       $args = array(
        'post_type' => 'service_cpt',
        'posts_per_page' => 5,
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
            <td>
            <?php echo $request_date; ?>
            </td>
            <td>
            <?php echo $rqt_start_time.' - '.$rqt_end_time; ?>
            </td>
            <?php
            if($post->post_status=='publish'):
              echo '<td>Approved</td>';
              else:
              echo '<td>Pending</td>';
            endif;
            ?>
            <td>
          <?php

          
            echo "<form id='updateForm' method='post' id='edit-request'>
                    <input type='hidden' name='update_id' value='".$post->ID."'>
                    <input type='button' class='updateButton' name='update' value='Edit'>
                  </form>";

                  // Delete

             
if (isset($post->ID)) {
 
 
  // Display the delete form with a button that triggers the confirmation
  echo '
  <form id="deleteForm" method="post">
          <input type="hidden" name="delete_id" value="' . $post->ID . '">
          <input type="button" class="deleteButton" value="Delete" name="delete">
        </form>';


         // Add JavaScript for Ajax confirmation
 


         // Add JavaScript for Ajax confirmation
//   echo '
//   <script>
//   document.addEventListener("DOMContentLoaded", function() {
//     // Step 1: Get the list of elements by class name
//     let items = document.querySelectorAll(".deleteButton");
//     for (let i = 0; i < items.length; i++) {
//         items[i].addEventListener("click", function() {
      
//          var shouldDelete = confirm("Are you sure you want to delete this post?");
//          if (shouldDelete) {
//            // If confirmed, send an Ajax request to handle deletion
//            var xhr = new XMLHttpRequest();
//            xhr.open("POST", "admin.php?page=delete", true);
//            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//            xhr.onreadystatechange = function() {
//              if (xhr.readyState == 4 && xhr.status == 200) {
//                // Handle success if needed
//                console.log(xhr.responseText);
//              }
//            };
//            xhr.send("delete_id=' . $post->ID . '&delete_confirm=true");
//          }
    
//         });
//       }
//   });
// </script>';
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
      <!-- <nav aria-label="Page navigation">
        <ul class="pagination">
         if ($prevpage !== 0) { 
          <li class="page-item">
            <a class="page-link" href="echo 'admin.php?page=requests&paged='.$prevpage ?>" >
            <span aria-hidden="true">&laquo;</span>
            Previous
            </a>
          </li>
          
        if ($post_query->max_num_pages  > $paged) {
      
          <li class="page-item">
            <a class="page-link" href="echo 'admin.php?page=requests&paged='.$nextpage ?>">
            Next
            <span aria-hidden="true">&raquo;</span> 
            </a>
          </li>
       } 
        </ul>
      </nav> -->
    

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

      <input type="submit" value="Update"> <br><br>


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
    let items = document.querySelectorAll(".deleteButton");
    let updateButton = document.querySelectorAll("updateButton");

    for (let i = 0; i < items.length; i++) {
        items[i].addEventListener("click", function() {
      
         var shouldDelete = confirm("Are you sure you want to delete this post?");
         if (shouldDelete) {
           // If confirmed, send an Ajax request to handle deletion
           var xhr = new XMLHttpRequest();
           xhr.open("POST", "admin.php?page=modify", true);
           xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
           xhr.onreadystatechange = function() {
             if (xhr.readyState == 4 && xhr.status == 200) {
               // Handle success if needed
              //  console.log(xhr.responseText);
             }
           };
           xhr.send("delete_id=' . $post->ID . '&delete_confirm=true");
         }
    
        });

        
      }
  });
</script>';

}