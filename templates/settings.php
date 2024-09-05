<div class="rqt-container">
    <div class="rqt-panel-head">
        <h3>Settings</h3>
    </div>
    <div class="rqt-panel-body">

      <div class="settings-tab">
        <ul class="settings-tabs-list">
          <li class="active"><a href="#tab-1">General</a></li>
          <li><a href="#tab-2">Date/Time</a></li>
          <li><a href="#tab-3">Payment</a></li>
          <li><a href="#tab-4">Email</a></li>
          <li><a href="#tab-5">Exports</a></li>
          <li><a href="#tab-6">Shortcodes</a></li>
        </ul>
      </div>

      <div class="settings-tab-content">
        <div id="tab-1" class="active tab-pane">
        <div class="rqt-body-container">
          <h3>General</h3>
        <h1>Custom Color Changer Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('custom_color_changer_settings');
            do_settings_sections('custom_color_changer');

            submit_button();
           
            ?>

        </form>

        </div>
          
        </div>
        <div id="tab-2" class="tab-pane">
        <div class="rqt-body-container">
        <h3>Date/Time</h3>

	<?php settings_errors(); ?>

	<form method="post" action="options.php">
  
		<?php 
			settings_fields( 'requests_options_group' );
			do_settings_sections( 'settings' );
			submit_button();
		?>
	</form>
          <h3>Select specific dates to disable in the calendar</h3>

  <div id="admin-calendar" class="admin-calendar">

<div class="admin-month">
          <button id="admin-prev">&#10094;</button>
          <h1 id="admin-month-year"></h1>
          <button id="admin-next">&#10095;</button>
      </div>
  <div class="admin-weekdays">
      <div>Sun</div>
      <div>Mon</div>
      <div>Tue</div>
      <div>Wed</div>
      <div>Thu</div>
      <div>Fri</div>
      <div>Sat</div>
  </div>
  <div id="admin-days" class="admin-days">
      <!-- Calendar days will go here -->
  </div>
</div>


  <table id="selected-dates">
  <thead>
      <tr>
          <th>Selected Dates</th>
          <th>Action</th>
      </tr>
  </thead>
  <tbody>
      <tr id="no-dates-message">
          <td colspan="2">No dates selected</td>
      </tr>
  </tbody>
</table>


<form method="post" id="settingsForm" data-url="<?php echo admin_url('admin-ajax.php'); ?>" action="options.php">
  <button type="submit" id="SelectedDatesButton">Save</button>
  <input type="hidden" name="action" value="save_dynamic_data"> 
  <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('selected-dates-nonce'); ?>">
</form>

</div>



  
        </div>
        <div id="tab-3" class="tab-pane">
        <div class="rqt-body-container">
          <h3>Payments</h3>
          <?php

?>

<form method="post" action="options.php">
<?php
settings_fields('custom_settings_group');
do_settings_sections('custom-settings-page');
submit_button();
?>
</form>
</div>



        </div>
        <div id="tab-4" class="tab-pane">
        <div class="rqt-body-container">
          <h3>Emails</h3>

          <form method="post" action="options.php">
  
  <?php 
    settings_fields( 'emails_options_group' );
    do_settings_sections( 'emails-settings' );
    submit_button();
  ?>
</form>

        </div>
        </div>

        <div id="tab-5" class="tab-pane">
          <div class="rqt-body-container">
               <h3>Export Requests</h3>
               <p>Select dates to export requests within the specified range</p>
                  <form method="post" id="export-form" data-url="<?php echo admin_url('admin-ajax.php'); ?>"> 
                  <p>
                  <b><label for="from">From Date</label></b><br>
                  <input type="date" name="from_date" id="from_date" autocomplete="off" required> 
                  </p>
                  <p>
                  <b><label for="to">To Date</label></b><br>
                  <input type="date" name="to_date" id="to_date" autocomplete="off" required>
                  </p>
                      

                  <p>
                  <b><label for="request_status">Status</label></b><br>
                  <select name="request_status">
                  <option value="any">All</option>
                  <option value="publish">Approved</option>
                  <option value="pending">Pending</option>
                  <option value="draft">Canceled</option>
                  </select>
                  </p>

                  <button type="submit" id="export-button">Export to CSV</button>
                

                  <input type="hidden" name="action" value="export_requests"> 
                  
                  <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('export-all-nonce'); ?>">
                  </form>

               </div>
        </div>
        <div id="tab-6" class="tab-pane">
        <div class="rqt-body-container">
          <h3>Shortcodes</h3>
          <code>[request_shortcode]</code><br><br><br>
          <code>[profile_shortcode]</code>
        </div>
        </div>

      </div>
      
  </div>
</div>