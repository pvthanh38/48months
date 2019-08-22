<?php

class vcode_home_kpi extends vtm_vc_map {
	function loadAssets(){

	}
}

new vcode_home_kpi(
	array(
            'name'     => __( 'Vcode KPI', 'vcode' ),
            'base'     => 'vcode_home_kpi',
            'category' => __( 'Themes Elements', 'vcode' ),
            'params'   => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'title', 'vcode' ),
                    'param_name' => 'title',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'subtitle', 'vcode' ),
                    'param_name' => 'subtitle',
                ),
				array(
							'type' => 'textfield',
							'heading' => __( 'Number item', 'vcode' ),
							'param_name' => 'number_item',
                ),
            )
		)
	 );