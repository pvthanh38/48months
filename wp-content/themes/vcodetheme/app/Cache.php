<?php

namespace App;

use phpFastCache\CacheManager;

class Cache{
	protected $cacheEngine;
	private static $instance = null;

	protected function __construct($driver = 'files') {
		CacheManager::setDefaultConfig(array(
			"path" => APP_ROOT_PATH.'/cache'
		));
		$this->cacheEngine = CacheManager::getInstance($driver);
		return $this;
	}

	function clean() {
		return $this->cacheEngine->clear();
	}

	static function getInstance($driver = 'files'){
		if (!self::$instance)
			self::$instance = new self($driver);
		return self::$instance;
	}

	function get($key, $default, $expired = 84600){
		$cached = $this->cacheEngine->getItem($key);
		if (is_null($cached->get()))
		{
			$cached->set($default)->expiresAfter($expired);
			$this->cacheEngine->save($cached);
			return $default;
		}
		return $cached->get();
	}
	function set( $key, $value, $expired = 84600 ) {
		$cached = $this->cacheEngine->getItem( $key );
		$cached->set( $value )->expiresAfter( $expired );
		$this->cacheEngine->save( $cached );
		return $value;
	}
}