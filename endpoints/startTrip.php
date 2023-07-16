<?php
// Endpoint to start trip

function startTrip(){
    // get token and other posted data
    // get POSTED data from Flutter form
    $token = ( ! empty($_GET['token']) ) ? $_GET['token'] : null;

    $trip_id = ( ! empty($_POST['trip_id']) ) ? $_POST['trip_id'] : null;
    $start_longitude = ( ! empty($_POST['start_longitude']) ) ? $_POST['start_longitude'] : null;
    $start_latitude = ( ! empty($_POST['start_latitude']) ) ? $_POST['start_latitude'] : null;


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

    if( empty($user_id) ){
        http_response_code(404);
        die();
    }


    $user_full_name = get_the_author_meta('user_full_name', $user_id ) ;

    $trip_status_meta = get_post_meta($trip_id, 'trip_status');
    $trip_status = $trip_status_meta[0];

    if( $trip_status !== 'ready' ){
        return array(
            'success' => false,
            'message' => 'trip is finished, you cant start again'
        );
    }

    // start trip
    // add record in tracking table
    global $wpdb;
    $table_name = $wpdb->prefix . "bikers_tracking";
    $insertTrack = $wpdb->insert( $table_name, array(
        'trip_id' => $trip_id,
        'start_longitude' => $start_longitude,
        'start_latitude' => $start_latitude,
        'status' => 'started',
    ) );

    $insertTrackLive = $wpdb->insert( $table_name, array(
        'trip_id' => $trip_id,
        'start_longitude' => $start_longitude,
        'start_latitude' => $start_latitude,
        'status' => 'live',
    ) );

    if( $insertTrack && $insertTrackLive ){
        // add post meta to trip with status ( live )
        update_post_meta($trip_id, 'trip_status', 'live');
        return array(
          'success' => true,
          'message' => 'trip in now live'
        );
    }


    wp_die();


}
add_action( 'rest_api_init', function (){

    register_rest_route( API_PATH, '/startTrip' , array(
        'methods' => 'POST', // define the method GET or POST
        'callback' => 'startTrip', // define the function that we call to get or post these data
    ) );

} );