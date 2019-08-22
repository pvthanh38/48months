<?php

define('APP_ROOT_THEME_PATH', __DIR__);
define('APP_ROOT_PATH', APP_ROOT_THEME_PATH.'/app');
define('APP_THEME_URL', get_bloginfo('stylesheet_directory'));
define('APP_VC_MAP_DIR', __DIR__.'/templates/vc');

define('APP_VC_MAP_URL', APP_THEME_URL.'/templates/vc');
define('APP_VC_TEMPLATE_URL', APP_THEME_URL.'/templates/vc/vc_map');

define('APP_SITE_NAME', get_bloginfo('name'));
define('APP_SITE_URL', get_bloginfo('url'));