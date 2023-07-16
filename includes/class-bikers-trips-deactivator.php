<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://github.com/abdallahoraby
 * @since      1.0.0
 *
 * @package    Bikers_Trips
 * @subpackage Bikers_Trips/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Bikers_Trips
 * @subpackage Bikers_Trips/includes
 * @author     Abdallah Ahmed Oraby <abdallahoraby@hotmail.com>
 */
class Bikers_Trips_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

        global $wpdb;
        $table_name = $wpdb->prefix . 'bikers_members';
        $sql = "DROP table IF EXISTS $table_name ";
        $wpdb->query($sql);

        $tracking_table_name = $wpdb->prefix . 'bikers_tracking';
        $tracking_sql = "DROP table IF EXISTS $tracking_table_name ";
        $wpdb->query($tracking_sql);

        // delete all trips posts
        $postmeta_table = $wpdb->postmeta;
        $posts_table = $wpdb->posts;

        $postmeta_table = str_replace($wpdb->base_prefix, $wpdb->prefix, $postmeta_table);
        $postmeta_table = str_replace($wpdb->base_prefix, $wpdb->prefix, $postmeta_table);

        $wpdb->query("DELETE FROM " . $postmeta_table . " WHERE meta_key = 'trip_dest'");
        $wpdb->query("DELETE FROM " . $postmeta_table . " WHERE meta_key = 'trip_members_count'");
        $wpdb->query("DELETE FROM " . $postmeta_table . " WHERE meta_key = 'trip_date'");
        $wpdb->query("DELETE FROM " . $postmeta_table . " WHERE meta_key = 'trip_duration'");
        $wpdb->query("DELETE FROM " . $postmeta_table . " WHERE meta_key = 'trip_distance'");
        $wpdb->query("DELETE FROM " . $postmeta_table . " WHERE meta_key = 'trip_status'");
        $wpdb->query("DELETE FROM " . $posts_table . " WHERE post_type = 'bikers-trips'");


        // delete pages on deactivation
        $dashboard_page_id = get_option('bikers_trips_dashboard_page_id');
        if( !empty($dashboard_page_id) ){
            wp_delete_post($dashboard_page_id, true);
        }

        $trip_page_id = get_option('bikers_trips_page_id');
        if( !empty($trip_page_id) ){
            wp_delete_post($trip_page_id, true);
        }

        $join_trip_page_id = get_option('bikers_trips_join_page_id');
        if( !empty($join_trip_page_id) ){
            wp_delete_post($join_trip_page_id, true);
        }

	}

}
