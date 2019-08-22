<?php

define('VC_SC_MAP_URL', APP_THEME_URL . '/templates/vc/vc_map');

foreach ( glob( dirname(__FILE__) . '/*/main.php' )  as $shortcode) {
	include_once $shortcode;
}