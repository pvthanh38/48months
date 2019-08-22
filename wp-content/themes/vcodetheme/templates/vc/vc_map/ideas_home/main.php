<?php

class ideas_home extends vtm_vc_map {
	function loadAssets(){

	}
}

new ideas_home(
	array(
		'name'     => __( 'Idea Home', 'vtm' ),
		'base'     => 'ideas_home',
		'category' => __( 'Themes Elements', 'vtm' ),
		'params'   => array(
				array(
							'type' => 'textfield',
							'heading' => __( 'Number item', 'vcode' ),
							'param_name' => 'number_item',
                ),
		
		)
		)
	 );