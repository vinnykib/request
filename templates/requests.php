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
            <div>
            <form action="" method="post">        
              <button class="rqt-export-btn">Export to CSV</button>
            <input type="hidden" name="rqt_csv" value="1">
            </form>
            </div>
      <thead>
      <tr>
          <th>Name</th>
          <th>Date</th>
          <th>Time</th>
          <th>Status</th>
      </tr>
      </thead>

      <tbody>




      <?php 
      
       

        while($post_query->have_posts() ) : 
        
        $post_query->the_post();
            
            ?>
            <tr>
            <td>
            <?php the_title(); ?>
            </td>
            <td>
            <?php the_date(); ?>
            </td>
            <td>
            <?php the_time(); ?>
            </td>
            <td>
            <?php echo 'Pending'; ?>
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
