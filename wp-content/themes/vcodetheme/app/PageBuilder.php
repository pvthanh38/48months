<?php

namespace App;

use Leafo\ScssPhp\Compiler;
use Leafo\ScssPhp\Formatter\Compressed;

class PageBuilder {
	protected static $ins = null;
	protected $inlineStyle = [];
	protected $inlineStyleFooter = [];
	protected $scss;

	static function loadTemplatePart( $postId, $echo = true ) {
		$content = get_post_field( 'post_content', $postId );
		$content = apply_filters( 'the_content', $content );
		$content = do_shortcode( $content );
		$css     = get_post_meta( $postId, '_wpb_shortcodes_custom_css', true );
		$content = "<style class='custom-css-$postId'>$css</style>" . $content;
		if ( $echo ) {
			echo $content;

			return true;
		}

		return $content;
	}

	private function __construct() {
		add_action( 'vtm_footer', array( $this, 'footer' ) );
		add_action( 'vtm_after_head_start', array( $this, 'header' ) );
		$this->scss = new Compiler();
		$this->scss->setFormatter( Compressed::class );
		$style = self::getCurrentSkinStyle();
		add_filter( 'body_class', array( $this, 'addBodyClass' ) );
		$this->scss->setVariables( $style );
	}

	function addBodyClass( $classes ) {
		$current_skin = self::getCurrentSkin();
		if ( $current_skin ) {
			$current_skin = str_replace( 'skin', 'landing', $current_skin );
			$classes[]    = $current_skin;
		} else {
			$classes[] = Options::getPostOption( get_the_ID(), 'skin' );
		}
		$classes[] = self::parseBodyClasses();

		return $classes;
	}

	static function parseAtts( $atts, $default = [] ) {
		if ( ! is_array( $atts ) ) {
			$atts = [];
		}

		return array_merge( $default, $atts );
	}

	static function parseBodyClasses() {
		$skin_style = self::getCurrentSkinStyle();
		$care       = [ 'button_shape', 'button_style', 'button_hover_style', 'header_style', 'header_bg_style' ];

		return self::parseClass( $skin_style, $care );
	}

	static function parseClass( $data, $care = null ) {
		if ( $care == null ) {
			$care = array_keys( $data );
		}
		$cssClasses = [];
		foreach ( $care as $key ) {
			$value = isset( $data[ $key ] ) ? $data[ $key ] : false;
			if ( $value ) {
				array_push( $cssClasses, "{$key}_{$value}" );
			}
		}

		return join( ' ', $cssClasses );
	}

	static function getCurrentSkin() {
		if ( is_singular( 'page' ) ) {
			return Options::getPostOption( get_the_ID(), 'skin' );
		}

		return false;
	}

	static function getCurrentSkinStyle() {
		$options_key = [
			'header_style',
			'button_shape',
			'menu',
			'footer',
			'button_shape',
			'button_style',
			'button_hover_style',
			'body_bg_color',
			'heading',
			'body',
			'logo_bg_color',
			'header_bg_color',
			'fixed_menu_bg_color',
			'fixed_menu_text_color',
			'fixed_logo_bg_color',
			'header_bg_image',
			'body_bg_image',
			'body_bg_color',
			'header_bg_style'
		];
		$options     = [];
		foreach ( $options_key as $key ) {
			$value = Options::getSiteOption( $key );
			if ( $value && ! empty( $value ) ) {
				$options[ $key ] = $value;
			}
		}

		return array_merge( [
			'header_style'          => 'header-1',
			'body_bg_color'         => '#fff',
			'fixed_logo_bg_color'   => 'transparent',
			'fixed_menu_text_color' => '#ffffff',
			'header_bg_image'       => false,
			'header_bg_style'       => 'cover'
		], $options );
	}

	static function getHeaderList() {
		$headers = get_option( 'vtm_post_vtm_header', [] );
		$hs      = [ '' => '---' ];
		foreach ( $headers as $header ) {
			$hs[ $header['value'] ] = $header['label'];
		}

		return $hs;
	}

	static function getFooterList() {
		$headers = get_option( 'vtm_post_vtm_footer', [] );
		$hs      = [ '' => '---' ];
		foreach ( $headers as $header ) {
			$hs[ $header['value'] ] = $header['label'];
		}

		return $hs;
	}

	static function getMenuList() {
		$menus = Cache::getInstance()->get( 'vtm_menu_list', false );
		if ( $menus ) {
			return $menus;
		}
		$menus = [];
		$terms = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
		foreach ( $terms as $term ) {
			$menus[ $term->term_id ] = $term->name;
		}
		Cache::getInstance()->set( 'vtm_menu_list', $menus );

		return $menus;
	}

	static function getHeader() {
		$headerId = Options::getSiteOption( 'default_header', false );
		if ( $headerId ) {
			return self::loadTemplatePart( $headerId, false );
		}

		return '';
	}

	static function getFooter() {
		$footerId = Options::getSiteOption( 'default_footer', false );
		if ( is_singular() ) {
			$postFooterId = Options::getPostOption( get_the_ID(), 'footer', $footerId );
			if ( $postFooterId ) {
				$footerId = $postFooterId;
			}
		}
		if ( $footerId ) {
			return self::loadTemplatePart( $footerId, false );
		}

		return '';
	}

	function setInlineStyle( $sel, $style, $position = 'head' ) {
		$css = self::arrayToCss( $sel, $style );
		if ( $position == 'head' ) {
			$this->inlineStyle( $css );
		} else {
			$this->inlineStyleFooter( $css );
		}
	}

	function inlineStyle( $style ) {
		if ( $style && strpos( $style, '{}' ) === false ) {
			array_push( $this->inlineStyle, $style );
		}
	}

	function inlineStyleFooter( $style ) {
		if ( $style && strpos( $style, '{}' ) === false ) {
			array_push( $this->inlineStyleFooter, $style );
		}
	}

	function header() {
		echo '<style id="inline-style">';
		echo join( '', $this->inlineStyle );
		echo '</style>';
	}

	function footer() {
		if ( $this->inlineStyleFooter ) {
			echo '<style id="inline-style-footer">';
			echo join( '', $this->inlineStyleFooter );
			echo '</style>';
		}
	}

	function compileCss( $case = 'main' ) {
		try {
			switch ( $case ) {
				case 'main':
					$this->scss->setImportPaths( APP_ROOT_THEME_PATH . '/scss' );
					$main_file = APP_ROOT_THEME_PATH . '/scss/main.scss';
					$output    = APP_ROOT_THEME_PATH . '/css/main.css';
					break;
				default:
					$this->scss->setImportPaths( APP_ROOT_THEME_PATH . "/skins/$case/scss" );
					$main_file = APP_ROOT_THEME_PATH . "/skins/$case/scss/$case.scss";
					$output    = APP_ROOT_THEME_PATH . "/skins/$case/css/$case.css";
					break;
			}

			if ( ! vtm_is_local() ) {
				if ( is_file( $output ) ) {
					return;
				}
			}

			if ( is_file( $main_file ) ) {
				$file = file_get_contents( $main_file );
				$css  = $this->scss->compile( $file );
				file_put_contents( $output, $css );
			}
		} catch ( \Exception $ex ) {
			solid_log( $ex->getMessage(), 'error.txt' );
		}
	}

	static function getIns() {
		static $ins = null;
		if ( $ins === null ) {
			$ins = new PageBuilder();
		}

		return $ins;
	}


	static function arrayToCss( $sel, $cssArray ) {
		$cssArray = self::cssRules( $cssArray );

		return "$sel{{$cssArray}}";
	}

	static function cssRules( $cssArray ) {
		$style = [];
		if ( ! is_array( $cssArray ) ) {
			return false;
		}
		foreach ( $cssArray as $key => $value ) {
			if ( $value ) {
				array_push( $style, "$key: $value" );
			}
		}

		return join( ';', $style );
	}

	static function paddingOptions() {
		return [
			'padding_top'    => [
				'type'  => 'text',
				'value' => '',
				'label' => __( 'Padding Top', 'lw' ),
				'desc'  => __( 'Ex: 15px', 'lw' ),
			],
			'padding_bottom' => [
				'type'  => 'text',
				'value' => '',
				'label' => __( 'Padding Bottom', 'lw' ),
				'desc'  => __( 'Ex: 15px', 'lw' ),
			],
			'padding_left'   => [
				'type'  => 'text',
				'value' => '',
				'label' => __( 'Padding left', 'lw' ),
				'desc'  => __( 'Ex: 15px', 'lw' ),
			],
			'padding_right'  => [
				'type'  => 'text',
				'value' => '',
				'label' => __( 'Padding Right', 'lw' ),
				'desc'  => __( 'Ex: 15px', 'lw' ),
			]
		];
	}

	static function paddingCss( $padding ) {
		return [
			'padding-left'   => $padding['padding_left'],
			'padding-right'  => $padding['padding_right'],
			'padding-top'    => $padding['padding_top'],
			'padding-bottom' => $padding['padding_bottom'],
		];
	}
}

function vtm_add_shortcode_params() {

	vc_add_params( 'vc_row', [
		[
			"type"       => "colorpicker",
			"class"      => "",
			'group'      => 'Design Options',
			"heading"    => __( "Text color", "vtm" ),
			"param_name" => "vtmtextcolor",
			"value"      => ''
		]
	] );

	vc_add_params( 'vc_row_inner', [
		[
			"type"       => "colorpicker",
			"class"      => "",
			'group'      => 'Design Options',
			"heading"    => __( "Text color", "vtm" ),
			"param_name" => "vtmtextcolor",
			"value"      => ''
		]
	] );

	vc_add_params( 'vc_column_text', [
		[
			"type"       => "colorpicker",
			"class"      => "",
			'group'      => 'Design Options',
			"heading"    => __( "Text color", "vtm" ),
			"param_name" => "vtmtextcolor",
			"value"      => ''
		]
	] );

	vc_add_params( 'vc_custom_heading', [
		[
			"type"       => "colorpicker",
			"class"      => "",
			'group'      => 'Design Options',
			"heading"    => __( "Text color", "vtm" ),
			"param_name" => "vtmtextcolor",
			"value"      => ''
		]
	] );
}

add_action( 'vc_before_init', 'App\vtm_add_shortcode_params' );