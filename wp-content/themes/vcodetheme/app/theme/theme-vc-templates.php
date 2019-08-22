<?php

add_filter( 'vc_load_default_templates', 'rella_reset_default_templates' ); // Hook in
function rella_reset_default_templates( $data ) {
    return array(); // This will remove all default templates. Basically you should use native PHP functions to modify existing array and then return it.
}

function rella_add_default_templates() {
	$templates = vtm_vc_templates();
	if (function_exists('vc_add_default_templates')){
		return array_map( 'vc_add_default_templates', $templates );
	}
	return $templates;
}
rella_add_default_templates();