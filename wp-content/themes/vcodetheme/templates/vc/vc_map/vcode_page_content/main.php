<?php

class vcode_page_content extends vtm_vc_map {
	function loadAssets(){

	}
}

new vcode_page_content(
	array(
            'name'     => __( 'Vcode Page Content', 'vcode' ),
            'base'     => 'vcode_page_content',
            'category' => __( 'Themes Elements', 'vcode' ),
            'params'   => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Title', 'vcode' ),
                    'param_name' => 'title',
                ),
                array(
                    'type' => 'textarea_html',
                    'heading' => __( 'Content', 'vcode' ),
                    'param_name' => 'content',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Link video', 'vcode' ),
                    'param_name' => 'link_video',
                ),
            )
		)
	 );