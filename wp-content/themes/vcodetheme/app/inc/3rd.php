<?php
require_once dirname( __FILE__ ) . '/TGM-Plugin-Activation/class-tgm-plugin-activation.php';

function _action_theme_register_required_plugins() {
	tgmpa( array(
		array(
			'name'      => 'Page builder',
			'slug'      => 'js_composer',
			'required'  => true,
			'source'             => get_stylesheet_directory() . '/app/plugins/js_composer.zip'
		),
		array(
			'name'      => 'Custom Field',
			'slug'      => 'advanced-custom-fields-pro',
			'required'  => true,
			'source'             => get_stylesheet_directory() . '/app/plugins/advanced-custom-fields-pro.zip'
		),
		array(
			'name'      => 'Slider',
			'slug'      => 'revslider',
			'required'  => true,
			'source'             => get_stylesheet_directory() . '/app/plugins/revslider.zip'
		)
	) );
}
add_action( 'tgmpa_register', '_action_theme_register_required_plugins' );