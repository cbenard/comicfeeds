<?php
	
class StorageService {
	private $prefix;
	private $ctx;
	
	public function __construct() {
		$this->prefix = "gs://#default#/";
		$options = ['gs' => ['Content-Type' => 'text/plain']];
		$this->ctx = stream_context_create($options);
	}

	public function save($filename, $contents) {
		file_put_contents($this->prefix . $filename, $contents, 0, $this->ctx);
	}
	
	public function load($filename) {
		$newFilename = $this->prefix . $filename;
		if (!file_exists($newFilename)) {
			throw new Exception("Sorry. That feed does not exist.");
		}
		return file_get_contents($newFilename);
	}
}