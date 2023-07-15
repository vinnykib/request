<div class="rqt-container">
    <div class="rqt-panel-head">
        <h3>Services</h3>
    </div>
    <div class="rqt-panel-body">

    <?php

    $plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
    $plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
    $plugin = plugin_basename( dirname( __FILE__, 3 ) );


    echo $plugin_path;
    ?>
    <br>
    <?php
    echo $plugin_url;

    ?>
<br>
    <?php
    echo $plugin;

    ?>
            
  </div>
</div>
