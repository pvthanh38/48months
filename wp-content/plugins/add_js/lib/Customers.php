<?php

namespace SolidApi;

class Customers {
	
	/*function customers() {
		$customer_orders = get_posts( array(
			'numberposts' => -1,
			'post_type'   => wc_get_order_types(),
			'post_status' => array_keys( wc_get_order_statuses() ),
		));
		$users = [];
		$customers = [];
		foreach($customer_orders as $order) {
			$od = new \WC_Order( $order->ID );
			$user_id = $od->user_id;
			$arr_order = json_decode($od, true);
			
			if (!in_array($user_id, $customers)) {
				$customers[] = $user_id;
				$data = (array)  get_userdata($user_id);
				$users_one = (array)$data['data'];
				$users_one['billing'] = $arr_order['billing'];
				$users[] = $users_one;
			}
			
		}
		echo json_encode($users);
		
	}*/
	function create_customers($data) {
		extract($data);
		$parts = explode("@", "$email");
		$username = $parts[0];
		$password = isset($password) ? $password : $username;
		if(!isset($email) || $email == ""){
			echo "email not null"; die;
		}
		$user = wp_create_user( $username, $password, $email );		
		if(is_int($user)){
			$arr = [];			
			$arr['ID'] = $user;
			$arr['user_url'] = isset($website) ? $website : '';
			$arr['user_email'] = isset($email) ? $email : '';
			$arr['display_name'] = isset($last_name) ? $last_name : '';
			$arr['user_nicename'] = isset($first_name) ? $first_name : $username;
			$arr['user_login'] = isset($username) ? (string)$username : $username;
			$arr['user_pass'] = isset($user_pass) ? $user_pass : $users['user_pass'];
			$user_id_update = wp_update_user( $arr );
			update_usermeta( $user, 'phone', isset($phone) ? $phone : '' );
			update_usermeta( $user, 'address', isset($address) ? $address : '' );
			$u = new \WP_User( $user );
			$u->remove_role( 'subscriber' );
			$u->add_role( 'customer' );
			echo $user; die;
		}else{
			echo "error"; die;
		}
		
	}
	
	function customers($orderby = "DESC") {
		$customer_orders = get_posts( array(
			'numberposts' => -1,
			'post_type'   => wc_get_order_types(),
			'post_status' => array_keys( wc_get_order_statuses() ),
		));
		$users = [];
		$customers = [];
		foreach($customer_orders as $order) {
			$od = new \WC_Order( $order->ID );
			$user_id = $od->user_id;
			$arr_order = json_decode($od, true);
			if (!in_array($user_id, $customers)) {
				$customers[] = $user_id;
				$cus = get_posts( array(
					'numberposts' => -1,
					'meta_key'    => '_customer_user',
					'meta_value'  => $user_id,
					'post_type'   => wc_get_order_types(),
					'post_status' => array_keys( wc_get_order_statuses() ),
				) );
				if(count($cus) > 0){
					$order_total = 0;
					foreach($cus as $o){
						$total = get_post_meta($o->ID, '_order_total');
						$order_total = $order_total + $total[0];
					}	
					$arr_child['order_id'] = $order->ID;
					$arr_child['total'] = $order_total;
				}
				$arr_result[] = $arr_child;
			}			
		}
		
		if($orderby == 'ASC'){
			sort($arr_result);
		}
		if($orderby == 'DESC'){
			rsort($arr_result);
		}
		$user_ids = [];
		foreach($arr_result as $ar_k => $ar_v) {
			$od = new \WC_Order( $ar_v['order_id'] );
			$user_id = $od->user_id;
			$arr_order = json_decode($od, true);
			$data = (array)  get_userdata($user_id);
				$users_one = (array)$data['data'];
				if(isset($users_one['ID'])) {
					$user_ids[] = $users_one['ID'];
				}
				
				$users_one['billing'] = $arr_order['billing'];
				$users_one['billing']['total'] = $ar_v['total'];
				$users[] = $users_one;
		}
		$args = array(
			'role' => 'customer',
			'orderby' => 'user_nicename',
			'order' => 'ASC'
		  );
		$usr = get_users($args);
		foreach($usr as $u) {
			if(!in_array($u->ID, $user_ids)){
				$data = (array)  get_userdata($u->ID);
				$users_one = (array)$data['data'];
				$users[] = $users_one;
			}
		}
		
		echo json_encode($users);
		
	}
	
	function customers_search_phone($phone) {
		$users = [];
		$user_ids = [];
		$args = array(
			'role' => 'customer',
			'meta_query' => array(
				array(
					'key' => 'billing_phone',
					'value' => "$phone",
					'compare' => 'LIKE'
				)
			),
			'orderby' => 'user_nicename',
			'order' => 'ASC'
		  );
		$usr = get_users($args);
		foreach($usr as $u) {
			$user_ids[] = $u->ID;
			$cus_id = $u->ID;
			$phone_cus = get_user_meta( $cus_id, 'billing_phone', true );
			$data = (array)  get_userdata($cus_id);
			$users_one = (array)$data['data'];
			$users_one['billing']['phone'] = $phone_cus;
			$users[] = $users_one;
		}
	
		$customer_orders = get_posts( array(
			'numberposts' => -1,
			'post_type'   => wc_get_order_types(),
			'post_status' => array_keys( wc_get_order_statuses() ),
			'meta_query' => array(
				array(
					'key' => '_billing_phone',
					'value' => "$phone",
					'compare' => 'LIKE'
				)
			)
		));
		foreach($customer_orders as $order) {
			$od = new \WC_Order( $order->ID );
			$user_id = $od->user_id;
			$arr_order = json_decode($od, true);
			
			if (!in_array($user_id, $user_ids)) {
				$user_ids[] = $user_id;
				$data = (array)  get_userdata($user_id);
				$users_one = (array)$data['data'];
				$users_one['billing'] = $arr_order['billing'];
				$users[] = $users_one;
			}
			
		}
		echo json_encode($users);
		
	}
}