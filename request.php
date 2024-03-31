<?php
/*
Plugin name: Request
Description: Simple and awesome plugin to allow your clients request services.
Version: 1.0.0
Plugin Author: Vinny
Plugin URI: http://www.requestplugin.com
Text Domain: request
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php' ;
}

/**
 * The code that runs during plugin activation
 */
function activate_rqt_plugin() {
	Includes\Main\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_rqt_plugin' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_rqt_plugin() {
	Includes\Main\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_rqt_plugin' );

/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists( 'Includes\\Init' ) ) {
	Includes\Init::register_services();
}

//Create Services Custom Post type
add_action( 'init', 'services_post_type' );

function services_post_type() {
    $args = array(
        'name' => __( 'Services' ),
        'singular_name' => __( 'Service' )
    );

    register_post_type( 'service_cpt',
        array(
            'labels' => $args, 
            'public' => false,
            'has_archive' => false,
            'show_ui' => true,
            'show_in_admin_bar' => false,
            'supports' => array('title', 'editor'),
            'taxonomies'    => array('service_taxonomy')
        )
    );
}

// Add services taxonomy
add_action( 'init', 'services_taxonomy', 0 );

function services_taxonomy() {
    $args = array(
        'name' => _x( 'Services', 'taxonomy general name' ),
        'singular_name' => _x( 'Service', 'taxonomy singular name' ),
        'menu_name' => __( 'Services' ),
        'all_items' => __( 'All Services' ),
        'edit_item' => __( 'Edit Service' ), 
        'update_item' => __( 'Update Service' ),
        'add_new_item' => __( 'Add New Service' ),
        'new_item_name' => __( 'New Service' ),
        'not_found'          => __( 'No Services found' ),
    );

    register_taxonomy('service_taxonomy','requests',array(
        'labels' => $args,
        'query_var'  => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => true
        ));
        
}

//Add custom user roles
add_action('init', 'custom_user_role');

function custom_user_role(){
    add_role(
        'request_staff', //  System name of the role.
        __( 'Request Staff'  ), // Display name of the role.
        array(
            'read'  => true,
            'delete_posts'  => true,
            'delete_published_posts' => true,
            'edit_posts'   => true,
            'publish_posts' => true,
            'upload_files'  => true,
            'edit_pages'  => true,
            'edit_published_pages'  =>  true,
            'publish_pages'  => true,
            'delete_published_pages' => false, // This user will NOT be able to  delete published pages.
        )
    );

    add_role( 
        'request_customer', 
        'Request Customer', 'request', 
        array(
          'read' => true,
      ));
}


//Highlight Service menu

add_action('parent_file', 'highlight_service_menu');

function highlight_service_menu($parent_file) {
    global $current_screen;

    // Check if it's the desired taxonomy screen
    if ($current_screen->taxonomy == 'service_taxonomy') {
        $parent_file = 'requests';
    }

    return $parent_file;
}

function rqt_enqueue_script() {   
    wp_enqueue_style( 'rqtpluginstyle', plugin_dir_url( __FILE__ ) . 'assets/css/css.css' );
    wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'assets/js/js.js' );
    wp_enqueue_script( 'rqtcalendarscript', plugin_dir_url( __FILE__ ) . 'assets/js/calendar.js' );
}
add_action('wp_enqueue_scripts', 'rqt_enqueue_script');



// Register shortcode
add_shortcode('requestform', 'request_form_shortcode');

// Shortcode function
function request_form_shortcode() {
    ob_start(); ?>

    <!-- HTML markup for the form -->
    <div class="wrapper">
        <div class="rqt-panel-head">
            <h3>Add Request</h3>
        </div>
        <header>
            <div class="header" id="header">
                <span id="prev" class="material-symbols-rounded"><</span>
                <button id="prevMonth">Previous Month</button>
                <button id="nextMonth">Next Month</button>
                <p class="current-date"></p>
                <span id="next" class="material-symbols-rounded">></span>
            </div>
        </header>
        <div id="calendar"></div>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
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
                <textarea class="description" name="description"></textarea><br><br>
                <input type="hidden" id="start-time" name="rqt_start_time">
                <input type="hidden" id="end-time" name="rqt_end_time">
                <input type="hidden" id="request_date" name="request_date">
                <input type="submit" value="Save"> <br><br>
            </form>
        </div>
    </div>

    <?php
    $output = ob_get_clean();
    return $output;
}

// Handle form submission

add_action('init', 'submit_request');

function submit_request() {
    if (isset($_POST['name']) && isset($_POST['email'])) {
        $user_name = sanitize_user($_POST['name']);
        $user_email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $description = sanitize_text_field($_POST['description']);
        $rqt_start_time = sanitize_text_field($_POST['rqt_start_time']);
        $rqt_end_time = sanitize_text_field($_POST['rqt_end_time']);
        $request_date = sanitize_text_field($_POST['request_date']);

        // Check if the email already exists
        $user = get_user_by('email', $user_email);

        if ($user) {
            $user_id = $user->ID;
        } else {
            // Email doesn't exist, create a new user
            $random_password = wp_generate_password(12, false);
            $user_id = wp_create_user($user_name, $random_password, $user_email);

            if (is_wp_error($user_id)) {
                // An error occurred while creating the user
                $error_message = $user_id->get_error_message();
                echo $error_message;
                return;
            }

            // Assign a role to the user
            $user = new WP_User($user_id);
            $user->remove_role('subscriber');
            $user->add_role('request_customer');

            // Update user meta data
            update_user_meta($user_id, 'phone', $phone);
        }

        // Create the post
        $new_post = array(
            'post_title' => 'New Request',
            'post_content' => $description,
            'post_type' => 'service_cpt',
            'post_author' => $user_id,
            'post_status' => 'draft'
        );
        $post_id = wp_insert_post($new_post);

        if ($post_id) {
            // Update post meta data
            update_post_meta($post_id, 'rqt_start_time', $rqt_start_time);
            update_post_meta($post_id, 'rqt_end_time', $rqt_end_time);
            update_post_meta($post_id, 'request_date', $request_date);
            echo "Request added successfully with the ID of $post_id and user id of $user_id";
        } else {
            echo "Error, Request not created";
        }
    }
}


// Profile shorcode
add_shortcode('requests_shortcode', 'custom_requests_shortcode_function');

function custom_requests_shortcode_function() {
    ob_start();
    ?>

    <div class="rqt-container">
        <div class="rqt-panel-head">
            <h3>Requests</h3>
        </div>
        <div class="rqt-panel-body">
            <?php
            $paged = isset($_GET['paged']) ? $_GET['paged'] : 1;
            $args = array(
                'post_type' => 'service_cpt',
                'post_status' => array('draft','publish'),
                'posts_per_page' => 10,
                'paged' => $paged
            );

            $post_query = new WP_Query($args);

            if ($post_query->have_posts()) :
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
                        while ($post_query->have_posts()) :
                            $post_query->the_post();
                            global $post;

                            $request_user_id = $post->post_author;
                            $user_id = get_userdata($request_user_id);
                            $logged_in_user_id = wp_get_current_user($request_user_id);

                            if ($user_id == $logged_in_user_id) :
                                $request_date = get_post_meta($post->ID, 'request_date', true);
                                $rqt_start_time = get_post_meta($post->ID, 'rqt_start_time', true);
                                $rqt_end_time = get_post_meta($post->ID, 'rqt_end_time', true);
                        ?>
                                <tr>
                                    <td><?php echo $user_id->display_name; ?></td>
                                    <td><?php echo $user_id->user_email; ?></td>
                                    <td><?php echo $request_date; ?></td>
                                    <td><?php echo $rqt_start_time . ' - ' . $rqt_end_time; ?></td>
                                    <?php if ($post->post_status == 'publish') :
                                        echo '<td>Approved</td>';
                                    elseif ($post->post_status == 'draft') :
                                        echo '<td>Pending</td>';
                                    endif;
                                    ?>
                                    <td>
                                        <?php
                                        echo "<form id='updateForm' method='post' id='edit-request'>
                                                    <input type='hidden' name='update_id' value='" . $post->ID . "'>
                                                    <input type='button' class='updateButton' name='update' value='Edit'>
                                                </form>";

                                        // Delete
                                        if (isset($post->ID)) {
                                            echo '
                                                <form id="deleteForm" method="post">
                                                    <input type="hidden" name="delete_id" value="' . $post->ID . '">
                                                    <input type="button" class="deleteButton" value="Delete" name="delete">
                                                </form>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php
                            endif;
                        endwhile;
                        ?>
                    </tbody>
                </table>

                <!-- Request table pagination -->
                <?php
                echo '<nav aria-label="Page navigation">';
                echo '<ul class="pagination">';
                echo '<li class="page-item">';
                echo paginate_links(array(
                    'base' => 'admin.php?page=requests&paged=%#%',
                    'total' => $post_query->max_num_pages,
                    'current' => $paged
                ));
                echo '</li>';
                echo '</ul>';
                echo '</nav>';
                ?>

            <?php else : ?>
                <div class="no-apt">
                    <h3>There are no Requests</h3>
                </div>
            <?php endif; 
            ?>
        </div>
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
                <textarea class="description" name="description"></textarea><br><br>
                <input type="hidden" id="start-time" name="rqt_start_time">
                <input type="hidden" id="end-time" name="rqt_end_time">
                <input type="hidden" id="request_date" name="request_date">
                <input type="submit" value="Update"> <br><br>
            </form>
        </div>
    </div>

    <?php
    if (isset($post->ID)) {
        // Add JavaScript for Ajax confirmation
        echo '
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let items = document.querySelectorAll(".deleteButton");
                for (let i = 0; i < items.length; i++) {
                    items[i].addEventListener("click", function() {
                        var shouldDelete = confirm("Are you sure you want to delete this request?");
                        if (shouldDelete) {
                            var xhr = new XMLHttpRequest();
                            xhr.open("POST", "admin.php?page=modify", true);
                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState == 4 && xhr.status == 200) {
                                    location.reload();
                                }
                            };
                            xhr.send("delete_id=' . $post->ID . '&delete_confirm=true");
                        }
                    });
                }
            });
        </script>';
    }

    wp_reset_postdata();


    return ob_get_clean();
}
