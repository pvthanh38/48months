<?php
use App\Cache;
use App\Options;
use App\PageBuilder;

function vtm_vc_templates() {
	$templates = [];
//	$data = include (APP_ROOT_PATH.'/theme/templates/banner-1.php');
//	array_push($templates, $data);
	$templates = apply_filters( 'vtm_vc_templates', $templates );

	return $templates;
}

add_filter( 'vtm_vc_templates', 'add_shortcode_to_templates' );

function vtm_is_local() {
	$locals = [ '.local' ];
	foreach ( $locals as $local ) {
		if ( strpos( APP_SITE_URL, $local ) !== false ) {
			return true;
		}
	}

	return false;
}

function add_shortcode_to_templates( $templates ) {
	foreach ( glob( APP_ROOT_THEME_PATH . '/templates/vc/vc_map/*/config.php' ) as $shortcode ) {
		$templates[] = include( $shortcode );
	}

	return $templates;
}

function vtm_numeric_posts_nav() {

	if ( is_singular() ) {
		return;
	}

	global $wp_query;

	/** Stop execution if there's only 1 page */
	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );

	/** Add current page to the array */
	if ( $paged >= 1 ) {
		$links[] = $paged;
	}

	/** Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<div class="navigation"><ul>' . "\n";

	/** Previous Post Link */
	if ( get_previous_posts_link() ) {
		printf( '<li>%s</li>' . "\n", get_previous_posts_link() );
	}

	/** Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active"' : '';

		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

		if ( ! in_array( 2, $links ) ) {
			echo '<li>…</li>';
		}
	}

	/** Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}

	/** Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) ) {
			echo '<li>…</li>' . "\n";
		}

		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}

	/** Next Post Link */
	if ( get_next_posts_link() ) {
		printf( '<li>%s</li>' . "\n", get_next_posts_link() );
	}

	echo '</ul></div>' . "\n";

}


add_action( 'fw_settings_form_saved', '_action_theme_fw_post_options_update', 10, 1 );
/*add_action( 'save_post', 'vtm_save_post' );
add_action( 'acf/save_post', '_action_theme_fw_post_options_update', 20 );


function vtm_save_post() {
	global $post;
	$post_id = $post->ID;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( false !== wp_is_post_revision( $post_id ) ) {
		return;
	}
	remove_action( 'save_post', 'vtm_save_post', 13 );
	_action_theme_fw_post_options_update();
	add_action( 'save_post', 'vtm_save_post', 13, 2 );
}*/

function _action_theme_fw_post_options_update( $old_values = null ) {
	$cache = Cache::getInstance();
	$cache->clean();
}


function solid_log( $data, $name = null, $mode = 'append' ) {
	if ( $name == null ) {
		$name = date( 'YmdH' ) . '.txt';
	}
	$dir = APP_ROOT_PATH . "/logs";

	if ( ! is_dir( $dir ) ) {
		mkdir( $dir, 0775, true );
	}

	$filename = "$dir/$name";
	if ( is_array( $data ) || is_object( $data ) ) {
		$data = serialize( $data );
	}
	$mode = $mode == 'append' ? FILE_APPEND : null;

	$data = date( "d-m-Y H:i:s " ) . $data;
	if ( $mode == FILE_APPEND ) {
		$data .= "\n";
	}

	return file_put_contents( $filename, $data, $mode );
}

function vtm_extract_phone_number( $number ) {
	return preg_replace( "/[a-zA-Z\s-]/", '', $number );
}

function disable_plugin_updates( $value ) {
	if ( isset( $value ) && is_object( $value ) ) {
		$value->response = vtm_dont_update_plugin( $value->response, [
			'LayerSlider/layerslider.php',
			'js_composer/js_composer.php',
			'advanced-custom-fields-pro/acf.php',
			'revslider/revslider.php'
		] );
	}

	return $value;
}

function vtm_dont_update_plugin( $response, $disabled_plugins ) {
	foreach ( $disabled_plugins as $plugin ) {
		if ( isset( $response[ $plugin ] ) ) {
			unset( $response[ $plugin ] );
		}
	}

	return $response;
}

function vtm_extract_atts( $atts, $default = [] ) {
	return PageBuilder::parseAtts( $atts, $default );
}

add_filter( 'site_transient_update_plugins', 'disable_plugin_updates' );

/**
 * Get post option
 * @param $postId
 * @param $optionId
 * @param bool $default
 *
 * @return bool|mixed
 */

function vtm_get_post_option( $postId, $optionId, $default = false ) {
	return Options::getPostOption( $postId, $optionId, $default);
}

/**
 * Get option in Option page
 * @param $key : Option key
 * @param bool $default
 *
 * @return bool|mixed
 */
function vtm_get_site_option( $key, $default = false ) {
	return Options::getSiteOption(  $key, $default );
}

/**
 * Get option in term
 * @param $termId
 * @param $key
 * @param bool $default
 *
 * @return bool|mixed
 */
function vtm_get_term_option( $termId, $key, $default = false) {
	return Options::getTermOption( $termId, $key, $default);
}

function vtm_get_site_option_wp( $key, $default = false ) {
	return Options::getSiteOptionWP( $key, $default );
}

function vtm_make_id($prefix = 'vtm_'){
	return $prefix. wp_generate_uuid4();
}

function vtm_get_uid(){
	return vtm_make_id();
}

function vtm_get_vc_shortcode_url($dir){
	return APP_VC_TEMPLATE_URL.DIRECTORY_SEPARATOR.basename($dir);
}

function vtm_get_youtube_id($url){
	preg_match(
		'@(https?://)?(?:www\.)?(youtu(?:\.be/([-\w]+)|be\.com/watch\?v=([-\w]+)))\S*@im',
		$url,
		$matches
	);
	if (is_array($matches) && isset($matches[4])) return $matches[4];
	return '';
}

function vtm_get_attachment_image_src($attachment_id, $size) {
	$src = wp_get_attachment_image_src( $attachment_id, $size );
	if ( is_array( $src ) ) {
		return $src[0];
	}

	return false;
}

function vtm_string_to_slug($str) {
	$str = trim( mb_strtolower( $str ) );
	$str = preg_replace( '/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str );
	$str = preg_replace( '/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str );
	$str = preg_replace( '/(ì|í|ị|ỉ|ĩ)/', 'i', $str );
	$str = preg_replace( '/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str );
	$str = preg_replace( '/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str );
	$str = preg_replace( '/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str );
	$str = preg_replace( '/(đ)/', 'd', $str );
	$str = preg_replace( '/[^a-z0-9-\s]/', '', $str );
	$str = preg_replace( '/([\s]+)/', '-', $str );

	return $str;
}

function vtm_get_file_content($path, $wp_content = true){
	$file = APP_ROOT_THEME_PATH."/$path";
	$content = '';
	if (is_file($file)){
		ob_start();
		include $file;
		$content = ob_get_clean();
		if ($wp_content)
			$content = apply_filters('the_content', $content);
	}
	return $content;
}

function vtm_get_tax_for_vc($exclude = []){
	$taxes = get_taxonomies([
		'public'   => true
	] , 'objects');
	$data = [];
	foreach ($taxes as $key=>$tax){
		$data[$tax->label] = $key;
	}
	return $data;
}