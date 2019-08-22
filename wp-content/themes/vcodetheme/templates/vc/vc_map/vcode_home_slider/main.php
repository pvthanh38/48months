<?php

class vcode_home_slider extends vtm_vc_map {
	function loadAssets(){

	}
}

new vcode_home_slider(
	array(
            'name'     => __( 'Vcode Home Slider', 'vcode' ),
            'base'     => 'vcode_home_slider',
            'category' => __( 'Themes Elements', 'vcode' ),
            'params'   => array(
                array(
                    "type"          => "param_group",
                    "admin_label"   => false,
                    "weight"        => 10,
                    "heading"       => __( "Sliders", "vcode" ),
                    "param_name"    => "sliders",
                    'params' => array(
                        array(
                            'type' => 'attach_image',
                            'heading' => __( 'Slider image', 'vcode' ),
                            'param_name' => 'slider_imgae',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Alt slider image', 'vcode' ),
                            'param_name' => 'alt_slider_image',
                        ),
                        array(
                            'type' => 'attach_image',
                            'heading' => __( 'Slogan image', 'vcode' ),
                            'param_name' => 'slogan_image',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Alt slogan image', 'vcode' ),
                            'param_name' => 'alt_slogan_image',
                        ),

                    )
                ),
            )
		)
	 );