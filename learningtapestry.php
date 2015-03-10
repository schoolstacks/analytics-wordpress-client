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

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-learningtapestry-activator.php';
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-learningtapestry-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-learningtapestry.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

	$plugin = new LearningTapestry();
	$plugin->run();

	add_action('wp_loaded', function() {
		global $current_user;
		wp_register_script('yt', 'http://www.youtube.com/player_api');
	  wp_enqueue_script( 'yt' );
		wp_register_script( 'lt', ( 'http://webdb01-ci.learningtapestry.com:8081/api/v1/loader.js?username=' . $current_user->ID . '&org_api_key=2866b962-a7be-44f8-9a0c-66502fba7d31&load=collector&autostart=true' ), false, null, true );
	  wp_enqueue_script( 'lt' );

	});
}
run_plugin_name();
