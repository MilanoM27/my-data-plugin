<?php
/*
Plugin Name: Custom Plugin
Description: A WordPress plugin to retrieve data from JSON files, implement AJAX search, and filter data.
Version: 1.0
Author: Milano
*/

// Enqueue scripts and styles
function custom_plugin_enqueue_scripts() {
    // Include jQuery
    wp_enqueue_script('jquery');

    // Include custom JavaScript for AJAX
    wp_enqueue_script('custom-plugin-ajax', plugin_dir_url(__FILE__) . 'js/custom-plugin-ajax.js', array('jquery'), '1.0', true);

    // Provide AJAX URL to JavaScript
    wp_localize_script('custom-plugin-ajax', 'custom_plugin_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'), // URL for AJAX requests
    ));

    // Include custom stylesheet
    wp_enqueue_style('custom-plugin-style', plugin_dir_url(__FILE__) . 'css/custom-plugin-style.css', array(), '1.0');
}
add_action('wp_enqueue_scripts', 'custom_plugin_enqueue_scripts');

// Retrieve data from JSON files
function custom_plugin_get_data() {
    $data = array(); // Initialize an empty array for data
    $json_files_path = plugin_dir_path(__FILE__) . 'json-files/'; // Path to JSON files

    // List of JSON files
    $json_file_names = array(
        'data-ecn-commodities.json',
        'data-ecn-forex.json',
        'data-ecn-indices.json',
        'data-ecn-shares.json',
        'data-mini-commodities.json',
        'data-mini-forex.json',
        'data-mini-indices.json',
        'data-mini-shares.json',
        'data-stp-commodities.json',
        'data-stp-forex.json',
        'data-stp-indices.json',
        'data-stp-shares.json',
    );

    // Iterate through JSON files
    foreach ($json_file_names as $filename) {
        $file_path = $json_files_path . $filename;
        if (file_exists($file_path)) { // Check if file exists
            $json_data = file_get_contents($file_path); // Read JSON data
            $data[] = json_decode($json_data, true); // Decode JSON and add to data array
        }
    }

    return $data; // Return fetched data
}

// AJAX request for filtering data
function custom_plugin_filter_data() {
    // Get selected account and asset types
    $type_of_accounts = isset($_POST['type_of_accounts']) ? $_POST['type_of_accounts'] : array();
    $type_of_assets = isset($_POST['type_of_assets']) ? $_POST['type_of_assets'] : array();
    $filtered_data = array(); // Initialize array for filtered data

    // Retrieve all data
    $all_data = custom_plugin_get_data();

    // Filter data based on selected types
    foreach ($all_data as $item) {
        if (in_array($item['account_type'], $type_of_accounts) && in_array($item['asset_type'], $type_of_assets)) {
            $filtered_data[] = $item; // Add matching items to filtered data
        }
    }

    // Send filtered data as JSON response
    wp_send_json($filtered_data);
}
add_action('wp_ajax_custom_plugin_filter_data', 'custom_plugin_filter_data'); // For logged-in users
add_action('wp_ajax_nopriv_custom_plugin_filter_data', 'custom_plugin_filter_data'); // For non-logged-in users

// Display data using shortcode
function custom_plugin_display_data() {
    ob_start(); // Start output buffering ?>

    <!-- Table to display data -->
    <table id="custom-plugin-table">
        <thead>
            <tr>
                <th>Account Type</th>
                <th>Asset Type</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data rows to show dynamically using AJAX -->
        </tbody>
    </table>

    <?php
    return ob_get_clean(); // Return buffered output
}
add_shortcode('custom_plugin_display', 'custom_plugin_display_data'); // Shortcode to display data
