<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://github.com/abdallahoraby
 * @since      1.0.0
 *
 * @package    Bikers_Trips
 * @subpackage Bikers_Trips/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Bikers_Trips
 * @subpackage Bikers_Trips/includes
 * @author     Abdallah Ahmed Oraby <abdallahoraby@hotmail.com>
 */
class Bikers_Trips_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'bikers-trips',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
