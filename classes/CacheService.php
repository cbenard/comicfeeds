<?php
	
class CacheService {
	private $prefix;
	private $mem;

	public function __construct() {
		$this->prefix = "comicfeeds_";
		$this->mem = new Memcached;
	}

	public function get($name) {
		try {
			return $this->mem->get($this->prefix . $name);
		}
		catch (Exception $ex) {
			return FALSE;
		}
	}
	
	public function set($name, $value, $expirationSeconds) {
		try {
			$this->mem->set($this->prefix . $name, $value, time() + $expirationSeconds);
		}
		catch (Exception $ex) {
		}
	}

	public function getAllKeys() {
		try {
			return $this->mem->getAllKeys();
		}
		catch (Exception $ex) {
			return FALSE;
		}
	}
}