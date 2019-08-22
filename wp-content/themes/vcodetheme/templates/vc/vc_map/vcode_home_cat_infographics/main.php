<?php

class vcode_home_cat_infographics extends vtm_vc_map {
	function loadAssets(){

	}
}

new vcode_home_cat_infographics(
	array(
            'name'     => __( 'Vcode Home Cat Infographics', 'vcode' ),
            'base'     => 'vcode_home_cat_infographics',
            'category' => __( 'Themes Elements', 'vcode' ),
            'params'   => array(
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Button', 'vcode' ),
                    'param_name' => 'button',
                ),
            )
		)
	 );