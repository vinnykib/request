<?php if (is_user_logged_in()): ?>
<div class="rqt-container">
  <div class="rqt-panel-body">

    <div class="profile-tab">
      <ul class="profile-tabs-list">
        <li class="active"><a href="#tab-1">Upcoming</a></li>
        <li><a href="#tab-2">Past</a></li>
      </ul>
    </div>

    <div class="profile-tab-content">

      <!-- Upcoming Tab -->
      <div id="tab-1" class="active tab-pane">
        <div class="rqt-body-container">
          <h3>Upcoming</h3>

          <?php
          $paged = isset($_GET['paged']) ? $_GET['paged'] : 1;
          $today = date('l, F j, Y'); // Get today's date in "Friday, October 11, 2024" format
          $now_time = current_time('H:i'); // Get the current time

          // Query for upcoming posts
          $upcoming_args = array(
            'post_type' => 'service_cpt',
            'posts_per_page' => 10,
            'paged' => $paged,
            'meta_query' => array(
              'relation' => 'OR',
              array(
                'key'     => 'request_date',
                'value'   => $today,
                'compare' => '>=',
                'type'    => 'CHAR',
              ),
              array(
                'key'     => 'request_date',
                'value'   => $today,
                'compare' => '=',
                'type'    => 'CHAR',
              ),
              array(
                'key'     => 'rqt_start_time',
                'value'   => $now_time,
                'compare' => '>=',
                'type'    => 'TIME',
              )
            )
          );

          $upcoming_query = new WP_Query($upcoming_args);

          if ($upcoming_query->have_posts()) :
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
              while ($upcoming_query->have_posts()) :
                $upcoming_query->the_post();
                global $post;

                $request_user_id = $post->post_author;
                $user_id = get_userdata($request_user_id);
                $logged_in_user_id = wp_get_current_user($request_user_id);

                if ($user_id == $logged_in_user_id) :
                  $request_date_raw = get_post_meta($post->ID, 'request_date', true);
                  $request_date = date('l, F j, Y', strtotime($request_date_raw));
                  $rqt_start_time = get_post_meta($post->ID, 'rqt_start_time', true);
                  $rqt_end_time = get_post_meta($post->ID, 'rqt_end_time', true);

                  // Determine if the post belongs in "Upcoming"
                  $is_upcoming = $request_date > $today || ($request_date === $today && $rqt_start_time >= $now_time);
              ?>
                  <?php if ($is_upcoming): ?>
                  <tr>
                    <td><?php echo $user_id->display_name; ?></td>
                    <td><?php echo $user_id->user_email; ?></td>
                    <td><?php echo $request_date_raw; ?></td>
                    <td><?php echo $rqt_start_time . ' - ' . $rqt_end_time; ?></td>
                    <td>
                      <?php
                      if ($post->post_status == 'publish') :
                        echo 'Approved';
                      elseif ($post->post_status == 'pending') :
                        echo 'Pending';
                      else :
                        echo 'Cancelled';
                      endif;
                      ?>
                    </td>
                    <td class="actionButtons">
                      <?php
                      if (isset($post->ID)) {
                        echo '<button type="submit" class="updateButton" data-postid="' . $post->ID . '">Edit</button>';
                        echo '
                        <form id="deleteForm" method="post" data-url="' . admin_url("admin-ajax.php") . '">
                          <button type="submit" class="deleteButton" data-postid="' . $post->ID . '">Delete</button>
                          <input type="hidden" name="delete_id" id="delete-id">
                          <input type="hidden" name="action" value="delete_request">
                          <input type="hidden" name="nonce" value="' . wp_create_nonce('delete-nonce') . '">
                        </form>';
                        echo '<button type="submit" class="viewButton" data-postid="' . $post->ID . '">View</button>';
                      }
                      ?>
                    </td>
                  </tr>
                  <?php endif; ?>
              <?php
                endif;
              endwhile;
              ?>
            </tbody>
          </table>

          <?php
          echo '<nav aria-label="Page navigation">';
          echo '<ul class="pagination">';
          echo '<li class="page-item">';
          echo paginate_links(array(
            'base' => 'admin.php?page=requests&paged=%#%',
            'total' => $upcoming_query->max_num_pages,
            'current' => $paged
          ));
          echo '</li>';
          echo '</ul>';
          echo '</nav>';

          wp_reset_postdata(); // Reset post data after query
          else :
          ?>
            <div class="no-apt
            ">
              <h3>There are no upcoming requests</h3>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Past Tab -->
      <div id="tab-2" class="tab-pane">
        <div class="rqt-body-container">
          <h3>Past</h3>

          <?php
          // Query for past posts
          $past_args = array(
            'post_type' => 'service_cpt',
            'posts_per_page' => 10,
            'paged' => $paged,
            'meta_query' => array(
              'relation' => 'OR',
              array(
                'key'     => 'request_date',
                'value'   => $today,
                'compare' => '<=',
                'type'    => 'CHAR',
              ),
              array(
                'key'     => 'request_date',
                'value'   => $today,
                'compare' => '=',
                'type'    => 'CHAR',
              ),
              array(
                'key'     => 'rqt_start_time',
                'value'   => $now_time,
                'compare' => '<',
                'type'    => 'TIME',
              )
            )
          );

          $past_query = new WP_Query($past_args);

          if ($past_query->have_posts()) :
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
              while ($past_query->have_posts()) :
                $past_query->the_post();
                global $post;

                $request_user_id = $post->post_author;
                $user_id = get_userdata($request_user_id);
                $logged_in_user_id = wp_get_current_user($request_user_id);

                if ($user_id == $logged_in_user_id) :
                  $request_date_raw = get_post_meta($post->ID, 'request_date', true);
                  $request_date = date('l, F j, Y', strtotime($request_date_raw));
                  $rqt_start_time = get_post_meta($post->ID, 'rqt_start_time', true);
                  $rqt_end_time = get_post_meta($post->ID, 'rqt_end_time', true);

                  // Determine if the post belongs in "Past"
                  $is_past = $request_date < $today || ($request_date === $today && $rqt_start_time < $now_time);
              ?>
                  <?php if ($is_past): ?>
                  <tr>
                    <td><?php echo $user_id->display_name; ?></td>
                    <td><?php echo $user_id->user_email; ?></td>
                    <td><?php echo $request_date_raw; ?></td>
                    <td><?php echo $rqt_start_time . ' - ' . $rqt_end_time; ?></td>
                    <td>
                      <?php
                      if ($post->post_status == 'publish') :
                        echo 'Approved';
                      elseif ($post->post_status == 'pending') :
                        echo 'Pending';
                      else :
                        echo 'Cancelled';
                      endif;
                      ?>
                    </td>
                    <td class="actionButtons">
                      <?php
                      if (isset($post->ID)) {
                        echo '<button type="submit" class="updateButton" data-postid="' . $post->ID . '">Edit</button>';
                        echo '
                        <form id="deleteForm" method="post" data-url="' . admin_url("admin-ajax.php") . '">
                          <button type="submit" class="deleteButton" data-postid="' . $post->ID . '">Delete</button>
                          <input type="hidden" name="delete_id" id="delete-id">
                          <input type="hidden" name="action" value="delete_request">
                          <input type="hidden" name="nonce" value="' . wp_create_nonce('delete-nonce') . '">
                        </form>';
                        echo '<button type="submit" class="viewButton" data-postid="' . $post->ID . '">View</button>';
                      }
                      ?>
                    </td>
                  </tr>
                  <?php endif; ?>
              <?php
                endif;
              endwhile;
              ?>
            </tbody>
          </table>

          <?php
          echo '<nav aria-label="Page navigation">';
          echo '<ul class="pagination">';
          echo '<li class="page-item">';
          echo paginate_links(array(
            'base' => 'admin.php?page=requests&paged=%#%',
            'total' => $past_query->max_num_pages,
            'current' => $paged
          ));
          echo '</li>';
          echo '</ul>';
          echo '</nav>';

          wp_reset_postdata(); // Reset post data after query
          else :
          ?>
            <div class="no-apt">
              <h3>There are no past requests</h3>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
