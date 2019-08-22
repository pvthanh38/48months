<?php


abstract class vtm_vc_map {
	protected $param;

	function __construct( $param ) {
		$this->param = $param;
		if ( is_callable( array( $this, 'loadAssets' ) ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'loadAssets' ), 32 );
		}
		add_action( 'vc_before_init', array( $this, 'vcMap' ) );
		add_shortcode( $this->param['base'], array( $this, 'shortcode' ) );
		$this->url  = vtm_get_vc_shortcode_url( __DIR__ );
		$this->slug = basename( __DIR__ );
	}

	function url( $file ) {
		return APP_VC_TEMPLATE_URL . '/' . $this->param['base'] . "/$file";
	}

	function isDemoMode(){
		return file_exists(APP_VC_MAP_DIR . '/vc_map/' . $this->param['base'].'/.demo');
	}

	function slug() {
		return $this->param['base'];
	}

	function vcMap() {
		vc_map( $this->param );
	}

	function shortcode( $atts, $content ) {
		if ( $atts ) {
			$atts['is_demo_mode'] = $this->isDemoMode();
		} else {
			$atts = array();
			$atts['is_demo_mode'] = $this->isDemoMode();
		}

		$file   = APP_VC_MAP_DIR . '/vc_map/' . $this->param['base'] . '/views/view.php';
		$render = '';
		if ( is_file( $file ) ) {
			ob_start();
			include $file;
			$render = ob_get_clean();
		}

		return $render;
	}
}
vc_add_shortcode_param( 'vc_dropdown_cat_voice', 'vc_dropdown_cat_voice_settings_field' );
function vc_dropdown_cat_voice_settings_field( $param, $value ) {
	$terms = get_terms( array( 'taxonomy' => 'voice_cat', 'hide_empty' => false) );
	$categories_array = [];
	foreach($terms as $k){
		if($k->name != 'Chưa phân loại'){
			$categories_array[$k->term_id] = $k->name;
		}
	}
	$param['value'] = $categories_array;
   $param_line = '';
   $param_line .= '<select multiple name="'. esc_attr( $param['param_name'] ).'" class="wpb_vc_param_value wpb-input wpb-select '. esc_attr( $param['param_name'] ).' '. esc_attr($param['type']).'">';
   foreach ( $param['value'] as $text_val => $val ) {
       if ( is_numeric($text_val) && (is_string($val) || is_numeric($val)) ) {
			//$text_val = $val;
		}
		$text_val = __($text_val, "js_composer");
		$selected = '';

		if(!is_array($value)) {
			$param_value_arr = explode(',',$value);
		} else {
			$param_value_arr = $value;
		}

		if ($value!=='' && in_array($text_val, $param_value_arr)) {
			$selected = ' selected="selected"';
		}
		$param_line .= '<option class="'.$text_val.'" value="'.$text_val.'"'.$selected.'>'.$val.'</option>';
	}
   $param_line .= '</select>';

   return  $param_line;
}

/* NEW PARAM */
vc_add_shortcode_param( 'dl_post_cat', 'vc_param_dl_post_cat_callback' );
function vc_param_dl_post_cat_callback( $settings, $value ) {
    $_terms = get_terms( array(
        'taxonomy' => 'category',
        'hide_empty' => false
    ) );

    $multiple = isset($settings['multiple']) ? 'multiple="multiple"' : '';

    ob_start(); ?>
    <select <?php echo $multiple ?> name="<?php echo esc_attr( $settings['param_name'] ) ?>"
                                    class="wpb_vc_param_value wpb-input wpb-select <?php echo esc_attr( $settings['param_name'] ) ?>_field <?php echo esc_attr( $settings['param_name'] ) ?>">
        <option value="0">Chose categories</option>
        <?php foreach ($_terms as $item) { ?>
            <option <?php selected( $value, $item->term_id ) ?> value="<?php echo $item->term_id ?>"><?php echo $item->name ?></option>
        <?php } ?>
    </select>
    <?php $render = ob_get_clean();
    return $render;
}

/* NEW PARAM */
vc_add_shortcode_param( 'dl_graphic_cat', 'vc_param_dl_graphic_cat_callback' );
function vc_param_dl_graphic_cat_callback( $settings, $value ) {
    $_terms = get_terms( array(
        'taxonomy' => 'graphic_cat',
        'hide_empty' => false
    ) );

    $multiple = isset($settings['multiple']) ? 'multiple="multiple"' : '';

    ob_start(); ?>
    <select <?php echo $multiple ?> name="<?php echo esc_attr( $settings['param_name'] ) ?>"
                                    class="wpb_vc_param_value wpb-input wpb-select <?php echo esc_attr( $settings['param_name'] ) ?>_field <?php echo esc_attr( $settings['param_name'] ) ?>">
        <option value="0">Chose categories</option>
        <?php foreach ($_terms as $item) { ?>
            <option <?php selected( $value, $item->term_id ) ?> value="<?php echo $item->term_id ?>"><?php echo $item->name ?></option>
        <?php } ?>
    </select>
    <?php $render = ob_get_clean();
    return $render;
}