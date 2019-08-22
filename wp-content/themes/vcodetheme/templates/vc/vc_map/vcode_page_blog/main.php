<?php

class vcode_page_blog extends vtm_vc_map {
	function loadAssets(){

	}
}

new vcode_page_blog(
	array(
            'name'     => __( 'Vcode Page Blog', 'vcode' ),
            'base'     => 'vcode_page_blog',
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
                    'type' => 'dl_post_cat',
                    'param_name' => 'cat',
                    'heading' => 'Categories',
                    'description' => 'posts in categories'
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Button', 'vcode' ),
                    'param_name' => 'button',
                ),
            )
        )
	 );