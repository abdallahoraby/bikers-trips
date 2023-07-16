<?php
// Endpoint to track trip

function trackTrip(){
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

    if ( FALSE === get_post_status( $trip_id ) ) {
        // The post does not exist
        return array(
            'success' => false,
            'message' => 'trip is not exist'
        );
    }

    $trip_status_meta = get_post_meta($trip_id, 'trip_status');
    $trip_status = $trip_status_meta[0];

    if( $trip_status !== 'live' ){
        return array(
            'success' => false,
            'message' => 'trip is finished, you cant track now'
        );
    }

    // start trip
    // add record in tracking table
    global $wpdb;
    $table_name = $wpdb->prefix . "bikers_tracking";

    $updated_at = date('Y-m-d H:i:s');

    // update live record in tracking table
    $table_name = $wpdb->prefix . "bikers_tracking";
    $trackLive = $wpdb->query($wpdb->prepare("UPDATE $table_name SET start_longitude='$start_longitude', start_latitude='$start_latitude', started_at='$updated_at' WHERE trip_id= %d AND status LIKE %s", array( $trip_id, 'live') ) );



    if( $trackLive ){
        // add post meta to trip with status ( live )
        return array(
            'success' => true,
            'message' => 'trip is updated successfully'
        );
    }


    wp_die();


}
add_action( 'rest_api_init', function (){

    register_rest_route( API_PATH, '/trackTrip' , array(
        'methods' => 'POST', // define the method GET or POST
        'callback' => 'trackTrip', // define the function that we call to get or post these data
    ) );

} );