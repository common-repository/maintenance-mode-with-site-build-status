<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           MM_Site_Build_Status
 *
 * @wordpress-plugin
 * Plugin Name:       Maintenance Mode with Site Build Status
 * Plugin URI:        http://live-maintenance-mode-with-site-build-status.pantheonsite.io/
 * Description:       Displays a maintenance mode page for website development with additional site build progress information for your clients.
 * Version:           1.0.9
 * Author:            Red Earth Design
 * Author URI:        https://redearthdesign.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mm-site-build-status
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// /**
//  * Currently plugin version.
//  * Start at version 1.0.0 and use SemVer - https://semver.org
//  * Rename this for your plugin and update it as you release new versions.
//  */
// define( 'MM_SITE_BUILD_STATUS_VERSION', '1.0.0' );

/**
 * Define global constants.
 */

 // Source: https://mycyberuniverse.com/using-constants-wordpress-plugin.html
 $plugin_data = get_file_data( __FILE__, array( 'name'=> 'Plugin Name', 'version'=> 'Version', 'text'=> 'Text Domain' ) );
 function allmetatags_constants( $constant_name, $value ) {
     $constant_name_prefix = 'MM_SITE_BUILD_STATUS_';
     $constant_name = $constant_name_prefix . $constant_name;
     if ( !defined( $constant_name ) )
         define( $constant_name, $value );
 }
 allmetatags_constants( 'DIR', dirname( plugin_basename( __FILE__ ) ) );
 allmetatags_constants( 'BASE', plugin_basename( __FILE__ ) );
 allmetatags_constants( 'URL', plugin_dir_url( __FILE__ ) );
 allmetatags_constants( 'PATH', plugin_dir_path( __FILE__ ) );
 allmetatags_constants( 'SLUG', dirname( plugin_basename( __FILE__ ) ) );
 allmetatags_constants( 'NAME', $plugin_data['name'] );
 allmetatags_constants( 'VERSION', $plugin_data['version'] );
 allmetatags_constants( 'TEXT', $plugin_data['text'] );
 allmetatags_constants( 'PREFIX', 'allmetatags' );
 allmetatags_constants( 'SETTINGS', 'allmetatags' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mm-site-build-status-activator.php
 */
function activate_mm_site_build_status() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mm-site-build-status-activator.php';
	MM_Site_Build_Status_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mm-site-build-status-deactivator.php
 */
function deactivate_mm_site_build_status() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mm-site-build-status-deactivator.php';
	MM_Site_Build_Status_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mm_site_build_status' );
register_deactivation_hook( __FILE__, 'deactivate_mm_site_build_status' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mm-site-build-status.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mm_site_build_status() {

	$plugin = new MM_Site_Build_Status();
	$plugin->run();

}
run_mm_site_build_status();
