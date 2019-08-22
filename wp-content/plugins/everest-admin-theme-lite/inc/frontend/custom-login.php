<?php
defined('ABSPATH') or die("No script kiddies please!");
// https://codex.wordpress.org/Customizing_the_Login_Form
//for wp-login.php edits
add_action( 'login_head', 'eat_div_before_login_form');
add_action( 'login_head', 'eat_my_login_logo');
add_action( 'login_enqueue_scripts', 'eat_custom_login_scripts', 1000 );
add_action( 'login_footer', 'eat_my_addition_to_login_footer' );

add_filter( 'login_body_class', 'eat_login_classes' );

function eat_my_login_logo() {
	$plugin_settings = get_option('eat_admin_theme_settings');
	$custom_login    = $plugin_settings['custom_login'];
	?>
	<style type="text/css">
		<?php
		if(isset($custom_login['login_form']['wordpress-logo']['hide'])){ ?>
			.eat-admin-theme-custom-login-wrap h1 {
				display:none;
			}
		<?php } ?>

		<?php if(isset($custom_login['login_form']['remember-me-checkbox']['hide'])){ ?>
			.eat-admin-theme-custom-login-wrap p.forgetmenot,
			.eat-admin-theme-custom-login-wrap #loginform .remem-field {
				display:none;
			}
		<?php } ?>
	</style>
<?php
}

function eat_login_classes( $classes ) {
	$classes[] = 'eat-custom-login-class';
	return $classes;
}

function eat_div_before_login_form(){
	$plugin_settings = get_option('eat_admin_theme_settings');
	$login_form_template   = $plugin_settings['custom_login']['login_form']['template'];
	$custom_login_settings = $plugin_settings['custom_login'];
	$background_type       = $custom_login_settings['background']['type'];

	$wrap_inner_styles = '';
	$dynamic_classes = '';
	if($background_type === 'background-color'){
		$background_color      = $custom_login_settings['background']['background-color']['color'];
		$wrap_inner_styles     .= "background: $background_color";

	}

	if($login_form_template == 'default' || !isset($login_for_template)){
		$template_classes = 'login-form-'.$login_form_template;
	}else{
		$template_classes = 'login-form-'.$login_form_template.' login-form-template';
	}
	?>
	<div class="eat-admin-theme-custom-login-wrap <?php echo $dynamic_classes; ?> <?php echo $template_classes; ?>" style="<?php echo $wrap_inner_styles; ?>">
	<?php
}

function eat_custom_login_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('eat_custom_login_js', EAT_ADMIN_THEME_JS_DIR . '/eat-custom-login.js', array('jquery'), EAT_ADMIN_THEME_VERSION , true );
	$plugin_settings = get_option('eat_admin_theme_settings');
	$custom_login = $plugin_settings['custom_login'];
	$temp_plugin_settings = array(
	        				'login_template' => $custom_login['login_form']['template']
	        				);
	wp_localize_script( 'eat_custom_login_js', 'eat_custom_login_plugin_settings', $temp_plugin_settings );
	wp_register_style( 'custom-login-css', EAT_ADMIN_THEME_CSS_DIR.'/eat-custom-login.css', false, EAT_ADMIN_THEME_VERSION );
	wp_enqueue_style( 'custom-login-css' );
}

function eat_my_addition_to_login_footer() {
 ?>
 </div>
<?php
}
