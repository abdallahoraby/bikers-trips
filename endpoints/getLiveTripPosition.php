<?php
// Endpoint to get trip live tracking data

function getLiveTripPosition(){
    // get token and other posted data
    // get POSTED data from Flutter form
    $token = ( ! empty($_GET['token']) ) ? $_GET['token'] : null;
    $trip_id = ( ! empty($_POST['trip_id']) ) ? $_POST['trip_id'] : null;


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


    // get current members joined to this trip

    global $wpdb;

    $table_name = $wpdb->prefix . "bikers_tracking";

    $trip_live_info_query = $wpdb->get_results( "SELECT * FROM $table_name WHERE `trip_id` = $trip_id" );

    $trip_live_info = count( $trip_live_info_query );

    if( $trip_live_info === 0 ){
        $live_trip_data = [];
    } else {
        $live_trip_data['started']['started_at'] = !empty( $trip_live_info_query[0]->started_at ) ? date("Y-m-d\TH:i:s\Z", strtotime($trip_live_info_query[0]->started_at)) : '';
        $live_trip_data['started']['start_longitude'] = !empty( $trip_live_info_query[0]->start_longitude ) ? $trip_live_info_query[0]->start_longitude : '' ;
        $live_trip_data['started']['start_latitude'] = !empty( $trip_live_info_query[0]->start_latitude ) ? $trip_live_info_query[0]->start_latitude : '';

        $live_trip_data['live']['started_at'] = !empty( $trip_live_info_query[1]->started_at ) ? date("Y-m-d\TH:i:s\Z", strtotime($trip_live_info_query[1]->started_at)) : '';
        $live_trip_data['live']['start_longitude'] = !empty( $trip_live_info_query[1]->start_longitude ) ? $trip_live_info_query[1]->start_longitude : '' ;
        $live_trip_data['live']['start_latitude'] = !empty( $trip_live_info_query[1]->start_latitude ) ? $trip_live_info_query[1]->start_latitude : '';

        $live_trip_data['finished']['started_at'] = !empty( $trip_live_info_query[2]->started_at ) ? date("Y-m-d\TH:i:s\Z", strtotime($trip_live_info_query[2]->started_at)) : '';
        $live_trip_data['finished']['start_longitude'] = !empty( $trip_live_info_query[2]->start_longitude ) ? $trip_live_info_query[2]->start_longitude : '' ;
        $live_trip_data['finished']['start_latitude'] = !empty( $trip_live_info_query[2]->start_latitude ) ? $trip_live_info_query[2]->start_latitude : '';


    }


    return $live_trip_data;




}

add_action( 'rest_api_init', function (){

    register_rest_route( API_PATH, '/getLiveTripPosition' , array(
        'methods' => 'POST', // define the method GET or POST
        'callback' => 'getLiveTripPosition', // define the function that we call to get or post these data
    ) );

} );