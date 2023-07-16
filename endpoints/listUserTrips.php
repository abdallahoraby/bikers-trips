<?php

// Endpoint to get all user trips

function listUserTrips(){

    // get POSTED data from Flutter form
    $token = ( ! empty($_GET['token']) ) ? $_GET['token'] : null;


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => SITE_URL . "/wp-json/api/flutter_user/get_currentuserinfo?token=" . $token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    if( json_decode( $response )->user ){
        $responseJson = json_decode( $response );
    } else {
        http_response_code(404);
        die();
    }

    $user_id = $responseJson->user->id;



    // query and get all bikers-trips associated with this user_id (author_id)

    $args = array(
        'author' => $user_id,
        'post_type' => 'bikers-trips',
        'post_status' => array( 'publish' ),
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    $bikers_trips_list = new WP_Query( $args );

    $user_trips = $bikers_trips_list->posts;

    if( count($user_trips) > 0 ){

        for( $i=0; $i < count($user_trips); $i++ ){
            $trips_data['user_trips'][$i]['trip_id'] = $user_trips[$i]->ID;
            $trips_data['user_trips'][$i]['trip_title'] = $user_trips[$i]->post_title;

            $trips_data['user_trips'][$i]['trip_distance'] = get_post_meta($user_trips[$i]->ID, 'trip_distance')[0];
            $trip_date = get_post_meta($user_trips[$i]->ID, 'trip_date')[0];
//            $trips_data['user_trips'][$i]['trip_date'] = __(date("l j F Y", strtotime($trip_date)), 'bikers-trips');
            $trips_data['user_trips'][$i]['trip_date'] = __(date("Y-m-d\TH:i:s\Z", strtotime($trip_date)), 'bikers-trips');

            $posted_date = get_the_date('U', $user_trips[$i]->ID);
//            $trips_data['user_trips'][$i]['trip_posted_since'] = human_time_diff($posted_date,current_time( 'U' ));
            $trips_data['user_trips'][$i]['trip_posted_since'] = __(date("Y-m-d\TH:i:s\Z", $posted_date), 'bikers-trips');

            $trips_data['user_trips'][$i]['trip_status'] = get_post_meta($user_trips[$i]->ID, 'trip_status')[0];

            $trips_data['user_trips'][$i]['trip_leader_id'] =  $user_trips[$i]->post_author;
            $trips_data['user_trips'][$i]['trip_leader'] =  get_user_meta($user_trips[$i]->post_author, 'user_full_name')[0];

            $trips_data['user_trips'][$i]['trip_maximum_members'] = get_post_meta($user_trips[$i]->ID, 'trip_members_count')[0];

            $trip_id = $user_trips[$i]->ID;

            // get members added in wp_bikers_members
            global $wpdb;
            $studentTable = $wpdb->prefix.'bikers_members';
            $result = $wpdb->get_results ( "SELECT * FROM $studentTable WHERE `trip_id` = $trip_id  ");
            $trips_data['user_trips'][$i]['trip_registered_members'] = strval( count($result) );


            $trips_data['user_trips'][$i]['trip_destination'] = get_post_meta($user_trips[$i]->ID, 'trip_dest')[0];
            $trips_data['user_trips'][$i]['trip_duration'] = get_post_meta($user_trips[$i]->ID, 'trip_duration')[0];

            $trips_data['user_trips'][$i]['start_position'] = !empty( get_post_meta($user_trips[$i]->ID, 'start_position')[0] ) ? get_post_meta($user_trips[$i]->ID, 'start_position')[0] : [];
            $trips_data['user_trips'][$i]['finish_position'] = !empty( get_post_meta($user_trips[$i]->ID, 'finish_position')[0] ) ? get_post_meta($user_trips[$i]->ID, 'finish_position')[0] : [];

        }

        return $trips_data;

    } else {
        return array(
				'user_trips' => array()
			);
    }

}
add_action( 'rest_api_init', function (){

    register_rest_route( API_PATH, '/listUserTrips' , array(
        'methods' => 'GET', // define the method GET or POST
        'callback' => 'listUserTrips', // define the function that we call to get or post these data
    ) );

} );
