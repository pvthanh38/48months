<?php
defined('ABSPATH') or die("No script kiddies please!");
if((wp_verify_nonce( $_POST['eat_settings_nonce'], 'eat_settings_action' ))){
	if(isset($_POST['everest_admin_theme'])){
		$plugin_settings = array_map('stripslashes_deep', $_POST['everest_admin_theme']);
		$sanitized_array = self:: eat_sanitize_array($plugin_settings);
	}

	$key = update_option('eat_admin_theme_settings', $sanitized_array);

	if($key == TRUE){
		wp_redirect(admin_url().'admin.php?page=everest-admin-theme-lite&message=1');
	}else{
		wp_redirect(admin_url().'admin.php?page=everest-admin-theme-lite&message=2');
	}
}
exit();