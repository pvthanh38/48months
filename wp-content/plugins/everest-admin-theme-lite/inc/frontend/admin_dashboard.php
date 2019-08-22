<?php
defined('ABSPATH') or die("No script kiddies please!");
add_action('admin_head', 'eat_my_custom_scripts');
add_filter( 'admin_body_class', 'eat_add_admin_body_class' );
add_action( 'customize_controls_print_footer_scripts', 'eat_custom_customize_enqueue' );
/**
 * Enqueue script for custom customize control.
 */
function eat_custom_customize_enqueue() {
	$plugin_settings = get_option('eat_admin_theme_settings');
	$template = $plugin_settings['general-settings']['template'];
	if($template !=''){
		$template_class = 'eat-wp-toolbar-addition eat-wp-toolbar-addition-'.$template;
		$body_template_class = 'eat-body-class-wrap eat-dashboard-'.$template;
	}else{
		$template_class ='';
		$body_template_class = 'eat-body-class-wrap eat-wordpress-default-template';
	}
	wp_enqueue_script('jquery');
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('body').addClass('<?php echo $body_template_class; ?>');
		});
	</script>
	<?php
}

/**
 * Adds one or more classes to the body tag in the dashboard.
 *
 * @param  String $classes Current body classes.
 * @return String          Altered body classes.
 */
function eat_add_admin_body_class( $classes ) {
	$plugin_settings = get_option('eat_admin_theme_settings');
	$template = $plugin_settings['general-settings']['template'];
	if(isset($plugin_settings['admin_bar']['layout']) && $plugin_settings['admin_bar']['layout'] == 'fixed'){
		$admin_bar_class ='eat-admin-bar-fixed';
	}else{
		$admin_bar_class ='';
	}

	if($template !=''){
		$body_template_class = "eat-body-class-wrap  eat-dashboard-$template ";
	}else{
		$body_template_class = 'eat-body-class-wrap eat-wordpress-default-template';
	}
    return "$classes $body_template_class $admin_bar_class";
}

function eat_my_custom_scripts() {
	$plugin_settings = get_option('eat_admin_theme_settings');
	$template = $plugin_settings['general-settings']['template'];
	if($template !=''){
		$template_class = 'eat-wp-toolbar-addition eat-wp-toolbar-addition-'.$template;
		$body_template_class = 'eat-body-class-wrap eat-dashboard-'.$template;
	}else{
		$template_class ='';
		$body_template_class = '';
	}
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('.wp-toolbar').addClass('<?php echo $template_class; ?>');
		});
	</script>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
			<?php
			$plugin_settings = get_option('eat_admin_theme_settings');
			$background_settings = $plugin_settings['general-settings']['background'];
			$background_type = $background_settings['type'];
			$data_attributes = array();

			if($background_type == 'background-color'){
				$background_color = $background_settings['background-color']['color'];
				?>
				$('#wpwrap').css('background-color', "<?php echo $background_color; ?>");
				<?php
			}
			?>
		});
	</script>
	<?php
}