<?php

class graphics extends vtm_vc_map {
	function loadAssets(){

	}
}

new graphics(
	array(
		'name'     => __( 'Graphics', 'vtm' ),
		'base'     => 'graphics',
		'category' => __( 'Themes Elements', 'vtm' ),
		'params'   => array(
			array(
				'type' => 'vc_dropdown_cat_voice', 'heading' => 'Chọn danh mục',
				'param_name' => 'cat_voice'
			)
		
		)
		)
	 );