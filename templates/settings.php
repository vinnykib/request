<div class="rqt-container">
    <div class="rqt-panel-head">
        <h3>Settings</h3>
    </div>
    <div class="rqt-panel-body">

      <div class="settings-tab">
        <ul class="settings-tabs-list">
          <li class="active"><a href="#tab-1">General</a></li>
          <li><a href="#tab-2">Date/Time</a></li>
          <li><a href="#tab-3">Email</a></li>
          <li><a href="#tab-4">Exports</a></li>
          <li><a href="#tab-5">Shortcodes</a></li>
        </ul>
      </div>

      <div class="settings-tab-content">
        <div id="tab-1" class="active tab-pane">
          <h3>General</h3>

          <div class="wrap">
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
        <h3>Date/Time</h3>

        <form action="">

          <label>Start:</label>
          <input type="time" value="09:00:00">

          <label>End:</label>
          <input type="time" value="17:00:00">

          <label>Time slot Interval:</label>
          <input type="text" value="30 mins">

        </form>

        <div class="wrap">
	<?php settings_errors(); ?>

	<form method="post" action="options.php">
		<?php 
			settings_fields( 'requests_options_group' );
			do_settings_sections( 'settings' );
			submit_button();
		?>
	</form>

  <form method="post" action="options.php">
		<?php 
			settings_fields( 'requests_colors_group' );
			do_settings_sections( 'settings' );
			submit_button();
		?>
	</form>
</div>
        </div>
        <div id="tab-3" class="tab-pane">
          <h3>Emails</h3>
        </div>

        <div id="tab-4" class="tab-pane">
          <div class="apt-container">
            <div class="apt-panel-body">
               <div class="export-container">
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
</div>
        </div>
        <div id="tab-5" class="tab-pane">
          <h3>Shortcodes</h3>
          <code>[requestform]</code>
        </div>
        

      </div>
      
  </div>
</div>