<?php


function vtm_gen_post_list( $post_id ) {
	if ( $parent_id = wp_is_post_revision( $post_id ) ) {
		$post_id = $parent_id;
	}
	$post_type = get_post_type($post_id);
	remove_action( 'save_post', 'vtm_gen_post_list' );
	$q        = new WP_Query( [
		'posts_per_page' => - 1,
		'post_type'      => $post_type
	] );
	$products = [];
	while ( $q->have_posts() ) {
		$q->the_post();
		array_push( $products, [
			'label' => get_the_title(),
			'value' => get_the_ID()
		] );
	}
	update_option('vtm_post_'.$post_type, $products);
	add_action( 'save_post', 'vtm_gen_post_list' );
}

add_action( 'save_post', 'vtm_gen_post_list' );