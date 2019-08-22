<?php

namespace SolidApi;
if ( ! function_exists( 'wp_handle_upload' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
}
use WP_Query as WP_Query;

class Users {
	
	function orders($user_id) {
		
		echo get_current_user_id(); die;
		$customer_orders = get_posts( array(
			//'numberposts' => -1,
			
			'meta_key'    => '_customer_user',			
			'meta_value'  => $user_id,
			'post_type'   => wc_get_order_types(),
			
			'post_status' => array_keys( wc_get_order_statuses() ),
			'orderby' => 'ID',
            'order' => 'ASC',
		) );
		echo json_encode($customer_orders);
		
	}
	function createUser($request) {
		extract($request);
		$parts = explode("@", "$email");
		$username = $parts[0];

		$user = wp_create_user( $username, '1234@qwer', $email );
		print_r($user); die;
	}
	
	function updateUser($request) {
		extract($request);
		global $wpdb;
		$arr = [];
		$data = (array)  get_userdata($user_id);
		$users = (array)$data['data'];
		$arr['ID'] = $user_id;
		$arr['user_url'] = isset($website) ? $website : $users['user_url'];
		$arr['user_email'] = isset($email) ? $email : $users['user_email'];
		$arr['display_name'] = isset($last_name) ? $last_name : $users['display_name'];
		$arr['user_nicename'] = isset($first_name) ? $first_name : $users['user_nicename'];
		$arr['user_login'] = isset($user_name) ? (string)$user_name : $users['user_login'];
		$arr['user_pass'] = isset($user_pass) ? $user_pass : $users['user_pass'];
		$user_id_update = wp_update_user( $arr );
		update_usermeta( $user_id, 'phone', $phone );
		update_usermeta( $user_id, 'address', $address );
		//print_r($arr); die;
		
		if ( is_wp_error( $user_id_update ) ) {
			http_response_code(500);
			echo "error"; die;
		} else {
			http_response_code(200);
			$wpdb->update($wpdb->users, array('user_login' => (string) $arr['user_login']), array('ID' => $user_id));
			$meta_phone = get_user_meta( $user_id, 'phone' )[0];
			$data = (array)  get_userdata($user_id);
			$users = (array)$data['data'];
			$users['phone'] = $meta_phone;
			$meta_address = get_user_meta( $user_id, 'address' )[0];
			$users['address'] = $meta_address;
			echo json_encode($users); die;
		}	
	}
	
	function updateUserPass($request) {
		extract($request);
		http_response_code(500);
		global $wpdb;
		$arr = [];
		$arr['user_login'] = isset($user_name) ? (string)$user_name : '0';
		$user_pass_old = isset($user_pass_old) ? $user_pass_old : "0";
		$login = wp_login($arr['user_login'], $user_pass_old);
		if($login === false) {
			echo json_encode("user name or password error"); die;
		}
		$user = get_userdatabylogin($arr['user_login']);
		if($user){
		   $arr['ID'] = $user->ID;
		}
		
		$data = (array)  get_userdata($arr['ID']);
		$users = (array)$data['data'];
		$arr['user_pass'] = isset($user_pass) ? $user_pass : $users['user_pass'];
		$user_id_update = wp_update_user( $arr );
		
		if ( is_wp_error( $user_id_update ) ) {
			
			echo json_encode("error"); die;
		} else {
			http_response_code(200);
			echo json_encode($users); die;
		}	
	}
}