<?php

// function to use google auto translate

function getTranslation($text_to_translate){

	$data = array(
		'q' => $text_to_translate,
		'source' => 'en',
        'target' => 'ar',
        'format' => 'text'
	);



	$url = 'https://translation.googleapis.com/language/translate/v2?key=' . GOOGLE_TRANSLATE_API;
	$response = wp_remote_post( $url, array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'body'        => $data,
			'cookies'     => array()
		)
	);

	if ( is_wp_error( $response ) ) {
		//$error_message = $response->get_error_message();
		http_response_code(404);
		die();
	} else {
		$response_translate = json_decode($response['body'])->data->translations[0]->translatedText;
		return $response_translate;
	}

}
