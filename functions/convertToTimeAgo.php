<?php

	/* Callback function for post time and date filter hooks */
	function convert_to_time_ago( $orig_time ) {
		//global $post;
		$orig_time = strtotime( $orig_time );
		return human_time_diff( $orig_time, current_time( 'timestamp' ) ).' '.__( 'ago' );
	}

