<?php
use App\Cache;

class vt_query {
	static function get( $args ) {
		$cache = Cache::getInstance();
		$key   = md5( serialize( $args ) );
		return $cache->get( $key, new \WP_Query( $args ) );
	}

	static function get_post_meta_cache($key, $post_id = null, $default = false){
		if ( $post_id == null ) {
			global $post;
			$post_id = $post->ID;
		}
		$cache = Cache::getInstance()->get( "_{$key}_{$post_id}", false );
		if ( $cache ) {
			return $cache;
		}
		return $default;
	}

	static function get_related_posts( $taxes = ['category'], $number_of_posts = 5, $post_id = null ) {
		if ( $post_id == null ) {
			global $post;
			$post_id = $post->ID;
		}
		$cache = self::get_post_meta_cache('related_posts', $post_id, false);
		if ( $cache ) {
			return $cache;
		}
		$type = get_post_type($post_id);

		$args = array(
			'post_type'        => $type,
			'post__not_in'     => array( $post_id ),
			'posts_per_page'   => $number_of_posts,
			'caller_get_posts' => 1,
			'tax_query' => []
		);

		if (empty($taxes)){
			$taxes = get_post_taxonomies();
		}

		foreach ($taxes as $tax){
			$this_terms = get_the_terms($post_id, $tax);
			$query_terms = [];

			foreach ($this_terms as $this_term){
				array_push($query_terms, $this_term->term_id);
			}

			$args['tax_query'][] = [
				'taxonomy' => $tax,
				'field' => 'term_id',
				'terms' => $query_terms
			];
		}
		return vt_query::get( $args );
	}
}