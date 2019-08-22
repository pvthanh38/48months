<?php

class vcode_home_focus_areas extends vtm_vc_map {
	function loadAssets(){

	}
}

new vcode_home_focus_areas(
	array(
            'name'     => __( 'Vcode Home Focus Areas', 'vcode' ),
            'base'     => 'vcode_home_focus_areas',
            'category' => __( 'Themes Elements', 'vcode' ),
            'params'   => array(
                array(
                    "type"          => "param_group",
                    "admin_label"   => false,
                    "weight"        => 10,
                    "heading"       => __( "Nav items", "vcode" ),
                    "param_name"    => "nav_items",
                    'params' => array(
                        array(
                            'type' => 'vc_link',
                            'heading' => __( 'nav item', 'vcode' ),
                            'param_name' => 'nav_item',
                        ),

                    )
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link 1', 'vcode' ),
                    'param_name' => 'link_1',
                    'group' => 'Link on the images'
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link 2', 'vcode' ),
                    'param_name' => 'link_2',
                    'group' => 'Link on the images'
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link 3', 'vcode' ),
                    'param_name' => 'link_3',
                    'group' => 'Link on the images'
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link 4', 'vcode' ),
                    'param_name' => 'link_4',
                    'group' => 'Link on the images'
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link 5', 'vcode' ),
                    'param_name' => 'link_5',
                    'group' => 'Link on the images'
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link 6', 'vcode' ),
                    'param_name' => 'link_6',
                    'group' => 'Link on the images'
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link 7', 'vcode' ),
                    'param_name' => 'link_7',
                    'group' => 'Link on the images'
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link 8', 'vcode' ),
                    'param_name' => 'link_8',
                    'group' => 'Link on the images'
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link 9', 'vcode' ),
                    'param_name' => 'link_9',
                    'group' => 'Link on the images'
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link 10', 'vcode' ),
                    'param_name' => 'link_10',
                    'group' => 'Link on the images'
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link 11', 'vcode' ),
                    'param_name' => 'link_11',
                    'group' => 'Link on the images'
                ),
            )
		)
	 );