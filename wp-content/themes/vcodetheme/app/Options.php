<?php
namespace App;

class Options {
	static function getPostOption( $postId, $optionId, $default = false ) {
		if (!function_exists('get_field')) return $default;
		$cache = Cache::getInstance();
		return $cache->get( "post_options_$postId" . "_" . "$optionId", get_field($optionId, $postId) );
	}
	static function getSiteOption( $key, $default = false ) {
		if (!function_exists('get_field')) return $default;
		$cache = Cache::getInstance();
		return $cache->get( 'option_' . $key, get_field($key, 'option') );
	}

	static function getSiteOptionWP( $key, $default = false ) {
		$cache = Cache::getInstance();
		$re = $cache->get( 'option_' . $key, get_option($key));
		if (!$re) return $default;
		return $re;
	}

	static function getTermOption($termId, $key, $default = false ) {
		if (!function_exists('get_field')) return $default;
		$cache = Cache::getInstance();
		return $cache->get( "term{$termId}_option_" . $key, get_field($key, $termId) );
	}
}