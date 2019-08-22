<?php
require_once ('constant.php');
require_once (APP_ROOT_PATH.'/app.php');
require_once (APP_VC_MAP_DIR.'/vtm_vc_map.php');
require_once (APP_VC_MAP_DIR.'/vc_map/load.php');

use App\Options;
use App\PageBuilder;

require_once (APP_ROOT_PATH.'/app.php');
require_once (APP_ROOT_THEME_PATH.'/templates/vc/vc_map/load.php');

add_action( 'after_setup_theme', 'vtm_add_theme_support' );

function vtm_add_theme_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

function get_page_builder(){
	return PageBuilder::getIns();
}

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page( array(
		'page_title' => 'Theme General Settings',
		'menu_title' => 'Theme Settings',
		'menu_slug'  => 'theme-general-settings',
		'capability' => 'edit_posts',
		'redirect'   => false
	) );
}