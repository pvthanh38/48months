<?php
	defined('ABSPATH') or die("No script kiddies please!");
	$eat_dynamic_css_at_end = array();
	$google_fonts_used_array = array();

	$plugin_settings = $this->plugin_settings;


	$template = $plugin_settings['general-settings']['template'];

	// $general_settings = $plugin_settings['genral-settings'][];

	$admin_bar_settings = $plugin_settings['admin_bar'];
	$admin_bar_menu_background_options = isset($admin_bar_settings['outer_background_settings']['menu']['background_selection']) ? $admin_bar_settings['outer_background_settings']['menu']['background_selection'] : array();
	$admin_bar_sub_menu_background_options = isset($admin_bar_settings['outer_background_settings']['sub_menu']['background_selection']) ? $admin_bar_settings['outer_background_settings']['sub_menu']['background_selection'] : array();
	?>
	<style>
		<?php
		// admin bar background settings
		$dynamic_css =array();

		if($admin_bar_menu_background_options['type'] == 'background-color'){
			$bg_color = $admin_bar_menu_background_options['background-color']['color'];
			$dynamic_css[] = "background:$bg_color;";
		}

		if(!empty($dynamic_css)){
			$dynamic_css = implode(' ', $dynamic_css);
		}else{
			$dynamic_css ='';
		}
		?>

		/* admin bar menu settings background color/image */
		.eat-body-class-wrap.eat-dashboard-<?php echo $template; ?> #wpadminbar { <?php echo $dynamic_css; ?> }

		<?php
		//admin bar submenu background settings
		$dynamic_css1 =array();

		if($admin_bar_sub_menu_background_options['type'] == 'background-color'){
			$bg_color = $admin_bar_sub_menu_background_options['background-color']['color'];
			$dynamic_css1[] = "background:$bg_color;";
		}

		if(!empty($dynamic_css1)){
			$dynamic_css1 = implode(' ', $dynamic_css1);
		}else{
			$dynamic_css1 ='';
		}
		?>
		/* admin bar sub menu settings background color */
		.eat-body-class-wrap.eat-dashboard-<?php echo $template; ?> #wpadminbar .menupop .ab-sub-wrapper,
		.eat-body-class-wrap.eat-dashboard-<?php echo $template; ?> #wpadminbar .menupop .ab-sub-wrapper,
		.eat-body-class-wrap.eat-dashboard-<?php echo $template; ?> #wpadminbar .quicklinks .menupop ul.ab-sub-secondary { <?php echo $dynamic_css1; ?> }

		<?php
		// Admin menu outer background settings
		$admin_menu_settings = $plugin_settings['admin_menu'];
		$admin_menu_background_settings = $admin_menu_settings['outer_background_settings']['menu'];

		$admin_menu_background_settings_dynamic_css = array();
		if($admin_menu_background_settings['type'] == 'background-color'){
			$admin_menu_background_settings_dynamic_css [] = "background: {$admin_menu_background_settings['background-color']['color']};";
		}

		if(!empty($admin_menu_background_settings_dynamic_css)){
			$admin_menu_background_settings_dynamic_css = implode(' ', $admin_menu_background_settings_dynamic_css);
		}else{
			$admin_menu_background_settings_dynamic_css ='';
		}

		// admin submenu outer background settings
		$admin_sub_menu_background_settings = $admin_menu_settings['outer_background_settings']['sub_menu'];

		$admin_sub_menu_background_settings_dynamic_css = array();

		if($admin_sub_menu_background_settings['type'] == 'background-color'){
			$admin_sub_menu_background_settings_dynamic_css [] = "background: {$admin_sub_menu_background_settings['background-color']['color']};";
		}

		if(!empty($admin_sub_menu_background_settings_dynamic_css)){
			$admin_sub_menu_background_settings_dynamic_css = implode(' ', $admin_sub_menu_background_settings_dynamic_css);
		}else{
			$admin_sub_menu_background_settings_dynamic_css ='';
		}
		?>
		.eat-body-class-wrap.eat-dashboard-<?php echo $template; ?> #adminmenuback { <?php echo $admin_menu_background_settings_dynamic_css; ?> }
		.eat-body-class-wrap.eat-dashboard-<?php echo $template; ?> #adminmenu .wp-has-current-submenu .wp-submenu,
		.eat-body-class-wrap.eat-dashboard-<?php echo $template; ?> #adminmenu .opensub .wp-submenu,
		.eat-body-class-wrap.eat-dashboard-<?php echo $template; ?> #adminmenu li.opensub ul.wp-submenu li > a,
		.eat-body-class-wrap.eat-dashboard-<?php echo $template; ?> #adminmenu .wp-submenu li a,
		.eat-body-class-wrap.eat-dashboard-<?php echo $template; ?> #adminmenu .wp-submenu li.current a
		 { <?php echo $admin_sub_menu_background_settings_dynamic_css; ?>  }
	</style>