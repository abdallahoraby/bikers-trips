<?php

/**
 * Fired during plugin activation
 *
 * @link       http://github.com/abdallahoraby
 * @since      1.0.0
 *
 * @package    Bikers_Trips
 * @subpackage Bikers_Trips/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Bikers_Trips
 * @subpackage Bikers_Trips/includes
 * @author     Abdallah Ahmed Oraby <abdallahoraby@hotmail.com>
 */
class Bikers_Trips_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

	    // generate custom tables for members in trip
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'bikers_members';

        $sql = "CREATE TABLE $table_name (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  name varchar(500) DEFAULT '' NOT NULL,
                  id_num varchar(500) DEFAULT '' NOT NULL,
                  license_id varchar(500) DEFAULT '' NOT NULL,
                  license_exp varchar(500) DEFAULT '' NOT NULL,
                  trip_id int(11) NOT NULL,
                  created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                  PRIMARY KEY  (id)
                ) $charset_collate;";

        $tracking_table_name = $wpdb->prefix . 'bikers_tracking';
        $tracking_sql = "CREATE TABLE $tracking_table_name (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  trip_id int(11) NOT NULL,
                  started_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                  start_longitude varchar(500) DEFAULT '' NOT NULL,
                  start_latitude varchar(500) DEFAULT '' NOT NULL,
                  status varchar(500) DEFAULT '' NOT NULL,
                  PRIMARY KEY  (id)
                ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        dbDelta( $tracking_sql );



        // create page for main shortcode
        $dashboard_page = array();
        $dashboard_page['post_title'] = "Bikers Dashboard";
        $dashboard_page['post_content'] = "[bikers-trips-dashboard]";
        $dashboard_page['post_status'] = "publish";
        $dashboard_page['post_name'] = "bikers-trips-dashboard";
        $dashboard_page['post_type'] = "page";
        $dashboard_page['comment_status'] = "closed";

        if( !is_page('bikers-trips-dashboard') ) {
            $dashboard_page_id = wp_insert_post($dashboard_page);
            add_option('bikers_trips_dashboard_page_id', $dashboard_page_id); // add record to wp_options table
            wp_reset_postdata ();
        }


        // create page for single trip shortcode
        $trip_info_page = array();
        $trip_info_page['post_title'] = "Trip Info";
        $trip_info_page['post_content'] = "[bikers-trips-trip]";
        $trip_info_page['post_status'] = "publish";
        $trip_info_page['post_name'] = "trip";
        $trip_info_page['post_type'] = "page";
        $trip_info_page['comment_status'] = "closed";

        if( !is_page('trip') ) {
            $trip_info_page_id = wp_insert_post($trip_info_page);
            add_option('bikers_trips_page_id', $trip_info_page_id); // add record to wp_options table
            wp_reset_postdata();
        }


        // create page for joining trip
        $join_trip_page = array();
        $join_trip_page['post_title'] = "Join Trip";
        $join_trip_page['post_content'] = "[bikers-trips-join]";
        $join_trip_page['post_status'] = "publish";
        $join_trip_page['post_name'] = "join-trip";
        $join_trip_page['post_type'] = "page";
        $join_trip_page['comment_status'] = "closed";

        if( !is_page('join-trip') ) {
            $join_trip_page_id = wp_insert_post($join_trip_page);
            add_option('bikers_trips_join_page_id', $join_trip_page_id); // add record to wp_options table
            wp_reset_postdata();
        }



	}



}
