<?php
// Endpoint to add new trip

function addTrip(){
    // get token and other posted data
    // get POSTED data from Flutter form
    $token = ( ! empty($_GET['token']) ) ? $_GET['token'] : null;

    $trip_dest = ( ! empty($_POST['trip_dest']) ) ? $_POST['trip_dest'] : null;
    $trip_members_count = ( ! empty($_POST['trip_members_count']) ) ? $_POST['trip_members_count'] : null;
    $trip_date = ( ! empty($_POST['trip_date']) ) ? $_POST['trip_date'] : null;
    $trip_duration = ( ! empty($_POST['trip_duration']) ) ? $_POST['trip_duration'] : null;
    $trip_distance = ( ! empty($_POST['trip_distance']) ) ? $_POST['trip_distance'] : null;
    $start_latitude = ( ! empty($_POST['start_latitude']) ) ? $_POST['start_latitude'] : null;
    $start_longitude = ( ! empty($_POST['start_longitude']) ) ? $_POST['start_longitude'] : null;
    $finish_latitude = ( ! empty($_POST['finish_latitude']) ) ? $_POST['finish_latitude'] : null;
    $finish_longitude = ( ! empty($_POST['finish_longitude']) ) ? $_POST['finish_longitude'] : null;

    $start_position = array(
        'start_latitdue' => $start_latitude,
        'start_longitude' => $start_longitude
    );

    $finish_position = array(
        'finish_latitdue' => $finish_latitude,
        'finish_longitude' => $finish_longitude
    );

    $trip_date_only = date("m/d/Y", strtotime($trip_date));
    $trip_time_only = date("h:m:s", strtotime($trip_date));



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


    $user_full_name = get_the_author_meta('user_full_name', $user_id ) ;




    // add trip data to custom post type
    $my_post = array(
        'post_type'     => 'bikers-trips',
        'post_title'    => $trip_dest,
        'post_content'  => 'Content',
        'post_status'   => 'pending',
        'post_author'   => $user_id,
        'meta_input' => array(
            'trip_dest' => $trip_dest,
            'trip_members_count' => $trip_members_count,
            'trip_date' => $trip_date,
            'trip_date_only' => $trip_date_only,
            'trip_time_only' => $trip_time_only,
            'trip_duration' => $trip_duration,
            'trip_distance' => $trip_distance,
            'trip_status'   => 'ready',
            'start_position' => $start_position,
            'finish_position' => $finish_position
        )
    );

    // Insert the post into the database
    if( wp_insert_post( $my_post ) ){
        return array(
            'message' => 'successfully added trip'
        );
    } else {
        http_response_code(404);
        die();
    }


    wp_die();



}
add_action( 'rest_api_init', function (){

    register_rest_route( API_PATH, '/addTrip' , array(
        'methods' => 'POST', // define the method GET or POST
        'callback' => 'addTrip', // define the function that we call to get or post these data
    ) );

} );