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
 * Description:       Learning data and analytics for your web users
 * Version:           1.0.0
 * Author:            Learning Tapestry, Inc.
 * Author URI:        https://learningtapestry.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       learningtapestry
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-learningtapestry-activator.php';
	Plugin_Name_Activator::activate();
}

function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-learningtapestry-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

require plugin_dir_path( __FILE__ ) . 'includes/class-learningtapestry.php';

function example_add_dashboard_widgets() {

	wp_add_dashboard_widget(
	         'example_dashboard_widget',         // Widget slug.
	         'Page View Overall Display',         // Title.
	         'example_dashboard_widget_function' // Display function.
	);	
}

function example_dashboard_widget_function() {

/*
	$response = wp_remote_get('');
	print_r $response;
*/

	$response = wp_remote_get('http://webdb01-ci.learningtapestry.com:8081/api/v1/getPageViewsSummary');
	$rows = json_decode($response["body"]);

	foreach ($rows as $row) {
		echo $row->display_name;
		echo '<br/>';
	}
}


/**
 * Begins execution of the plugin.
 * @since    1.0.0
 */
function run_learningtapestry() {

	$plugin = new LearningTapestry();
	$plugin->run();

	add_action('wp_loaded', function() {
		global $current_user;
		wp_register_script( 'lt', ( 'http://webdb01-ci.learningtapestry.com:8081/api/v1/loader.js?username=' . $current_user->ID . '&org_api_key=2866b962-a7be-44f8-9a0c-66502fba7d31&load=collector&autostart=true' ), false, null, true );
	  wp_enqueue_script( 'lt' );
	  wp_register_script('d3', ( 'http://d3js.org/d3.v3.min.js' ) );
	  wp_enqueue_script( 'd3' );
	  wp_register_script('c3', ( 'http://cdnjs.cloudflare.com/ajax/libs/c3/0.4.9/c3.min.js' ) );
	  wp_enqueue_script( 'c3' );
	  wp_register_script( 'youtube', 'http://www.youtube.com/iframe_api');
	  wp_enqueue_script( 'youtube' );

	});

	add_action( 'wp_dashboard_setup', 'example_add_dashboard_widgets' );

	/** Step 2 (from text above). */
	add_action( 'admin_menu', 'my_plugin_menu' );

}

/** Step 1. */
function my_plugin_menu() {
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

	if ( $_POST["org_api_key"] ) {
		update_option("lt_org_api_key", $_POST["org_api_key"]);
		$lt_org_api_key = $_POST["org_api_key"];
	}
	
	if ( !$lt_org_api_key ) {
	echo '<div class="error"><p>Learning Tapestry not configured.</p></div>';
	}
	
	echo '<div class="wrap">';
	echo '<h2>Learning Tapestry Settings</h2>';
	echo '<form id="learningtapestry_org_api_key" action="options-general.php?page=learningtapestry" method="post">';

	echo 'Learning Tapestry Organization API Key: <input name="org_api_key" type="text" value="' . $lt_org_api_key . '"><br/><br/>';


	// echo &lt_key;
	submit_button();
	echo '</form>';
	echo '</div>';
}

run_learningtapestry();
