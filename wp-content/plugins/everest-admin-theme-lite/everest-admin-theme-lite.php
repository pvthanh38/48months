<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
/*
  Plugin name: Everest Admin Theme Lite
  Plugin URI: https://accesspressthemes.com/wordpress-plugins/everest-admin-theme-lite/
  Description: A plugin to change the admin interface with various dynamic configuration options.
  version: 1.0.3
  Author: AccessPress Themes
  Author URI: https://accesspressthemes.com/
  Text Domain: everest-admin-theme-lite
  Domain Path: /languages/
  License: GPLv2 or later
*/

/**
* Plugin's main class initilization
*/
if(! class_exists( 'everestAdminThemeLiteClass' )){
	class everestAdminThemeLiteClass {
		var $plugin_settings;
		function __construct() {
			$this->plugin_settings = get_option('eat_admin_theme_settings');
			add_action( 'init', array( $this, 'eat_plugin_contants') );
			add_action( 'init', array( $this, 'eat_plugin_variables' ) ); // Register globals variables
			add_action( 'init', array( $this, 'eat_plugin_text_domain' ) );

			add_action('admin_head', array($this, 'eat_my_custom_css'));

			add_action( 'admin_enqueue_scripts', array( $this, 'eat_register_plugin_assets' ) );

			add_action( 'admin_menu', array($this, 'eat_plugin_menu') );
			add_action( 'admin_post_eat_settings_action', array($this, 'eat_save_plugin_settings'));

			// admin dashboard widgets functions
			add_action('init', array($this, 'eat_admin_dashboard_widgets'));

			// init hook for the footer texts
			add_action('init', array($this, 'eat_admin_footer_options'));

			// hook for the admin bar items removal

			add_action('init', array($this, 'eat_custom_login_options'));

			add_action('init', array($this, 'eat_admin_bar_options'));

			// action for the posts and pages meta boxes removal
			add_action( 'admin_menu', array($this, 'eat_remove_meta_boxes_for_posts_pages' ));

			add_action('init', array($this, 'eat_everest_admin_dashboard'));

			// function for the favicon set
			add_action('login_head', array($this, 'eat_set_favicon'));
			add_action('admin_head', array($this, 'eat_set_favicon'));

            add_action( 'admin_footer', array($this, 'eat_at_footer_custom_css') );
		}

		function eat_at_footer_custom_css(){
			?>
			<style type="text/css" >
				<?php echo  $this->plugin_settings['custom_css']; ?>
			</style>
			<?php
		}

		function eat_my_custom_css() {
			include('inc/frontend/dynamic.css.php');
		}

		function eat_set_favicon(){
			$plugin_settings = $this->plugin_settings;

			$favicon_url = isset($plugin_settings['general-settings']['favicon']['url']) ? $plugin_settings['general-settings']['favicon']['url']: '';

			if($favicon_url !=''){
				echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
			}
		}

		public function eat_everest_admin_dashboard(){
			include(EAT_ADMIN_THEME_PLUGIN_DIR . 'inc/frontend/admin_dashboard.php');
		}

		function eat_admin_bar_options(){
			include(EAT_ADMIN_THEME_PLUGIN_DIR . 'inc/frontend/admin_bar_menu.php');
		}

		function eat_admin_dashboard_widgets(){
			include(EAT_ADMIN_THEME_PLUGIN_DIR . 'inc/frontend/admin_widgets.php');
		}

		function eat_admin_footer_options(){
			include(EAT_ADMIN_THEME_PLUGIN_DIR . 'inc/frontend/admin_footer.php');
		}

		function eat_remove_meta_boxes_for_posts_pages() {
			$plugin_settings = get_option( 'eat_admin_theme_settings' );
			$posts_pages_settings = isset($plugin_settings['posts_pages']) ? $plugin_settings['posts_pages'] : array();


			if ( current_user_can( 'manage_options' ) ) {
				if(isset($posts_pages_settings['excerpt-box'])){
					remove_meta_box( 'postexcerpt', 'post', 'normal' ); // for excerpt
				}

				if(isset($posts_pages_settings['category-box'])){
					remove_meta_box( 'categorydiv', 'post', 'normal' ); // for category
				}

				if(isset($posts_pages_settings['format-box'])){
					remove_meta_box( 'formatdiv', 'post', 'normal' ); // for formats
				}

				if(isset($posts_pages_settings['trackback-box'])){
					remove_meta_box( 'trackbacksdiv', 'post', 'normal' ); // for trackbacks
				}

				if(isset($posts_pages_settings['comment-status-box'])){
					remove_meta_box( 'commentstatusdiv', 'post', 'normal' ); // for discussions
				}

				if(isset($posts_pages_settings['comments-list-box'])){
					remove_meta_box( 'commentsdiv', 'post', 'normal' ); // for comments
				}

				if(isset($posts_pages_settings['custom-fields-box'])){
					remove_meta_box( 'postcustom', 'post', 'normal' ); // for custom fields
				}

				if(isset($posts_pages_settings['revisions-box'])){
					remove_meta_box( 'revisionsdiv', 'post', 'normal' ); // for rivisions
				}

				if(isset($posts_pages_settings['author-box'])){
					remove_meta_box( 'authordiv', 'post', 'normal' ); // for author name
				}

				if(isset($posts_pages_settings['slug-box'])){
					remove_meta_box( 'slugdiv', 'post', 'normal' );  // for post's slug
				}
			}
		}


		public function eat_custom_login_options(){
			include(EAT_ADMIN_THEME_PLUGIN_DIR . 'inc/frontend/custom-login.php');
		}

		function eat_save_plugin_settings(){
			if(isset($_POST['eat_settings_submit']) && (wp_verify_nonce( $_POST['eat_settings_nonce'], 'eat_settings_action' ) )){
				include('inc/backend/save-settings.php');
			}else if(isset($_POST['eat_reset_settings']) && (wp_verify_nonce( $_POST['eat_settings_nonce'], 'eat_settings_action' ))){
				global $eat_variables;
				$key = update_option('eat_admin_theme_settings', $eat_variables['default_settings']);
				if($key == TRUE){
					wp_redirect(admin_url().'admin.php?page=everest-admin-theme-lite&message=3');
				}else{
					wp_redirect(admin_url().'admin.php?page=everest-admin-theme-lite&message=2');
				}
				die();
			}

		}

		function eat_plugin_menu(){
			add_menu_page( "Everest Admin Theme Lite", "Everest Admin Theme Lite", 'manage_options', 'everest-admin-theme-lite', array($this, 'eat_main_page' ), 'dashicons-smiley');
			add_submenu_page('everest-admin-theme-lite', 'Plugin Settings', 'Plugin Settings', 'manage_options', 'everest-admin-theme-lite', array($this, 'eat_main_page'));
			add_submenu_page( "everest-admin-theme-lite", "About", 'About', 'manage_options', 'everest-admin-lite-menu-about', array($this, 'eat_about_page') );
		}

		function eat_about_page(){
			include('inc/backend/about.php');
		}

		function eat_main_page(){
			include('inc/backend/main_page.php');
		}

		/**
		 * Function for the contant declaration of the plugins.
		 * @return null
		 */
		function eat_plugin_contants(){
			//Declearation of the necessary constants for plugin
			defined('EAT_ADMIN_THEME_VERSION')  or define( 'EAT_ADMIN_THEME_VERSION', '1.0.3' );

			defined('EAT_ADMIN_PLUGIN_PREFIX')  or define( 'EAT_ADMIN_PLUGIN_PREFIX', 'eat' );

			defined( 'EAT_ADMIN_THEME_IMAGE_DIR' ) or define( 'EAT_ADMIN_THEME_IMAGE_DIR', plugin_dir_url( __FILE__ ) . 'images' );

			defined( 'EAT_ADMIN_THEME_JS_DIR' ) or define( 'EAT_ADMIN_THEME_JS_DIR', plugin_dir_url( __FILE__ ) . 'js' );

			defined( 'EAT_ADMIN_THEME_CSS_DIR' ) or define( 'EAT_ADMIN_THEME_CSS_DIR', plugin_dir_url( __FILE__ ) . 'css' );

			// defined( 'EAT_ADMIN_THEME_ASSETS_DIR' ) or define( 'EAT_ADMIN_THEME_ASSETS_DIR', plugin_dir_url( __FILE__ ) . 'assets' );

			defined( 'EAT_ADMIN_THEME_LANG_DIR' ) or define( 'EAT_ADMIN_THEME_LANG_DIR', basename( dirname( __FILE__ ) ) . '/languages/' );

			defined( 'EAT_ADMIN_THEME_TEXT_DOMAIN' ) or define( 'EAT_ADMIN_THEME_TEXT_DOMAIN', 'everest-admin-theme-lite' );

			defined( 'EAT_ADMIN_THEME_SETTINGS' ) or define( 'EAT_ADMIN_THEME_SETTINGS', 'everest_admin_theme_settings' );

			defined( 'EAT_ADMIN_THEME_PLUGIN_DIR') or define( 'EAT_ADMIN_THEME_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			defined( 'EAT_ADMIN_THEME_PLUGIN_DIR_URL' ) or define( 'EAT_ADMIN_THEME_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) ); //plugin directory url
		}

		/**
		 * Make plugin's variables available all around
		 * @return NULL
		 */
		public function eat_plugin_variables() {
			global $eat_variables;
			include_once( EAT_ADMIN_THEME_PLUGIN_DIR . 'inc/plugin_variables.php' );
		}

		/**
		 * Function to load the plugin text domain for plugin translation
		 * @return type
		 */
		function eat_plugin_text_domain(){
			load_plugin_textdomain( 'everest-admin-theme-lite' , false, EAT_ADMIN_THEME_LANG_DIR );
		}

		/**
		 * Function to add  plugin's necessary CSS and JS files for backend
		 * @return null
		 */
		function eat_register_plugin_assets() {
	        //register the styles
	        wp_register_style( 'font-awesome-icons-v4.7.0', EAT_ADMIN_THEME_CSS_DIR.'/font-awesome/font-awesome.min.css', false, EAT_ADMIN_THEME_VERSION );
	        wp_register_style( 'jquery-ui-css', EAT_ADMIN_THEME_CSS_DIR . '/jquery-ui.css', false, EAT_ADMIN_THEME_VERSION );
	        wp_register_style( 'jquery-selectbox-css', EAT_ADMIN_THEME_CSS_DIR . '/jquery.selectbox.css', false, EAT_ADMIN_THEME_VERSION );
	        wp_register_style( 'eat_dashboard_css', EAT_ADMIN_THEME_CSS_DIR . '/eat-dashboard.css', false, EAT_ADMIN_THEME_VERSION );
	        wp_register_style( 'eat_dashboard_resp_css', EAT_ADMIN_THEME_CSS_DIR . '/eat-dashboard-responsive.css', false, EAT_ADMIN_THEME_VERSION );
	        wp_register_style( 'eat_codemirror_css', EAT_ADMIN_THEME_CSS_DIR . '/eat-codemirror.css', false, EAT_ADMIN_THEME_VERSION );
	        wp_register_style( 'eat_codemirror_theme_eclipse_css', EAT_ADMIN_THEME_CSS_DIR . '/eclipse.css', false, EAT_ADMIN_THEME_VERSION );
	        wp_register_style( 'eat_perfect_scrollbar_css', EAT_ADMIN_THEME_CSS_DIR . '/perfect-scrollbar.css', false, EAT_ADMIN_THEME_VERSION );

	        wp_register_style( 'eat_admin_css', EAT_ADMIN_THEME_CSS_DIR . '/eat-backend.css', false, EAT_ADMIN_THEME_VERSION );

	        //enqueue of the styles
	        wp_enqueue_style('font-awesome-icons-v4.7.0');
	        wp_enqueue_style('wp-color-picker');
	        wp_enqueue_style('jquery-ui-css');
	        wp_enqueue_style('jquery-selectbox-css');
	        wp_enqueue_style('eat_dashboard_css');
	        wp_enqueue_style('eat_codemirror_css');
	        wp_enqueue_style('eat_codemirror_theme_eclipse_css');
	        wp_enqueue_style('eat_perfect_scrollbar_css');
	        wp_enqueue_style( 'eat_admin_css' );
	        wp_enqueue_style('eat_dashboard_resp_css');
	        wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Rubik:400,500,700|PT+Sans+Narrow|Poppins|Roboto|Oxygen:300,400,600,700|Josefin+Sans:400,600,700|Ubuntu:300,400,500,700', array(), EAT_ADMIN_THEME_VERSION );

	        // registration of the js
	        wp_enqueue_script( 'wp-color-picker-alpha', EAT_ADMIN_THEME_JS_DIR.'/wp-color-picker-alpha.js',array('jquery','wp-color-picker'), '2.1.2' );
	        wp_enqueue_script( 'resize-sensor', EAT_ADMIN_THEME_JS_DIR.'/ResizeSensor.js',array('jquery'), EAT_ADMIN_THEME_VERSION );
	        wp_enqueue_script( 'theia-sticky-sidebar', EAT_ADMIN_THEME_JS_DIR.'/theia-sticky-sidebar.js',array('jquery', 'resize-sensor'), EAT_ADMIN_THEME_VERSION );
	        wp_enqueue_script( 'selectbox-min-js', EAT_ADMIN_THEME_JS_DIR.'/jquery-selectbox.js',array('jquery'), EAT_ADMIN_THEME_VERSION );
	        wp_enqueue_script( 'codemirror-js', EAT_ADMIN_THEME_JS_DIR.'/eat-codemirror.js',array('jquery'), EAT_ADMIN_THEME_VERSION );
	        wp_register_script('eat_codemirror-dynamic-css', EAT_ADMIN_THEME_JS_DIR. '/codemirror-css.js', array('jquery', 'codemirror-js', 'codemirror-js'), EAT_ADMIN_THEME_VERSION );
			wp_register_script('eat_perfect_scrollbar_js', EAT_ADMIN_THEME_JS_DIR . '/perfect-scrollbar.js', array('jquery'), EAT_ADMIN_THEME_VERSION );
	        wp_register_script( 'eat_admin_js', EAT_ADMIN_THEME_JS_DIR . '/eat-backend.js', array( 'jquery', 'wp-color-picker', 'wp-color-picker-alpha', 'jquery-ui-sortable', 'resize-sensor', 'theia-sticky-sidebar', 'selectbox-min-js', 'codemirror-js', 'eat_codemirror-dynamic-css', 'eat_perfect_scrollbar_js' ),  EAT_ADMIN_THEME_VERSION, true );


	        // enqueue of the js
	        wp_enqueue_media();
	        wp_enqueue_script('jquery-ui-sortable');
	        wp_enqueue_script('wp-color-picker');
	        wp_enqueue_script('eat_icon_picker');
	        wp_enqueue_script('eat_perfect_scrollbar_js');
	        wp_enqueue_script('eat_admin_js');
	        wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-slider');
		}

		/**
		  * Sanitizes Multi Dimensional Array
		  * @param array $array
		  * @param array $sanitize_rule
		  * @return array
		  *
		  * @since 1.0.0
		  */
		static function eat_sanitize_array( $array = array(), $sanitize_rule = array() ){
			if ( ! is_array( $array ) || count( $array ) == 0 ) {
				return array();
			}

			foreach ( $array as $k => $v ) {
				if ( ! is_array( $v ) ) {
					$default_sanitize_rule = (is_numeric( $k )) ? 'text' : 'html';
					$sanitize_type = isset( $sanitize_rule[ $k ] ) ? $sanitize_rule[ $k ] : $default_sanitize_rule;
					$array[ $k ] = self:: eat_sanitize_value( $v, $sanitize_type );
				}

				if ( is_array( $v ) ) {
					$array[ $k ] = self:: eat_sanitize_array( $v, $sanitize_rule );
				}
			}

			return $array;
		}

		/**
		* Sanitizes Value
		*
		* @param type $value
		* @param type $sanitize_type
		* @return string
		*
		* @since 1.0.0
		*/
		static function eat_sanitize_value( $value = '', $sanitize_type = 'text' ){
			switch ( $sanitize_type ) {
			 case 'html':
			     $allowed_html = wp_kses_allowed_html( 'post' );
			     return wp_kses( $value, $allowed_html );
			     break;
			 default:
			     return sanitize_text_field( $value );
			     break;
			}
		}

		/**
		 * Print array
		 * @param $array
		 * @return array return array in print_r format
		 */
		public static function eat_print_array($array){
			echo "<pre>";
			print_r($array);
			echo "</pre>";
		}

		public static function eat_get_php_version(){
			$php_ver = phpversion();
			return $php_ver;
		}

		public static function eat_get_mysql_version(){
			$mysql_version = mysql_get_server_info();
			return $mysql_version;
		}
	}

	$new_everest_admin_theme_obj = new everestAdminThemeLiteClass();

}