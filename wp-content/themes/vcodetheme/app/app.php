<?php
require_once(__DIR__.'/vendor/autoload.php');
require_once(__DIR__.'/inc/3rd.php');
require_once(__DIR__.'/general-settings.php');
require_once (__DIR__.'/common-functions.php');
require_once(__DIR__."/theme/theme-vc-templates.php");
require_once(__DIR__."/theme/theme-vc-templates-panel-editor.php");

require_once (__DIR__.'/Cache.php');
require_once (__DIR__.'/Options.php');
require_once (__DIR__.'/PageBuilder.php');
require_once (__DIR__.'/inc/post-types/footer.php');

require_once (__DIR__.'/classes/vt_html.php');
require_once (__DIR__.'/classes/vt_query.php');
require_once (__DIR__.'/classes/vt_data_gen.php');
require_once (__DIR__.'/system-hooks.php');
require_once (__DIR__.'/theme-hooks.php');

add_action( 'init', 'vtm_init', 25 );

function vtm_init(){
	if (is_plugin_active('js_composer/js_composer.php')){
		global $vc_manager;
		if ($vc_manager){
			$vc_manager->setIsAsTheme();
			$vc_manager->disableUpdater();
			$app_templates = new Rella_Vc_Templates_Panel_Editor();
			$app_templates->init();
		}
	}
}

add_action('admin_enqueue_scripts', 'vtm_enqueue_admin_assets', PHP_INT_MAX);

function vtm_enqueue_admin_assets(){
	$dependencies = array(
		'vc_accordion_script',
		'wpb_php_js',
		'wpb_json-js',
		'ace-editor',
		'webfont',
		'vc-backend-min-js',
	);

	wp_register_style('lw-admin', APP_THEME_URL.'/app/assets/lw-admin.css');
	wp_register_style('lw-admin-2', APP_THEME_URL.'/app/assets/lw-style.css');
	wp_register_script('lw-admin', APP_THEME_URL.'/app/assets/vc-script.js', $dependencies, false, true);
	wp_enqueue_style('lw-admin');
	wp_enqueue_style('lw-admin-2');
	wp_enqueue_script('lw-admin');
}

add_action('wp_enqueue_scripts', 'vtm_enqueue_assets', PHP_INT_MAX);

function vtm_enqueue_assets(){
	$dependencies = array(
		'vc_accordion_script'
	);
	wp_register_script('lw-admin', APP_THEME_URL.'/app/assets/vc-script.js', $dependencies, false, true);
	wp_enqueue_script('lw-admin');
}