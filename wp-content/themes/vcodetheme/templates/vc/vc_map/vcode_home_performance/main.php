<?php

class vcode_home_performance extends vtm_vc_map {
	function loadAssets(){

	}
}

new vcode_home_performance(
	array(
            'name'     => __( 'Vcode Home Performance', 'vcode' ),
            'base'     => 'vcode_home_performance',
            'category' => __( 'Themes Elements', 'vcode' ),
            'params'   => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'title', 'vcode' ),
                    'param_name' => 'title',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'subtitle', 'vcode' ),
                    'param_name' => 'subtitle',
                ),
            )
		)
	 );