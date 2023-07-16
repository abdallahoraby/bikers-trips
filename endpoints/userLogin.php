<?php

// Endpoint to login and get user data

function userLogin(){


	$loginStatus = array();

	// get POSTED data from Flutter form
	$email = ( ! empty($_POST['email']) ) ? $_POST['email'] : null;
	$password = ( ! empty($_POST['password']) ) ? $_POST['password'] : null;

	// function to get jwtToken
	function getJWT($email, $password){
		$url = SITE_URL . '/?rest_route=/simple-jwt-login/v1/auth&email=' . $email .'&password=' . $password;
		$get_user_jwt = array(
			'email'         => $email,
			'password'      => $password
		);
		$response = wp_remote_post( $url, array(
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(),
				'body'        => '',
				'cookies'     => array()
			)
		);

		if ( is_wp_error( $response ) OR json_decode($response['body'])->success !== true ) {
			return $response->get_error_message();
			//http_response_code(404);
			die();
		} else {
			return json_decode($response['body'])->data->jwt;
		}

		unset($url);
		unset($response);
	}

	// function to get user avatar profile image
	function getUserAvatar($user_id){
		return ( ! empty(get_avatar_url($user_id)) ) ? get_avatar_url($user_id) : '';
	}

	$jwtToken = getJWT($email, $password);


	$url = SITE_URL . '/wp-json/simple-jwt-login/v1/auth/validate?JWT=' . $jwtToken;
	$response = wp_remote_post( $url, array(
			'method'      => 'GET',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'body'        => '',
			'cookies'     => array()
		)
	);

	if ( is_wp_error( $response ) OR json_decode($response['body'])->success !== true ) {
		//return $response->get_error_message();
		http_response_code(404);
        die();
	} else {
		$response_user_data = json_decode($response['body']);
	}


	unset($url);
	unset($response);


	$userData = $response_user_data->data->user;
	$user_id = $userData->ID;
	$userData->user_avatar = getUserAvatar($user_id);
	$userData->jwt = $jwtToken;
	$responseData['user_data'] = $userData;


	$user_info = get_userdata($user_id);
	$userData->first_name = $user_info->first_name;
	$userData->last_name = $user_info->last_name;
	$userData->full_name = $user_info->first_name . ' ' .$user_info->last_name;

	unset($userData->user_login);
	unset($userData->user_nicename);
	unset($userData->user_url);
	unset($userData->user_activation_key);
	unset($userData->user_status);

	// Bikers add user data
    $responseData['user_data']->user_full_name = get_the_author_meta( 'user_full_name', $user_id );
    $responseData['user_data']->user_id_num = get_the_author_meta( 'user_id_num', $user_id );
    $responseData['user_data']->user_license_id = get_the_author_meta( 'user_license_id', $user_id );
    $responseData['user_data']->user_license_exp = get_the_author_meta( 'user_license_exp', $user_id );

	return $responseData;


	die();
}
add_action( 'rest_api_init', function (){

	register_rest_route( API_PATH, '/userLogin' , array(
		'methods' => 'POST', // define the method GET or POST
		'callback' => 'userLogin', // define the function that we call to get or post these data
	) );

} );