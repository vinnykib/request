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
        'post_status' => array('pending'),
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
            <td>
            <?php echo $request_date; ?>
            </td>
            <td>
            <?php echo $rqt_start_time.' - '.$rqt_end_time; ?>
            </td>
            <?php
            if($post->post_status=='pending'):
              echo '<td>Pending</td>';
            endif;
            ?>
            <td>
          <?php

          
            echo '<form id="approveForm" method="post">
                    <input type="button" name="approve_id" class="approveButton" value="Approve" data-postid="'. $post->ID .'">
                  </form>';

                  // Delete

             
if (isset($post->ID)) {
 
 
  // Display the Cancel form with a button that triggers the confirmation
  echo '
  <form id="cancelForm" method="post">
       <input type="button" name="cancel_id" class="cancelButton" value="Cancel" data-postid="'. $post->ID .'">
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
      <h3>No Pending Requests</h3>
      </div>
      <?php endif; ?>

      
  </div>

<?php   



if (isset($post->ID)) {
// Add JavaScript for Ajax confirmation
  echo '
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    // Step 1: Get the list of elements by class name
    let cancelItems = document.querySelectorAll(".cancelButton");

    for (let i = 0; i < cancelItems.length; i++) {
      cancelItems[i].addEventListener("click", function() {

        
        let cancelId = cancelItems[i].dataset.postid;
        console.log(cancelId);
      
         var shouldCancel = confirm("Are you sure you want to cancel this request?");
         if (shouldCancel) {
           // If confirmed, send an Ajax request to handle deletion
           var xhr = new XMLHttpRequest();
           xhr.open("POST", "admin.php?page=approve-cancel", true);
           xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
           xhr.onreadystatechange = function() {
             if (xhr.readyState == 4 && xhr.status == 200) {
               // Handle success if needed
               // Reload the page after deletion
               location.reload();
             }
           };
           xhr.send("cancel_id=" + cancelId + "&cancel_confirm=true");
         }
    
        });

        
      }


      // Step 1: Get the list of elements by class name
      let approveItems = document.querySelectorAll(".approveButton");
  
      for (let i = 0; i < approveItems.length; i++) {
        approveItems[i].addEventListener("click", function() {

           let approveId = cancelItems[i].dataset.postid;

           var shouldApprove = confirm("Are you sure you want to Approve this request?");
           if (shouldApprove) {
             // If confirmed, send an Ajax request to handle deletion
             var xhr = new XMLHttpRequest();
             xhr.open("POST", "admin.php?page=approve-cancel", true);
             xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
             xhr.onreadystatechange = function() {
               if (xhr.readyState == 4 && xhr.status == 200) {
                 // Handle success if needed
                 // Reload the page after deletion
                 location.reload();
               }
             };
             xhr.send("approve_id=" + approveId + "&approve_confirm=true");
           }
      
          });
  
          
        }
  });
</script>';

}