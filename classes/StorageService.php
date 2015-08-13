<?php
	
class StorageService {
	private $prefix;
	private $ctx;
	private $cache;
	const EXPIRE_TIME = 600;
	
	public function __construct(CacheService $cacheService) {
		$this->prefix = "gs://#default#/";
		$options = ['gs' => ['Content-Type' => 'text/plain']];
		$this->ctx = stream_context_create($options);
		$this->cache = $cacheService;
	}

	public function save($filename, $contents) {
		file_put_contents($this->prefix . $filename, $contents, 0, $this->ctx);
		$this->cache->set($filename, $contents, self::EXPIRE_TIME);
	}
	
	public function load($filename) {
		$contents = $this->cache->get($filename);
		
		if (!$contents) {
			$newFilename = $this->prefix . $filename;
			if (!file_exists($newFilename)) {
				throw new Exception("Sorry. That feed does not exist.");
			}
			$contents = file_get_contents($newFilename);
			$this->cache->set($filename, $contents, self::EXPIRE_TIME);
			header('X-Memcache-Hit: False');
		}
		else {
			header('X-Memcache-Hit: True');
		}		
		return $contents;
	}
}