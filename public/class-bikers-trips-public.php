<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://github.com/abdallahoraby
 * @since      1.0.0
 *
 * @package    Bikers_Trips
 * @subpackage Bikers_Trips/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bikers_Trips
 * @subpackage Bikers_Trips/public
 * @author     Abdallah Ahmed Oraby <abdallahoraby@hotmail.com>
 */
class Bikers_Trips_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}



	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bikers_Trips_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bikers_Trips_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', array(), null, true);
		wp_enqueue_script( 'Popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array(), rand(), true );
        wp_enqueue_script( 'moment-js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js', array(), rand(), true );
        wp_enqueue_script( 'calendar-js', plugin_dir_url( __FILE__ ) . 'js/calendar.min.js', array(), rand(), true );
        wp_enqueue_script( 'cbpFWTabs-js', plugin_dir_url( __FILE__ ) . 'js/cbpFWTabs.js', array(), rand(), true );
        wp_enqueue_script( 'unpkg-js', 'https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js', array(), rand(), true );
        wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.js', array(), rand(), true );
        wp_enqueue_script( 'timepicker-js', plugin_dir_url( __FILE__ ) . 'js/timepicker.min.js', array(), rand(), true );
        wp_enqueue_script( 'comment-js', plugin_dir_url( __FILE__ ) . 'js/comment.js', array(), rand(), true );
        wp_enqueue_script( 'timer-js', plugin_dir_url( __FILE__ ) . 'js/timer.js', array(), rand(), true );
        wp_enqueue_script( 'custom-script', plugin_dir_url( __FILE__ ) . 'js/bikers-trips-public.js', array(), rand(), true );


    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Bikers_Trips_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Bikers_Trips_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style( 'bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), rand(), 'all' );
        wp_enqueue_style( 'fontawesome', plugin_dir_url( __FILE__ ) . 'css/fontawesome-all.css', array(), rand(), 'all' );
        wp_enqueue_style( 'tabs', plugin_dir_url( __FILE__ ) . 'css/tabs.css', array(), rand(), 'all' );
        wp_enqueue_style( 'page', plugin_dir_url( __FILE__ ) . 'css/page.css', array(), rand(), 'all' );
        wp_enqueue_style( 'style-calendar', plugin_dir_url( __FILE__ ) . 'css/style-calendar.css', array(), rand(), 'all' );
        wp_enqueue_style( 'theme', plugin_dir_url( __FILE__ ) . 'css/theme.css', array(), rand(), 'all' );
        wp_enqueue_style( 'tabstyles', plugin_dir_url( __FILE__ ) . 'css/tabstyles.css', array(), rand(), 'all' );
        wp_enqueue_style( 'timepicker', plugin_dir_url( __FILE__ ) . 'css/timepicker.min.css', array(), rand(), 'all' );
        wp_enqueue_style( 'unpkg', plugin_dir_url( __FILE__ ) . 'css/gijgo.min.css', array(), rand(), 'all' );
        wp_enqueue_style( 'styles-css', plugin_dir_url( __FILE__ ) . 'css/bikers-trips-public.css', array(), rand(), 'all' );



    }




}
