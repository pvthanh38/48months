<?php

class vcode_page_infographics extends vtm_vc_map {
	function loadAssets(){

	}
}

new vcode_page_infographics(
	array(
            'name'     => __( 'Vcode Page Infographics', 'vcode' ),
            'base'     => 'vcode_page_infographics',
            'category' => __( 'Themes Elements', 'vcode' ),
            'params'   => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Title', 'vcode' ),
                    'param_name' => 'title',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Subtitle', 'vcode' ),
                    'param_name' => 'subtitle',
                ),
                array(
                    'type' => 'dl_graphic_cat',
                    'param_name' => 'cat',
                    'heading' => 'Categories graphic',
                    'description' => 'posts in categories graphic'
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Button', 'vcode' ),
                    'param_name' => 'button',
                ),
            )
		)
	 );