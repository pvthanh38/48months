<?php

namespace SolidApi;

use WP_Query as WP_Query;

class Product {
	
	static function Clone_product ($data)  
	{
		global $wpdb;
		extract($data);
		$arr = [];
		$product = wc_get_product( $product_id );
		$terms = wp_get_post_terms( $product_id, 'product_cat' );
		$terms_ar = [];
		foreach($terms as $ar){
			$terms_ar[] = $ar->term_id;
		}
		$arr['name'] = $product->name;
		$arr['description'] = $product->description;
		$arr['max_price'] = $product->get_regular_price();
		$arr['price_cost'] = get_post_meta( $product_id, '_price_cost', true );
		$arr['max_sale_price'] = $product->get_sale_price();
		$arr['qty'] = get_post_meta( $product_id, '_stock', true );
		$arr['thumbnail'] = get_post_thumbnail_id($product_id);
		$arr['attachment_ids'] = $product->get_gallery_attachment_ids();
		$arr['available_attributes'] = ['size', 'color'];
		$variation_data = Product::get_child_product($product_id);
		$variation_ar = [];
		foreach($variation_data as $val){					
			$product = wc_get_product( $val );
			if($product !== false){
				$object = ['size'=>$product->get_attribute( 'pa_size' ), 'color'=>$product->get_attribute( 'pa_color' )];						
				$object_parent = ['attributes'=>$object, 'price'=>$product->get_regular_price()];						
				$object_parent['price'] = $product->get_regular_price();
				$object_parent['sale_price'] = $product->get_sale_price();
				$attachment_ids = $product->get_gallery_attachment_ids();
				$str_attachment_ids = "";
				if(count($attachment_ids) > 0) {
					foreach($attachment_ids as $att){
						$str_attachment_ids = $str_attachment_ids == "" ? $att : $str_attachment_ids. ','. $att;
					}					
				}
				$object_parent['attachment_ids'] = $str_attachment_ids;
				$object_parent['thumbnail'] = get_post_thumbnail_id($val);
				$object_parent['qty'] = get_post_meta( $val, '_stock', true );
				$variation_ar[] = $object_parent;
			}
		}
		$arr['variations'] = $variation_ar;
		$arr['categories'] = $terms_ar;
		$product = wc_get_product( $product_id );
		$pro_id = self::insert_product($arr);
		
		update_post_meta($pro_id, '_regular_price', $arr['max_price']);
		update_post_meta($pro_id, '_manage_stock', 'yes');
		update_post_meta($pro_id, '_sku', $product->sku.'-'.$pro_id);
		
		update_post_meta($pro_id, '_stock', (int)$arr['qty']);
		update_post_meta($pro_id, '_sale_price', $arr['max_sale_price']);
		
		foreach($terms_ar as $term) {
			wp_set_object_terms($pro_id, $term, 'product_cat');
		}
		
		if(count($arr['attachment_ids']) > 0) {
			foreach($arr['attachment_ids'] as $att){
				$str_attachment_ids = $str_attachment_ids == "" ? $att : $str_attachment_ids. ','. $att;
			}					
		}
		
		set_post_thumbnail( $pro_id, $arr['thumbnail'] );
			
		update_post_meta( $pro_id, '_product_image_gallery', $str_attachment_ids);
		
		echo $pro_id; die;
		
	}
	
	static function Delete_color ($data)  
	{
		global $wpdb;
		extract($data);
		$querystr = "SELECT * FROM `$wpdb->terms` WHERE `name` = '$color' OR `slug` = '$color' limit 0,1";
		$terms = $wpdb->get_results($querystr);
		if(count($terms) > 0){
			$term_key = $terms[0]->slug;
			$term_name = $terms[0]->name;
			wp_remove_object_terms( $product_id, (string)$term_key, 'pa_color' );
			$terms = wp_get_post_terms( $product_id, 'pa_color' );
			$variation_data = self::get_child_product($product_id);
			foreach($variation_data as $val){					
				$product = wc_get_product( $val );
				if($product !== false){
					$color_name = $product->get_attribute( 'pa_color' );
					if($term_name == $color_name){
						wp_delete_post($val);
					}
				}
			}
		}
		\WC_Product_Variable::sync( $product_id );
		echo $product_id; die;
	}
	
	static function CreateProduct($data) {
		$json = str_replace('\\"','"', $data);
		$json = str_replace('\\\"',"'", $json);
		$ar = json_decode($json, true);
		$ar = $ar['products'];
		$ar_size = ['S','M','L','XL','XXL'];
		foreach($ar as $pro) {
			$pro['available_attributes'] = ['size', 'color'];
			$variations = [];
			$variations_compare = [];
			$variations_update_qty = [];
			for($i=0; $i<count($pro['colors']); $i++) {
				$cl = $pro['colors'][$i];
				$variations_child = [];
				$variations_child['price'] = $pro['max_price'];
				$variations_child['sale_price'] = $pro['max_sale_price'];
				$attachment_ids = "";
				$thumbnail = "";
				if ($cl['images'] !== false) {
					foreach($cl['images'] as $i_k=>$image) {
						$post = get_post($image['attachment_id']);
						if($post && $post->post_type == "attachment"){
							$thumbnail = $image['attachment_id'];
							$attachment_ids = $attachment_ids == "" ? $image['attachment_id'] :	$attachment_ids.','.$image['attachment_id'];			
						}
					}
				}
				$variations_child['attachment_ids'] = $attachment_ids;
				$variations_child['thumbnail'] = $thumbnail;
				$size = [];
				$color_child = $cl['data']['value'];
				foreach($cl['sizes'] as $s_k=>$s) {
					$ar_attr = [];
					if($s === true){
						$ar_attr['size'] = $ar_size[$s_k];
						$ar_attr['color'] = $color_child;
						$variations_compare[] = [$ar_size[$s_k]=>$color_child];
						$variations_update_qty[] = $cl['qty'][$s_k];
						$variations_child['attributes'] = $ar_attr;
						$variations_child['qty'] = $cl['qty'][$s_k];					
						$variations[] = $variations_child;
					}
					//thay doi cho nay
					$variations_child = [];
				}	
				
			}
			$pro['variations'] = $variations;
			$product = wc_get_product( $pro['id'] );			
			if( $product === false){
				echo Product::insert_product($pro);	die;			
			}else{
				$pro_id = Product::update_product($pro);
				echo $pro_id; die;
			}
		}
	}
	
	static function get_child_product ($product_id)  
	{
		$variation_data = [];
		$product_variation_child = get_posts( array(
			'numberposts' => -1,
			'post_type'   => 'product_variation',
			'post_parent'      => $product_id,
			'post_status' => 'publish',
			'orderby'          => 'ID',
			'order'            => 'DESC',
		));
		if(count($product_variation_child) > 0){
			foreach($product_variation_child as $val){
				$variation_data[] = $val->ID;
			}
		}
		return $variation_data;
	}
	
	static function update_product ($product_data)  
	{
		$post = array( // Set up the basic post data to insert for our product
			'ID' => isset($product_data['id']) ? $product_data['id'] : '',
			'post_author'  => 1,
			'post_content' => isset($product_data['description']) ? $product_data['description'] : '',
			'post_status'  => 'publish',
			'post_title'   => isset($product_data['name']) ? $product_data['name'] : '',
			'post_parent'  => '',
			'post_type'    => 'product'
		);

		$post_id = wp_update_post($post); // Insert the post returning the new post id

		if (!$post_id) // If there is no post id something has gone wrong so don't proceed
		{
			return false;
		}
		update_post_meta($post_id, '_price_cost', isset($product_data['price_cost']) ? $product_data['price_cost'] : ''); 
		update_post_meta($post_id, '_sku', isset($product_data['sku']) ? $product_data['sku'] : ''); // Set its SKU
		update_post_meta( $post_id,'_visibility','visible'); // Set the product to visible, if not it won't show on the front end

		//wp_set_object_terms($post_id, $product_data['categories'], 'product_cat'); // Set up its categories
		wp_set_object_terms($post_id, 'variable', 'product_type'); // Set it to a variable product type

		Product::insert_product_attributes_n($post_id, $product_data['available_attributes'], $product_data['variations']); // Add attributes passing the new post id, attributes & variations
		Product::insert_product_variations_n($post_id, $product_data['variations']); // Insert variations passing the new post id & variations   
		return $post_id;
	}
	
	static function insert_product ($product_data)  
	{
		global $wpdb;
		$id = $product_data['id'];
		$post = array( // Set up the basic post data to insert for our product
			'post_author'  => 1,
			'post_content' => isset($product_data['description']) ? $product_data['description'] : '',
			'post_status'  => 'publish',
			'post_title'   => isset($product_data['name']) ? $product_data['name'] : '',
			'post_parent'  => '',
			'post_type'    => 'product'
		);
		$post_id = wp_insert_post($post); // Insert the post returning the new post id
		
		/*$querystr = "UPDATE `$wpdb->posts` SET `ID` = '$id' WHERE `wnkp_posts`.`ID` = $post_id;";
		$pageposts = $wpdb->get_results($querystr);
		$post_id = $id;*/
		
		if (!$post_id) // If there is no post id something has gone wrong so don't proceed
		{
			return false;
		}
		update_post_meta($post_id, '_price_cost', isset($product_data['price_cost']) ? $product_data['price_cost'] : ''); 
		update_post_meta($post_id, '_sku', isset($product_data['sku']) ? $product_data['sku'] : ''); // Set its SKU
		update_post_meta( $post_id,'_visibility','visible'); // Set the product to visible, if not it won't show on the front end

		//wp_set_object_terms($post_id, $product_data['categories'], 'product_cat'); // Set up its categories
		wp_set_object_terms($post_id, 'variable', 'product_type'); // Set it to a variable product type

		Product::insert_product_attributes_n($post_id, $product_data['available_attributes'], $product_data['variations']); // Add attributes passing the new post id, attributes & variations
		Product::insert_product_variations_n($post_id, $product_data['variations']); // Insert variations passing the new post id & variations   
		return $post_id;
	}
	
	static function insert_product_attributes_n ($post_id, $available_attributes, $variations)  
	{
		foreach ($available_attributes as $attribute) // Go through each attribute
		{   
			$values = array(); // Set up an array to store the current attributes values.

			foreach ($variations as $variation) // Loop each variation in the file
			{
				$attribute_keys = array_keys($variation['attributes']); // Get the keys for the current variations attributes

				foreach ($attribute_keys as $key) // Loop through each key
				{
					if(isset($key) && $key != ""){
						if ($key === $attribute) // If this attributes key is the top level attribute add the value to the $values array
						{
							$values[] = $variation['attributes'][$key];
						}
					}
				}
			}

			// Essentially we want to end up with something like this for each attribute:
			// $values would contain: array('small', 'medium', 'medium', 'large');

			$values = array_unique($values); // Filter out duplicate values

			// Store the values to the attribute on the new post, for example without variables:
			// wp_set_object_terms(23, array('small', 'medium', 'large'), 'pa_size');
			wp_set_object_terms($post_id, $values, 'pa_' . $attribute);
		}

		$product_attributes_data = array(); // Setup array to hold our product attributes data

		foreach ($available_attributes as $attribute) // Loop round each attribute
		{
			$product_attributes_data['pa_'.$attribute] = array( // Set this attributes array to a key to using the prefix 'pa'

				'name'         => 'pa_'.$attribute,
				'value'        => '',
				'is_visible'   => '1',
				'is_variation' => '1',
				'is_taxonomy'  => '1'

			);
		}

		update_post_meta($post_id, '_product_attributes', $product_attributes_data); // Attach the above array to the new posts meta data key '_product_attributes'
	}
	
	static function insert_product_variations_n ($post_id, $variations)  
	{
		$variation_data = Product::get_child_product($post_id);
		$variation_compare = [];
		$variation_ids = [];
		foreach($variation_data as $val){					
			$product = wc_get_product( $val );
			if($product !== false){
				$ar_compare = ['size'=>$product->get_attribute( 'pa_size' ), 'color'=>$product->get_attribute( 'pa_color' )];						
				$variation_compare[] = $ar_compare;
				$variation_ids[] = $val;
			}
		}
		foreach ($variations as $index => $variation)
		{
			if (!in_array($variation['attributes'], $variation_compare)) {
				$variation_post = array( // Setup the post data for the variation

					'post_title'  => 'Variation #'.$index.' of '.count($variations).' for product#'. $post_id,
					'post_name'   => 'product-'.$post_id.'-variation-'.$index,
					'post_status' => 'publish',
					'post_parent' => $post_id,
					'post_type'   => 'product_variation',
					'guid'        => home_url() . '/?product_variation=product-' . $post_id . '-variation-' . $index
				);

				$variation_post_id = wp_insert_post($variation_post); // Insert the variation
			}else{
				$key = array_search($variation['attributes'], $variation_compare);
				$variation_post_id = $variation_ids[$key];
				unset($variation_ids[$key]);
			}
			

			foreach ($variation['attributes'] as $attribute => $value) // Loop through the variations attributes
			{   
				$attribute_term = get_term_by('name', $value, 'pa_'.$attribute); // We need to insert the slug not the name into the variation post meta

				update_post_meta($variation_post_id, 'attribute_pa_'.$attribute, $attribute_term->slug);
				
			}

			update_post_meta($variation_post_id, '_price', $variation['price']);
			
			update_post_meta($variation_post_id, '_regular_price', $variation['price']);
			update_post_meta($variation_post_id, '_manage_stock', 'yes');
			
			update_post_meta($variation_post_id, '_stock', (int)$variation['qty']);
			update_post_meta($variation_post_id, '_sale_price', $variation['sale_price']);
			if($variation['thumbnail'] != ""){
				set_post_thumbnail( $variation_post_id, $variation['thumbnail'] );
			}
			if($variation['attachment_ids'] != ""){
				update_post_meta( $variation_post_id, '_product_image_gallery', $variation['attachment_ids']);
			}	
			\WC_Product_Variable::sync( $post_id );
		}
		foreach($variation_ids as $val){
			wp_delete_post($val);
		}
	}
	
	static function BestSelling($type = "y", $date) {
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
		$date_query = ['after' => date('Y-m-d', strtotime($after)),
            'before' => date('Y-m-d', strtotime($before)) ];
		$orders = get_posts( array(
			'numberposts' => -1,
			'meta_key'    => '_customer_user',
			'date_query' => $date_query,
			'meta_value'  => $user_id,
			'post_type'   => wc_get_order_types(),
			'post_status' => array('wc-completed'),
		));
		$product_sel = [];
		foreach ( $orders as $or ) {
			$od = new \WC_Order( $or->ID );
			$items = $od->get_items();
			$order_date = $od->order_date;
			foreach ( $items as $item ) {
				$product_id = $item['product_id'];
				$product_qty = $item['qty'];
				if (array_key_exists($product_id, $product_sel)) {
					$product_sel[$product_id]= $product_qty + $product_sel[$product_id];
				}else{
					$product_sel[$product_id]= $product_qty;
				}
				

			}
		}
		$products = [];
		if(count($product_sel) > 0){
			foreach ( $product_sel as $pr_k=>$pr_v ) {
				$product_sel[$pr_k] = (int)$pr_v.'.'.$pr_k;
			}
			rsort($product_sel);
			
			foreach ( $product_sel as $pr_k=>$pr_v ) {
				$ar = explode('.',$pr_v);
				$product = json_decode(get_product( $ar[1] ), true);
				$product['qty'] = $ar[0];
				$products[] = $product;
			}
		}
		echo json_encode($products); die;
	}
	
	static function Product_qty($orderby = "DESC") {
		$args = [
			'post_type' => 'product'
		];
		$q    = new WP_Query( $args );
		$data = [];
		while ( $q->have_posts() ) {
			$q->the_post();
			$id = get_the_ID();
			$stock = (int) get_post_meta( $id, '_stock', true );
			$product_sel[$id] = $stock;
			
		}
		$products = [];
		if(count($product_sel) > 0){
			foreach ( $product_sel as $pr_k=>$pr_v ) {
				$product_sel[$pr_k] = (int)$pr_v.'.'.$pr_k;
			}
			if($orderby == 'ASC'){
				sort($product_sel);
			}
			if($orderby == 'DESC'){
				rsort($product_sel);
			}
			foreach ( $product_sel as $pr_k=>$pr_v ) {
				$ar = explode('.',$pr_v);
				$product = json_decode(get_product( $ar[1] ), true);
				$product['quantity'] = $ar[0];
				$products[] = $product;
			}
		}
		echo json_encode($products); die;
	}
	
	
	
	static function getProducts() {
		$args = [
			'post_type' => 'product'
		];
		$q    = new WP_Query( $args );
		$data = [];
		while ( $q->have_posts() ) {
			$q->the_post();
			$id          = get_the_ID();
			$data[ $id ] = [
				'name' => get_the_title()
			];
		}
	}

	function updateProduct() {
		$code = 'TEST-' . time();
		$data = [
			'sku'         => $code,
			'product_id'  => 281,
			'name'        => 'Test insert',
			'description' => 'Test content',
			'categories'  => [ 37 ],
			'colors'      => [
				'cam'  => [
					'sku' => $code.'-cam',
					'size'   => [ 'xs', 's' ],
					'stocks' => 200,
					'images' => [ 128, 179 ]
				],
				'do-1' => [
					'sku' => $code.'-do',
					'size'   => [ 'xs', 's' ],
					'images' => [ 119, 110 ],
					'stocks' => 201,
				]
			],
			'images'      => [ 128, 179 ],
			'price'       => 100000
		];

		
		$variations = [];
		$totalStock = 0;

		foreach ( $data['colors'] as $key => $color ) {
			foreach ( $color['size'] as $size ) {
				array_push( $variations, [
					'attributes' => [
						'mau-sac' => $key,
						'size'    => $size
					],
					'price'      => $data['price'],
					'sku'      => $color['sku'].'-'.$size,
					'stocks'     => $color['stocks']
				] );
				$totalStock += $color['stocks'];
			}
		}


		$pd = array_merge( $data, [
			"available_attributes" => [ "size", "mau-sac" ],
			'variations'           => $variations,
			'stocks'               => $totalStock
		] );
		$this->removeNoneExistVariation($data['product_id'], $variations);
		return true;

	}

	function deleteVariation( $variation_ids ) {
		//if ( current_user_can( 'edit_products' ) ) {
			foreach ( $variation_ids as $variation_id ) {
				if ( 'product_variation' === get_post_type( $variation_id ) ) {
					$variation = wc_get_product( $variation_id );

					$variation->delete( true );
				}
			}
		//}
		return true;
	}

	function updateVariation( $variationId, $data ) {

	}

	function removeNoneExistVariation($productId, $variations){
		$p     = wc_get_product( $productId );
		$vs    = $p->get_available_variations();
		$variation_ids = [];
		foreach ($vs as $v2){
			$check = false;
			foreach ($variations as $v1){
				$compare = self::compareVariation($v1, $v2);
				if($compare === true) {
					$check = true;					
				}
			}
			if($check === false) {
				$variation_ids[] = $v2['variation_id'];
			}
		}
		$this->deleteVariation($variation_ids);
		return true;
	}

	static function compareVariation($v1, $v2woo){
		$v2_attributes = $v2woo['attributes'];
		$check = true;
		foreach($v1['attributes'] as $k=>$v){
			if (!in_array($v, $v2_attributes)) {
				$check = false;
			}
		}
		return $check;
	}

	function findVariation( $productId, $variation ) {
		$p     = wc_get_product( $productId );
		$vs    = $p->get_available_variations();
		$_variations = [];
		foreach ($variation['attributes'] as $key => $v1){
			if (strpos($key,'attribute_pa_') === false){
				$key = 'attribute_pa_'.$key;
			}
			$_variations[$key] = $v1;
		}

		$variation = $_variations;
		foreach ( $vs as $v ) {
			if ( is_array( $v['attributes'] ) ) {
				$found = empty( array_diff_assoc( $variation, $v['attributes'] ) );
				if ( $found ) {
					return $v;
				}
			}
		}

		return false;
	}

	function insertProduct( $product_data ) {
		if (!isset($product_data['product_id']) || $product_data['product_id'] == false || !wc_get_product($product_data['product_id'])){
			$post = array( // Set up the basic post data to insert for our product
				'post_author'  => 1,
				'post_content' => $product_data['description'],
				'post_status'  => 'publish',
				'post_title'   => $product_data['name'],
				'post_parent'  => '',
				'post_type'    => 'product'
			);

			$post_id = wp_insert_post( $post ); // Insert the post returning the new post id

			if ( ! $post_id ) // If there is no post id something has gone wrong so don't proceed
			{
				return false;
			}
		}
		else{
			$post_id= $product_data['product_id'];
		}

		update_post_meta( $post_id, '_sku', $product_data['sku'] ); // Set its SKU
		update_post_meta( $post_id, '_visibility', 'visible' ); // Set the product to visible, if not it won't show on the front end
		update_post_meta( $post_id, '_manage_stock', 'yes' ); // Set the product to visible, if not it won't show on the front end
		update_post_meta( $post_id, '_stock', $product_data['stocks'] ); // Set the product to visible, if not it won't show on the front end
		if ( is_array( $product_data['images'] ) ) {
			if ( isset( $product_data['images'][0] ) ) {
				update_post_meta( $post_id, '_thumbnail_id', $product_data['images'][0] );
			}
			if ( isset( $product_data['images'][1] ) ) {
				fw_set_db_post_option( $post_id, 'back_img', [
					'attachment_id' => $product_data['images'][1],
					'url' => wp_get_attachment_image_src($product_data['images'][1],'full')
				] );
			}
		}

		$p     = wc_get_product( $post_id );
		$vs    = $p->get_available_variations();

		foreach ($product_data['variations'] as $v){
			if (!$this->findVariation($post_id,$v)){
				$this->deleteVariation([]);
			}
		}

		wp_set_object_terms( $post_id, $product_data['categories'], 'product_cat' ); // Set up its categories
		wp_set_object_terms( $post_id, 'variable', 'product_type' ); // Set it to a variable product type




		$this->insert_product_attributes( $post_id, $product_data['available_attributes'], $product_data['variations'] ); // Add attributes passing the new post id, attributes & variations
		$this->insert_product_variations( $post_id, $product_data['variations'] ); // Insert variations passing the new post id & variations

		return true;
	}

	function insert_product_attributes( $post_id, $available_attributes, $variations ) {
		foreach ( $available_attributes as $attribute ) // Go through each attribute
		{
			$values = array(); // Set up an array to store the current attributes values.

			foreach ( $variations as $variation ) // Loop each variation in the file
			{
				$attribute_keys = array_keys( $variation['attributes'] ); // Get the keys for the current variations attributes

				foreach ( $attribute_keys as $key ) // Loop through each key
				{
					if ( $key === $attribute ) // If this attributes key is the top level attribute add the value to the $values array
					{
						$values[] = $variation['attributes'][ $key ];
					}
				}
			}

			// Essentially we want to end up with something like this for each attribute:
			// $values would contain: array('small', 'medium', 'medium', 'large');

			$values = array_unique( $values ); // Filter out duplicate values

			// Store the values to the attribute on the new post, for example without variables:
			// wp_set_object_terms(23, array('small', 'medium', 'large'), 'pa_size');
			wp_set_object_terms( $post_id, $values, 'pa_' . $attribute );
		}

		$product_attributes_data = array(); // Setup array to hold our product attributes data

		foreach ( $available_attributes as $attribute ) // Loop round each attribute
		{
			$product_attributes_data[ 'pa_' . $attribute ] = array( // Set this attributes array to a key to using the prefix 'pa'

				'name'         => 'pa_' . $attribute,
				'value'        => '',
				'is_visible'   => '1',
				'is_variation' => '1',
				'is_taxonomy'  => '1'

			);
		}

		update_post_meta( $post_id, '_product_attributes', $product_attributes_data ); // Attach the above array to the new posts meta data key '_product_attributes'
	}

	function insert_product_variations( $post_id, $variations ) {
		foreach ( $variations as $index => $variation ) {
			if ($found = $this->findVariation($post_id, $variation)){
				$variation_post_id = $found['variation_id'];
			}
			else{
				$variation_post = array( // Setup the post data for the variation

					'post_title'  => 'Variation #' . $index . ' of ' . count( $variations ) . ' for product#' . $post_id,
					'post_name'   => 'product-' . $post_id . '-variation-' . $index,
					'post_status' => 'publish',
					'post_parent' => $post_id,
					'post_type'   => 'product_variation',
					'guid'        => home_url() . '/?product_variation=product-' . $post_id . '-variation-' . $index
				);

				$variation_post_id = wp_insert_post( $variation_post ); // Insert the variation
			}

			if ( $variation_post_id ) {
				foreach ( $variation['attributes'] as $attribute => $value ) // Loop through the variations attributes
				{
					$attribute_term = get_term_by( 'slug', $value, 'pa_' . $attribute ); // We need to insert the slug not the name into the variation post meta

					update_post_meta( $variation_post_id, 'attribute_pa_' . $attribute, $attribute_term->slug );
					// Again without variables: update_post_meta(25, 'attribute_pa_size', 'small')
				}

				update_post_meta( $variation_post_id, '_price', $variation['price'] );
				update_post_meta( $variation_post_id, '_regular_price', $variation['price'] );

				$stock = $variation['stocks'];
				$sku = $variation['sku'];
				if ( $stock > 0 ) {
					update_post_meta( $variation_post_id, '_manage_stock', 'yes' ); // Set the product to visible, if not it won't show on the front end
					update_post_meta( $variation_post_id, '_stock', $stock ); // Set the product to visible, if not it won't show on the front end
				}
				if (isset($sku)){
					update_post_meta( $variation_post_id, '_sku', $sku ); // Set the product to visible, if not it won't show on the front end
				}
			}
		}
	}

	function insert_products( $products ) {
		if ( ! empty( $products ) ) // No point proceeding if there are no products
		{
			array_map( 'insertProduct', $products ); // Run 'insert_product' function from above for each product
		}
	}
	
	function deleteProduct( $product_ids ) {
		$arr = explode(',',$product_ids);
		foreach ( $arr as $id ) {
			wp_delete_post( trim($id));
		}
		return true;
	}
}