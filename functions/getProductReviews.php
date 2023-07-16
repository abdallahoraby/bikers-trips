<?php

	function getProductReviews($product_id){

		$url = SITE_URL . '/wp-json/wc/v3/products/reviews?product='. $product_id .'&consumer+key=' . CONSUMER_KEY . '&consumer+secret=' . CONSUMER_SECRET;
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
			//$error_message_getProductReviews = $response->get_error_message();
			http_response_code(404);
			die();
		} else {
			$response_getProductReviews = json_decode($response['body']);
			return $response_getProductReviews;
		}
	}