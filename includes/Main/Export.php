<?php

/**
 * @package  Request
 */

namespace Includes\Main;

use Includes\Main\Main;

class Export extends Main
{
    public function register()
    {
        add_action('wp_ajax_export_requests', array($this, 'export_requests'));
    }



    public function export_requests() {
        // Verify AJAX request and nonce
        if ( ! defined('DOING_AJAX') || ! DOING_AJAX || ! check_ajax_referer('export-all-nonce', 'nonce') ) {
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
    
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=requests_export.csv');
    
        $output = fopen('php://output', 'w');
    
        // CSV column headers
        $export_titles = array(
            'Name',
            'Email',
            'Date',
            'Time',
            'Status'
        );
    
        fputcsv($output, $export_titles);
    
        // Prepare the query arguments
        $query_args = array(
            'post_type'      => 'service_cpt',
            'posts_per_page' => -1 // Retrieve all posts
        );
    
        // Check for filters
        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
        $to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';
        
        // Initialize the meta query array
        $query_args['meta_query'] = array(
            'relation' => 'AND'
        );
        
        // Check if from_date is valid
        if (strtotime($from_date)) {
            $query_args['meta_query'][] = array(
                'key'     => 'request_date',
                'value'   => $from_date,
                'compare' => '>=',
                'type'    => 'DATE'
            );
        }
        
        // Check if to_date is valid
        if (strtotime($to_date)) {
            $query_args['meta_query'][] = array(
                'key'     => 'request_date',
                'value'   => $to_date,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }
        
        // If both dates are provided and valid, use BETWEEN
        if (strtotime($from_date) && strtotime($to_date)) {
            $query_args['meta_query'] = array(
                array(
                    'key'     => 'request_date',
                    'value'   => array($from_date, $to_date),
                    'compare' => 'BETWEEN',
                    'type'    => 'DATE'
                )
            );
        }
    
        // Check for status filter
        if (isset($_POST['request_status']) && !empty($_POST['request_status'])) {
            $query_args['post_status'] = sanitize_text_field($_POST['request_status']);
        }
    
        // Execute the query
        $requests = new \WP_Query($query_args);
    
        // Check if any posts are found
        if ($requests->have_posts()) {
            while ($requests->have_posts()) {
                $requests->the_post();
                global $post;
    
                // Get user data
                $request_user_id = $post->post_author;
                $user_data = get_userdata($request_user_id);
                if ($user_data) {
                    $name = $user_data->display_name;
                    $email = $user_data->user_email;
                } else {
                    $name = '';
                    $email = '';
                }
    
                // Get and format request date
                $request_date = get_post_meta($post->ID, 'request_date', true);
                if ($request_date) {
                    $dateTimestamp = strtotime($request_date);
                    $formattedDate = date("l, F j, Y", $dateTimestamp);
                } else {
                    $formattedDate = '';
                }
    
                // Get request time
                $rqt_start_time = get_post_meta($post->ID, 'rqt_start_time', true);
                $rqt_end_time = get_post_meta($post->ID, 'rqt_end_time', true);
                $time = $rqt_start_time . ' - ' . $rqt_end_time;
    
                // Get request status
                if ($post->post_status == 'publish') {
                    $request_status = 'Approved';
                } elseif ($post->post_status == 'pending') {
                    $request_status = 'Pending';
                } else {
                    $request_status = 'Cancelled';
                }
    
                // Prepare the data row
                $request_data = array(
                    $name,
                    $email,
                    $formattedDate,
                    $time,
                    $request_status
                );
    
                // Write the row to CSV
                fputcsv($output, $request_data);
            }
        } else {
            // Log if no requests are found
            echo'No requests found';
        }
    
        wp_reset_postdata();
        fclose($output);
        exit;
    }
    
    


}
