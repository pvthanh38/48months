<?php

namespace SolidApi;


class Orders {
	
	
	static function Merge($data) {
		extract($data);
		global $wpdb;
		$order_ar = explode(',',$order_ids);
		$order_primary = (int)$order_ar[0];
		$prefix = $wpdb->prefix;
		$arr_ids = [];
		$arr_items = [];
		foreach ( $order_ar as $or ) {
			$od = new \WC_Order( $or );
			$items = $od->get_items();
			foreach ( $items as $key=>$item ) {		
			
				$product_id = $item['product_id'];
				$product = wc_get_product( $product_id );
				$product_var_id = wc_get_order_item_meta($key, '_variation_id');
				$product_qty = $item['qty'];
				$arr_id = [$product_id=>$product_var_id];
				if (in_array($arr_id, $arr_ids)) {
					$key_item = array_search($arr_id, $arr_ids);
					$item_id_update = $arr_items[$key_item];
					
					$qty = wc_get_order_item_meta($key,'_qty');
					$qty_update = wc_get_order_item_meta($item_id_update,'_qty');
					$price_total = wc_get_order_item_meta($item_id_update,'_line_total');
					$price_one = $price_total / $qty_update;
					wc_update_order_item_meta($item_id_update, '_line_total',($qty+$qty_update) * $price_one);
					wc_update_order_item_meta($item_id_update, '_qty',$qty+$qty_update);
					wc_delete_order_item( $key );
				}else{
					$arr_ids[] = $arr_id;
					$arr_items[] = $key;
				}
				$querystr = "UPDATE `$prefix"."woocommerce_order_items` SET `order_id` = ".(int)$order_primary." WHERE `order_item_id` = $key";
				$pageposts = $wpdb->get_results($querystr);				

			}
			if($or != $order_primary){
				$order = new \WC_Order($or);
				$order->update_status('wc-cancelled');
				$order->save();
			}
			
		}
		//print_r($arr_items); die;
		
		echo $order_primary; die;
	}
	static function report_sales($data) {
		extract($data);
		if(isset($type) && isset($date)){
			if($date == null) {
				$date = date('Y-m-d');
			}
			$month = date("m",strtotime($date));
			$year = date("Y",strtotime($date));
			$d = new \DateTime();
			
			if($type == "m"){
				$after = date("Y-$month-01");
				$before = date("Y-$month-t"); 
			}
			if($type == "y"){
				$after = date("$year-01-01");
				$before = date("$year-12-t"); 
			}
		}
		if(isset($from) && isset($to)){
			$after = $from;
			$before = $to; 
		}
		$date_query = ['after' => date('Y-m-d', strtotime($after)),
            'before' => date('Y-m-d', strtotime($before)) ];
		$orders = get_posts( array(
			'numberposts' => -1,
			'meta_key'    => '_customer_user',
			'date_query' => $date_query,
			'meta_value'  => $user_id,
			'post_type'   => wc_get_order_types(),
			'post_status' => 'wc-completed',
		));
		$product_sel = [];
		$price_cost = 0;
		$order_total = 0;
		$orders_ar = [];
		$orders_object = [];
		foreach ( $orders as $or ) {
			$orders_object[] = $or;
			$od = new \WC_Order( $or->ID );
			$costs = get_post_meta($or->ID, 'price_cost_'.$or->ID);
			
			if(isset($costs[0])) {
				$price_cost = $price_cost + $costs[0];
			}
			
			$items = $od->get_items();
			$order_date = $od->order_date;
			foreach ( $items as $item ) {
				$product_id = $item['product_id'];
				$product = wc_get_product( $product_id );
				$price_cost_item = get_post_meta($product_id, '_price_cost');
				$totlal_item = get_post_meta($or->ID, '_order_total');
				$product_qty = $item['qty'];
				//$price_cost = isset($price_cost_item[0]) ? ($price_cost_item[0] * $product_qty) + $price_cost : 0;
				$order_total = (int)$order_total + (int)$totlal_item[0];
				if (array_key_exists($product_id, $product_sel)) {
					$product_sel[$product_id]= $product_qty + $product_sel[$product_id];
				}else{
					$product_sel[$product_id]= $product_qty;
				}
			}
		}
		$orders_ar['order_total'] = $order_total;
		$orders_ar['order_cost'] = $price_cost;
		$orders_ar['income'] = $order_total - $price_cost;
		$orders_ar['orders'] = $orders_object;
		echo json_encode($orders_ar); die;
	}
	
	function orders($data) {
		extract($data);
		if($filter == 'new') {
			$order_by = 'DESC';
		}
		if($filter == 'old') {
			$order_by = 'ASC';
		}
		$customer_orders = get_posts( array(
			'numberposts' => -1,
			'meta_key'    => '_customer_user',
			'meta_value'  => $user_id,
			'post_type'   => wc_get_order_types(),
			'order' => $order_by,
			'post_status' => array_keys( wc_get_order_statuses() ),
		));
		$arr_result = [];
		
		if(count($customer_orders) > 0){
			foreach($customer_orders as $o){
				 
				if($filter == 'total_height' || $filter == 'total_low' ){
					$total = get_post_meta($o->ID, '_order_total');
					$arr_result[$o->ID] = $total[0];
					//echo $o->ID.'---'.$total[0].'//';
				}
				if($filter == 'number_more' || $filter == 'small_amount' ){
					$order = new \WC_Order( $o->ID );
					$items = $order->get_items();
					$qty = 0;
					foreach ( $items as $item ) {
						$qty = $qty + $item['qty'];
					}
					$arr_result[$o->ID] = $qty;
				}
				
				
			}	
			
		}
		if($filter == 'total_height' || $filter == 'number_more' || $filter == 'total_low' || $filter == 'small_amount'){
			$orders = [];
			if(count($arr_result) > 0){
				foreach ( $arr_result as $pr_k=>$pr_v ) {
					$arr_result[$pr_k] = (int)$pr_v.'.'.$pr_k;
				}
				if($filter == 'total_height' || $filter == 'number_more' ){
					rsort($arr_result);
				}
				if($filter == 'total_low' || $filter == 'small_amount' ){
					sort($arr_result);
				}
				//print_r($arr_result); die;
				foreach ($arr_result as $pr_k=>$pr_v) {
					$ar = explode('.',$pr_v);
					$order_w = new \WC_Order( $ar[1] );
					
					$order_item = json_decode($order_w, true);
					$order_item['total'] = $ar[0];
					$orders[] = $order_item;
				}
			}
			
			echo json_encode($orders);
		}else{
			echo json_encode($customer_orders);
		}
		
	}
	
	function updateStatus($order_id, $status) {		
		$order = new \WC_Order($order_id);
		$sta = $order->update_status($status);
		if($order->save()) {
			http_response_code(200);
			echo "success"; die;
		}
		http_response_code(500);
		echo "error"; die;
		
	}
}