<?php
/**
 * Plugin Name: My Data retrive  Plugin
 * Plugin URI:  # https://mmoses.co.za
 * Description: Retrieves data, stores it in database, and visualizes it in a table.
 * Version:     1.0
 * Author:      Milano Moses
 * Author URI:  # https://mmoses.co.za
 * License:     GPLv2 or later
 * Text Domain: my-data-plugin
 */
function get_data_from_source($secret) {
  $url = 'https://xyzalpha.kinsta.cloud/interview/quote/';
  $data = array('secret' => $secret);

  $args = array(
      'body' => json_encode($data),
      'headers' => array(
          'Content-Type' => 'application/json',
      ),
      'method' => 'POST',
  );

  $response = wp_remote_post($url, $args);

  if (is_wp_error($response)) {
      return false; // Handle error appropriately (e.g., logging)
  } else {
      $body = json_decode(wp_remote_retrieve_body($response));
      return $body; // Return the decoded data or handle errors if necessary
  }
}

define('MY_DATA_SECRET', '!Qj9*iop'); // Replace with your chosen secret

function store_data($data) {
  global $wpdb;

  $table_name = $wpdb->prefix . 'my_data_plugin_data'; // Create a custom table

  $wpdb->insert($table_name, array(
      'data' => serialize($data), // Serialize data for storage
      'timestamp' => current_time('mysql'),
  ));

  if ($wpdb->insert_id) {
      return true;
  } else {
      return false; // Handle storage error
  }
}

function display_data_table($data) {
  $html = '<table class="my-data-table">';
  $html .= '<tr><th>Type</th><th>Value</th></tr>';

  foreach ($data as $type => $value) {
      $html .= "<tr><td>$type</td><td>$value</td></tr>";
  }

  $html .= '</table>';

  return $html;
}
function activate_plugin() {
  global $wpdb;

  $table_name = $wpdb->prefix . 'my_data_plugin_data';

  $
}
