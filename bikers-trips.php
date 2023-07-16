<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://github.com/abdallahoraby
 * @since             1.0.0
 * @package           Bikers_Trips
 *
 * @wordpress-plugin
 * Plugin Name:       Bikers Trips
 * Plugin URI:        http://github.com/abdallahoraby
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.9
 * Author:            Abdallah Ahmed Oraby
 * Author URI:        http://github.com/abdallahoraby
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bikers-trips
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BIKERS_TRIPS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bikers-trips-activator.php
 */
function activate_bikers_trips() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bikers-trips-activator.php';
	Bikers_Trips_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bikers-trips-deactivator.php
 */
function deactivate_bikers_trips() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bikers-trips-deactivator.php';
	Bikers_Trips_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bikers_trips' );
register_deactivation_hook( __FILE__, 'deactivate_bikers_trips' );
//register_uninstall_hook( __FILE__, 'deactivate_bikers_trips' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bikers-trips.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bikers_trips() {

	$plugin = new Bikers_Trips();
	$plugin->run();

}
run_bikers_trips();
