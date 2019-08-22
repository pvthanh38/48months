<?php

class vcode_page_banner_menu_scroll extends vtm_vc_map {
	function loadAssets(){

	}
}

new vcode_page_banner_menu_scroll(
	array(
		'name'     => __( 'Vcode Page Banner With Menu Scroll', 'vcode' ),
		'base'     => 'vcode_page_banner_menu_scroll',
		'category' => __( 'Themes Elements', 'vcode' ),
		'params'   => array()
		)
	 );