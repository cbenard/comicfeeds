<?php

class ViewService {
	protected $log;
	protected $store;

	public function __construct(Logger $logger, StorageService $store) {
		$this->log = $logger;
		$this->store = $store;
	}
	
	public function getFeed($requestUri) {
		$pattern = "@^/view/(\w+?)/(\w+?)$@";
		$requestUriWithoutQuery = strtok($requestUri, '?');
		$ret = preg_match($pattern, $requestUriWithoutQuery, $matches);
		
		if ($ret === FALSE) {
			throw new Exception("Regex error occurred.");
		} elseif ($ret === 0) {
			throw new Exception("Regex did not match subject.");
		} elseif ($ret !== 1) {
			throw new Exception("Unexpected regex return value: $ret");
		} elseif (count($matches) < 3) {
			throw new Exception("Regex had no matches.");
		}
		
		$feedName = $matches[1];
		$feedType = $matches[2];
		$filename = "feed_{$feedName}_{$feedType}";
		
		$rawContents = $this->store->load($filename);
		$cleanedContents = $this->sanitizeXml($rawContents);
		return $cleanedContents;
	}
	
	// From: http://stackoverflow.com/a/29418829/448
	protected function sanitizeXml($rawContents) {
		return preg_replace('/<\?xml\-[^>]+\?>\r?\n?/im', '', $rawContents);
	}
}