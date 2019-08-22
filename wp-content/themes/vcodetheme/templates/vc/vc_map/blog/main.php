<?php

class blog extends vtm_vc_map {
	function loadAssets(){

	}
}

new blog(
	array(
		'name'     => __( 'Blog', 'vtm' ),
		'base'     => 'blog',
		'category' => __( 'Themes Elements', 'vtm' ),
		'params'   => array(
		
		
		)
		)
	 );