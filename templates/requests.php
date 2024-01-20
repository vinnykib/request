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
        'posts_per_page' => 10,
        'paged' => $paged
    );

    $post_query = new WP_Query($args);  
    if($post_query->have_posts() ) :

      ?>

      
<table class="rqt-table">
            <div>
            <form action="" method="post">        
              <button class="rqt-export-btn">Export to CSV</button>
            <input type="hidden" name="rqt_csv" value="1">
            </form>
            </div>
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
          
            echo "<form method='post' action=''>
                    <input type='hidden' name='update_id' value='".$post->ID."'>
                    <input type='submit' name='update' value='Update'>
                  </form>
                  <form method='post' action='' onsubmit='return confirmDelete()'>
                    <input type='hidden' name='delete_id' value='".$post->ID."'>
                    <input type='submit' name='delete' value='Delete'>
                  </form>"
          
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
</div>

<?php

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


// Delete
if (isset($_POST["delete"])) {
  $id = $_POST["delete_id"];
  $delete_result = wp_delete_post($id, true);
}


?>