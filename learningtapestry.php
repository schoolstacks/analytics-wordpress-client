<?php

/**
 * The plugin bootstrap file
 *
 *
 * @link              https://learningtapestry.com
 * @since             1.0.0
 * @package           Learning Tapestry for WordPress
 *
 * @wordpress-plugin
 * Plugin Name:       Learning Tapestry for WordPress
 * Plugin URI:        https://learningtapestry.com/plugins/wordpress
 * Description:       Learning data and analytics for web users
 * Version:           1.0.0
 * Author:            Learning Tapestry, Inc.
 * Author URI:        https://learningtapestry.com
 * License:           Apache 2.0
 * License URI:       http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain:       learningtapestry
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function activate_learningtapestry() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-learningtapestry-activator.php';
	Plugin_Name_Activator::activate();
}

function deactivate_learningtapestry() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-learningtapestry-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_learningtapestry' );
register_deactivation_hook( __FILE__, 'deactivate_learningtapestry' );

require plugin_dir_path( __FILE__ ) . 'includes/class-learningtapestry.php';

/**
 * Begins execution of the plugin.
 * @since    1.0.0
 */
function run_learningtapestry() {

	$plugin = new LearningTapestry();
	$plugin->run();

	add_action('wp_loaded', function() {
		global $current_user;
	  global $options;

	  if (get_option("lt_org_api_key")) {
			$lt_org_api_key = get_option("lt_org_api_key");
			$lt_api_server = get_option("lt_api_server");
			wp_register_script( 'lt', ( $lt_api_server . '/api/v1/loader.js?username=' . $current_user->ID . '&org_api_key=' . $lt_org_api_key . '&load=collector&autostart=true' ), false, null, true );
		  wp_enqueue_script( 'lt' );
	  } else {
	  	echo '<div class="error"><p>Learning Tapestry not configured.</p></div>';
	  }

	  // TODO: Enable these for graphing functions as part of 1.5
	  /* wp_register_script('d3', ( 'http://d3js.org/d3.v3.min.js' ) );
	  wp_enqueue_script( 'd3' );
	  wp_register_script('c3', ( 'http://cdnjs.cloudflare.com/ajax/libs/c3/0.4.9/c3.min.js' ) );
	  wp_enqueue_script( 'c3' );
	  */

	});

	add_action( 'admin_menu', 'learningtapestry_menu' );
	add_action( 'wp_head', 'buffer_start');
    add_action( 'wp_footer', 'buffer_end');
}


 // if a YouTube video has been placed in HTML, insert enablejsapi parameter necessary for analytics
function lt_youtube_monitor($buffer) {
  $position = stripos($buffer, 'youtube.com/embed/');
  while ($position !== false) {
    $end_position = stripos($buffer, '"', $position);
    $youtube_url = substr($buffer, $position, $end_position-$position);
    $youtube_url = urldecode($youtube_url);

    if (stripos($youtube_url, 'enablejsapi') == false) {
      if (stripos($youtube_url, '?') == false) {
        $buffer = str_replace($youtube_url, $youtube_url . '?enablejsapi=1', $buffer);
      } else {
        $buffer = str_replace($youtube_url, $youtube_url . '&enablejsapi=1', $buffer);
      }
    } else {
       if (stripos($youtube_url, 'enablejsapi=0') !== false) {
         $buffer = str_replace($youtube_url, str_replace('enablejsapi=0', 'enablejsapi=1', $youtube_url), $buffer);
       }
    }
    $position = strpos($buffer, 'youtube.com/embed/', $end_position);
  }
  return $buffer;
}

function buffer_start() { ob_start("lt_youtube_monitor"); }

function buffer_end() { ob_end_flush(); }



/** Step 1. */
function learningtapestry_menu() {
	add_options_page( 'Learning Tapestry', 'Learning Tapestry', 'manage_options', 'learningtapestry', 'learningtapestry_options' );
}

/** Step 3. */
function learningtapestry_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	global $options;
	print_r($options);

	$lt_org_api_key = get_option("lt_org_api_key");
	$lt_api_server = get_option("lt_api_server");

	if ( $_POST["org_api_key"] ) {
		update_option("lt_org_api_key", $_POST["org_api_key"]);
		$lt_org_api_key = $_POST["org_api_key"];
		update_option("lt_api_server", $_POST["api_server"]);
		$lt_api_server = $_POST["api_server"];
	}

	if ( !$lt_org_api_key || !$lt_api_server) {
	echo '<div class="error"><p>Learning Tapestry not configured.</p></div>';
	}
	
	echo '<div class="wrap">';
	echo '<h2>Learning Tapestry Settings</h2>';
	echo '<br/><br/><br/><form id="learningtapestry_org_api_key" action="options-general.php?page=learningtapestry" method="post">';
	echo 'Learning Tapestry API server: <br/><input style="width: 500px;" name="api_server" type="text" value="' . $lt_api_server . '"><br/><br/>';
	echo 'Learning Tapestry Organization API Key: <br/><input style="width: 500px;" name="org_api_key" type="text" value="' . $lt_org_api_key . '"><br/><br/>';
	// echo &lt_key;
	submit_button();
	echo '</form>';
	echo '</div>';
}

run_learningtapestry();
