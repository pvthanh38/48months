<?php
namespace SolidApi;
class VTMWoo extends VTMPost {
	protected $product;
	function __construct() {
		parent::__construct();
		$this->product = false;
	}
	function saveGallery( $images ) {
		if ( ! is_array( $images ) ) {
			return false;
		}
		$ids = [];
		foreach ( $images as $image ) {
			$attachment = VTMCrawler::downloadImageToWP( $image );
			if ( $attachment ) {
				array_push( $ids, $attachment['attachment_id'] );
			}
		}
		$ids = join( ',', $ids );
		if ( $ids && ! empty( $ids ) ) {
			$this->saveMeta( $this->getId(), [
				'_product_image_gallery' => [
					'value' => $ids
				]
			] );
			return true;
		}
		return false;
	}
	function getProduct() {
		if ( ! $this->getId() ) {
			return false;
		}
		if ( ! $this->product ) {
			$this->product = wc_get_product( $this->getId() );
		}
		return $this->product;
	}
	function insert_product_attributes ($available_attributes, $variations)
	{
		$post_id = $this->getId();
		foreach ($available_attributes as $attribute) // Go through each attribute
		{
			$values = array(); // Set up an array to store the current attributes values.
			foreach ($variations as $variation) // Loop each variation in the file
			{
				$attribute_keys = array_keys($variation['attributes']); // Get the keys for the current variations attributes
				foreach ($attribute_keys as $key) // Loop through each key
				{
					if ($key === $attribute) // If this attributes key is the top level attribute add the value to the $values array
					{
						$values[] = $variation['attributes'][$key];
					}
				}
			}
			// Essentially we want to end up with something like this for each attribute:
			// $values would contain: array('small', 'medium', 'medium', 'large');
			$values = array_unique($values); // Filter out duplicate values
			// Store the values to the attribute on the new post, for example without variables:
			// wp_set_object_terms(23, array('small', 'medium', 'large'), 'pa_size');
			//print_r($values); die;
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
	function insert_product_variations ($variations)
	{
		$post_id = $this->getId();
		foreach ($variations as $index => $variation)
		{
			$variation = array_merge([
				'sale_price' => false,
				'regular_price' => false,
			], $variation);
			$variation_post = array( // Setup the post data for the variation
				//'post_title'  => 'Variation #'.$index.' of '.count($variations).' for product#'. $post_id,
				'post_title'  => get_the_title($post_id).' '.$index,
				//'post_name'   => 'product-'.$post_id.'-variation-'.$index,
				'post_status' => 'publish',
				'post_parent' => $post_id,
				'post_type'   => 'product_variation',
				'guid'        => home_url() . '/?product_variation=product-' . $post_id . '-variation-' . $index
			);
			$variation_post_id = wp_insert_post($variation_post); // Insert the variation
			foreach ($variation['attributes'] as $attribute => $value) // Loop through the variations attributes
			{
				$attribute_term = get_term_by('name', $value, 'pa_'.$attribute); // We need to insert the slug not the name into the variation post meta
				update_post_meta($variation_post_id, 'attribute_pa_'.$attribute, $attribute_term->slug);
			}
			update_post_meta($variation_post_id, '_price', $variation['regular_price']);
			update_post_meta($variation_post_id, '_regular_price', $variation['regular_price']);
			update_post_meta($variation_post_id, '_sale_price', $variation['sale_price']);
			wc_get_product($variation_post_id)->save();
		}
	}
	function saveVariations( $variations ) {
		if ( ! is_array( $variations ) ) {
			$variations = [];
		}
		$attributes_meta = [];
		foreach ( $variations as $variation ) {
			$attributes = $variation['attributes'];
			foreach ( $attributes as $key => $attribute ) {
				if (!$attributes_meta[$key]) $attributes_meta[$key] = [];
				array_push($attributes_meta[$key], $attribute);
				$attributes_meta[$key] = array_unique($attributes_meta[$key]);
			}
		}
		$availableAttributes = array_keys($attributes_meta);
		$this->insert_product_attributes($availableAttributes, $variations);
		$this->insert_product_variations($variations);
	}
	
	function saveProductJson($path){
		if (is_file($path)){
			$data = json_decode(file_get_contents($path), true);
			if ($data['url']){
				$data['metas']['_old_url'] = [
					'value' => $data['url']
				];
			}
			$this->saveProduct($data, [
				'post_status' => 'publish'
			]);
		}
		else{
			echo "$path is not found";
		}
	}
	function deleteVariations($id){
		$args = array(
			'post_type'     => 'product_variation',
			'post_status'   => array( 'private', 'publish' ),
			'numberposts'   => -1,
			'orderby'       => 'menu_order',
			'order'         => 'asc',
			'post_parent'   => $id // get parent post-ID
		);
		$variations = get_posts( $args );
		//print_r($variations); die;
		foreach ( $variations as $variation ) {
			$variation_ID = $variation->ID;
			wp_delete_post($variation_ID);
		}
		return true;
	}
	function saveProduct( $data, $default = [] ) {
		$default = array_merge( $default, [
			'post_type'  => 'product',
			'gallery'    => [],
			'price'      => false,
			'sale_price' => false,
			'post_status' => 'publish'
		] );
		if (!is_array($data)) $data = [];
		$data    = array_merge( $default, $data );
		$this->savePost( $data );
		if ( $this->getId() ) {
			//Check if variation product
			wp_set_object_terms( $this->getId(), 'simple', 'product_type' );
			update_post_meta($this->getId(), '_stock', (int)$data['stock']);
			update_post_meta($this->getId(), '_stock_status', 'instock');
			$this->deleteVariations( $this->getId() );
			if ($data['variations']) {
				wp_set_object_terms( $this->getId(), 'variable', 'product_type' );
				//Save Variations
				
				$this->saveVariations( $data['variations'] );
			}
			// Set Price
			update_post_meta($this->getId(), '_price', $data['regular_price']);
			$this->getProduct()->set_regular_price( $data['regular_price'] );
			if ( $data['sale_price'] ) {
				$this->getProduct()->set_sale_price( $data['sale_price'] );
			}
			$this->getProduct()->save();
			// Save Gallery
			$this->saveGallery( $data['gallery'] );
		}
		return $this->getId();
	}
}