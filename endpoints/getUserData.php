<?php

// Endpoint to get user info and jwt into object

function getUserData(){

    $loginStatus = array();

    // get POSTED data from Flutter form
    $token = ( ! empty($_GET['token']) ) ? $_GET['token'] : null;

    // function to get user avatar profile image
    function getUserAvatar($user_id){
        return ( ! empty(get_avatar_url($user_id)) ) ? get_avatar_url($user_id) : '';
    }

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

    //$user_info = get_userdata($user_id);


    // Bikers add user data
    $responseData['user_data']['user_full_name'] = ( !empty(get_the_author_meta( 'user_full_name', $user_id ) ) ) ? get_the_author_meta( 'user_full_name', $user_id ) : '';
    $responseData['user_data']['user_id_num'] = ( !empty(get_the_author_meta( 'user_id_num', $user_id ) ) ) ? get_the_author_meta( 'user_id_num', $user_id ) : '';
    $responseData['user_data']['user_license_id'] = ( !empty(get_the_author_meta( 'user_license_id', $user_id ) ) ) ? get_the_author_meta( 'user_license_id', $user_id ) : '';
    $responseData['user_data']['user_license_exp'] = ( !empty(get_the_author_meta( 'user_license_exp', $user_id ) ) ) ? get_the_author_meta( 'user_license_exp', $user_id ) : '';

    return $responseData;

    die();
}
add_action( 'rest_api_init', function (){

    register_rest_route( API_PATH, '/getUserData' , array(
        'methods' => 'GET', // define the method GET or POST
        'callback' => 'getUserData', // define the function that we call to get or post these data
    ) );

} );