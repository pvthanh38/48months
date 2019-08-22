<?php
namespace SolidApi;
if ( ! function_exists( 'wp_handle_upload' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
}
require_once(ABSPATH . 'wp-content/plugins/import/lib/XLSXReader.php');
require_once(ABSPATH . 'wp-content/plugins/import/lib/VTMCrawler.php');
require_once(ABSPATH . 'wp-content/plugins/import/lib/VTMPost.php');
require_once(ABSPATH . 'wp-content/plugins/import/lib/VTMData.php');
//include 'shipchung.php';
use WP_Query as WP_Query;
//require_once ('VTMCrawle.php');
class Upload {
	Static function Get_Content_xlsx($file) {
		$xlsx = new \XLSXReader($file);
		$sheetNames = $xlsx->getSheetNames();
		
		$arr = [];
		foreach($sheetNames as $sheetName) {
			$sheet = $xlsx->getSheet($sheetName);
			//print_r($sheet); die;
			$arr[] = self::array2Table($sheet->getData());
		}
		return $arr;
	}
	
	Static function array2Table($data) {
		$arr = [];
		$key = [];
		
		foreach($data as $k=>$row) {
			$ob = [];
			foreach($row as $m=>$cell) {
				if($k == 0){
					$key[] = trim($cell);
				}else{
					$key_m = trim($key[$m]);
					$ob[$key_m] = $cell;
				}
			}
			if($k != 0){
				$arr[] = $ob;
			}
			
		}
		return $arr;
		
	}

	function escape($string) {
		return htmlspecialchars($string, ENT_QUOTES);
	}
	
	function Cronjob($data) {
		set_time_limit(0);
		global $wpdb;
		header('Content-Type: text/plain; charset=UTF-8');
		extract($data);		
		$path = ABSPATH . 'wp-content/plugins/import/zip/';
		$VTMData = new VTMData();
		$results = $VTMData->get_cronjob();
		$VTMCrawle = new VTMCrawler();
		$post = new VTMPost();
		//$file_upload = $VTMCrawle->downloadImageToWP("http://s2.letweb.net/charleswembley/wp-content/uploads/2018/08/825Square-600x600.png");
		//$file_upload = $VTMCrawle->downloadImageToWP($path."images/BB004/BB004.jpg");
		//print_r($file_upload); die;
		//print_r($results); die;
		//die;
		if(count($results)>0){
			foreach ( $results as $k=>$vl ) {
				$v = (array) $vl;
				
				$post_id = $VTMData->update_or_insert_post($v);
				
				$v["post_id"] = $post_id;
				$path = ABSPATH . 'wp-content/plugins/import/';
				if(isset($v['gallery']) && $v['gallery'] != ""){
					$path = ABSPATH . 'wp-content/plugins/import/';
					$files_img = glob($path.'zip/'.$v['gallery'].'/*.{png,jpg,jpeg,gif}', GLOB_BRACE);
					$ids_img = [];
					if(count($files_img) > 0){
						foreach($files_img as $file_img) {
							$file_img = str_replace($path.'zip/', "", $file_img);
							$file_upload = $VTMCrawle->downloadImageToWP($file_img);
							$ids_img[] = $file_upload['attachment_id'];
						}
						update_post_meta($post_id, '_product_image_gallery', implode(",",$ids_img));
					}
				}
				/*
				if($v['type'] == 'product'){
					update_post_meta($post_id, '_price_cost', $v['price']); 
					update_post_meta($post_id, '_sku', $v['sku']); // Set its SKU
					update_post_meta( $post_id,'_visibility','visible'); // Set the product to visible, if not it won't show on the front end
					update_post_meta($post_id, '_price', $v['price']);
					update_post_meta($post_id, '_sale_price', $v['sale_price']);
				
					update_post_meta($post_id, '_regular_price', $v['price']);
					update_post_meta($post_id, '_manage_stock', 'yes');
					update_post_meta($post_id, '_backorders', 'yes');
					
					update_post_meta($post_id, '_stock', (int)$v['stock']);
					$path = ABSPATH . 'wp-content/plugins/import/';
					if(isset($v['gallery']) && $v['gallery'] != ""){
						
						$files_img = glob($path.'zip/'.$v['gallery'].'/*.{png,jpg,jpeg,gif}', GLOB_BRACE);
						$ids_img = [];
						if(count($files_img) > 0){
							foreach($files_img as $file_img) {
								$file_upload = $VTMCrawle->downloadImageToWP($file_img);
								$ids_img[] = $file_upload['attachment_id'];
							}
							update_post_meta($post_id, '_product_image_gallery', implode(",",$ids_img));
						}
					}
					
					if(isset($v['attribute']) && $v['attribute'] != ""){
						$attribute = json_decode($v['attribute'], true);
						$product_attributes = [];
						foreach($attribute as $mt=>$mtv){
							$product_attributes[$mt] = array(
									//Make sure the 'name' is same as you have the attribute
									'name' => htmlspecialchars(stripslashes($mt)),
									'value' => $mtv,
									'position' => 1,
									'is_visible' => 1,
									'is_variation' => 1,
									'is_taxonomy' => 0
								);
							//Add as post meta
							
						}
						update_post_meta($post_id, '_product_attributes', $product_attributes);
					}
				}*/
				print_r($post_id);// die;
				
				$post_arr[] = $post_id;
				/*if (!$post_id || $post_id == "") // If there is no post id something has gone wrong so don't proceed
				{
					self::Log_f($v['sku'].'|'.$v['title'].'||Tên sản phẩm rổng|'.$v['version']);
					$id_cronjob = $v['id'];
					$table_name = $wpdb->prefix . "cronjob";
					$ereminders = $wpdb->get_results( "
						UPDATE $table_name
						SET status = 'completed'
						WHERE id = $id_cronjob
					");
				}*/
				
				
				
			}
		}
		
		echo json_encode($post_arr);
	}
	
	Static function Drive_xlsx($data) {
		set_time_limit(0);
		global $wpdb;
		header('Content-Type: text/plain; charset=UTF-8');
		extract($data);
		$parts = parse_url($url);
		parse_str($parts['query'], $query);
		$id = "";
		if($query['id']){
			$id = $query['id'];
		}else{
			echo "Link Google Drive không tồn tại, không tìm thấy id File zip"; die;
		}
		$path = ABSPATH . 'wp-content/plugins/import/';
		WP_Filesystem();
		$newfname = $path."file.zip";
		unlink($newfname);
		$url = "https://drive.google.com/uc?id=".$id."&export=download";
		
		$file = fopen ($url, "rb");
		if($file) {
			$newf = fopen ($newfname, "wb");
			if($newf){
				while(!feof($file)) {
					fwrite($newf, fread($file, 1024 * 1000), 1024 * 1000 );
				}
			}
		}
		if($file) {
			fclose($file);
		}
		if($newf) {
			fclose($newf);
		}
		//$table_name = $wpdb->prefix . "cronjob";
		$newdb = $path."/backup/db_post_".date("d-m-Y_H-i-s").".sql";
		$newbk = fopen ($newdb, "wb");
		$str = self::backup_tables(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, ['cronjob', 'posts']);
		fwrite($newbk, $str );
		//print_r($str); die;
		echo 'success'; die;
	}
	
	Static function Unzip($data) {
		header('Content-Type: text/plain; charset=UTF-8');
		set_time_limit(0);
		global $wpdb;
		extract($data);
		self::Create_table();
		$path = ABSPATH . 'wp-content/plugins/import/';
		WP_Filesystem();
		$files = glob($path.'zip/*.{xlsx}', GLOB_BRACE);
		foreach($files as $file) {
			unlink($file);
		}
		//echo $path."zip"; die;
		$res = unzip_file($path."file.zip", $path.'zip');
		//$res = unzip_file("F:\Work\xampp\htdocs\wp\shipchung/wp-content/plugins/import/file.zip", 'F:\Work\xampp\htdocs\wp\shipchung/wp-content/plugins/import/zip/');
		//echo 2; die;
		if ($res) {
			$files = glob($path.'zip/*.{xlsx}', GLOB_BRACE);
			if(count($files)>0){
				foreach($files as $file) {
					$data = self::Get_Content_xlsx($file);
					foreach ( $data as $kv=>$re ) {
						array_shift($re);
						foreach ( $re as $k=>$v ) {
							$arr = array( 
									'sku' => $v['Sku'], 
									'title' => $v['Title'], 
									'descriptions' => $v['Descriptions'], 
									'short_description' => $v['Short description'], 
									'feature_image' => $v['Feature image'], 
									'gallery' => $v['Gallery'], 
									'category' => $v['Category'], 
									'variation' => $v['Variations'], 
									'price' => $v['Price'], 
									'sale_price' => $v['Sale price'], 
									'stock' => $v['Stock'], 
									'tags' => $v['Tags'],
									'type' => $type
								);
							$meta = [];
							$atb = [];
							$acf = [];
							$tax = [];
							foreach($v as $key=>$value){
								if (strpos($key, 'META:') !== false) {
									$key_s = str_replace('META:', "", $key);
									$meta[$key_s] = $value;
								}
								if (strpos($key, 'ATB:') !== false) {
									$key_s = str_replace('ATB:', "", $key);
									$atb[$key_s] = $value;
								}
								if (strpos($key, 'ACF:') !== false) {
									$key_s = str_replace('ACF:', "", $key);
									$acf[$key_s] = $value;
								}
								if (strpos($key, 'TAX:') !== false ) {
									$key_s = str_replace('TAX:', "", $key);
									$tax[$key_s] = $value;
								}
							}
							$arr['meta'] = json_encode($meta, JSON_UNESCAPED_UNICODE);
							$arr['attribute'] = json_encode($atb, JSON_UNESCAPED_UNICODE);
							$arr['acf'] = json_encode($acf, JSON_UNESCAPED_UNICODE);
							$arr['taxonomy_custom'] = json_encode($tax, JSON_UNESCAPED_UNICODE);
							//print_r($arr); die;
							$table_name = $wpdb->prefix . "cronjob";
							$wpdb->insert( $table_name, $arr);
						}
					}
				}
			}else{
				echo "Không tìm thấy file xlsx"; die;
			}
			echo 'success'; die;
		} else {
			echo 'error!';
		}
		die;
	}
	
	Static function Log_f($str) {
		$logfile = fopen(ABSPATH . "wp-content/plugins/import/log.txt", "a") or die("Unable to open file!");
		$txt = $str."\n";
		fwrite($logfile, $txt);
		fclose($logfile);
	}
	
	
	Static function Create_table(){
		global $wpdb;
		$table_name = $wpdb->prefix.'cronjob';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$charset_collate = $wpdb->get_charset_collate();
		
			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				sku text NOT NULL,
				title text NULL,
				descriptions text  NULL,
				short_description text  NULL,
				feature_image text  NULL,
				gallery text  NULL,
				category text  NULL,
				taxonomy_custom text  NULL,
				price text  NULL,
				sale_price text  NULL,
				stock text  NULL,
				tags text  NULL,
				meta text  NULL,
				attribute text  NULL,
				variation text  NULL,
				acf text  NULL,
				status text  NULL,
				type text  NULL,
				creation_time   DATETIME DEFAULT CURRENT_TIMESTAMP,
				modification_time  DATETIME ON UPDATE CURRENT_TIMESTAMP,
				UNIQUE KEY id (id)
			) $charset_collate;";
			
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}else{
			$ereminders = $wpdb->get_results( "ALTER TABLE `$table_name` ADD `gallery` TEXT NULL AFTER `sku`;");
			$ereminders = $wpdb->get_results( "ALTER TABLE `$table_name` ADD `sale_price` TEXT NULL AFTER `sku`;");
			
		}
	
	}
	
	Static function backup_tables($host, $user, $pass, $name, $tables = '*'){
		global $wpdb;
		$data = "\n/*---------------------------------------------------------------".
			  "\n  SQL DB BACKUP ".date("d.m.Y H:i")." ".
			  "\n  HOST: {$host}".
			  "\n  DATABASE: {$name}".
			  "\n  TABLES: {$tables}".
			  "\n  ---------------------------------------------------------------*/\n";
		
		//$link = mysqli_connect($host,$user,$pass);
		$link = mysqli_connect($host,$user,$pass,$name);
		mysqli_query(  $link, "SET NAMES `utf8` COLLATE `utf8_general_ci`"); // Unicode
		//print_r($data); die;
		if($tables == '*'){ //get all of the tables
			$tables = array();
			$result = mysqli_query("SHOW TABLES");
			while($row = mysqli_fetch_row($result)){
				$tables[] = $row[0];
			}
		}else{
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}
		//print_r($tables); die;
		foreach($tables as $table){
			
			$table = $wpdb->prefix.$table;
			$data.= "\n/*---------------------------------------------------------------".
					"\n  TABLE: `{$table}`".
					"\n  ---------------------------------------------------------------*/\n";           
			$data.= "DROP TABLE IF EXISTS `{$table}`;\n";
			$res = mysqli_query($link,"SHOW CREATE TABLE `{$table}`");
			
			$row = mysqli_fetch_row($res);
			$data.= $row[1].";\n";
			//print_r($data); die;
			$result = mysqli_query( $link, "SELECT * FROM `{$table}`");
			$num_rows = mysqli_num_rows($result);
			//print_r($num_rows); die;
			if($num_rows>0){
				$vals = Array(); 
				$z=0;
				for($i=0; $i<$num_rows; $i++){
					$items = mysqli_fetch_row($result);
					//print_r($items); die;
					$vals[$z]="(";
					for($j=0; $j<count($items); $j++){
						if (isset($items[$j])) { 
							$vals[$z].= "'".mysqli_real_escape_string( $link, $items[$j] )."'"; } 
						else 
							{ $vals[$z].= "NULL"; }
						
						if ($j<(count($items)-1)){ $vals[$z].= ","; }
					}
					$vals[$z].= ")"; 
					$z++;
				}
			$data.= "INSERT INTO `{$table}` VALUES ";      
			$data .= "  ".implode(";\nINSERT INTO `{$table}` VALUES ", $vals).";\n";
			}
		}
		mysqli_close( $link );
		return $data;
	}
	/*
	Static function Upload_Files($url, $generate = false) {
		$file = $url;
		$wp_upload_dir = wp_upload_dir();
		//echo $file; die;
		if (!file_exists($file)){
			return 2;
		}
		$filename = basename($file);
		if (file_exists( $wp_upload_dir['path'].'/'.$filename)){
			$k = md5_file($wp_upload_dir['path'].'/'.$filename);
			$s = md5_file($url);
			if($k == $s){
				$id = self::get_attachment_id($wp_upload_dir['url'].'/'.$filename);
				return $id;
			}
		}
		$upload_file = wp_upload_bits($filename, null, file_get_contents($file));
		if (!$upload_file['error']) {
			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file']);
			if (!is_wp_error($attachment_id)) {
				$file_id = $attachment_id;
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				if($generate == true && $wp_filetype['type'] != "application/pdf"){
					$attach_data = wp_generate_attachment_metadata( $attachment_id, get_attached_file($attachment_id) );
					$kk = add_post_meta($attachment_id,"_wp_attachment_metadata",$attach_data);
				}
				return $file_id;
			}
		}
		return 0;
	}
	/*Static function get_attachment_ids( $url ) {
		$attachment_id = 0;
		$dir = wp_upload_dir();
		if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
			$file = basename( $url );
			//echo $url; die;
			$query_args = array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'fields'      => 'ids',
				'meta_query'  => array(
					array(
						'value'   => $file,
						'compare' => 'LIKE',
						'key'     => '_wp_attachment_metadata',
					),
				)
			);
			$query = new WP_Query( $query_args );
			if ( $query->have_posts() ) {
				foreach ( $query->posts as $post_id ) {
					$meta = wp_get_attachment_metadata( $post_id );
					$original_file       = basename( $meta['file'] );
					$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
					if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
						$attachment_id = $post_id;
						//echo $attachment_id."/////"; die;
						break;
					}
				}
			}
		}
		return $attachment_id;
	}
	
	function get_attachment_id( $attachment_url = '' ) {
 
		global $wpdb;
		$attachment_id = 0;
		// If there is no url, return.
		if ( '' == $attachment_url )
			return;
		// Get the upload directory paths
		$upload_dir_paths = wp_upload_dir();
		// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
		if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
			// If this is the URL of an auto-generated thumbnail, get the URL of the original image
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif|pdf)$)/i', '', $attachment_url );
			// Remove the upload path base directory from the attachment URL
			$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
			// Finally, run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
		}
		//echo $attachment_id; die;
		return $attachment_id;
	}
	/*
	Static function pippin_get_image_id($image_url) {
		global $wpdb;
		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
		print_r($attachment); die;		
		return $attachment[0]; 
	}
	Static function Files($files_image) {
		header('Content-Type: text/plain; charset=UTF-8');
		$path = ABSPATH . 'wp-content/plugins/import/';
		$files = $files_image['files'];
		if ($files['name']) {
			$file = array(
				'name'     => $files['name'],
				'type'     => $files['type'],
				'tmp_name' => $files['tmp_name'],
				'error'    => $files['error'],
				'size'     => $files['size']
			);
			$upload_overrides = array( 'test_form' => false );
			$movefile = wp_handle_upload( $file, $upload_overrides );			
			$filename = $movefile['file'];
			WP_Filesystem();
			$arr_name = explode('/',$filename);
			$res = unzip_file($filename, $path);
			if ($res) {
				echo $filename;
			} else {
				echo 'error!';
			}
			die;
			
		}
	}
	/*
	Static function Images($files_image) {
		$path = './uploads/image/large_image/';
		$images = "";
		//
		//echo "sdfdsf"; die;
		$id_images = [];
		$files = $files_image['files'];
		//print_r($files); die;
		foreach ($files['name'] as $key => $value) {
			if ($files['name'][$key]) {
				$file = array(
					'name'     => $files['name'][$key],
					'type'     => $files['type'][$key],
					'tmp_name' => $files['tmp_name'][$key],
					'error'    => $files['error'][$key],
					'size'     => $files['size'][$key]
				);
				//print_r($file); die;
				$upload_overrides = array( 'test_form' => false );
				$movefile = wp_handle_upload( $file, $upload_overrides );
				$filename = $movefile['file'];
				$parent_post_id = 0;
				$filetype = wp_check_filetype( basename( $filename ), null );
				$wp_upload_dir = wp_upload_dir();
				$attachment = array(
					'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
					'post_mime_type' => $filetype['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);
				$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
				$id_images[] = $attach_id;
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				//$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
				wp_update_attachment_metadata( $attach_id, $attach_data );
				set_post_thumbnail( $parent_post_id, $attach_id );
			}
		}
		echo json_encode($id_images);
		

		
	}
	
	/*
	Static function Enter_Products() {
		global $wpdb; 
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 500
		);
		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) : $loop->the_post();
			global $product;
			$id = $product->get_id();
			$content = get_field( 'product_content', $id );
			$content = str_replace('&#8211; ', '</br>- ', $content);
			update_field( 'product_content', (string)$content, $id );
			print_r($content);
			
		endwhile;

		wp_reset_query();

	}
	
	Static function Update_Products_Color() {
		global $wpdb; 
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 500
		);
		$loop = new WP_Query( $args );
		//print_r($loop); die;
		while ( $loop->have_posts() ) : $loop->the_post();
			global $product;
			$post_id = $product->get_id();
			//$post_id = 849;
			//$pro = wc_get_product( $post_id );
			$price = $product->get_regular_price();
			echo $post_id;
			if($price != ""){
				$color = get_field( 'slides', $post_id );
				$ar_color = [];
				$ar_color_name = [];
				$color_names = "";
				if(count($color) > 0){
					foreach($color as $key=>$cl){
						//echo $cl->title;
						$ar_color[] = $cl['title'];
						$terms = get_term( $cl['title'], 'color');
						$color_name = $terms->name;
						$ar_color_name[] = $terms->name;
						$color_names = $color_names == "" ? $color_name : $color_names.' | '.$color_name;
					}
					wp_set_object_terms($post_id, $ar_color, 'color'); // Set up its categories
					$product_attributes[sanitize_title("color")] = array (
						'name' => "Color", // set attribute name
						'value' => $color_names, // set attribute value
						'position' => 1,
						'is_visible' => 1,
						'is_variation' => 1,
						'is_taxonomy' => 0
					);
					update_post_meta($post_id, '_product_attributes', $product_attributes);
					foreach ($ar_color_name as $index => $variation)
					{
						$variation_post = array( // Setup the post data for the variation
							'post_title'  => 'Variation #'.$index.' of '.count($variations).' for product#'. $post_id,
							'post_name'   => 'product-'.$post_id.'-variation-'.$index,
							'post_status' => 'publish',
							'post_parent' => $post_id,
							'post_type'   => 'product_variation',
							'guid'        => home_url() . '/?product_variation=product-' . $post_id . '-variation-' . $index
						);
						$variation_post_id = wp_insert_post($variation_post); // Insert the variation
						update_post_meta($variation_post_id, 'attribute_color', $variation);
						update_post_meta($variation_post_id, '_manage_stock', 'yes');
						update_post_meta($variation_post_id, '_backorders', 'yes');
						update_post_meta($variation_post_id, '_price', $price);
						update_post_meta($variation_post_id, '_regular_price', $price);
					}
					wp_set_object_terms($post_id, 'variable', 'product_type');
					\WC_Product_Variable::sync( $post_id );
				}
			}
			//die;
		endwhile;
		echo "success";
		wp_reset_query();

	}*/
	
	
}