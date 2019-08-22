<?php

class vcode_home_video_slider extends vtm_vc_map {
	function loadAssets(){

	}
}

new vcode_home_video_slider(
	array(
		'name'     => __( 'Vcode Home Video Slider', 'vcode' ),
		'base'     => 'vcode_home_video_slider',
		'category' => __( 'Themes Elements', 'vcode' ),
		'params'   => array(
			array(
				'type' => 'vc_dropdown_cat_voice', 'heading' => 'Chọn danh mục',
				'param_name' => 'cat_voice'
			)
		)
		)
	 );