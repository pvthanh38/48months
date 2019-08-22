<?php
use App\PageBuilder;
use App\Options;

add_action( 'wp_enqueue_scripts', 'vtm_theme_enqueue_assets' );

function vtm_theme_enqueue_assets() {

	wp_register_script( 'sticky', APP_THEME_URL . '/js/vendors/jquery.sticky.js', [ 'jquery' ] );

	wp_register_script( 'slick', APP_THEME_URL . '/js/vendors/slick.min.js', [ 'jquery' ], false, true );
	wp_register_style( 'slick', APP_THEME_URL . '/css/vendors/slick.min.css');


	wp_register_script( 'mmmenu', APP_THEME_URL . '/js/vendors/jquery.mmenu.all.js', [ 'jquery' ], false, true );
	wp_register_style( 'mmmenu', APP_THEME_URL . '/css/vendors/jquery.mmenu.all.css');

	wp_register_script( 'fancybox', APP_THEME_URL . '/js/vendors/jquery.fancybox.min.js', [ 'jquery' ], false, true );
	wp_register_style( 'fancybox', APP_THEME_URL . '/css/vendors/jquery.fancybox.min.css');

	wp_register_style( 'bootstrap', APP_THEME_URL . '/css/bootstrap.css' );
	wp_register_style( 'init', APP_THEME_URL . '/css/init.css' );
	wp_register_style( 'fa', APP_THEME_URL . '/css/font-awesome.min.css' );
}

register_nav_menus( [
	'main-menu' => __( 'Main menu', 'vtm' )
] );

register_nav_menus( [
	'main-menu' => __( 'Main menu', 'vtm' )
]);


add_action('vtm_footer','vtm_footer_render');

function vtm_footer_render(){
	echo PageBuilder::getFooter();
}

add_action('vtm_header','vtm_header_render');

function vtm_header_render(){
	echo PageBuilder::getHeader();
}


add_action('vtm_after_head_start','vtm_after_head_start_add_custom_code');
add_action('vtm_before_head_end','vtm_before_head_end_add_custom_code');

add_action('vtm_after_body_start','vtm_after_body_start_add_custom_code');
add_action('vtm_before_body_end','vtm_before_body_end_add_custom_code');

function vtm_after_head_start_add_custom_code(){
	echo vtm_add_custom_code('after_head_open');
}

function vtm_before_head_end_add_custom_code(){
	echo vtm_add_custom_code('before_head_close');
}

function vtm_after_body_start_add_custom_code(){
	echo vtm_add_custom_code('after_body_open');
}

function vtm_before_body_end_add_custom_code(){
	echo vtm_add_custom_code('before_body_close');
}


function vtm_add_custom_code($key){
	$code = Options::getSiteOption($key, false);
	if ($code){
		return $code;
	}
	return '';
}


function vtm_embed_html( $html ) {
	return '<div class="vtm-video">' . $html . '</div>';
}

add_filter( 'embed_oembed_html', 'vtm_embed_html', 10, 3 );
add_filter( 'video_embed_html', 'vtm_embed_html' );

add_action( 'wp_footer', 'vtm_add_back_to_top' );
add_action( 'wp_footer', 'vtm_add_phone_button' );

function vtm_add_phone_button() {
	if ( Options::getSiteOption( 'call_button' ) !== 'yes' ) {
		return;
	}
	?>
	<div class="mobilephone-outer">
		<a href="tel:<?php echo vtm_extract_phone_number( Options::getSiteOption( 'call_number' ) ); ?>">
			<div class="border-small-mobilephone"></div>
			<div class="wrap-mobilephone">
				<i class="fa fa-phone" aria-hidden="true"></i>
			</div>
		</a>
	</div>
	<?php
}

function vtm_add_back_to_top() {
	if ( Options::getSiteOption( 'back_to_top' ) !== 'yes' ) {
		return;
	}
	?>
	<a href="#" class="back-to-top">
		<i class="fa fa-angle-up"></i>
	</a>
	<?php
}
