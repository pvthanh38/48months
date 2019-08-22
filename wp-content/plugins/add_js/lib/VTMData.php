<?php
namespace SolidApi;
require_once(ABSPATH . 'wp-content/plugins/import/lib/VTMCrawler.php');
require_once(ABSPATH . 'wp-content/plugins/import/lib/VTMPost.php');
require_once(ABSPATH . 'wp-content/plugins/import/lib/VTMWoo.php');
class VTMData {
	static function get_cronjob() {
		global $wpdb;
		header('Content-Type: text/plain; charset=UTF-8');
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}cronjob WHERE status IS NULL ORDER BY id DESC limit 0,50", OBJECT );
		return $results;
	}
	static function update_cronjob($id, $status) {
		global $wpdb;
		header('Content-Type: text/plain; charset=UTF-8');
		$table_name = $wpdb->prefix . "cronjob";
		$ereminders = $wpdb->get_results( "
			UPDATE $table_name
			SET status = '$status'
			WHERE id = $id
		");
		return $ereminders;
	}
	Static function Log_f($str) {
		$logfile = fopen(ABSPATH . "wp-content/plugins/import/log.txt", "a") or die("Unable to open file!");
		$txt = $str."\n";
		fwrite($logfile, $txt);
		fclose($logfile);
	}
	Static function update_or_insert_post( $v ) {
		$VTMCrawle = new VTMCrawler();
		$post = new VTMPost();
		$woo = new VTMWoo();
		$metas = Self::arr_meta($v);
		$cat = explode(",", $v['category']);
		$data_post = [
			'post_title'    => $v['title'],
			'post_content'    => $v['descriptions'],
			'post_type'     => $v['type'],
			'post_status'   => 'publish',
			'feature_image' => $v['feature_image'], // accept remote url & absolute url
			'terms'         => [
				
			],
			'metas'         => $metas,
			/*'variations'    => [
				[
					'attributes' => [
						'mau-sac' => 'Yellow',
						'size' => 'XL',
						'type1' => 'Vai',
					],
					'regular_price' => $v['price']
				],
				[
					'attributes' => [
						'mau-sac' => 'Blue'
					],
					'regular_price' => $v['price'],
					'sale_price' => $v['price']
				],
				[
					'attributes' => [
						'mau-sac' => 'Red'
					],
					'regular_price' => $v['price']
				]
			]*/
		];
		if($v['taxonomy_custom'] != ""){
			$taxonomy_custom = json_decode($v['taxonomy_custom'], true);
			foreach($taxonomy_custom as $mt=>$mtv){
				$data_post['terms'][$mt][]  = $mtv;
			}
		}
		if($v['type'] == 'product'){
			$data_post['terms']['product_cat']  = $cat;
			$data_post['terms']['product_tag']  = explode("|", $v['tags']);
			$post_id = Self::get_product_by_sku($v['sku']);
			if($v['attribute'] != ""){
				$attribute = Self::arr_attribute($v);
				//print_r($attribute); die;
				$data_post['variations'] = $attribute;
			}
			if($post_id != null){
				$data_post[ID] = $post_id;				
			}
			$data_post['regular_price'] = $v['price'];
			$data_post['sale_price'] = $v['sale_price'];
			$woo->saveProduct($data_post);
			if ( $woo->getId() ) {
				$post_id = $woo->getId();
				Self::update_cronjob($v['id'],"success");
				update_post_meta($post_id, '_sku', $v['sku']);
			}else{
				Self::update_cronjob($v['id'],"error");
				$v['title'] = $v['title'] == "" ? 'Title rổng' : $v['title'];
				if($v['id'] && $v['id'] != "" ){
					self::Log_f($v['id']."|".$v['title']."|".date('d-m-Y'));
				}
			}
		}else{
			$data_post['terms']['category']  = $cat;
			$data_post['terms']['post_tag']  = explode("|", $v['tags']);
			$post_id = Self::get_product_by_title($v['title'], $v['descriptions']);
			if($post_id != null){
				$data_post[ID] = $post_id;
			}
			$post->savePost( $data_post );
			if ( $post->getId() ) {
				$post_id = $post->getId();
				Self::update_cronjob($v['id'],"success");
			}else{
				Self::update_cronjob($v['id'],"error");
				$v['title'] = $v['title'] == "" ? 'Title rổng' : $v['title'];
				if($v['id'] && $v['id'] != "" ){
					self::Log_f($v['id']."|".$v['title']."|".date('d-m-Y'));
				}
			}
			
		}
		$v['post_id'] = $post_id;
		Self::save_attribute($v);
		//print_r($data_post);
		
		return $post_id;
	}
	Static function save_attribute( $v ) {
		if($v['attribute'] != ""){
			$attribute  = json_decode(trim($v['attribute']), true);
			//print_r($attribute); die;
			foreach($attribute as $k=>$va){
				wp_set_object_terms($v['post_id'], explode("|", $va), 'pa_' . $k);
			}
			return true;
		}
		return NULL;
	}
	Static function arr_attribute( $v ) {
		if($v['variation'] != ""){
			$arr_var  = explode(",", trim($v['variation']));
			//
			$att = [];
			foreach($arr_var as $k1=>$v1){
				$arr_var1  = explode("|", trim($v1));
				
				$attributes = [];
				foreach($arr_var1 as $k2=>$v2){
					$arr_var2 = explode(":", trim($v2));
					//print_r($arr_var2); die;
					if(count($arr_var2) == 2 && $arr_var2[0] != 'price'){
						$attributes['attributes'][trim($arr_var2[0])] = trim($arr_var2[1]);
					}
					if(count($arr_var2) == 2 && trim($arr_var2[0]) == 'price'){
						$attributes['regular_price'] = trim($arr_var2[1]);
					}
				}
				$att[] = $attributes;
			}
			return $att;
		}
		return Null;
	}
	Static function arr_meta( $v ) {
		$metas = [];
		if($v['meta'] != ""){
			$meta = json_decode($v['meta'], true);
			foreach($meta as $mt=>$mtv){
				$metas[$mt] = ["value"=>$mtv];
			}
		}
		
		if($v['acf'] != ""){
			$acf = json_decode($v['acf'], true);
			foreach($acf as $mt=>$mtv){
				$metas[$mt] = ['type' => 'acf', "value"=>$mtv];
			}
			
		}
		return $metas;
	}
	Static function get_product_by_title( $title, $post_content ) {
		header('Content-Type: text/plain; charset=UTF-8');
		global $wpdb;
		//echo 'SELECT ID FROM ' . $wpdb->posts . ' WHERE post_title = "'.$title.'" and post_content ="'.$post_content.'"'; die;
        $query = $wpdb->prepare('SELECT ID FROM ' . $wpdb->posts . ' WHERE post_title = %s and post_content = %s', $title, $post_content);
        $cID = $wpdb->get_var( $query );
		if ( !empty($cID) ) {
			return $cID;
		}
		return null;
	}
	
	Static function get_product_by_sku( $sku ) {
		global $wpdb;
		$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
		if ( $product_id ){
			return $product_id;
		}
		return null;
	}
}