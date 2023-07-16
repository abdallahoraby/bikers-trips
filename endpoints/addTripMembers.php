<?php
// Endpoint to add new members to a trip

function addTripMembers(){
    // get token and other posted data
    // get POSTED data from Flutter form
    $token = ( ! empty($_GET['token']) ) ? $_GET['token'] : null;
    $member_name = ( ! empty($_POST['member_name']) ) ? $_POST['member_name'] : null;
    $member_id_num = ( ! empty($_POST['member_id_num']) ) ? $_POST['member_id_num'] : null;
    $member_license_id = ( ! empty($_POST['member_license_id']) ) ? $_POST['member_license_id'] : null;
    $member_license_exp = ( ! empty($_POST['member_license_exp']) ) ? $_POST['member_license_exp'] : null;
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


    // get members limit by trip_id
    $trip_members_count = get_post_meta($trip_id, 'trip_members_count')[0];

    // get current members joined to this trip
    global $wpdb;

    $table_name = $wpdb->prefix . "bikers_members";

    $members_joined_to_trip = $wpdb->get_results( "SELECT * FROM $table_name WHERE `trip_id` = $trip_id" );

    $already_joined_members = count( $members_joined_to_trip );

    if( $already_joined_members >= $trip_members_count ){
        return array(
            'status' => false,
            'message' => 'no members available to join'
        );
    } else {
        // insert member data to table ( there's available slots in trip )
        $wpdb->insert( $table_name, array(
            'name' => $member_name,
            'id_num' => $member_id_num,
            'license_id' => $member_license_id,
            'license_exp' => $member_license_exp,
            'trip_id' => $trip_id,
        ) );

        return array(
            'status' => true,
            'message' => 'member data added successfully'
        );

        wp_die();

    }





}
add_action( 'rest_api_init', function (){

    register_rest_route( API_PATH, '/addTripMembers' , array(
        'methods' => 'POST', // define the method GET or POST
        'callback' => 'addTripMembers', // define the function that we call to get or post these data
    ) );

} );