<?php
// Endpoint to Update user profile

function updateUserProfile(){

	$token = ( ! empty($_GET['token']) ) ? $_GET['token'] : null;

	$user_full_name = ( ! empty($_POST['user_full_name']) ) ? $_POST['user_full_name'] : null;
	$user_id_num = ( ! empty($_POST['user_id_num']) ) ? $_POST['user_id_num'] : null;
	$user_license_id = ( ! empty($_POST['user_license_id']) ) ? $_POST['user_license_id'] : null;
	$user_license_exp = ( ! empty($_POST['user_license_exp']) ) ? $_POST['user_license_exp'] : null;


	$url = SITE_URL . '/wp-json/api/flutter_user/get_currentuserinfo?token=' . $token;
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

	if ( is_wp_error( $response ) ) {
		//$error_message_user = $response->get_error_message();
		http_response_code(404);
		die();
	} else {
		$user_data = json_decode($response['body']);
		$user_id = $user_data->user->id;
	}

	if( !isset($user_id) && empty($user_id) ){
//		return array(
//			'message' => 'User ID not found'
//		);
		http_response_code(404);
		die();
	}


	unset($url);
	unset($response);



	// Update Bikers User meta
    if( isset($user_full_name) ){
        update_user_meta( $user_id, 'user_full_name', $user_full_name );
    }else{
        delete_user_meta($user_id, 'user_full_name');
    }

    if( isset( $user_id_num )){
        update_user_meta( $user_id, 'user_id_num', $user_id_num );
    }else{
        delete_user_meta($user_id, 'user_id_num');
    }

    if( isset($user_license_id) ){
        update_user_meta( $user_id, 'user_license_id', $user_license_id );
    }else{
        delete_user_meta($user_id, 'user_license_id');
    }

    if( isset( $user_license_exp ) ){
        update_user_meta( $user_id, 'user_license_exp', $user_license_exp );
    }else{
        delete_user_meta($user_id, 'user_license_exp');
    }

    return array(
        'status' => 'success',
        'message' => 'user personal data updated successfully'
    );


}
add_action( 'rest_api_init', function (){

	register_rest_route( API_PATH, '/updateUserProfile' , array(
		'methods' => 'POST', // define the method GET or POST
		'callback' => 'updateUserProfile', // define the function that we call to get or post these data
	) );

} );