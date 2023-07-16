<?php
// Endpoint to list members joined in a trip

function listTripMembers(){
    // get token and other posted data
    // get POSTED data from Flutter form
    $token = ( ! empty($_GET['token']) ) ? $_GET['token'] : null;
    $trip_id = ( ! empty($_GET['trip_id']) ) ? $_GET['trip_id'] : null;


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

    $table_name = $wpdb->prefix . "bikers_members";

    $members_joined_to_trip = $wpdb->get_results( "SELECT * FROM $table_name WHERE `trip_id` = $trip_id" );

    $already_joined_members = count( $members_joined_to_trip );

    if( $already_joined_members === 0 ){
        $members_data = [];
    } else {
        for( $i=0; $i < $already_joined_members; $i++ ){
            $members_data[$i]['name'] = $members_joined_to_trip[$i]->name;
            $members_data[$i]['id_num'] = $members_joined_to_trip[$i]->id_num;
            $members_data[$i]['license_id'] = $members_joined_to_trip[$i]->license_id;
            $members_data[$i]['license_exp'] = $members_joined_to_trip[$i]->license_exp;
            $members_data[$i]['created_at'] = $members_joined_to_trip[$i]->created_at;
        }
    }


    return array(
        'members_data' => array(
            'joined_members_count' => $already_joined_members,
            'trip_id' => $trip_id,
            'members' => $members_data
        )
    );




}

add_action( 'rest_api_init', function (){

    register_rest_route( API_PATH, '/listTripMembers' , array(
        'methods' => 'GET', // define the method GET or POST
        'callback' => 'listTripMembers', // define the function that we call to get or post these data
    ) );

} );