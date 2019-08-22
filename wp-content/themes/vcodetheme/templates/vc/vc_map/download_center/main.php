<?php

class download_center extends vtm_vc_map {
	function loadAssets(){

	}
}

new download_center(
	array(
		'name'     => __( 'Download Center', 'vtm' ),
		'base'     => 'download_center',
		'category' => __( 'Themes Elements', 'vtm' ),
		'params'   => array(
		
		
		)
		)
	 );