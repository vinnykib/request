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
            <td>
            <?php echo $formattedDate; ?>
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

          if (isset($post->ID)) {

          
            echo '<form method="post" id="approveForm" data-url="'. admin_url("admin-ajax.php").'">

                    <button type="submit" class="approveButton" data-postid="'. $post->ID .'">Approve</button>

                    <input type="hidden" name="approve_id" value="'. $post->ID .'"> 
             
                    <input type="hidden" name="action" value="approve_request"> 
             
                    <input type="hidden" name="nonce" value="'. wp_create_nonce('approve-nonce') .'">

                  </form>';
             
                 
          
            // Display the Cancel form with a button that triggers the confirmation
            echo '<form method="post" id="cancelForm" data-url="'. admin_url("admin-ajax.php") .'">
                
                <button type="submit" class="cancelButton" data-postid="'. $post->ID .'">Cancel</button>

                <input type="hidden" name="cancel_id" value="'. $post->ID .'"> 

                <input type="hidden" name="action" value="cancel_request"> 

                <input type="hidden" name="nonce" value="'. wp_create_nonce('cancel-nonce') .'">

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
