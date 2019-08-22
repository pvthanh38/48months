<?php

namespace SolidApi;
if ( ! function_exists( 'wp_handle_upload' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
}
use WP_Query as WP_Query;

class Attachment {
	
	function delete_attachment($attachment_id) {
		if ( false === wp_delete_attachment( $attachment_id ) ){
			echo false; die;
		}

		echo true; die;
	}
}