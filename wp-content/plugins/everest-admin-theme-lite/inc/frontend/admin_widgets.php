<?php
defined('ABSPATH') or die("No script kiddies please!");
$plugin_settings    = get_option('eat_admin_theme_settings');
$dashboard_settings = $plugin_settings['dashboard'];

add_action( 'wp_dashboard_setup', 'eat_remove_dashboard_widgets' );

function eat_remove_dashboard_widgets() {
	$plugin_settings = get_option('eat_admin_theme_settings');
	$dashboard_settings = $plugin_settings['dashboard'];
	if(isset($dashboard_settings['hide_welcome_panel'])){
		remove_action('welcome_panel', 'wp_welcome_panel');
	}

	if(isset($dashboard_settings['hide_wordpress_events_news'])){
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );   // Other WordPress News (Wordpress events and news)
	}

	if(isset($dashboard_settings['hide_quick_draft'])){
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );  // Quick Press (Quick draft)
	}

	if(isset($dashboard_settings['hide_at_a_glance'])){
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );   // Right Now (At a glance)
	}

	if(isset($dashboard_settings['hide_activity'])){
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );   // (activity)
	}

	if(isset($dashboard_settings['hide_recent_draft'])){
		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );  // Recent Drafts
	}

	if(isset($dashboard_settings['hide_recent_comments'])){
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' ); // Recent Comments
	}

	if(isset($dashboard_settings['hide_incoming_links'])){
		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );  // Incoming Links
	}

	if(isset($dashboard_settings['hide_plugins'])){
		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );   // Plugins
	}

	if(isset($dashboard_settings['hide_wordpress_blog'])){
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );   // WordPress blog
	}
	// use 'dashboard-network' as the second parameter to remove widgets from a network dashboard.
}