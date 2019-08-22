<?php

class vtm_vc_sample extends vtm_vc_map {
	function loadAssets(){

	}
}

new vtm_vc_sample(
	array(
		'name'     => __( 'VTM Sample', 'vtm' ),
		'base'     => 'vtm_sample',
		'category' => __( 'Themes Elements', 'vtm' ),
		'params'   => array()
		)
	 );